<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\EntityManager\Factory;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Database\Connection\Factory\ConnectionSubManager;
use Ixocreate\Database\ORM\Mapping\EntityMapper;
use Ixocreate\Database\Repository\EntityRepositoryMapping;
use Ixocreate\Database\Repository\Factory\DoctrineRepositoryFactory;
use Ixocreate\Database\Repository\Factory\RepositorySubManager;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

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
        return EntityManager::create(
            $container->get(ConnectionSubManager::class)->get($requestedName),
            $this->createConfiguration($container)
        );
    }

    private function createConfiguration(ServiceManagerInterface $container): Configuration
    {
        /** @var ApplicationConfig $applicationConfig */
        $applicationConfig = $container->get(ApplicationConfig::class);

        $configuration = new Configuration();
        $configuration->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_NEVER);
        $configuration->setProxyDir(\sys_get_temp_dir());
        $configuration->setProxyNamespace('Ixocreate\DoctrineProxy');

        $configuration->setMetadataDriverImpl(
            new EntityMapper($container->get(EntityRepositoryMapping::class))
        );

        $configuration->setRepositoryFactory(
            new DoctrineRepositoryFactory($container->get(RepositorySubManager::class), $container->get(EntityRepositoryMapping::class))
        );

        $configuration->setMetadataCacheImpl(new PhpFileCache(
            $applicationConfig->getPersistCacheDirectory() . 'database/doctrine_metadata'
        ));

        if ($applicationConfig->isDevelopment()) {
            $configuration->setMetadataCacheImpl(new ArrayCache());
        }

        return $configuration;
    }
}
