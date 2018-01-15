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
namespace KiwiSuite\Database\Repository;

use Doctrine\Instantiator\Instantiator;

class EntityRepositoryMapping implements \Serializable
{
    private $mapping = [];

    /**
     * EntityRepositoryMapping constructor.
     * @param array $mapping
     */
    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    public static function create(RepositoryServiceManagerConfig $repositoryServiceManagerConfig) : EntityRepositoryMapping
    {
        $mapping = [];
        $instantiator = new Instantiator();

        $repositories = \array_keys($repositoryServiceManagerConfig->getFactories());
        foreach ($repositories as $repository) {
            /** @var RepositoryInterface $repositoryClass */
            $repositoryClass = $instantiator->instantiate($repository);
            $mapping[$repositoryClass->getEntityName()] = $repository;
        }

        return new EntityRepositoryMapping($mapping);
    }

    public function getEntities() : array
    {
        return \array_keys($this->mapping);
    }

    public function getRepositories() : array
    {
        return \array_values($this->mapping);
    }

    public function getRepositoryByEntity(string $entity) : string
    {
        if (!\array_key_exists($entity, $this->mapping)) {
            //TODO Exception
        }

        return $this->mapping[$entity];
    }

    public function getEntityByRepository(string $repository) : string
    {
        $search = \array_search($repository, $this->mapping, true);
        if ($search === false) {
            //TODO Exception
        }

        return $this->mapping[$search];
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return \serialize($this->mapping);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->mapping = \unserialize($serialized);
    }
}
