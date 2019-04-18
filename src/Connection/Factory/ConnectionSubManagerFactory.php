<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Connection\Factory;

use Doctrine\DBAL\Connection;
use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Ixocreate\Database\Connection\ConnectionConfig;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerFactoryInterface;
use Ixocreate\ServiceManager\SubManager\SubManagerInterface;

final class ConnectionSubManagerFactory implements SubManagerFactoryInterface
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
