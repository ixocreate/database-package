<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Type\Generator;

use Ixocreate\ServiceManager\NamedServiceInterface;
use Ixocreate\Database\Type\TypeConfig;

final class FileWriter
{
    public function write(string $baseDir, TypeConfig $typeConfig): void
    {
        $baseDir = \rtrim($baseDir, '/') . '/database/types/';

        if (!\is_dir($baseDir)) {
            \mkdir($baseDir, 0777, true);
        }

        $generator = new Generator();

        $typeCollector = [];

        foreach ($typeConfig->getTypes() as $type => $config) {
            if (\is_subclass_of($type, NamedServiceInterface::class, true)) {
                $typeName = \call_user_func($type . '::serviceName');
            } else {
                $typeName = $type;
            }

            $typeCollector[] = [
                'type' => $type,
                'typeName' => $typeName,
            ];

            $fileName = $baseDir . $generator->generateName($typeName) . '.php';

            \file_put_contents(
                $fileName,
                $generator->generate(
                    $typeName,
                    $config['baseType']
                )
            );
        }

        \file_put_contents(
            $baseDir . "types.php",
            "<?php return " . \var_export($typeCollector, true) . ";"
        );
    }
}
