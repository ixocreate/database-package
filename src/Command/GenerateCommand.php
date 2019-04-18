<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Database\Command;

use Doctrine\DBAL\Migrations\Configuration\Configuration;

class GenerateCommand extends ProxyCommand
{
    public function __construct(Configuration $migrationConfig)
    {
        $this->command = new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand();
        $this->command->setMigrationConfiguration($migrationConfig);
        $this->command->setName(self::getCommandName());
        $this->command->setAliases(['make:migration']);

        $class = new \ReflectionClass($this->command);
        $property = $class->getProperty('_template');
        $property->setAccessible(true);
        $property->setValue(
            \str_replace(
                "/**\n * Auto-generated Migration: Please modify to your needs!\n */\n",
                '',
                $property->getValue()
        )
        );

        parent::__construct(null);
    }

    public static function getCommandName()
    {
        return 'migration:generate';
    }
}
