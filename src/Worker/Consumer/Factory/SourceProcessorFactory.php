<?php

/*
 * This file is part of the Worker project.
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Consumer\Factory;

use Worker\Consumer\Model\Source;
use Worker\Consumer\Processor\SourceProcessorInterface;

/**
 * Class SourceProcessorFactory
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class SourceProcessorFactory
{
    /**
     * Returns the correct source processor.
     *
     * @param Source $source The given source to process.
     *
     * @throws \RuntimeException If the type is not implemented or source is null.
     *
     * @return SourceProcessorInterface
     */
    public function getSourceProcessor(Source $source)
    {
        if (isset($source)) {
            $processorClass = sprintf('Worker\\Consumer\\Processor\\%sProcessor', ucfirst($source->getType()));

            if (class_exists($processorClass)) {
                return new $processorClass($source);
            }

            throw new \RuntimeException(sprintf('The type: %s is not supported.', $source->getType()));
        }

        throw new \RuntimeException('Source cannot be empty.');
    }
}
