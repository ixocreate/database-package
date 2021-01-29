<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Console;

use Ixocreate\Database\Generator\EntityGenerator;

/**
 * Class GenerateRepositoriesCommand
 * @package Ixocreate\Database\Console
 */
class GenerateEntitiesCommand extends AbstractGenerateCommand
{
    protected static function getType(): string
    {
        return 'entity';
    }

    protected function getGenerators(): array
    {
        return [new EntityGenerator($this->typeSubManager)];
    }
}
