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
 * The context for executing the correct strategy.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class MessageContext
{
    /**
     * @var StrategyInterface
     */
    protected $strategy;

    /**
     * Constructor class.
     *
     * @param StrategyInterface $strategy An instance of strategy class (default null).
     */
    public function __construct(StrategyInterface $strategy = null)
    {
        $this->strategy = $strategy;
    }

    /**
     * Setter for object strategy.
     *
     * @param StrategyInterface $strategy An instance of strategy class.
     */
    public function setStrategy(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * Executes create strategy.
     *
     * @param Source $source The source of pulled items.
     * @param array  $items  The list of pulled items from source.
     */
    public function executeStrategyCreate(Source $source, array $items = [])
    {
        $this->strategy->create($source, $items);
    }
}
