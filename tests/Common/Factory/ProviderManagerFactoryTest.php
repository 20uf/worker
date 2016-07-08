<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\Factory;

use Worker\Common\Factory\ProviderManagerFactory;
use Worker\Common\Provider\MessageContext;
use Worker\Common\Provider\ExampleProvider\Manager\ManagerFacade;

/**
 * Class ProviderManagerFactoryTest
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class ProviderManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testGetProviderManagerNotExists()
    {
        try {
            (new ProviderManagerFactory())->getProviderManager('nothing');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Provider Worker\\Common\\Provider\\NothingProvider\\Manager\\ManagerFacade does not exist');
        }
    }

    /**
     * @test
     */
    public function testGetProviderManager()
    {
        $providerManager    = (new ProviderManagerFactory())->getProviderManager('example');
        $context            = new MessageContext();

        $this->assertEquals($providerManager, new ManagerFacade($context));
    }
}
