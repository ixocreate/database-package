<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Database;

use Ixocreate\Application\Service\ServiceRegistryInterface;
use Ixocreate\Database\Connection\ConnectionConfig;
use Ixocreate\Database\Connection\Option\ConnectionOptionInterface;
use Ixocreate\Database\DatabaseConfigurator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ixocreate\Database\DatabaseConfigurator
 */
class DatabaseConfiguratorTest extends TestCase
{
    public function testConnection()
    {
        $collector = [];
        $serviceRegistry = $this->createMock(ServiceRegistryInterface::class);
        $serviceRegistry->method('add')->willReturnCallback(function ($name, $object) use (&$collector) {
            $collector[$name] = $object;
        });

        $options = [
            'name' => 'IXOCREATE'
        ];
        $optionMock = $this->createMock(ConnectionOptionInterface::class);
        $optionMock->method('toArray')->willReturn($options);
        $databaseConfigurator = new DatabaseConfigurator();
        $databaseConfigurator->addConnection('ixocreate', $optionMock);

        $databaseConfigurator->registerService($serviceRegistry);

        $this->assertArrayHasKey(ConnectionConfig::class, $collector);

        /** @var ConnectionConfig $connectionConfig */
        $connectionConfig = $collector[ConnectionConfig::class];
        $this->assertInstanceOf(ConnectionConfig::class, $connectionConfig);

        $this->assertTrue(\in_array('ixocreate', $connectionConfig->getConnections()));
        $this->assertSame($options, $connectionConfig->getConnectionParams('ixocreate'));
    }
}
