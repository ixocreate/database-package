<?php
declare(strict_types=1);

namespace KiwiSuite\Database\Tree;

use KiwiSuite\Entity\Entity\EntityInterface;

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
