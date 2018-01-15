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

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use KiwiSuite\Entity\Entity\EntityInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * AbstractRepository constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    /**
     * @param mixed $id
     * @return null|object
     */
    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @return \Doctrine\Common\Persistence\ObjectRepository|EntityRepository|mixed|null|object
     */
    public function findOneBy(array $criteria)
    {
        return $this->getRepository()-$this->findOneBy($criteria);
    }

    /**
     * @return string
     */
    abstract public function getEntityName() : string;

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->getEntityName();
    }

    /**
     * @param Criteria $criteria
     * @return Collection
     */
    public function matching(Criteria $criteria)
    {
        return $this->getRepository()->matching($criteria);
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository() : EntityRepository
    {
        if ($this->repository === null) {
            $this->repository = new EntityRepository($this->getEntityManager(), $this->getEntityManager()->getClassMetadata($this->getEntityName()));
        }
        return $this->repository;
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager() : EntityManagerInterface
    {
        return $this->entityManager;
    }

    public function save(EntityInterface $entity) : EntityInterface
    {
        //TODO Check $entity

        return $this->entityManager->merge($entity);
    }

    /**
     * @param $name
     * @param $arguments
     * @throws \Doctrine\ORM\ORMException
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->getRepository()->__call($name, $arguments);
    }

    /**
     * @param array $criteria
     * @return int
     */
    public function count(array $criteria)
    {
        return $this->getRepository()->count($criteria);
    }

    /**
     *
     */
    public function clear()
    {
        return $this->getRepository()->clear();
    }

    /**
     * @param $queryName
     * @return \Doctrine\ORM\NativeQuery
     */
    public function createNativeNamedQuery($queryName)
    {
        return $this->getRepository()->createNativeNamedQuery($queryName);
    }

    /**
     * @param $queryName
     * @return \Doctrine\ORM\Query
     */
    public function createNamedQuery($queryName)
    {
        return $this->getRepository()->createNamedQuery($queryName);
    }

    /**
     * @param $alias
     * @return \Doctrine\ORM\Query\ResultSetMappingBuilder
     */
    public function createResultSetMappingBuilder($alias)
    {
        return $this->getRepository()->createResultSetMappingBuilder($alias);
    }

    /**
     * @param $alias
     * @param null $indexBy
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilder($alias, $indexBy = null)
    {
        return $this->getRepository()->createQueryBuilder($alias, $indexBy);
    }

    abstract public function loadMetadata() : void;
}
