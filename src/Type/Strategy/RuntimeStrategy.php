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
namespace Ixocreate\Database\Type\Strategy;

use Doctrine\DBAL\Types\Type;
use Ixocreate\Contract\ServiceManager\NamedServiceInterface;
use Ixocreate\Database\Type\Generator\Generator;
use Ixocreate\Database\Type\TypeConfig;

final class RuntimeStrategy
{
    public function generate(TypeConfig $typeConfig) : void
    {
        $generator = new Generator();

        foreach ($typeConfig->getTypes() as $type => $config) {
            if (\is_subclass_of($type, NamedServiceInterface::class, true)) {
                $typeName = \call_user_func($type .'::serviceName');
            } else {
                $typeName = $type;
            }

            $className = $generator->generateFullQualifiedName($typeName);
            if (\class_exists($className)) {
                continue;
            }

            $fileName = \tempnam(\sys_get_temp_dir(), $className . '.php.tmp.');

            \file_put_contents(
                $fileName,
                $generator->generate(
                    $typeName,
                    $config['baseType']
                )
            );

            /* @noinspection PhpIncludeInspection */
            require $fileName;
            \unlink($fileName);
        }

        foreach ($typeConfig->getTypes() as $type => $config) {
            if (\is_subclass_of($type, NamedServiceInterface::class, true)) {
                $typeName = \call_user_func($type .'::serviceName');
            } else {
                $typeName = $type;
            }

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
