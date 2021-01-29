<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Console;

use Doctrine\Migrations\DependencyFactory;

class GenerateCommand extends ProxyCommand
{
    public function __construct(DependencyFactory $dependencyFactory)
    {
        $this->command = new \Doctrine\Migrations\Tools\Console\Command\GenerateCommand($dependencyFactory);
        $this->command->setName(self::getCommandName());
        $this->command->setAliases(['make:migration']);

        parent::__construct(null);
    }

    public static function getCommandName()
    {
        return 'migration:generate';
    }
}
