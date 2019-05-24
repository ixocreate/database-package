<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Database\Connection;

use InvalidArgumentException;
use Ixocreate\Database\Connection\ConnectionConfig;
use PHPUnit\Framework\TestCase;

class ConnectionConfigTest extends TestCase
{
    public function testConnections()
    {
        $options = [
            'name' => 'IXOCREATE',
        ];

        $connectionConfig = new ConnectionConfig(['ixocreate' => $options]);

        $this->assertTrue(\in_array('ixocreate', $connectionConfig->getConnections()));
        $this->assertSame($options, $connectionConfig->getConnectionParams('ixocreate'));
    }

    public function testInvalidConnectionName()
    {
        $this->expectException(InvalidArgumentException::class);

        $connectionConfig = new ConnectionConfig([]);
        $connectionConfig->getConnectionParams('ixocreate');
    }

    public function testSerialize()
    {
        $options = [
            'name' => 'IXOCREATE',
        ];

        $connectionConfig = new ConnectionConfig(['ixocreate' => $options]);

        $connectionConfig = \unserialize(\serialize($connectionConfig));

        $this->assertInstanceOf(ConnectionConfig::class, $connectionConfig);
        $this->assertTrue(\in_array('ixocreate', $connectionConfig->getConnections()));
        $this->assertSame($options, $connectionConfig->getConnectionParams('ixocreate'));
    }
}
