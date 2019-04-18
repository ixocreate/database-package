<?php
namespace Ixocreate\Package\Database;

/** @var ConsoleConfigurator $console */
use Ixocreate\Application\Console\ConsoleConfigurator;
use Ixocreate\Package\Database\Command\GenerateClassesCommand;
use Ixocreate\Package\Database\Command\GenerateCommand;
use Ixocreate\Package\Database\Command\GenerateEntitiesCommand;
use Ixocreate\Package\Database\Command\GenerateMetadataCommand;
use Ixocreate\Package\Database\Command\GenerateRepositoriesCommand;
use Ixocreate\Package\Database\Command\MigrateCommand;
use Ixocreate\Package\Database\Command\StatusCommand;
use Ixocreate\Package\Database\Command\VersionCommand;

$console->addCommand(GenerateCommand::class);
$console->addCommand(MigrateCommand::class);
$console->addCommand(StatusCommand::class);
$console->addCommand(VersionCommand::class);
$console->addCommand(GenerateEntitiesCommand::class);
$console->addCommand(GenerateRepositoriesCommand::class);
$console->addCommand(GenerateClassesCommand::class);
