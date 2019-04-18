<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Database\Tree;

use Ixocreate\Package\Entity\EntityInterface;

interface NodeInterface extends EntityInterface
{
    public function id();

    public function left(): ?int;

    public function right(): ?int;

    public function parentId();

    public function idName(): string;

    public function leftParameterName(): string;

    public function rightParameterName(): string;

    public function parentIdParameterName(): string;
}
