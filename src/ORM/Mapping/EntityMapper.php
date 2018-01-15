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
namespace KiwiSuite\Database\ORM\Mapping;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Instantiator\Instantiator;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use KiwiSuite\Database\Repository\EntityRepositoryMapping;
use KiwiSuite\Database\Repository\Factory\RepositorySubManager;
use KiwiSuite\Database\Repository\RepositoryInterface;
use KiwiSuite\Entity\Entity\EntityInterface;

final class EntityMapper implements MappingDriver
{
    /**
     * @var RepositorySubManager
     */
    private $repositorySubManager;
    /**
     * @var EntityRepositoryMapping
     */
    private $entityRepositoryMapping;

    /**
     * EntityMapper constructor.
     * @param RepositorySubManager $repositorySubManager
     * @param EntityRepositoryMapping $entityRepositoryMapping
     */
    public function __construct(RepositorySubManager $repositorySubManager, EntityRepositoryMapping $entityRepositoryMapping)
    {
        $this->repositorySubManager = $repositorySubManager;
        $this->entityRepositoryMapping = $entityRepositoryMapping;
    }

    /**
     * Loads the metadata for the specified class into the provided container.
     *
     * @param string $className
     * @param ClassMetadata $metadata
     *
     * @return void
     */
    public function loadMetadataForClass($className, ClassMetadata $metadata)
    {
        $repositoryName = $this->entityRepositoryMapping->getRepositoryByEntity($className);
        $instantiator = new Instantiator();

        /** @var RepositoryInterface $repository */
        $repository = $instantiator->instantiate($repositoryName);
        $classMetaDataBuilder = new ClassMetadataBuilder($metadata);
        $repository->loadMetadata($classMetaDataBuilder);
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
        if (!\class_exists($className)) {
            return false;
        }

        $instances = \class_implements($className);
        if ($instances === false) {
            return false;
        }

        if (!\in_array(EntityInterface::class, $instances)) {
            return false;
        }

        return true;
    }
}
