<?php
declare(strict_types=1);

namespace KiwiSuite\Database\Tree;

use KiwiSuite\Database\Repository\AbstractRepository;
use KiwiSuite\Entity\Entity\EntityInterface;

abstract class TreeRepository extends AbstractRepository
{

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
            $last = current($result);
            $left = $last->right() + 1;
        }

        $right = $left + 1;
        return $this->insertNode($node, $left, $right, null);
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
        return $this->insertNode($node, $left, $right, $sibling->parentId());
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
        return $this->insertNode($node, $left, $right, $sibling->parentId());
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
        return $this->insertNode($node, $left, $right, $parent->id());
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
        return $this->insertNode($node, $left, $right, $parent->id());
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
            ->setParameter("left", $left)
            ->andWhere("node." . $entity->rightParameterName() ." <= :right")
            ->setParameter("right", $right);

        $queryBuilder->getQuery()->execute();

        $first = $right + 1;
        $delta = $left - $right - 1;
        $this->shiftNestedRange($entity, $first, 0, $delta);
    }

    /**
     * @param NodeInterface $node
     * @param int $left
     * @param int $right
     * @param $parent
     * @return NodeInterface
     */
    private function insertNode(NodeInterface $node, int $left, int $right, $parent): NodeInterface
    {
        /** @var NodeInterface $node */
        $node = $node->with($node->leftParameterName(), $left);
        $node = $node->with($node->rightParameterName(), $right);
        $node = $node->with($node->parentIdParameterName(), $parent);

        return $this->save($node);
    }

    /**
     * @param NodeInterface $node
     * @param int $destinationLeft
     * @return NodeInterface|EntityInterface
     */
    private function updateNode(NodeInterface $node, int $destinationLeft, $parent): NodeInterface
    {
        $node = $node->with($node->parentIdParameterName(), $parent);
        $node = $this->save($node);

        $left = $node->left();
        $right = $node->right();
        $size = $right - $left + 1;

        $this->shiftNestedRange($node, $destinationLeft, 0, $size);

        if ($left >= $destinationLeft) {
            $left += $size;
            $right += $size;
        }

        $this->shiftNestedRange($node, $left, $right, $destinationLeft - $left);
        $this->shiftNestedRange($node, $right + 1, 0, $size * -1);

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
                ->setParameter("delta", $delta)
                ->where("node." . $field ." >= :first")
                ->setParameter("first", $first);

            if ($last > 0) {
                $queryBuilder->andWhere("node." . $field ." <= :last")
                    ->setParameter("last", $last);
            }

            $queryBuilder->getQuery()->execute();
        }
    }
}
