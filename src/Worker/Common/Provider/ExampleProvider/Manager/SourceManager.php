<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Common\Provider\ExampleProvider\Manager;

use Worker\Common\Builder\DaoBuilder;
use Monolog\Logger;

/**
 * Class responsible for managing sources in database.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class SourceManager
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * Constructor class.
     */
    public function __construct()
    {
        $this->pdo    = DaoBuilder::getInstance()->getPdo();
        $this->logger = new Logger('producer');
    }

    /**
     * Returns the list of all sources.
     *
     * @return \PDOStatement
     */
    public function findAll()
    {
        // nothing, todo :)

        return [];
    }
}
