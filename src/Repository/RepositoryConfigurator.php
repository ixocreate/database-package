<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Repository;

use Doctrine\Instantiator\Instantiator;
use Ixocreate\Application\Configurator\ConfiguratorInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\Application\Service\SubManagerConfigurator;
use Ixocreate\Database\Repository\Factory\RepositorySubManager;
use Ixocreate\ServiceManager\Factory\AutowireFactory;

final class RepositoryConfigurator implements ConfiguratorInterface
{
    /**
     * @var SubManagerConfigurator
     */
    private $subManagerConfigurator;

    /**
     * MiddlewareConfigurator constructor.
     */
    public function __construct()
    {
        $this->subManagerConfigurator = new SubManagerConfigurator(
            RepositorySubManager::class,
            RepositoryInterface::class
        );
    }

    /**
     * @param string $directory
     * @param bool $recursive
     */
    public function addDirectory(string $directory, bool $recursive = true): void
    {
        $this->subManagerConfigurator->addDirectory($directory, $recursive);
    }

    /**
     * @param string $action
     * @param string $factory
     */
    public function addRepository(string $action, string $factory = AutowireFactory::class): void
    {
        $this->subManagerConfigurator->addFactory($action, $factory);
    }

    /**
     * @return SubManagerConfigurator
     */
    public function getManagerConfigurator(): SubManagerConfigurator
    {
        return $this->subManagerConfigurator;
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @throws \Doctrine\Instantiator\Exception\ExceptionInterface
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $config = $this->subManagerConfigurator->getServiceManagerConfig();
        $mapping = [];
        $instantiator = new Instantiator();

        $repositories = \array_keys($config->getFactories());
        foreach ($repositories as $repository) {
            /** @var RepositoryInterface $repositoryClass */
            $repositoryClass = $instantiator->instantiate($repository);
            $mapping[$repositoryClass->getEntityName()] = $repository;
        }

        $serviceRegistry->add(EntityRepositoryMapping::class, new EntityRepositoryMapping($mapping));
        $this->subManagerConfigurator->registerService($serviceRegistry);
    }
}
