<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Worker\Consumer\Factory;

use Worker\Consumer\Factory\SourceProcessorFactory;
use Worker\Consumer\Model\Source;

/**
 * Class SourceProcessorFactoryTest
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class SourceProcessorFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testGetSourceProcessorNotExists()
    {
        try {
            (new SourceProcessorFactory())->getSourceProcessor(new Source());
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'The type:  is not supported.');
        }
    }

    /**
     * @test
     */
    public function testGetSourceProcessor()
    {
        $sourceProcessor = (new SourceProcessorFactory())->getSourceProcessor((new Source())->setType('example'));
    }
}
