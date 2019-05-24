<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database;

use Ixocreate\Application\Configurator\ConfiguratorInterface;
use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\Database\Connection\ConnectionConfig;
use Ixocreate\Database\Connection\Option\ConnectionOptionInterface;

final class DatabaseConfigurator implements ConfiguratorInterface
{
    /**
     * @var ConnectionOptionInterface[];
     */
    private $connections = [];

    /**
     * @param string $name
     * @param ConnectionOptionInterface $option
     */
    public function addConnection(string $name, ConnectionOptionInterface $option)
    {
        $this->connections[$name] = $option;
    }

    /**
     * @param ServiceRegistryInterface $serviceRegistry
     * @return void
     */
    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $config = [];
        foreach ($this->connections as $name => $options) {
            $config[$name] = $options->toArray();
        }

        $serviceRegistry->add(ConnectionConfig::class, new ConnectionConfig($config));
    }
}
