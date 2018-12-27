<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Command;

use Ixocreate\Database\Generator\ResourceGenerator;

/**
 * Class GenerateResourcesCommand
 * @package Ixocreate\Database\Command
 */
class GenerateResourcesCommand extends AbstractGenerateCommand
{
    protected static function getType(): string
    {
        return 'resource';
    }

    protected function getGenerators(): array
    {
        return [new ResourceGenerator()];
    }
}
