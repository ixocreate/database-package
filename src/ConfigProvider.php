<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database;

use Ixocreate\Application\Config\ConfigProviderInterface;

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
        return 'database';
    }

    /**
     * @return string
     */
    public function configContent(): string
    {
        return \file_get_contents(__DIR__ . '/../resources/database.config.example.php');
    }
}
