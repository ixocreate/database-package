<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Command;

use Doctrine\DBAL\Migrations\Configuration\Configuration;

class MigrateCommand extends ProxyCommand
{
    public function __construct(Configuration $migrationConfig)
    {
        $this->command = new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand();
        $this->command->setMigrationConfiguration($migrationConfig);
        $this->command->setName(self::getCommandName());
        $this->command->setAliases(['migrate']);

        parent::__construct(null);
    }

    public static function getCommandName()
    {
        return 'migration:migrate';
    }
}
