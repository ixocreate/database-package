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

use Doctrine\Common\Collections\Selectable;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\QueryBuilder;
use KiwiSuite\Entity\Entity\EntityInterface;

interface RepositoryInterface extends ObjectRepository, Selectable
{
    public function getEntityName() : string;

    public function save(EntityInterface $entity) : EntityInterface;

    public function persist(EntityInterface $entity): EntityInterface;

    public function flush(EntityInterface $entity): void;

    public function remove(EntityInterface $entity): void;

    public function count(array $criteria): int;

    public function createSelectQueryBuilder($alias, $indexBy = null): QueryBuilder;

    public function createQueryBuilder(): QueryBuilder;
}
