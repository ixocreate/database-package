<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Type\Strategy;

use Doctrine\DBAL\Types\Type;
use Ixocreate\Database\Type\Generator\Generator;

final class FileStrategy
{
    public function load(string $baseDir) : void
    {
        $baseDir = \rtrim($baseDir, '/') . '/database/types/';

        if (!\file_exists($baseDir . 'types.php')) {
            throw new \Exception("No Type data is generated");
        }

        $types = require_once $baseDir . 'types.php';

        $generator = new Generator();

        foreach ($types as $item) {
            $type = $item['type'];
            $typeName = $item['typeName'];

            if (Type::hasType($typeName)) {
                Type::overrideType($typeName, $generator->generateFullQualifiedName($typeName));
            } else {
                Type::addType($typeName, $generator->generateFullQualifiedName($typeName));
            }

            //TODO: DEPRECATED
            if (!Type::hasType($type)) {
                Type::addType($type, $generator->generateFullQualifiedName($typeName));
            }
        }
    }
}
