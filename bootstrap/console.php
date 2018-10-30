<?php
namespace KiwiSuite\Database;

/** @var ConsoleConfigurator $console */
use KiwiSuite\ApplicationConsole\ConsoleConfigurator;
use KiwiSuite\Database\Command\GenerateClassesCommand;
use KiwiSuite\Database\Command\GenerateCommand;
use KiwiSuite\Database\Command\GenerateEntitiesCommand;
use KiwiSuite\Database\Command\GenerateMetadataCommand;
use KiwiSuite\Database\Command\GenerateRepositoriesCommand;
use KiwiSuite\Database\Command\MigrateCommand;
use KiwiSuite\Database\Command\StatusCommand;
use KiwiSuite\Database\Command\VersionCommand;

$console->addCommand(GenerateCommand::class);
$console->addCommand(MigrateCommand::class);
$console->addCommand(StatusCommand::class);
$console->addCommand(VersionCommand::class);
$console->addCommand(GenerateEntitiesCommand::class);
$console->addCommand(GenerateRepositoriesCommand::class);
$console->addCommand(GenerateClassesCommand::class);
