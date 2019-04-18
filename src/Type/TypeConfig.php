<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Package\Type;

final class TypeConfig
{
    /**
     * @var array
     */
    private $types;

    /**
     * TypeConfig constructor.
     * @param array $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @return array
     */
    public function getTypes() : array
    {
        return $this->types;
    }
}
