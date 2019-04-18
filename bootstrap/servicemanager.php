<?php
declare(strict_types=1);
namespace Ixocreate\Package\Database;

use Ixocreate\Package\Database\Connection\ConnectionConfig;
use Ixocreate\Package\Database\Connection\Factory\ConnectionConfigFactory;
use Ixocreate\Package\Database\Connection\Factory\ConnectionSubManager;
use Ixocreate\Package\Database\Connection\Factory\ConnectionSubManagerFactory;
use Ixocreate\Package\Database\EntityManager\Factory\EntityManagerSubManager;
use Ixocreate\Package\Database\EntityManager\Factory\EntityManagerSubManagerFactory;
use Ixocreate\Package\Database\Migration\Factory\MigrationConfigFactory;
use Ixocreate\Package\Database\Repository\Factory\RepositorySubManager;
use Ixocreate\Package\Database\Type\Factory\TypeConfigFactory;
use Ixocreate\Package\Database\Type\TypeConfig;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;
use Doctrine\DBAL\Migrations\Configuration\Configuration as MigrationConfiguration;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(ConnectionConfig::class, ConnectionConfigFactory::class);
$serviceManager->addFactory(MigrationConfiguration::class, MigrationConfigFactory::class);
$serviceManager->addFactory(TypeConfig::class, TypeConfigFactory::class);
$serviceManager->addSubManager(ConnectionSubManager::class, ConnectionSubManagerFactory::class);
$serviceManager->addSubManager(RepositorySubManager::class);
$serviceManager->addSubManager(EntityManagerSubManager::class, EntityManagerSubManagerFactory::class);
