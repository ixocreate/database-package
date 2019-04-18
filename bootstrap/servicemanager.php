<?php
declare(strict_types=1);
namespace Ixocreate\Database;

use Ixocreate\Database\Connection\ConnectionConfig;
use Ixocreate\Database\Connection\Factory\ConnectionConfigFactory;
use Ixocreate\Database\Connection\Factory\ConnectionSubManager;
use Ixocreate\Database\Connection\Factory\ConnectionSubManagerFactory;
use Ixocreate\Database\EntityManager\Factory\EntityManagerSubManager;
use Ixocreate\Database\EntityManager\Factory\EntityManagerSubManagerFactory;
use Ixocreate\Database\Migration\Factory\MigrationConfigFactory;
use Ixocreate\Database\Repository\Factory\RepositorySubManager;
use Ixocreate\Database\Type\Factory\TypeConfigFactory;
use Ixocreate\Database\Type\TypeConfig;
use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Doctrine\DBAL\Migrations\Configuration\Configuration as MigrationConfiguration;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(ConnectionConfig::class, ConnectionConfigFactory::class);
$serviceManager->addFactory(MigrationConfiguration::class, MigrationConfigFactory::class);
$serviceManager->addFactory(TypeConfig::class, TypeConfigFactory::class);
$serviceManager->addSubManager(ConnectionSubManager::class, ConnectionSubManagerFactory::class);
$serviceManager->addSubManager(RepositorySubManager::class);
$serviceManager->addSubManager(EntityManagerSubManager::class, EntityManagerSubManagerFactory::class);
