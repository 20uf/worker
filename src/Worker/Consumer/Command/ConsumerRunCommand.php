<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Consumer\Command;

use Monolog\Logger;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

/**
 * Command for running the consumer to launch processes.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class ConsumerRunCommand extends Command
{

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->logger = new Logger('consumer');
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
                ->setName('consumer:run')
                ->setDescription('Start consumer to fetch sources from RabbitMQ')
                ->addOption('provider', '', InputOption::VALUE_OPTIONAL, 'The name of provider', "socialive")
                ->addOption('nb_process', '', InputOption::VALUE_OPTIONAL, 'Total of processes to launch', 2)
                ->addOption('env', '', InputOption::VALUE_OPTIONAL, 'Set the environment', 'prod')
                ->addOption('routing_key', '', InputOption::VALUE_OPTIONAL, 'Set the routing key', "socialtv.socialive.source")
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $provider       = $input->getOption('provider');
        $totalProcesses = $input->getOption('nb_process');
        $environment    = $input->getOption('env');
        $routingKey     = $input->getOption('routing_key');

        $inputOutput = new SymfonyStyle($input, $output);
        $processes   = array();

        for ($i = 0; $i < $totalProcesses; $i++) {
            $processes[] = $this->launchChildProcess($routingKey, $provider);
        }

        // kill all child processes handler
        $exitHandler = function ($sigNo) use ($processes, $inputOutput) {
            foreach ($processes as $key => $process) {
                $process->stop();
                $inputOutput->comment(sprintf('Process is exited with code %s after a signal %s.', $process->getExitCode(), $sigNo));
            }
            exit;
        };

        pcntl_signal(SIGINT, $exitHandler);
        pcntl_signal(SIGTERM, $exitHandler);

        $inputOutput->note(sprintf('Launch %s processes for Provider %s . To exit press CTRL+C', $totalProcesses, $provider));

        while (true) {
            pcntl_signal_dispatch();

            try {
                foreach ($processes as $key => $process) {
                    $process->checkTimeout();

                    if ($process->isRunning()) {
                        $latestLog = $process->getIncrementalOutput();
                        if (isset($latestLog) && strlen($latestLog) > 0) {
                            $inputOutput->comment($latestLog);
                        }
                    }
                }
            } catch (ProcessTimedOutException $ex) {
                $processes[] = $this->launchChildProcess($routingKey, $provider);
            }

            if ($environment === 'test') {
                break;
            }

            // Get all logs every second.
            sleep(1);
        }
    }

    /**
     * Starts child process.
     *
     * @param string $routingKey The routing key for the message sended in rabbitmq.
     * @param string $provider   The provider of the worker.
     *
     * @return Process
     */
    protected function launchChildProcess($routingKey, $provider)
    {
        $process = new Process(sprintf('exec php bin/worker consumer:item:pull --routing_key=%s --provider=%s', $routingKey, $provider));

        try {
            $process->setTimeout(300);
            $process->start();
        } catch (\RuntimeException $exception) {
            $this->logger->addError($exception->getMessage());
            $process->stop();
        }

        return $process;
    }
}
