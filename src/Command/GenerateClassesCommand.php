<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Database\Command;

use Ixocreate\Package\Database\Generator\EntityGenerator;
use Ixocreate\Package\Database\Generator\RepositoryGenerator;

/**
 * Class GenerateRepositoriesCommand
 * @package Ixocreate\Package\Database\Command
 */
class GenerateClassesCommand extends AbstractGenerateCommand
{
    protected static function getType(): string
    {
        return 'from-db';
    }

    protected function getGenerators(): array
    {
        return [
            new EntityGenerator($this->typeSubManager),
            new RepositoryGenerator(),
        ];
    }
}
