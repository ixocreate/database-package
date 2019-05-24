<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Test\Database;

use Ixocreate\Database\DatabaseBootstrapItem;
use Ixocreate\Database\DatabaseConfigurator;
use PHPUnit\Framework\TestCase;

class DatabaseBootstrapItemTest extends TestCase
{
    public function testDatabaseBootstrapItem()
    {
        $bootstapItem = new DatabaseBootstrapItem();

        $this->assertInstanceOf(DatabaseConfigurator::class, $bootstapItem->getConfigurator());
        $this->assertSame('database.php', $bootstapItem->getFileName());
        $this->assertSame('database', $bootstapItem->getVariableName());
    }
}
