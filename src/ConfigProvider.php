<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database;

use Ixocreate\Contract\Application\ConfigProviderInterface;
use Ixocreate\Contract\Application\ConfigExampleInterface;

final class ConfigProvider implements ConfigProviderInterface, ConfigExampleInterface
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

    /**
     * @return string
     */
    public function configName(): string
    {
        return "database";
    }

    /**
     * @return string
     */
    public function configContent(): string
    {
        return \file_get_contents(__DIR__ . '/../resources/database.config.example.php');
    }
}
