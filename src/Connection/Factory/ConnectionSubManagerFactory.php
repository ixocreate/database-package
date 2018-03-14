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
namespace KiwiSuite\Database\Connection\Factory;

use Doctrine\DBAL\Connection;
use KiwiSuite\Contract\ServiceManager\ServiceManagerInterface;
use KiwiSuite\Contract\ServiceManager\SubManager\SubManagerFactoryInterface;
use KiwiSuite\Contract\ServiceManager\SubManager\SubManagerInterface;
use KiwiSuite\Database\Connection\ConnectionConfig;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

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
