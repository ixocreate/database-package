<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\EntityManager\Factory;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Repository\RepositoryFactory;
use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Contract\ServiceManager\FactoryInterface;
use Ixocreate\Contract\ServiceManager\ServiceManagerInterface;
use Ixocreate\Database\Connection\Factory\ConnectionSubManager;
use Ixocreate\Database\ORM\Mapping\EntityMapper;
use Ixocreate\Database\Repository\EntityRepositoryMapping;
use Ixocreate\Database\Repository\Factory\DoctrineRepositoryFactory;
use Ixocreate\Database\Repository\Factory\RepositorySubManager;

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
