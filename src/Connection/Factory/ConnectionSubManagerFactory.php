<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Connection\Factory;

use Doctrine\DBAL\Connection;
use Ixocreate\Contract\ServiceManager\ServiceManagerInterface;
use Ixocreate\Contract\ServiceManager\SubManager\SubManagerFactoryInterface;
use Ixocreate\Contract\ServiceManager\SubManager\SubManagerInterface;
use Ixocreate\Database\Connection\ConnectionConfig;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;

final class ConnectionSubManagerFactory implements SubManagerFactoryInterface
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
        $serviceManagerConfigurator = new ServiceManagerConfigurator();

        /** @var ConnectionConfig $connectionConfig */
        $connectionConfig = $container->get(ConnectionConfig::class);
        foreach ($connectionConfig->getConnections() as $connection) {
            $serviceManagerConfigurator->addFactory($connection, ConnectionFactory::class);
            $serviceManagerConfigurator->addLazyService($connection, Connection::class);
        }

        return new ConnectionSubManager(
            $container,
            $serviceManagerConfigurator->getServiceManagerConfig(),
            \Doctrine\DBAL\Driver\Connection::class
        );
    }
}