<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\EntityManager\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Ixocreate\ServiceManager\SubManager\AbstractSubManager;

final class EntityManagerSubManager extends AbstractSubManager
{
    public static function validation(): ?string
    {
        return EntityManagerInterface::class;
    }
}
