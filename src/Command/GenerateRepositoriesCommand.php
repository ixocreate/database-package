<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Package\Command;

use Ixocreate\Database\Package\Generator\RepositoryGenerator;

/**
 * Class GenerateRepositoriesCommand
 * @package Ixocreate\Database\Package\Command
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
