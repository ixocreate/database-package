<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Database\Connection\Factory;

use Doctrine\DBAL\Connection;
use Ixocreate\Database\Connection\ConnectionConfig;
use Ixocreate\Database\Connection\Factory\ConnectionFactory;
use Ixocreate\ServiceManager\ServiceManagerInterface;
use PHPUnit\Framework\TestCase;

class ConnectionFactoryTest extends TestCase
{
    public function testFactory()
    {
        $config = [
            'driver' => 'pdo_sqlite',
            'user' => 'user',
            'password' => 'password',
            'memory' => true,
        ];

        $container = $this->createMock(ServiceManagerInterface::class);
        $container->method('get')->willReturn(new ConnectionConfig(['db' => $config]));

        $connectionFactory = new ConnectionFactory();
        $result = $connectionFactory($container, 'db');

        $this->assertInstanceOf(Connection::class, $result);
    }
}
