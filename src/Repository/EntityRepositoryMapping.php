<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Repository;

use Ixocreate\Application\Service\SerializableServiceInterface;

class EntityRepositoryMapping implements SerializableServiceInterface
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
