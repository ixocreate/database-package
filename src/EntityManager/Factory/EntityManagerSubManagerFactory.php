<?php
/**
 * kiwi-suite/database (https://github.com/kiwi-suite/database)
 *
 * @package kiwi-suite/database
 * @see https://github.com/kiwi-suite/database
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace Ixocreate\Database\EntityManager\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Ixocreate\Contract\ServiceManager\ServiceManagerInterface;
use Ixocreate\Contract\ServiceManager\SubManager\SubManagerFactoryInterface;
use Ixocreate\Contract\ServiceManager\SubManager\SubManagerInterface;
use Ixocreate\Database\Connection\Factory\ConnectionSubManager;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;

final class EntityManagerSubManagerFactory implements SubManagerFactoryInterface
{

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return SubManagerInterface
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null): SubManagerInterface
    {
        $connections = \array_keys(
            $container->get(ConnectionSubManager::class)->getServiceManagerConfig()->getFactories()
        );

        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        foreach ($connections as $connectionName) {
            $serviceManagerConfigurator->addFactory($connectionName, EntityManagerFactory::class);
        }

        return new EntityManagerSubManager(
            $container,
            $serviceManagerConfigurator->getServiceManagerConfig(),
            EntityManagerInterface::class
        );
    }
}
