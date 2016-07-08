<?php

/*
 * This file is part of the Worker project.
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Common\Rabbitmq;

use PhpAmqpLib\Connection\AbstractConnection;
use Worker\Common\Parser\YamlParser;

/**
 * Factory for creating AMQP connection.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class AMQPConnectionFactory
{
    /**
     * @var array
     */
    private $parameters = array(
        'host'     => null,
        'port'     => null,
        'user'     => null,
        'password' => null,
    );

    /**
     * Constructor class.
     */
    public function __construct()
    {
        $this->setParameters((new YamlParser())->parse());
    }

    /**
     * Creates a connection.
     *
     * @param string $type The type of connection amqp.
     *
     * @return AbstractConnection
     */
    public function create($type = 'Stream')
    {
        $class = sprintf('PhpAmqpLib\\Connection\\AMQP%sConnection', $type);

        if (class_exists($class)) {
            return new $class($this->parameters['host'], $this->parameters['port'], $this->parameters['user'], $this->parameters['password']);
        }

        throw new \RuntimeException(sprintf('The type %s is not supported.', $type));
    }

    /**
     * Setter for parameters.
     *
     * @param array $parameters The list of parameters to merge.
     *
     * @return array
     */
    public function setParameters(array $parameters = array())
    {
        if (array_key_exists('rabbitmq', $parameters)) {
            $this->parameters = array_merge($this->parameters, $parameters['rabbitmq']);
        }
    }

    /**
     * Getter for parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
