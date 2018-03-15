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
namespace KiwiSuite\Database;

use KiwiSuite\Contract\Application\ConfigProviderInterface;

final class ConfigProvider implements ConfigProviderInterface
{

    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'database' => [
                'master' => [
                    'dbname' => 'test',
                    'user' => 'root',
                    'password' => '',
                    'host' => '127.0.0.1',
                    'driver' => 'pdo_mysql',
                ],
            ],
        ];
    }
}
