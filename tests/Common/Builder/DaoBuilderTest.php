<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\Builder;

/**
 * Class DaoBuilderTest
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class DaoBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $daoBuilder;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->daoBuilder = \Phake::mock('Worker\Common\Builder\DaoBuilder');
    }

    /**
     * @test
     */
    public function testGetInstance()
    {
        \Phake::whenStatic($this->daoBuilder)->getInstance()->thenReturn($this->daoBuilder);
        $proxy = new \Phake_Proxies_StaticVisibilityProxy($this->daoBuilder);

        $this->assertEquals($this->daoBuilder, $proxy->getInstance());

        \Phake::verifyStatic($this->daoBuilder)->getInstance();
    }
}
