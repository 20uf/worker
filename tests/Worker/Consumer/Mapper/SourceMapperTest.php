<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Worker\Consumer\Mapper;

/**
 * Class SourceProcessorFactoryTest
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class SourceMapperTest extends \PHPUnit_Framework_TestCase
{
    protected $sourceMapper;

    /**
     * @setUp
     */
    public function setUp()
    {
        $this->sourceMapper = \Phake::mock('Worker\Consumer\Model\Source\SourceMapper');
    }

    /**
     * @test
     */
    public function testMap()
    {
        $sourceMapper = \Phake::mock('Worker\Consumer\Model\Source\SourceMapper');

        $sourceMapper->map('');

        \Phake::verify($sourceMapper)->map($this->anything());
    }
}
