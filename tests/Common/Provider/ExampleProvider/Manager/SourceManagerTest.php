<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Common\Provider\ExampleProvider\Manager;

/**
 * Class SourceManagerTest
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class SourceManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $sourceManager;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->sourceManager = \Phake::mock('Worker\Common\Provider\ExampleProvider\Manager\SourceManager');
    }

    /**
     * @test
     */
    public function testFindAll()
    {
        $this->sourceManager->findAll();

        \Phake::verify($this->sourceManager)->findAll();
    }
}
