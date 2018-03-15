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
namespace KiwiSuite\Database\Type;

use KiwiSuite\Contract\Application\ConfiguratorInterface;
use KiwiSuite\Contract\Application\ServiceRegistryInterface;

final class TypeConfigurator implements ConfiguratorInterface
{
    /**
     * @var array
     */
    private $types = [];

    /**
     * @param string $type
     * @param string $baseType
     * @param bool $extend
     */
    public function addType(string $type, string $baseType, bool $extend = true): void
    {
        //TODO Check

        $this->types[$type] = [
            'baseType' => $baseType,
            'extend' => $extend,
        ];
    }

    /**
     * @return array
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    public function registerService(ServiceRegistryInterface $serviceRegistry): void
    {
        $serviceRegistry->add(TypeConfig::class, new TypeConfig($this));
    }
}
