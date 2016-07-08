<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Consumer\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception;
use GuzzleHttp\Exception\ClientException;
use Psr\Log\LoggerInterface;

/**
 * Rest client.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class RestClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor class.
     *
     * @param LoggerInterface $logger Instance of logger.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->client = new Client();
        $this->logger = $logger;
    }

    /**
     * Requests the api.
     *
     * @param string $url    The url of webservice.
     * @param string $method The method of webservice (default GET).
     *
     * @return array
     * @throws \Exception
     */
    public function request($url, $method = 'GET')
    {
        try {
            $response = $this->client->request($method, $url);
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
            throw $exception;
        } catch (ClientException $exception) {
            $this->logger->error($exception->getMessage());
            throw $exception;
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
