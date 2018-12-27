<?php
namespace Ixocreate\Database;

/** @var ConsoleConfigurator $console */
use Ixocreate\ApplicationConsole\ConsoleConfigurator;
use Ixocreate\Database\Command\GenerateClassesCommand;
use Ixocreate\Database\Command\GenerateCommand;
use Ixocreate\Database\Command\GenerateEntitiesCommand;
use Ixocreate\Database\Command\GenerateMetadataCommand;
use Ixocreate\Database\Command\GenerateRepositoriesCommand;
use Ixocreate\Database\Command\MigrateCommand;
use Ixocreate\Database\Command\StatusCommand;
use Ixocreate\Database\Command\VersionCommand;

$console->addCommand(GenerateCommand::class);
$console->addCommand(MigrateCommand::class);
$console->addCommand(StatusCommand::class);
$console->addCommand(VersionCommand::class);
$console->addCommand(GenerateEntitiesCommand::class);
$console->addCommand(GenerateRepositoriesCommand::class);
$console->addCommand(GenerateClassesCommand::class);
