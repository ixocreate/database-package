<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Command;

use Ixocreate\Application\Console\CommandInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ProxyCommand extends Command implements CommandInterface
{
    /**
     * @var Command
     */
    protected $command;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->setName(static::getCommandName());

        $this->cleanOptions();
    }

    public function getName()
    {
        return static::getCommandName();
    }

    public function getDescription()
    {
        return $this->command->getDescription();
    }

    public function getHelp()
    {
        return $this->command->getHelp();
    }

    public function getProcessedHelp()
    {
        return $this->command->getProcessedHelp();
    }

    public function getAliases()
    {
        return $this->command->getAliases();
    }

    public function getSynopsis(bool $short = false)
    {
        $this->cleanOptions();
        return $this->command->getSynopsis($short);
    }

    public function getUsages()
    {
        return $this->command->getUsages();
    }

    public function getHelperSet()
    {
        return $this->command->getHelperSet();
    }

    public function getApplication()
    {
        return $this->command->getApplication();
    }

    public function getDefinition()
    {
        return $this->command->getDefinition();
    }

    public function getNativeDefinition()
    {
        return $this->command->getNativeDefinition();
    }

    public function getHelper(string $name)
    {
        return $this->command->getHelper($name);
    }

    public function setApplication(Application $application = null)
    {
        parent::setApplication($application);
        $this->command->setApplication($application);
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
//        $this->restoreOptions();

        $this->command->setCode(function (InputInterface $input, OutputInterface $output) {
            return $this->execute($input, $output);
        });

        return $this->command->run($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->restoreOptions();
        $input->bind($this->command->getDefinition());
        $input->setOption('configuration', null);
        $input->setOption('db-configuration', null);

        return $this->command->execute($input, $output);
    }

    protected function cleanOptions()
    {
        $inputDefinition = $this->command->getDefinition();
        $options = $inputDefinition->getOptions();
        unset($options['configuration'], $options['db-configuration']);


        $inputDefinition->setOptions($options);
    }

    protected function restoreOptions()
    {
        $this->command->addOption('configuration', null);
        $this->command->addOption('db-configuration', null);
    }
}
