<?php
declare(strict_types=1);

namespace Ixocreate\Test\Database\Connection\Option;

use Ixocreate\Database\Connection\Option\MySqlOption;
use PHPUnit\Framework\TestCase;

class MySqlOptionTest extends TestCase
{
    public function testWithUser()
    {
        $mySqlOption = new MySqlOption();
        $this->assertArrayNotHasKey('user', $mySqlOption->toArray());

        $mySqlOption = $mySqlOption->withUser('user');
        $this->assertArrayHasKey('user', $mySqlOption->toArray());
        $this->assertSame('user', $mySqlOption->toArray()['user']);
    }

    public function testWithPassword()
    {
        $mySqlOption = new MySqlOption();
        $this->assertArrayNotHasKey('password', $mySqlOption->toArray());

        $mySqlOption = $mySqlOption->withPassword('password');
        $this->assertArrayHasKey('password', $mySqlOption->toArray());
        $this->assertSame('password', $mySqlOption->toArray()['password']);
    }

    public function testWithHost()
    {
        $mySqlOption = new MySqlOption();
        $this->assertArrayNotHasKey('host', $mySqlOption->toArray());

        $mySqlOption = $mySqlOption->withHost('host');
        $this->assertArrayHasKey('host', $mySqlOption->toArray());
        $this->assertSame('host', $mySqlOption->toArray()['host']);
    }

    public function testWithPort()
    {
        $mySqlOption = new MySqlOption();
        $this->assertArrayNotHasKey('port', $mySqlOption->toArray());

        $mySqlOption = $mySqlOption->withPort(3006);
        $this->assertArrayHasKey('port', $mySqlOption->toArray());
        $this->assertSame(3006, $mySqlOption->toArray()['port']);
    }

    public function testWithDbName()
    {
        $mySqlOption = new MySqlOption();
        $this->assertArrayNotHasKey('dbname', $mySqlOption->toArray());

        $mySqlOption = $mySqlOption->withDbName('dbname');
        $this->assertArrayHasKey('dbname', $mySqlOption->toArray());
        $this->assertSame('dbname', $mySqlOption->toArray()['dbname']);
    }

    public function testWithUnixSocket()
    {
        $mySqlOption = new MySqlOption();
        $this->assertArrayNotHasKey('unix_socket', $mySqlOption->toArray());

        $mySqlOption = $mySqlOption->withUnixSocket('unix_socket');
        $this->assertArrayHasKey('unix_socket', $mySqlOption->toArray());
        $this->assertSame('unix_socket', $mySqlOption->toArray()['unix_socket']);
    }

    public function testWithCharset()
    {
        $mySqlOption = new MySqlOption();
        $this->assertArrayNotHasKey('charset', $mySqlOption->toArray());

        $mySqlOption = $mySqlOption->withCharset('charset');
        $this->assertArrayHasKey('charset', $mySqlOption->toArray());
        $this->assertSame('charset', $mySqlOption->toArray()['charset']);
    }
}
