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
namespace Ixocreate\Database\Command;

use Ixocreate\Database\Generator\RepositoryGenerator;

/**
 * Class GenerateRepositoriesCommand
 * @package Ixocreate\Database\Command
 */
class GenerateRepositoriesCommand extends AbstractGenerateCommand
{
    protected static function getType(): string
    {
        return 'repository';
    }

    protected function getGenerators(): array
    {
        return [new RepositoryGenerator()];
    }
}
