<?php

namespace KiwiSuite\Database\ConfiguratorItem;


use KiwiSuite\Application\ConfiguratorItem\ConfiguratorItemInterface;

class MigrationConfiguratorItem implements ConfiguratorItemInterface
{
    public function getConfigurator()
    {
        // TODO: Implement getConfigurator() method.
    }

    public function getVariableName(): string
    {
        return 'migrationConfigurator';
    }

    public function getFileName(): string
    {
        return 'migration.php';
    }

    public function getService($configurator): \Serializable
    {
        // TODO: Implement getService() method.
    }
}
