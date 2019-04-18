<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Package\Connection\Factory;

use Ixocreate\Application\Config\Config;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use Ixocreate\Database\Package\Connection\ConnectionConfig;

final class ConnectionConfigFactory implements FactoryInterface
{
    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        $connectionConfig = [];

        if ($container->has(Config::class)) {
            /** @var Config $config */
            $config = $container->get(Config::class);
            $connectionConfig = $config->get("database", []);
        }

        return new ConnectionConfig($connectionConfig);
    }
}
