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
namespace KiwiSuite\Database\Repository;

use Doctrine\Instantiator\Instantiator;
use KiwiSuite\Contract\Application\ConfiguratorInterface;
use KiwiSuite\Contract\Application\ServiceRegistryInterface;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\ServiceManager\Factory\AutowireFactory;
use KiwiSuite\ServiceManager\SubManager\SubManagerConfigurator;

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
        $this->subManagerConfigurator = new SubManagerConfigurator(RepositorySubManager::class, RepositoryInterface::class);
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
