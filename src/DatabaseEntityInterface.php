<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

interface DatabaseEntityInterface
{
    public static function loadMetadata(ClassMetadataBuilder $builder);
}
