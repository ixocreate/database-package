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
namespace KiwiSuite\Database\Repository\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;

final class DoctrineRepositoryFactory implements RepositoryFactory
{
    /**
     * @var RepositorySubManager
     */
    private $repositorySubManager;

    /**
     * DoctrineRepositoryFactory constructor.
     * @param RepositorySubManager $repositorySubManager
     */
    public function __construct(RepositorySubManager $repositorySubManager)
    {
        $this->repositorySubManager = $repositorySubManager;
    }


    /**
     * Gets the repository for an entity class.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager the EntityManager instance
     * @param string $entityName the name of the entity
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        // TODO: Implement getRepository() method.
    }
}
