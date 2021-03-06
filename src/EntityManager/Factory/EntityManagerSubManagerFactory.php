<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\EntityManager\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Ixocreate\Database\Connection\Factory\ConnectionSubManager;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerFactoryInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerInterface;

final class EntityManagerSubManagerFactory implements SubManagerFactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @return SubManagerInterface
     */
    public function __invoke(
        ServiceManagerInterface $container,
        $requestedName,
        array $options = null
    ): SubManagerInterface {
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
