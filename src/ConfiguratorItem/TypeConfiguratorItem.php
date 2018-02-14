<?php
namespace KiwiSuite\Database\ConfiguratorItem;

use KiwiSuite\Application\ConfiguratorItem\ConfiguratorItemInterface;
use KiwiSuite\Database\Type\TypeConfig;
use KiwiSuite\Database\Type\TypeConfigurator;

final class TypeConfiguratorItem implements ConfiguratorItemInterface
{

    /**
     * @return mixed
     */
    public function getConfigurator()
    {
        return new TypeConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'databaseTypeConfigurator';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'type_database.php';
    }

    /**
     * @param $configurator
     * @return \Serializable
     */
    public function getService($configurator): \Serializable
    {
        return new TypeConfig($configurator);
    }
}
