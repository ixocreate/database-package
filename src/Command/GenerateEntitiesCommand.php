<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Package\Command;

use Ixocreate\Database\Package\Generator\EntityGenerator;

/**
 * Class GenerateRepositoriesCommand
 * @package Ixocreate\Database\Package\Command
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
