<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker;

use Worker\Consumer\Command\ConsumerItemPullCommand;
use Worker\Consumer\Command\ConsumerRunCommand;
use Worker\Producer\Command\ProducerRunCommand;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Main class for application
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class Application extends BaseApplication
{
    /**
     * Returns the default commands that should always be available.
     *
     * @return array
     */
    public function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        $commands[] = new ProducerRunCommand();
        $commands[] = new ConsumerRunCommand();
        $commands[] = new ConsumerItemPullCommand();

        return $commands;
    }
}
