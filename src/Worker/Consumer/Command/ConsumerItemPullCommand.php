<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Consumer\Command;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Worker\Consumer\Factory\SourceProcessorFactory;
use Worker\Consumer\Mapper\SourceMapper;
use Worker\Common\Rabbitmq\AMQPConnectionFactory;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for pulling.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class ConsumerItemPullCommand extends Command
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * @var SourceProcessorFactory
     */
    protected $sourceProcessorFactory;

    /**
     * @var SourceMapper
     */
    protected $sourceMapper;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->connection             = (new AMQPConnectionFactory())->create();
        $this->logger                 = new Logger('consumer');
        $this->channel                = $this->connection->channel();
        $this->sourceProcessorFactory = new SourceProcessorFactory();
        $this->sourceMapper           = new SourceMapper();
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('consumer:item:pull')
            ->setDescription('Start consumer to fetch items from source')
            ->addOption('channel', "c", InputOption::VALUE_OPTIONAL, 'Set the channel', "socialtv")
            ->addOption('provider', "p", InputOption::VALUE_OPTIONAL, 'Set the provider', "socialive")
            ->addOption('queue', "qe", InputOption::VALUE_OPTIONAL, 'Set the queue', "ftven-socialtv-socialive")
            ->addOption('routing_key', "rk", InputOption::VALUE_OPTIONAL, 'Set the routing key', "socialtv.socialive.source")
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $provider   = $input->getOption('provider');
        $channel    = $input->getOption('channel');
        $queueName  = $input->getOption('queue');
        $routingKey = $input->getOption('routing_key');

        $inputOutput = new SymfonyStyle($input, $output);

        $this->channel->exchange_declare(
            $channel,
            'direct',
            false,
            false,
            false
        );

        $this->channel->queue_declare(
            $queueName,
            false,
            false,
            false,
            false, // autodelete
            false,
            ['x-max-priority' => ['I', 10]],
            $ticket = null
        );


        $this->channel->queue_bind($queueName, $channel, $routingKey);

        // Rabbimq must send 1 message to a customer, not a pool of message.
        $this->channel->basic_qos(null, 1, null);

        $callback = function (AMQPMessage $message) use ($provider, $inputOutput) {
            $inputOutput->note(sprintf("%s : %s => priority : %d", $message->delivery_info['routing_key'], $message->body, $message->get('priority')));

            $source          = $this->sourceMapper->map($message->body);
            $sourceProcessor = $this->sourceProcessorFactory->getSourceProcessor($source);

            $stats = $sourceProcessor->process($provider);
            if ($stats['total'] > 0) {
                $inputOutput->success(sprintf('There was %s items but only %s of them was imported', $stats['total'], $stats['total_import']));
            } else {
                $inputOutput->success(sprintf('There\'s no more items.'));
            }
        };

        $this->channel->basic_consume(
            $queueName,
            '',
            false,
            true,
            false,
            false,
            $callback,
            null
        );

        $output->writeln(sprintf("[*] Waiting for %s messages . To exit press CTRL+C", $provider));

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->channel->close();
        $this->connection->close();
    }
}
