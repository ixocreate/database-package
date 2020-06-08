<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Tree;

use Ixocreate\Database\Exception\InvalidArgumentException;
use Ixocreate\Database\Repository\AbstractRepository;
use Ixocreate\Entity\EntityInterface;

abstract class TreeRepository extends AbstractRepository
{
    private $leftParameterName;

    private $rightParameterName;

    private $parentIdParameterName;

    private $levelParameterName;

    public function leftParameterName()
    {
        if ($this->leftParameterName === null) {
            $reflection = new \ReflectionClass($this->getEntityName());
            $instance = $reflection->newInstanceWithoutConstructor();
            $this->leftParameterName = $instance->leftParameterName();
            $this->rightParameterName = $instance->rightParameterName();
            $this->parentIdParameterName = $instance->parentIdParameterName();
            $this->leftParameterName = $instance->levelParameterName();
        }
        return $this->leftParameterName;
    }

    public function rightParameterName()
    {
        if ($this->rightParameterName === null) {
            $reflection = new \ReflectionClass($this->getEntityName());
            $instance = $reflection->newInstanceWithoutConstructor();
            $this->leftParameterName = $instance->leftParameterName();
            $this->rightParameterName = $instance->rightParameterName();
            $this->parentIdParameterName = $instance->parentIdParameterName();
            $this->leftParameterName = $instance->levelParameterName();
        }
        return $this->rightParameterName;
    }

    public function parentIdParameterName()
    {
        if ($this->parentIdParameterName === null) {
            $reflection = new \ReflectionClass($this->getEntityName());
            $instance = $reflection->newInstanceWithoutConstructor();
            $this->leftParameterName = $instance->leftParameterName();
            $this->rightParameterName = $instance->rightParameterName();
            $this->parentIdParameterName = $instance->parentIdParameterName();
            $this->leftParameterName = $instance->levelParameterName();
        }
        return $this->parentIdParameterName;
    }

    public function levelParameterName()
    {
        if ($this->levelParameterName === null) {
            $reflection = new \ReflectionClass($this->getEntityName());
            $instance = $reflection->newInstanceWithoutConstructor();
            $this->leftParameterName = $instance->leftParameterName();
            $this->rightParameterName = $instance->rightParameterName();
            $this->parentIdParameterName = $instance->parentIdParameterName();
            $this->leftParameterName = $instance->levelParameterName();
        }
        return $this->levelParameterName;
    }

    /**
     * @param NodeInterface $node
     * @return NodeInterface
     */
    public function createRoot(NodeInterface $node): NodeInterface
    {
        $left = 1;

        $result = $this->findBy([], [$node->rightParameterName() => 'DESC'], 1);
        if (!empty($result)) {
            /** @var NodeInterface $last */
            $last = \current($result);
            $left = $last->right() + 1;
        }

        $right = $left + 1;
        $node = $this->insertNode($node, $left, $right, null, 0);
        $this->getEntityManager()->clear($this->getEntityName());
        return $node;
    }

    /**
     * @param NodeInterface $node
     * @param NodeInterface $sibling
     * @return NodeInterface
     */
    public function insertAsPreviousSibling(NodeInterface $node, NodeInterface $sibling): NodeInterface
    {
        $left = $sibling->left();
        $right = $left + 1;

        $this->shiftNestedRange($node, $left, 0, 2);
        $node = $this->insertNode($node, $left, $right, $sibling->parentId(), $sibling->level());
        $this->getEntityManager()->clear($this->getEntityName());
        return $node;
    }

    /**
     * @param NodeInterface $node
     * @param NodeInterface $sibling
     * @return NodeInterface
     */
    public function insertAsNextSibling(NodeInterface $node, NodeInterface $sibling): NodeInterface
    {
        $left = $sibling->right() + 1;
        $right = $left + 1;

        $this->shiftNestedRange($node, $left, 0, 2);
        $node = $this->insertNode($node, $left, $right, $sibling->parentId(), $sibling->level());
        $this->getEntityManager()->clear($this->getEntityName());
        return $node;
    }

    /**
     * @param NodeInterface $node
     * @param NodeInterface $parent
     * @return NodeInterface
     */
    public function insertAsLastChild(NodeInterface $node, NodeInterface $parent): NodeInterface
    {
        $left = $parent->right();
        $right = $left + 1;

        $this->shiftNestedRange($node, $left, 0, 2);
        $node = $this->insertNode($node, $left, $right, $parent->id(), $parent->level() + 1);
        $this->getEntityManager()->clear($this->getEntityName());
        return $node;
    }

    /**
     * @param NodeInterface $node
     * @param NodeInterface $parent
     * @return NodeInterface
     */
    public function insertAsFirstChild(NodeInterface $node, NodeInterface $parent): NodeInterface
    {
        $left = $parent->left() + 1;
        $right = $left + 1;

        $this->shiftNestedRange($node, $left, 0, 2);
        $node = $this->insertNode($node, $left, $right, $parent->id(), $parent->level() + 1);
        $this->getEntityManager()->clear($this->getEntityName());
        return $node;
    }

    /**
     * @param NodeInterface $node
     * @param NodeInterface $sibling
     */
    public function moveAsPreviousSibling(NodeInterface $node, NodeInterface $sibling)
    {
        $this->updateNode($node, $sibling->left(), $sibling->parentId());
    }

    /**
     * @param NodeInterface $node
     * @param NodeInterface $sibling
     */
    public function moveAsNextSibling(NodeInterface $node, NodeInterface $sibling)
    {
        $this->updateNode($node, $sibling->right() + 1, $sibling->parentId());
    }

    /**
     * @param NodeInterface $node
     * @param NodeInterface $parent
     */
    public function moveAsFirstChild(NodeInterface $node, NodeInterface $parent)
    {
        $this->updateNode($node, $parent->left() + 1, $parent->id());
    }

    /**
     * @param NodeInterface $node
     * @param NodeInterface $parent
     */
    public function moveAsLastChild(NodeInterface $node, NodeInterface $parent)
    {
        $this->updateNode($node, $parent->right(), $parent->id());
    }

    /**
     * @param EntityInterface $entity
     */
    public function remove(EntityInterface $entity): void
    {
        if (!($entity instanceof NodeInterface)) {
            //TODO Exception
        }

        $left = $entity->left();
        $right = $entity->right();

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->delete($this->getEntityName(), 'node')
            ->where("node." . $entity->leftParameterName() . ">= :left")
            ->setParameter('left', $left)
            ->andWhere("node." . $entity->rightParameterName() . " <= :right")
            ->setParameter('right', $right);

        $queryBuilder->getQuery()->execute();

        $first = $right + 1;
        $delta = $left - $right - 1;
        $this->shiftNestedRange($entity, $first, 0, $delta);

        $this->getEntityManager()->clear($this->getEntityName());
    }

    /**
     * @param NodeInterface $node
     * @param int $left
     * @param int $right
     * @param $parent
     * @param int $level
     * @return NodeInterface
     */
    private function insertNode(NodeInterface $node, int $left, int $right, $parent, int $level): NodeInterface
    {
        /** @var NodeInterface $node */
        $node = $node->with($node->leftParameterName(), $left);
        $node = $node->with($node->rightParameterName(), $right);
        $node = $node->with($node->parentIdParameterName(), $parent);
        $node = $node->with($node->levelParameterName(), $level);

        return $this->save($node);
    }

    /**
     * @param NodeInterface $node
     * @param int $destinationLeft
     * @param mixed $parent
     * @return NodeInterface|EntityInterface
     */
    private function updateNode(NodeInterface $node, int $destinationLeft, $parent): NodeInterface
    {
        $left = $node->left();
        $right = $node->right();
        $size = $right - $left + 1;

        if ($destinationLeft >= $left && $destinationLeft <= $right) {
            throw new InvalidArgumentException('node can not be its own child');
        }

        $node = $node->with($node->parentIdParameterName(), $parent);
        /** @var NodeInterface $node */
        $node = $this->save($node);

        $this->shiftNestedRange($node, $destinationLeft, 0, $size);

        if ($left >= $destinationLeft) {
            $left += $size;
            $right += $size;
        }

        $this->shiftNestedRange($node, $left, $right, $destinationLeft - $left);
        $this->shiftNestedRange($node, $right + 1, 0, $size * -1);

        $this->getEntityManager()->clear($this->getEntityName());

        $node = $this->find($node->{$node->idName()}());

        $classMetadata = $this->getEntityManager()->getClassMetadata($this->getEntityName());
        $tableName = $classMetadata->getTableName();
        $sql = "UPDATE {$tableName} as s INNER JOIN
(SELECT n.id,
         n.nestedLeft,
         COUNT(*)-1 AS level,
         ROUND ((n.nestedRight - n.nestedLeft - 1) / 2) AS offspring
    FROM {$tableName} AS n,
         {$tableName} AS p
   WHERE (n.nestedLeft BETWEEN p.nestedLeft AND p.nestedRight)
     AND (n.nestedLeft BETWEEN {$node->left()} AND {$node->right()})
GROUP BY n.id, n.nestedLeft
ORDER BY n.nestedLeft) as sub ON (s.id = sub.id)
SET s.level=sub.level";


        $this->getEntityManager()->getConnection()->exec($sql);
        $this->getEntityManager()->clear($this->getEntityName());

        $node = $this->find($node->{$node->idName()}());

        return $node;
    }

    /**
     * @param NodeInterface $node
     * @param int $first
     * @param int $last
     * @param int $delta
     */
    private function shiftNestedRange(NodeInterface $node, int $first, int $last, int $delta)
    {
        foreach ([$node->leftParameterName(), $node->rightParameterName()] as $field) {
            $queryBuilder = $this->createQueryBuilder();
            $queryBuilder->update($this->getEntityName(), "node")
                ->set("node." . $field, "node." . $field . " + :delta")
                ->setParameter('delta', $delta)
                ->where("node." . $field . " >= :first")
                ->setParameter('first', $first);

            if ($last > 0) {
                $queryBuilder->andWhere("node." . $field . " <= :last")
                    ->setParameter('last', $last);
            }

            $queryBuilder->getQuery()->execute();
        }
    }

    /**
     * @return int
     */
    public function getOddnessCount() :int
    {
        $left = $this->leftParameterName();
        $right = $this->rightParameterName();

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->select('count(1) as count')
            ->from($this->getEntityName(), 'node')
            ->orWhere("node.$left >= node.$right")
            ->orWhere("mod(node.$right - node.$left, 2) = 0");

        $result = $queryBuilder->getQuery()->execute();
        return (int) $result[0]['count'];
    }

    /**
     * @return int
     */
    public function getDuplicateCount() :int
    {
        $left = $this->leftParameterName();
        $right = $this->rightParameterName();

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->select('count(1) as count')
            ->from($this->getEntityName(), 'n1')
            ->from($this->getEntityName(), 'n2')
            ->andWhere('n1.id < n2.id')
            ->andWhere("n1.$left = n2.$left OR n1.$right = n2.$right OR n1.$left = n2.$right OR n1.$right = n2.$left");

        $result = $queryBuilder->getQuery()->execute();
        return (int) $result[0]['count'];
    }

    /**
     * @return int
     */
    public function getWrongParentCount() :int
    {
        $left = $this->leftParameterName();
        $right = $this->rightParameterName();
        $parentId = $this->parentIdParameterName();

        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder
            ->select('count(distinct(c.id)) as count')
            ->from($this->getEntityName(), 'c')
            ->from($this->getEntityName(), 'p')
            ->from($this->getEntityName(), 'i')
            ->andWhere("c.$parentId = p.id")
            ->andWhere('i.id <> p.id')
            ->andWhere('i.id <> c.id')
            ->andWhere("c.$left not between p.$left and p.$right OR
            (c.$left between i.$left and i.$right AND i.$left between p.$left and p.$right)");

        $result = $queryBuilder->getQuery()->execute();
        return (int) $result[0]['count'];
    }

    /**
     * @return int
     */
    protected function getMissingParentCount(): int
    {
        $parameterName = $this->parentIdParameterName();

        $sub = $this->createQueryBuilder();
        $sub->select('t.id');
        $sub->from($this->getEntityName(), 't');

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder
            ->select('count(1) as count')
            ->from($this->getEntityName(), 'node')
            ->andWhere("node.$parameterName IS NOT NULL")
            ->andWhere($queryBuilder->expr()->not($queryBuilder->expr()->in('node.' . $parameterName, $sub->getDql())));

        $result = $queryBuilder->getQuery()->execute();
        return (int)$result[0]['count'];
    }

    public function healthStatus(): array
    {
        $result = [];
        $result['oddness'] = $this->getOddnessCount();
        $result['duplicates'] = $this->getDuplicateCount();
        $result['wrongParent'] = $this->getWrongParentCount();
        $result['missingParent'] = $this->getMissingParentCount();

        return $result;
    }

    public function validateTree(): bool
    {
        return \array_sum($this->healthStatus()) == 0;
    }
}
