<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Worker\Producer;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Worker\Application;
use Worker\Producer\Command\ProducerRunCommand;

/**
 * Class ProducerRunCommandTest
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class ProducerRunCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $input;
    protected $output;
    protected $args;
    protected $amqpConnection;
    protected $channel;
    protected $logger;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->args             = [
            'command'               => 'producer:run',
            '--environment'         => 'test',
            '--no-interaction'      => false,
        ];
        $this->input            = new ArrayInput($this->args);
        $this->output           = new BufferedOutput();
    }

    /**
     * Execute
     */
    public function testExecute()
    {

        $application = new Application();
        $application->add(new ProducerRunCommand());

        $exitCode = $application->doRun($this->input, $this->output);

        $this->assertEquals($exitCode, 0);
    }
}
