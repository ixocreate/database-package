<?php
/**
 * kiwi-suite/database (https://github.com/kiwi-suite/database)
 *
 * @package kiwi-suite/database
 * @see https://github.com/kiwi-suite/database
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Database\Connection;

use KiwiSuite\Database\Exception\InvalidArgumentException;

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
