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
 * This interface is an implementation of facade design pattern and it exposes complex methods
 * for source manager and message manager.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
interface ManagerFacadeInterface
{
    /**
     * Returns the list of sources from data source.
     *
     * @return \PDOStatement
     */
    public function findSources();

    /**
     * Creates messages in database.
     *
     * @param Source $source The source of pulled items.
     * @param array  $items  The list of pulled items from source.
     */
    public function createMessages(Source $source, array $items = []);
}
