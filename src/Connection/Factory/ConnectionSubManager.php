<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Connection\Factory;

use Ixocreate\ServiceManager\SubManager\AbstractSubManager;

final class ConnectionSubManager extends AbstractSubManager
{
    public static function validation(): ?string
    {
        return \Doctrine\DBAL\Driver\Connection::class;
    }
}
