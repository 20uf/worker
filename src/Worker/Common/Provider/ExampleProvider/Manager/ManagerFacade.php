<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Common\Provider\ExampleProvider\Manager;

use Worker\Common\Provider\ManagerFacadeInterface;
use Worker\Common\Provider\MessageContext;
use Worker\Consumer\Model\Source;

/**
 * This class implements the design pattern facade and expose complex methods
 * for source manager and message manager.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class ManagerFacade implements ManagerFacadeInterface
{
    /**
     * @var SourceManager
     */
    protected $sourceManager;

    /**
     * @var MessageManager
     */
    protected $messageManager;

    /**
     * @var MessageContext
     */
    protected $context;

    /**
     * ManagerFacade constructor.
     *
     * @param MessageContext $context
     */
    public function __construct(MessageContext $context)
    {
        $this->context        = $context;
        $this->sourceManager  = new SourceManager();
        $this->messageManager = new MessageManager();
    }

    /**
     * {@inheritdoc}
     */
    public function findSources()
    {
        return $this->sourceManager->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function createMessages(Source $source, array $items = [])
    {
        $this->context->setStrategy($this->messageManager);
    }
}
