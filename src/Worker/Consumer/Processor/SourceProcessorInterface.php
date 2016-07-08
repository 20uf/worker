<?php

/*
 * This file is part of the Worker project.
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Consumer\Processor;

/**
 * Interface for Processors of sources.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
interface SourceProcessorInterface
{
    /**
     * Pulls items from source.
     *
     * @param string $provider The name of provider to handle.
     *
     * @return array
     */
    public function process($provider);
}
