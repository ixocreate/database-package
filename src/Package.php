<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\Service\Configurator\ConfiguratorRegistryInterface;
use Ixocreate\Application\PackageInterface;
use Ixocreate\Application\Service\Registry\ServiceRegistryInterface;
use Ixocreate\Database\Bootstrap\RepositoryBootstrapItem;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\Database\Type\Strategy\FileStrategy;
use Ixocreate\Database\Type\Strategy\RuntimeStrategy;
use Ixocreate\Database\Type\TypeConfig;

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
