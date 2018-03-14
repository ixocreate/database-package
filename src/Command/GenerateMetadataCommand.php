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

use KiwiSuite\Database\Generator\GeneratorInterface;
use KiwiSuite\Database\Generator\MetadataGenerator;

/**
 * Class GenerateMetadataCommand
 * @package KiwiSuite\Database\Command
 */
class GenerateMetadataCommand extends AbstractGenerateCommand
{
    static protected function getType(): string
    {
        return 'metadata';
    }

    protected function getGenerators(): array
    {
        return [new MetadataGenerator()];
    }

}
