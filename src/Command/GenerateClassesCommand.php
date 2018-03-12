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

use KiwiSuite\Database\Generator\EntityGenerator;
use KiwiSuite\Database\Generator\GeneratorInterface;
use KiwiSuite\Database\Generator\MetadataGenerator;
use KiwiSuite\Database\Generator\RepositoryGenerator;
use KiwiSuite\Database\Generator\ResourceGenerator;

/**
 * Class GenerateRepositoriesCommand
 * @package KiwiSuite\Database\Command
 */
class GenerateClassesCommand extends AbstractGenerateCommand
{

    static protected function getType(): string
    {
        return 'from-db';
    }

    protected function getGenerators(): array
    {
        return [
            new EntityGenerator(),
            new MetadataGenerator(),
            new RepositoryGenerator(),
            new ResourceGenerator()
        ];
    }
}
