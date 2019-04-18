<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Repository;

use Doctrine\Common\Collections\Selectable;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Ixocreate\Entity\EntityInterface;

interface RepositoryInterface extends ObjectRepository, Selectable
{
    public function getEntityName() : string;

    public function save(EntityInterface $entity) : EntityInterface;

    public function persist(EntityInterface $entity): EntityInterface;

    public function flush(EntityInterface $entity): void;

    public function remove(EntityInterface $entity): void;

    public function count($criteria): int;

    public function createSelectQueryBuilder($alias, $indexBy = null): QueryBuilder;

    public function createQueryBuilder(): QueryBuilder;

    public function createQuery(string $dql): Query;
}
