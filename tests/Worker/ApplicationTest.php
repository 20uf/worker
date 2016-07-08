<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Worker;

/**
 * Description of SourceMapper
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test Application
     */
    public function testGetDefaultCommands()
    {
        $commandTester = new \Symfony\Component\Console\Tester\CommandTester($command = (new \Worker\Application())->get('list'));
        $commandTester->execute(array('command' => $command->getName(), '--raw' => true));

        $output = <<<EOF
help                 Displays help for a command
list                 Lists commands
consumer:item:pull   Start consumer to fetch items from source
consumer:run         Start consumer to fetch sources from RabbitMQ
producer:run         start producer to fetch sources and send them to rabbitmq

EOF;

        $this->assertEquals($output, $commandTester->getDisplay(true));
    }
}