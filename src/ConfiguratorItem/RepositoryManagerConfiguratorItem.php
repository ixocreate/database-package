<?php
namespace KiwiSuite\Database\ConfiguratorItem;

use KiwiSuite\Application\ConfiguratorItem\ConfiguratorItemInterface;
use KiwiSuite\Database\Repository\RepositoryServiceManagerConfig;
use KiwiSuite\ServiceManager\ServiceManagerConfigurator;

final class RepositoryManagerConfiguratorItem implements ConfiguratorItemInterface
{

    /**
     * @return mixed
     */
    public function getConfigurator()
    {
        $serviceManagerConfigurator = new ServiceManagerConfigurator(RepositoryServiceManagerConfig::class);

        return $serviceManagerConfigurator;
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'repositoryConfigurator';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'repository.php';
    }

    /**
     * @param $configurator
     * @return \Serializable
     */
    public function getService($configurator): \Serializable
    {
        return $configurator->getServiceManagerConfig();
    }
}
