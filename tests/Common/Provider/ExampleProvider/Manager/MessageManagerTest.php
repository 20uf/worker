<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Common\Provider\ExampleProvider\Manager;

use Worker\Consumer\Model\Source;

/**
 * Class MessageManagerTest
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class MessageManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $messageManager;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->messageManager = \Phake::mock('Worker\Common\Provider\ExampleProvider\Manager\MessageManager');
    }

    /**
     * @test
     */
    public function testShouldCreateItem()
    {
        $this->messageManager->create(new Source(), []);

        \Phake::verify($this->messageManager)->create($this->anything(), $this->anything());
    }
}
