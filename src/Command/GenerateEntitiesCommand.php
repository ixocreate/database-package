<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Database\Command;

use Ixocreate\Package\Database\Generator\EntityGenerator;

/**
 * Class GenerateRepositoriesCommand
 * @package Ixocreate\Package\Database\Command
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
