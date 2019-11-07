<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\ORM\Mapping;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\MappingException;
use Ixocreate\Database\DatabaseEntityInterface;
use Ixocreate\Database\Repository\EntityRepositoryMapping;

final class EntityMapper implements MappingDriver
{
    /**
     * @var EntityRepositoryMapping
     */
    private $entityRepositoryMapping;

    /**
     * EntityMapper constructor.
     * @param EntityRepositoryMapping $entityRepositoryMapping
     */
    public function __construct(EntityRepositoryMapping $entityRepositoryMapping)
    {
        $this->entityRepositoryMapping = $entityRepositoryMapping;
    }

    /**
     * @inheritDoc
     * @throws MappingException
     */
    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
        if (!\is_subclass_of($className, DatabaseEntityInterface::class)) {
            throw new MappingException('entity ' . $className . ' does not implement ' . DatabaseEntityInterface::class);
        }

        $classMetaDataBuilder = new ClassMetadataBuilder($metadata);
        $className::loadMetadata($classMetaDataBuilder);
    }

    /**
     * Gets the names of all mapped classes known to this driver.
     *
     * @return array the names of all mapped classes known to this driver
     */
    public function getAllClassNames()
    {
        return $this->entityRepositoryMapping->getEntities();
    }

    /**
     * Returns whether the class with the specified name should have its metadata loaded.
     * This is only the case if it is either mapped as an Entity or a MappedSuperclass.
     *
     * @param string $className
     *
     * @return bool
     */
    public function isTransient($className)
    {
        $instances = @\class_implements($className);
        if ($instances === false) {
            return false;
        }

        if (!\in_array(DatabaseEntityInterface::class, $instances)) {
            return false;
        }

        return true;
    }
}
