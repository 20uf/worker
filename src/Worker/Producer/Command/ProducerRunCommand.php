<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Producer\Command;

use Worker\Common\Factory\ProviderManagerFactory;
use Worker\Common\Rabbitmq\AMQPConnectionFactory;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The command for running the producer of sources in rabbitmq.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class ProducerRunCommand extends Command
{
    /**
     * @var AbstractConnection
     */
    protected $amqpConnection;

    /**
     * @var AMQPChannel
     */
    protected $channel;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * ProducerRunCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->amqpConnection = (new AMQPConnectionFactory())->create();
        $this->channel        = $this->amqpConnection->channel();
        $this->logger         = (new Logger('producer'))->pushHandler(new ErrorLogHandler());
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('producer:run')
            ->setDescription('start producer to fetch sources and send them to rabbitmq')
            ->addOption('channel', '', InputOption::VALUE_OPTIONAL, 'Set the channel', 'socialtv')
            ->addOption('provider', '', InputOption::VALUE_OPTIONAL, 'Set the provider', 'socialive')
            ->addOption('routing_key', '', InputOption::VALUE_OPTIONAL, 'Set the routing key', 'socialtv.socialive.source')
            ->addOption('refresh', '', InputOption::VALUE_OPTIONAL, 'Set the refresh time', 20)
            ->addOption('env', '', InputOption::VALUE_OPTIONAL, 'Set the environment', 'prod')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $provider    = $input->getOption('provider');
        $channel     = $input->getOption('channel');
        $refresh     = $input->getOption('refresh');
        $routingKey  = $input->getOption('routing_key');
        $environment = $input->getOption('env');

        // AMQP_EX_TYPE_DIRECT = direct
        $this->channel->exchange_declare($channel, 'direct', false, false, false);

        $managerFacade = (new ProviderManagerFactory())->getProviderManager($provider);

        while (true) {
            $this->logger->info(sprintf('Get sources for %s', $provider));

            $sources = $managerFacade->findSources();

            foreach ($sources as $source) {
                $msg = new AMQPMessage(json_encode($source), array(
                    'delivery_mode' => 2, // AMQP_DURABLE durable : log on disk
                    'priority'      => 1,
                ));

                $this->channel->basic_publish($msg, $channel, $routingKey);
                $this->logger->info(sprintf(sprintf('Message send => body : %s, provider : %s', $msg->body, $provider)));
            }

            $this->logger->info(sprintf('Waiting %d seconds before the next crawl', $refresh));

            if ($environment === 'test') {
                break;
            }

            sleep((int) $refresh);
        }

        $this->channel->close();
        $this->amqpConnection->close();
    }
}
