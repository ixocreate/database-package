<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Package\Migration\Factory;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\Database\Package\Connection\Factory\ConnectionSubManager;

class MigrationConfigFactory implements FactoryInterface
{
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        /** @var Connection $connection */
        $connection = $container->get(ConnectionSubManager::class)->get('master');

        $migrationConfig = new Configuration($connection);
        $migrationConfig->setMigrationsDirectory('resources/migrations');
        $migrationConfig->setMigrationsNamespace('IxocreateMigration');
        $migrationConfig->setMigrationsTableName('database_migrations');

        $migrationConfig->setName('Ixocreate Database Migrations');

        return $migrationConfig;
    }
}
