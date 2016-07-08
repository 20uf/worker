<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Consumer\Processor;

use Worker\Common\Provider\ExampleProvider\Manager\ManagerFacade;
use Worker\Common\Provider\MessageContext;
use Worker\Common\Factory\ProviderManagerFactory;
use Worker\Consumer\Model\Source;
use Worker\Consumer\Client\RestClient;
use Monolog\Logger;

/**
 * Processor for example source.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
final class ExampleProcessor implements SourceProcessorInterface
{
    /**
     * @var Source
     */
    private $source;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var RestClient
     */
    private $restClient;

    /**
     * @var ManagerFacade
     */
    private $managerFacade;

    /**
     * Constructor class.
     *
     * @param Source $source The source of items.
     */
    public function __construct($source = null)
    {
        $this->source          = $source;
        $this->logger          = new Logger('consumer');
        $this->restClient      = new RestClient($this->logger);
        $this->managerFacade   = new ManagerFacade(new MessageContext());
    }

    /**
     * {@inheritdoc}
     */
    public function process($provider)
    {
        $totalOfImported = 0;

        $managerFacade        = (new ProviderManagerFactory($provider))->getProviderManager($provider);

        $items       = $this->restClient->request($this->source->getUrl());
        $pulledItems = [];
        $totalItems  = count($items);

        if ($totalItems > 0) {
            foreach ($items as $item) {
                $pulledItems[$item['network']][] = $item;
                $totalOfImported++;
            }

            $managerFacade->createMessages($this->source, $pulledItems);
        }

        return [
            'total'        => $totalItems,
            'total_import' => $totalOfImported,
        ];
    }
}
