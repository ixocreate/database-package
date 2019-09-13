<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Repository\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;
use Ixocreate\Database\Exception\RepositoryException;
use Ixocreate\Database\Repository\EntityRepositoryMapping;

final class DoctrineRepositoryFactory implements RepositoryFactory
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
     * DoctrineRepositoryFactory constructor.
     * @param RepositorySubManager $repositorySubManager
     * @param EntityRepositoryMapping $entityRepositoryMapping
     */
    public function __construct(RepositorySubManager $repositorySubManager, EntityRepositoryMapping $entityRepositoryMapping)
    {
        $this->repositorySubManager = $repositorySubManager;
        $this->entityRepositoryMapping = $entityRepositoryMapping;
    }

    /**
     * @inheritDoc
     * @throws RepositoryException
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $repositoryName = $this->entityRepositoryMapping->getRepositoryByEntity($entityName);
        if ($repositoryName === null) {
            throw new RepositoryException('repository for ' . $entityName . ' not found');
        }
        return $this->repositorySubManager->get($repositoryName);
    }
}
