<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database;

use Doctrine\DBAL\Migrations\Configuration\Configuration as MigrationConfiguration;
use Ixocreate\Application\Service\ServiceManagerConfigurator;
use Ixocreate\Database\Connection\Factory\ConnectionSubManager;
use Ixocreate\Database\Connection\Factory\ConnectionSubManagerFactory;
use Ixocreate\Database\EntityManager\Factory\EntityManagerSubManager;
use Ixocreate\Database\EntityManager\Factory\EntityManagerSubManagerFactory;
use Ixocreate\Database\Migration\Factory\MigrationConfigFactory;
use Ixocreate\Database\Repository\Factory\RepositorySubManager;
use Ixocreate\Database\Type\Factory\TypeConfigFactory;
use Ixocreate\Database\Type\TypeConfig;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(MigrationConfiguration::class, MigrationConfigFactory::class);
$serviceManager->addFactory(TypeConfig::class, TypeConfigFactory::class);
$serviceManager->addSubManager(ConnectionSubManager::class, ConnectionSubManagerFactory::class);
$serviceManager->addSubManager(RepositorySubManager::class);
$serviceManager->addSubManager(EntityManagerSubManager::class, EntityManagerSubManagerFactory::class);
