<?php
declare(strict_types=1);
namespace Ixocreate\Database\Package;

use Ixocreate\Database\Package\Connection\ConnectionConfig;
use Ixocreate\Database\Package\Connection\Factory\ConnectionConfigFactory;
use Ixocreate\Database\Package\Connection\Factory\ConnectionSubManager;
use Ixocreate\Database\Package\Connection\Factory\ConnectionSubManagerFactory;
use Ixocreate\Database\Package\EntityManager\Factory\EntityManagerSubManager;
use Ixocreate\Database\Package\EntityManager\Factory\EntityManagerSubManagerFactory;
use Ixocreate\Database\Package\Migration\Factory\MigrationConfigFactory;
use Ixocreate\Database\Package\Repository\Factory\RepositorySubManager;
use Ixocreate\Database\Package\Type\Factory\TypeConfigFactory;
use Ixocreate\Database\Package\Type\TypeConfig;
use Ixocreate\ServiceManager\ServiceManagerConfigurator;
use Doctrine\DBAL\Migrations\Configuration\Configuration as MigrationConfiguration;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(ConnectionConfig::class, ConnectionConfigFactory::class);
$serviceManager->addFactory(MigrationConfiguration::class, MigrationConfigFactory::class);
$serviceManager->addFactory(TypeConfig::class, TypeConfigFactory::class);
$serviceManager->addSubManager(ConnectionSubManager::class, ConnectionSubManagerFactory::class);
$serviceManager->addSubManager(RepositorySubManager::class);
$serviceManager->addSubManager(EntityManagerSubManager::class, EntityManagerSubManagerFactory::class);
