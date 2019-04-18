<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Entity;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

interface DatabaseEntityInterface
{
    public static function loadMetadata(ClassMetadataBuilder $builder);
}
