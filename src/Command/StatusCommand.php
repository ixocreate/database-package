<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Package\Command;

use Doctrine\DBAL\Migrations\Configuration\Configuration;

class StatusCommand extends ProxyCommand
{
    public function __construct(Configuration $migrationConfig)
    {
        $this->command = new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand();
        $this->command->setMigrationConfiguration($migrationConfig);
        $this->command->setName(self::getCommandName());

        parent::__construct(null);
    }

    public static function getCommandName()
    {
        return 'migration:status';
    }
}
