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
namespace KiwiSuite\Database\Bootstrap;

use Doctrine\DBAL\Migrations\Configuration\Configuration as MigrationConfiguration;
use KiwiSuite\Application\Bootstrap\BootstrapInterface;
use KiwiSuite\Application\ConfiguratorItem\ConfiguratorRegistry;
use KiwiSuite\Application\ConfiguratorItem\ServiceManagerConfiguratorItem;
use KiwiSuite\Application\Service\ServiceRegistry;
use KiwiSuite\ApplicationConsole\ConfiguratorItem\ConsoleConfiguratorItem;
use KiwiSuite\Database\Command\GenerateCommand;
use KiwiSuite\Database\Command\MigrateCommand;
use KiwiSuite\Database\Command\StatusCommand;
use KiwiSuite\Database\Command\VersionCommand;
use KiwiSuite\Database\ConfiguratorItem\RepositoryManagerConfiguratorItem;
use KiwiSuite\Database\ConfiguratorItem\TypeConfiguratorItem;
use KiwiSuite\Database\Connection\ConnectionConfig;
use KiwiSuite\Database\Connection\Factory\ConnectionConfigFactory;
use KiwiSuite\Database\Connection\Factory\ConnectionSubManager;
use KiwiSuite\Database\Connection\Factory\ConnectionSubManagerFactory;
use KiwiSuite\Database\EntityManager\Factory\EntityManagerSubManager;
use KiwiSuite\Database\EntityManager\Factory\EntityManagerSubManagerFactory;
use KiwiSuite\Database\Migration\Factory\MigrationConfigFactory;
use KiwiSuite\Database\Repository\EntityRepositoryMapping;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\Factory\RepositorySubManagerFactory;
use KiwiSuite\Database\Repository\RepositoryServiceManagerConfig;
use KiwiSuite\Database\Type\Strategy\RuntimeStrategy;
use KiwiSuite\Database\Type\TypeConfig;
use KiwiSuite\ServiceManager\ServiceManager;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

class DatabaseBootstrap implements BootstrapInterface
{

    /**
     * @param ConfiguratorRegistry $configuratorRegistry
     */
    public function configure(ConfiguratorRegistry $configuratorRegistry): void
    {
        /** @var ServiceManagerConfigurator $serviceManagerConfigurator */
        $serviceManagerConfigurator = $configuratorRegistry->get(ServiceManagerConfiguratorItem::class);

        $serviceManagerConfigurator->addFactory(ConnectionConfig::class, ConnectionConfigFactory::class);
        $serviceManagerConfigurator->addFactory(MigrationConfiguration::class, MigrationConfigFactory::class);

        $serviceManagerConfigurator->addSubManager(ConnectionSubManager::class, ConnectionSubManagerFactory::class);
        $serviceManagerConfigurator->addSubManager(RepositorySubManager::class, RepositorySubManagerFactory::class);
        $serviceManagerConfigurator->addSubManager(EntityManagerSubManager::class, EntityManagerSubManagerFactory::class);

        if ($configuratorRegistry->has(ConsoleConfiguratorItem::class)) {
            $consoleConfigurator = $configuratorRegistry->get(ConsoleConfiguratorItem::class);

            $consoleConfigurator->addFactory(GenerateCommand::class);
            $consoleConfigurator->addFactory(MigrateCommand::class);
            $consoleConfigurator->addFactory(StatusCommand::class);
            $consoleConfigurator->addFactory(VersionCommand::class);
        }
    }

    /**
     * @return array|null
     */
    public function getDefaultConfig(): ?array
    {
        return [
            'database' => [
                'master' => [
                    'dbname' => 'test',
                    'user' => 'root',
                    'password' => '',
                    'host' => '127.0.0.1',
                    'driver' => 'pdo_mysql',
                ],
            ],
        ];
    }

    /**
     * @param ServiceManager $serviceManager
     */
    public function boot(ServiceManager $serviceManager): void
    {
        $runtimeStrategy = new RuntimeStrategy();
        $runtimeStrategy->generate($serviceManager->get(TypeConfig::class));
    }

    /**
     * @param ServiceRegistry $serviceRegistry
     */
    public function addServices(ServiceRegistry $serviceRegistry): void
    {
        $serviceRegistry->addService(EntityRepositoryMapping::class, EntityRepositoryMapping::create($serviceRegistry->getService(RepositoryServiceManagerConfig::class)));
    }

    /**
     * @return array|null
     */
    public function getConfiguratorItems(): ?array
    {
        return [
            RepositoryManagerConfiguratorItem::class,
            TypeConfiguratorItem::class
        ];
    }
}
