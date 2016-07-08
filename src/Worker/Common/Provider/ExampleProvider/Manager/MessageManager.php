<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Common\Provider\ExampleProvider\Manager;

use Worker\Common\Provider\StrategyInterface;
use Worker\Common\Builder\DaoBuilder;
use Worker\Consumer\Model\Source;
use Monolog\Logger;

/**
 * Class responsible for managing message in database.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class MessageManager implements StrategyInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Constructor class.
     */
    public function __construct()
    {
        $this->pdo    = DaoBuilder::getInstance()->getPdo();
        $this->logger = new Logger('consumer');
    }

    /**
     * Creates a message in database.
     *
     * @param Source $source The source of pulled items.
     * @param array  $items  The list of pulled items from source.
     */
    public function create(Source $source, array $items = [])
    {
        // Nothing, todo :)
    }
}
