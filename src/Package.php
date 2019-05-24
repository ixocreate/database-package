<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Configurator\ConfiguratorRegistryInterface;
use Ixocreate\Application\Package\PackageInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\Database\Repository\RepositoryBootstrapItem;
use Ixocreate\Database\Type\Strategy\FileStrategy;
use Ixocreate\Database\Type\Strategy\RuntimeStrategy;
use Ixocreate\Database\Type\TypeConfig;
use Ixocreate\ServiceManager\ServiceManagerInterface;

final class Package implements PackageInterface
{
    /**
     * @param ConfiguratorRegistryInterface $configuratorRegistry
     */
    public function configure(ConfiguratorRegistryInterface $configuratorRegistry): void
    {
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     */
    public function addServices(ServiceRegistryInterface $serviceRegistry): void
    {
    }

    /**
     * @return array|null
     */
    public function getBootstrapItems(): ?array
    {
        return [
            RepositoryBootstrapItem::class,
        ];
    }

    /**
     * @return array|null
     */
    public function getConfigProvider(): ?array
    {
        return [
            ConfigProvider::class,
        ];
    }

    /**
     * @param ServiceManagerInterface $serviceManager
     * @throws \Exception
     */
    public function boot(ServiceManagerInterface $serviceManager): void
    {
        /** @var ApplicationConfig $applicationConfig */
        $applicationConfig = $serviceManager->get(ApplicationConfig::class);
        if ($applicationConfig->isDevelopment()) {
            (new RuntimeStrategy())->generate($serviceManager->get(TypeConfig::class));
            return;
        }

        (new FileStrategy())->load($applicationConfig->getPersistCacheDirectory());
    }

    /**
     * @return null|string
     */
    public function getBootstrapDirectory(): ?string
    {
        return __DIR__ . '/../bootstrap';
    }

    /**
     * @return null|string
     */
    public function getConfigDirectory(): ?string
    {
        return null;
    }

    /**
     * @return array|null
     */
    public function getDependencies(): ?array
    {
        return null;
    }
}
