<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Database;

use Ixocreate\Application\ApplicationConfig;
use Ixocreate\Application\ApplicationConfigurator;
use Ixocreate\Application\Configurator\ConfiguratorRegistryInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\Database\ConfigProvider;
use Ixocreate\Database\DatabaseBootstrapItem;
use Ixocreate\Database\Package;
use Ixocreate\Database\Repository\RepositoryBootstrapItem;
use Ixocreate\Database\Type\TypeConfig;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use PHPUnit\Framework\TestCase;

class PackageTest extends TestCase
{
    private function mockServiceManager()
    {
        $mock = $this->createMock(ServiceManagerInterface::class);
        $mock->method('get')
            ->willReturnCallback(function ($request) {
                switch ($request) {
                    case ApplicationConfig::class:
                        $applicationConfigurator = new ApplicationConfigurator('bootstrap');
                        return new ApplicationConfig($applicationConfigurator);
                    case TypeConfig::class:
                        return new TypeConfig([]);
                }
                return null;
            });
        return $mock;
    }

    /**
     * @covers \Ixocreate\Database\Package
     */
    public function testPackage()
    {
        $configuratorRegistry = $this->getMockBuilder(ConfiguratorRegistryInterface::class)->getMock();
        $serviceRegistry = $this->getMockBuilder(ServiceRegistryInterface::class)->getMock();
        $serviceManager = $this->mockServiceManager();

        $package = new Package();
        $package->configure($configuratorRegistry);
        $package->addServices($serviceRegistry);
        $package->boot($serviceManager);

        $this->assertSame([
            RepositoryBootstrapItem::class,
            DatabaseBootstrapItem::class
        ], $package->getBootstrapItems());
        $this->assertNull($package->getConfigDirectory());
        $this->assertNull($package->getConfigProvider());
        $this->assertNull($package->getDependencies());
        $this->assertDirectoryExists($package->getBootstrapDirectory());
    }
}
