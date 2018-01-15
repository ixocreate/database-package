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
namespace KiwiSuite\Database\EntityManager\Factory;

use Doctrine\ORM\EntityManagerInterface;
use KiwiSuite\Database\Connection\Factory\ConnectionSubManager;
use KiwiSuite\ServiceManager\ServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerInterface;
use KiwiSuite\ServiceManager\SubManager\SubManagerFactoryInterface;
use KiwiSuite\ServiceManager\SubManager\SubManagerInterface;

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

        $config = [
            'factories' => \array_combine(
                $connections,
                \array_fill(0, \count($connections), EntityManagerFactory::class)
            ),
        ];

        return new EntityManagerSubManager(
            $container,
            new ServiceManagerConfig($config),
            EntityManagerInterface::class
        );
    }
}
