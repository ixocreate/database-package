<?php
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
