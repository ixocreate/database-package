<?php
declare(strict_types=1);
namespace KiwiSuite\Database;

use KiwiSuite\Database\Connection\ConnectionConfig;
use KiwiSuite\Database\Connection\Factory\ConnectionConfigFactory;
use KiwiSuite\Database\Connection\Factory\ConnectionSubManager;
use KiwiSuite\Database\Connection\Factory\ConnectionSubManagerFactory;
use KiwiSuite\Database\EntityManager\Factory\EntityManagerSubManager;
use KiwiSuite\Database\EntityManager\Factory\EntityManagerSubManagerFactory;
use KiwiSuite\Database\Migration\Factory\MigrationConfigFactory;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;
use Doctrine\DBAL\Migrations\Configuration\Configuration as MigrationConfiguration;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(ConnectionConfig::class, ConnectionConfigFactory::class);
$serviceManager->addFactory(MigrationConfiguration::class, MigrationConfigFactory::class);
$serviceManager->addSubManager(ConnectionSubManager::class, ConnectionSubManagerFactory::class);
$serviceManager->addSubManager(RepositorySubManager::class);
$serviceManager->addSubManager(EntityManagerSubManager::class, EntityManagerSubManagerFactory::class);
