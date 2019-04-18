<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Database\Command;

use Ixocreate\Package\Database\Generator\ResourceGenerator;

/**
 * Class GenerateResourcesCommand
 * @package Ixocreate\Package\Database\Command
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
