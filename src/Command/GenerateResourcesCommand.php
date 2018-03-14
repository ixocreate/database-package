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
namespace KiwiSuite\Database\Command;

use KiwiSuite\Database\Generator\ResourceGenerator;

/**
 * Class GenerateResourcesCommand
 * @package KiwiSuite\Database\Command
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
