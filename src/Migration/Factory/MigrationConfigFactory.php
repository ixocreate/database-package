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
namespace Ixocreate\Database\Migration\Factory;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Ixocreate\Contract\ServiceManager\FactoryInterface;
use Ixocreate\Contract\ServiceManager\ServiceManagerInterface;
use Ixocreate\Database\Connection\Factory\ConnectionSubManager;

class MigrationConfigFactory implements FactoryInterface
{
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        /** @var Connection $connection */
        $connection = $container->get(ConnectionSubManager::class)->get('master');

        $migrationConfig = new Configuration($connection);
        $migrationConfig->setMigrationsDirectory('resources/migrations');
        $migrationConfig->setMigrationsNamespace('KiwiMigration');
        $migrationConfig->setMigrationsTableName('database_migrations');

        $migrationConfig->setName('Kiwi Database Migrations');

        return $migrationConfig;
    }
}
