<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Common\Builder;

use Monolog\Logger;
use Worker\Common\Parser\YamlParser;

/**
 * Builder for dao.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class DaoBuilder
{
    /**
     * @var string
     */
    private $driver;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $dbname;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $port;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var DaoBuilder
     */
    private static $instance;

    /**
     * DaoBuilder constructor.
     *
     * @param $environment
     */
    private function __construct($environment = 'prod')
    {
        $this
            ->configure((new YamlParser($environment))->parse())
            ->connect()
        ;
    }

    /**
     * Returns a single instance of pdo connection.
     *
     * @param string $environment
     *
     * @return DaoBuilder
     */
    public static function getInstance($environment = 'prod')
    {
        if (isset(self::$instance[$environment])) {
            return self::$instance[$environment];
        }

        self::$instance[$environment] = new DaoBuilder($environment);

        return self::$instance[$environment];
    }

    /**
     * Returns the pdo instance.
     *
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Connects to database.
     *
     * @return $this
     */
    private function connect()
    {
        try {
            $this->pdo = new \PDO(
                sprintf('%s:host=%s;dbname=%s;port=%s', $this->driver, $this->host, $this->dbname, $this->port),
                $this->user,
                $this->password,
                [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
            );
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            (new Logger('producer'))->crit($e->getMessage());
        }

        return $this;
    }

    /**
     * Configures database.
     *
     * @param array $parameters The list of parameters to merge.
     * @param string $provider
     *
     * @return self
     */
    private function configure(array $parameters = array(), $provider = 'social_live')
    {
        if (array_key_exists($provider, $parameters)) {
            $providerParams = $parameters[$provider];
            $this->user     = array_key_exists('database_user', $providerParams) ? $providerParams['database_user'] : null;
            $this->password = array_key_exists('database_password', $providerParams) ? $providerParams['database_password'] : null;
            $this->dbname   = array_key_exists('database_name', $providerParams) ? $providerParams['database_name'] : null;
            $this->port     = array_key_exists('database_port', $providerParams) ? $providerParams['database_port'] : null;
            $this->driver   = array_key_exists('database_driver', $providerParams) ? $providerParams['database_driver'] : null;
            $this->host     = array_key_exists('database_host', $providerParams) ? $providerParams['database_host'] : null;
        }

        return $this;
    }
}
