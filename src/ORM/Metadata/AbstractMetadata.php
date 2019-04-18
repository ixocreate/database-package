<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Package\ORM\Metadata;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;
use Ixocreate\Application\Exception\InvalidArgumentException;

/**
 * Class AbstractMetadata
 * @package Ixocreate\Database\Package\ORM\Metadata
 */
abstract class AbstractMetadata
{
    /**
     * @var FieldBuilder[]
     */
    protected $fields;

    /**
     * @var ClassMetadataBuilder
     */
    protected $builder;

    /**
     * AbstractMetadata constructor.
     * @param ClassMetadataBuilder $builder
     */
    public function __construct(ClassMetadataBuilder $builder)
    {
        $this->builder = $builder;
        $this->buildMetadata();
    }

    abstract protected function buildMetadata() : void;

    /**
     * @return ClassMetadataBuilder
     */
    public function getBuilder(): ClassMetadataBuilder
    {
        return $this->builder;
    }

    /**
     * @param string $field
     * @return FieldBuilder
     */
    protected function getField($field)
    {
        if (\array_key_exists($field, $this->fields)) {
            throw new InvalidArgumentException("Unable to retrieve FieldBuilder instance by field name '$field'");
        }
        return $this->fields[$field];
    }

    /**
     * @param string $field
     * @param FieldBuilder $builder
     * @return FieldBuilder
     */
    protected function setFieldBuilder($field, FieldBuilder $builder)
    {
        $this->fields[$field] = $builder;
        return $builder;
    }
}
