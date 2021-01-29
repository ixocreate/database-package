<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Migration\Factory;

use Doctrine\DBAL\Connection;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ConfigurationArray;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Finder\MigrationFinder;
use Doctrine\Migrations\Finder\RecursiveRegexFinder;
use Ixocreate\Database\Connection\Factory\ConnectionSubManager;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

class DependencyFactoryFactory implements FactoryInterface
{
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        /** @var Connection $connection */
        $connection = $container->get(ConnectionSubManager::class)->get('master');

        $config = [
            'table_storage' => [
                'table_name' => 'database_migrations',
                'version_column_name' => 'version',
                'version_column_length' => 191,
                'executed_at_column_name' => 'executed_at',
                'execution_time_column_name' => 'execution_time',
            ],

            'migrations_paths' => [
                'Ixocreate\Migration' => 'resources/migrations',
            ],

            'all_or_nothing' => true,
            'check_database_platform' => true,

            'custom_template' => __DIR__ . '/../../../resources/migration_template.txt',
        ];

        $configurationLoader = new ConfigurationArray($config);
        $connectionLoader = new ExistingConnection($connection);

        $factory = DependencyFactory::fromConnection($configurationLoader, $connectionLoader);
        $factory->setDefinition(MigrationFinder::class, function (): MigrationFinder {
            return new RecursiveRegexFinder();
        });

        return $factory;
    }
}
