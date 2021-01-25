<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database;

use Doctrine\Migrations\DependencyFactory;
use Ixocreate\Application\ServiceManager\ServiceManagerConfigurator;
use Ixocreate\Database\Connection\Factory\ConnectionSubManager;
use Ixocreate\Database\Connection\Factory\ConnectionSubManagerFactory;
use Ixocreate\Database\EntityManager\Factory\EntityManagerSubManager;
use Ixocreate\Database\EntityManager\Factory\EntityManagerSubManagerFactory;
use Ixocreate\Database\Migration\Factory\DependencyFactoryFactory;
use Ixocreate\Database\Repository\Factory\RepositorySubManager;
use Ixocreate\Database\Type\Factory\TypeConfigFactory;
use Ixocreate\Database\Type\TypeConfig;

/** @var ServiceManagerConfigurator $serviceManager */
$serviceManager->addFactory(DependencyFactory::class, DependencyFactoryFactory::class);
$serviceManager->addLazyService(DependencyFactory::class);

$serviceManager->addFactory(TypeConfig::class, TypeConfigFactory::class);
$serviceManager->addSubManager(ConnectionSubManager::class, ConnectionSubManagerFactory::class);
$serviceManager->addSubManager(RepositorySubManager::class);
$serviceManager->addSubManager(EntityManagerSubManager::class, EntityManagerSubManagerFactory::class);
