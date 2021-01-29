<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Console;

use Ixocreate\Database\Generator\RepositoryGenerator;

/**
 * Class GenerateRepositoriesCommand
 * @package Ixocreate\Database\Console
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
