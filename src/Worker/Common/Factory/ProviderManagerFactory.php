<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Common\Factory;

use Worker\Common\Provider\ManagerFacadeInterface;
use Worker\Common\Provider\MessageContext;

/**
 * The factory for creating an instance of provider's manager.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class ProviderManagerFactory
{
    /**
     * Returns the manager for provider.
     *
     * @param string $provider The name of provider to load.
     *
     * @return ManagerFacadeInterface
     */
    public function getProviderManager($provider)
    {
        $class = sprintf('Worker\\Common\\Provider\\%sProvider\\Manager\\ManagerFacade', ucfirst($provider));

        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('Provider %s does not exist', $class));
        }

        return new $class(new MessageContext());
    }
}
