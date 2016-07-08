<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Worker\Consumer\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Worker\Application;
use Worker\Consumer\Command\ConsumerRunCommand;

/**
 * Class ConsumerRunCommandTest
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class ConsumerRunCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testExecute()
    {
        $application = new Application();
        $application->add(new ConsumerRunCommand());

        $command = $application->find('consumer:run');
        $commandTester = new CommandTester($command);

        $exitCode = $commandTester->execute([
            'command'               => $command->getName(),
            '--environment'         => 'test',
        ]);

        $this->assertEquals($exitCode, 0);
    }
}
