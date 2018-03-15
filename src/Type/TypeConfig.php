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

use KiwiSuite\Contract\Application\SerializableServiceInterface;

final class TypeConfig implements SerializableServiceInterface
{
    /**
     * @var array
     */
    private $types;

    /**
     * TypeConfig constructor.
     * @param TypeConfigurator $typeConfigurator
     */
    public function __construct(TypeConfigurator $typeConfigurator)
    {
        $this->types = $typeConfigurator->getTypes();
    }

    /**
     * @return array
     */
    public function getTypes() : array
    {
        return $this->types;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return \serialize($this->types);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->types = \unserialize($serialized);
    }
}
