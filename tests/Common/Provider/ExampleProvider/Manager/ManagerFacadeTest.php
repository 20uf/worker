<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Common\Provider\ExampleProvider\Manager;

/**
 * Class ManagerFacadeTest
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class ManagerFacadeTest extends \PHPUnit_Framework_TestCase
{
    protected $managerFacade;
    protected $source;
    protected $context;

    /**
     * Setup
     */
    public function setUp()
    {
        $this->context          = \Phake::mock('Worker\Common\Provider\MessageContext');
        $this->managerFacade    = \Phake::partialMock('\Worker\Common\Provider\SocialiveProvider\Manager\ManagerFacade', $this->context);
        $this->source           = \Phake::mock('\Worker\Consumer\Model\Source');

        \Phake::when($this->context)->createMessages($this->anyThing(), $this->anyThing())->thenReturn(true);
    }
}
