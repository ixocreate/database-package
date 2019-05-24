<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Connection;

use Ixocreate\Application\Service\SerializableServiceInterface;
use Ixocreate\Database\Exception\InvalidArgumentException;

final class ConnectionConfig implements SerializableServiceInterface
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

    /**
     * @return string
     */
    public function serialize()
    {
        return \serialize($this->config);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->config = \unserialize($serialized);
    }
}
