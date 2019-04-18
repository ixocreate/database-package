<?php
namespace Ixocreate\Database\Package;

/** @var ConsoleConfigurator $console */
use Ixocreate\Application\Console\ConsoleConfigurator;
use Ixocreate\Database\Package\Command\GenerateClassesCommand;
use Ixocreate\Database\Package\Command\GenerateCommand;
use Ixocreate\Database\Package\Command\GenerateEntitiesCommand;
use Ixocreate\Database\Package\Command\GenerateMetadataCommand;
use Ixocreate\Database\Package\Command\GenerateRepositoriesCommand;
use Ixocreate\Database\Package\Command\MigrateCommand;
use Ixocreate\Database\Package\Command\StatusCommand;
use Ixocreate\Database\Package\Command\VersionCommand;

$console->addCommand(GenerateCommand::class);
$console->addCommand(MigrateCommand::class);
$console->addCommand(StatusCommand::class);
$console->addCommand(VersionCommand::class);
$console->addCommand(GenerateEntitiesCommand::class);
$console->addCommand(GenerateRepositoriesCommand::class);
$console->addCommand(GenerateClassesCommand::class);
