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
namespace KiwiSuite\Database\Generator;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class MetadataGenerator
 * @package KiwiSuite\Database\Repository
 */
class MetadataGenerator extends AbstractGenerator
{
    protected static $template = '<?php

<fileHeader>

declare(strict_types=1);

namespace <namespace>;

use KiwiSuite\Database\ORM\Metadata\AbstractMetadata;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;
<uses>

final class <className> extends AbstractMetadata
{

    protected function buildMetadata(): void
    {
        $builder = $this->getBuilder();
        $builder->setTable(\'<tableName>\');

<fields>
    }
    
<getters>
}

';

    /**
     * @var string
     */
    protected static $getPropertyTemplate = '<indent>private $<fieldName>;';

    /**
     * @var string
     */
    protected static $getGetterTemplate =
'<indent>public function <methodName>(): <optional><fieldType>
<indent>{
<indent><methodBody>
<indent>}';

    /**
     * @param $metadata ClassMetadataInfo
     * @return string
     */
    public function generateCode(ClassMetadataInfo $metadata)
    {
        $fields = $this->generateFields($metadata);

        $variables = [
            '<fileHeader>'          => $this->getFileHeader(),
            '<namespace>'           => $this->getMetadataClassNamespace($metadata->name),
            '<uses>'                => $this->generateUses($fields),
            '<className>'           => $this->getMetadataClassName($metadata->name),
            '<tableName>'           => $metadata->getTableName(),
            '<getters>'             => $this->generateGetters($fields),
            '<fields>'              => $this->buildFields($fields),
        ];

        return \str_replace(\array_keys($variables), \array_values($variables), static::$template);
    }

    /**
     * @param array $fields
     * @return string
     */
    private function generateUses(array $fields)
    {
        return '';
    }

    /**
     * @param array $fields
     * @return string
     */
    private function generateGetters(array $fields)
    {
        $lines = [];
        foreach ($fields as $column => $details) {
            $lines[] = $this->generateGetter(
                $column,
                'FieldBuilder',
                false,
                '<indent>return $this->getField(\'' . $column . '\');'
            );
        }

        return \implode("\n", $lines);
    }

    private function generateGetter($name, $type, $optional, $body)
    {
        $variables = [
            '<methodName>'          => $name,
            '<fieldType>'           => \mb_substr($type, (\mb_strrpos($type, '\\') ?: -1) + 1),
            '<optional>'            => $optional ? '?' : '',
            '<methodBody>'          => $body,
        ];

        return \str_replace(\array_keys($variables), \array_values($variables), static::$getGetterTemplate) . "\n";
    }


    /**
     * @param array $fields
     * @return string
     */
    private function buildFields(array $fields)
    {
        $lines = [];
        foreach ($fields as $column => $details) {
            $lines[] = '<indent><indent>$this->setFieldBuilder(\'' . $column . '\',';

            $fieldLines = [];
            $fieldLines[] = '<indent><indent><indent>$builder->createField(\'' . $column . '\', \'' . $details['type'] . '\')';

            if (!empty($details['id']) && $details['id'] === true) {
                $fieldLines[] = '<indent><indent><indent><indent>->makePrimaryKey()';
            }

            $fieldLines[] = '<indent><indent>)->build();';
            $lines[] = \implode("\n", $fieldLines) . "\n";
        }

        return \implode("\n", $lines);
    }

    /**
     * @param ClassMetadataInfo $metadata
     * @param string $destinationPath
     * @param bool $overwrite
     * @return string|null
     */
    public function generate(ClassMetadataInfo $metadata, $destinationPath, $overwrite = false) : ?string
    {
        $content = $this->generateCode($metadata);
        $content = \str_replace('<indent>', $this->getIntention(), $content);

        $path = $destinationPath . DIRECTORY_SEPARATOR
              . \str_replace('\\', \DIRECTORY_SEPARATOR, $this->getMetadataFQCN($metadata->name)) . '.php';

        if ($this->writeFile($content, $path, $overwrite)) {
            return $path;
        }

        return null;
    }

    /**
     * @return string
     */
    public function getNamespacePostfix(): string
    {
        return 'Metadata\\';
    }
}
