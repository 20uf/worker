<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Common\Provider\Example\Provider;

/**
 * Class MessageContextTest
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class MessageContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testSetStrategy()
    {
        $strategy   = \Phake::mock('Worker\Common\Provider\StrategyInterface');
        $context    = \Phake::partialMock('\Worker\Common\Provider\MessageContext', $strategy);

        $context->setStrategy($strategy);

        \Phake::verify($context)->setStrategy($strategy);
    }

    /**
     * @test
     */
    public function testExecuteStrategy()
    {
        $strategy   = \Phake::mock('Worker\Common\Provider\StrategyInterface');
        $context    = \Phake::partialMock('\Worker\Common\Provider\MessageContext', $strategy);
        $source     = new \Worker\Consumer\Model\Source();

        $context->executeStrategyCreate($source, []);

        \Phake::verify($strategy)->create($source, []);
    }
}