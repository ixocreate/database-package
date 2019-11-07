<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Tree;

use Ixocreate\Entity\EntityInterface;

interface NodeInterface extends EntityInterface
{
    public function id();

    public function left(): ?int;

    public function right(): ?int;

    public function level(): ?int;

    public function parentId();

    public function idName(): string;

    public function leftParameterName(): string;

    public function rightParameterName(): string;

    public function parentIdParameterName(): string;

    public function levelParameterName(): string;
}
