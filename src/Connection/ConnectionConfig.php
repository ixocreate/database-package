<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Database\Connection;

use Ixocreate\Package\Database\Exception\InvalidArgumentException;

final class ConnectionConfig
{
    /**
     * @var array
     */
    private $config;

    /**
     * ConnectionConfig constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        //TODO validation
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConnections() : array
    {
        return \array_keys($this->config);
    }

    /**
     * @param string $name
     * @return array
     */
    public function getConnectionParams(string $name) : array
    {
        if (!\array_key_exists($name, $this->config)) {
            throw new InvalidArgumentException(\sprintf('unable to find connection config for %s', $name));
        }

        return $this->config[$name];
    }
}
