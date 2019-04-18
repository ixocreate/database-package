<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Package\Command;

use Ixocreate\Database\Package\Generator\ResourceGenerator;

/**
 * Class GenerateResourcesCommand
 * @package Ixocreate\Database\Package\Command
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
