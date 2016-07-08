<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Common\Provider;

use Worker\Consumer\Model\Source;

/**
 * The interface for each strategy class.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
interface StrategyInterface
{
    /**
     * Creates a message in database.
     *
     * @param Source $source The source of pulled items.
     * @param array  $items  The list of pulled items from source.
     */
    public function create(Source $source, array $items = []);
}
