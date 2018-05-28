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

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Repository\RepositoryFactory;
use KiwiSuite\Application\ApplicationConfig;
use KiwiSuite\Contract\ServiceManager\FactoryInterface;
use KiwiSuite\Contract\ServiceManager\ServiceManagerInterface;
use KiwiSuite\Database\Connection\Factory\ConnectionSubManager;
use KiwiSuite\Database\ORM\Mapping\EntityMapper;
use KiwiSuite\Database\Repository\EntityRepositoryMapping;
use KiwiSuite\Database\Repository\Factory\DoctrineRepositoryFactory;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;

final class EntityManagerFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Doctrine\ORM\ORMException
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $configuration = new Configuration();
        $configuration->setMetadataDriverImpl($this->getMetaDriverImpl($container));
        $configuration->setMetadataCacheImpl($this->getMetaCacheImpl($container));
        $configuration->setRepositoryFactory($this->getRepositoryFactory($container));
        $configuration->setProxyDir($container->get(ApplicationConfig::class)->getPersistCacheDirectory() . 'doctrine_proxy/');
        $configuration->setProxyNamespace('DoctrineProxies');

        //TODO change for production
        $configuration->setAutoGenerateProxyClasses(ProxyFactory::AUTOGENERATE_EVAL);

        return EntityManager::create(
            $container->get(ConnectionSubManager::class)->get($requestedName),
            $configuration
        );
    }

    private function getMetaCacheImpl(ServiceManagerInterface $container) : Cache
    {
        return new ArrayCache();
    }

    /**
     * @param ServiceManagerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return MappingDriver
     */
    private function getMetaDriverImpl(ServiceManagerInterface $container) : MappingDriver
    {
        return new EntityMapper($container->get(RepositorySubManager::class), $container->get(EntityRepositoryMapping::class));
    }

    /**
     * @param ServiceManagerInterface $container
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return RepositoryFactory
     */
    private function getRepositoryFactory(ServiceManagerInterface $container) : RepositoryFactory
    {
        return new DoctrineRepositoryFactory(
            $container->get(RepositorySubManager::class),
            $container->get(EntityRepositoryMapping::class)
        );
    }
}
