<?php
declare(strict_types=1);

namespace Ixocreate\Database\Connection\Option;

interface ConnectionOptionInterface
{
    /**
     * @return array
     */
    public function toArray(): array;
}
