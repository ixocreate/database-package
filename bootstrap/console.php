<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database;

/** @var ConsoleConfigurator $console */
use Ixocreate\Application\Console\ConsoleConfigurator;
use Ixocreate\Database\Console\GenerateClassesCommand;
use Ixocreate\Database\Console\GenerateCommand;
use Ixocreate\Database\Console\GenerateEntitiesCommand;
use Ixocreate\Database\Console\GenerateRepositoriesCommand;
use Ixocreate\Database\Console\MigrateCommand;
use Ixocreate\Database\Console\StatusCommand;
use Ixocreate\Database\Console\VersionCommand;

$console->addCommand(GenerateCommand::class);
$console->addCommand(MigrateCommand::class);
$console->addCommand(StatusCommand::class);
$console->addCommand(VersionCommand::class);
$console->addCommand(GenerateEntitiesCommand::class);
$console->addCommand(GenerateRepositoriesCommand::class);
$console->addCommand(GenerateClassesCommand::class);
