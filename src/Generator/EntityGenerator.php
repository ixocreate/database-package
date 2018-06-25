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
 * Class EntityGenerator
 * @package KiwiSuite\Database\Repository
 */
class EntityGenerator extends AbstractGenerator
{
    protected static $template = '<?php

<fileHeader>

declare(strict_types=1);

<namespace>

use KiwiSuite\Entity\Entity\Definition;
use KiwiSuite\Entity\Entity\DefinitionCollection;
use KiwiSuite\Entity\Entity\EntityInterface;
use KiwiSuite\Entity\Entity\EntityTrait;
<uses>

final class <className> implements EntityInterface
{
<indent>use EntityTrait;

<properties>

<getters>
    
<definition>
 
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
'<indent>public <static>function <methodName>(): <optional><fieldType>
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
            '<namespace>'           => $this->generateNamespace($metadata->name),
            '<uses>'                => $this->generateUses($fields),
            '<className>'           => $this->getClassNameKiwi($metadata->name),
            '<properties>'          => $this->generateProperties($fields),
            '<getters>'             => $this->generateGetters($fields),
            '<definition>'          => $this->generateDefinition($fields),
        ];

        return \str_replace(\array_keys($variables), \array_values($variables), static::$template);
    }

    /**
     * @param array $fields
     * @return string
     */
    private function generateUses(array $fields)
    {
        $lines = [];

        foreach ($fields as $column => $details) {
            $lines[$details['type']] = 'use ' . $details['type'] . ';';
        }

        return \implode("\n", $lines);
    }

    /**
     * @param array $fields
     * @return string
     */
    private function generateProperties(array $fields)
    {
        $lines = [];

        foreach ($fields as $column => $details) {
            $variables = [
                '<fieldName>' => $column,
            ];

            $lines[] = \str_replace(\array_keys($variables), \array_values($variables), static::$getPropertyTemplate);
        }

        return \implode("\n", $lines);
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
                $details['type'],
                !empty($details['nullable']),
                '<indent>return $this->' . $column . ';'
            );
        }

        return \implode("\n", $lines);
    }

    private function generateGetter($name, $type, $optional, $body, $static = false)
    {
        $variables = [
            '<methodName>'          => $name,
            '<static>'              => ($static) ? 'static ' : '',
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
    private function generateDefinition(array $fields)
    {
        $lines = [];

        $lines[] = '<indent>return new DefinitionCollection([';
        foreach ($fields as $column => $details) {
            $lines[] = "<indent><indent><indent>new Definition('" .
                            $column . "', '" .
                            $details['type'] .
                            '\', true, true),';
        }

        $lines[] = '<indent><indent>]);';

        return $this->generateGetter('createDefinitions', 'DefinitionCollection', false, \implode("\n", $lines), true);
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
              . \str_replace('\\', \DIRECTORY_SEPARATOR, $this->getEntityFQCN($metadata->name)) . '.php';

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
        return 'Entity\\';
    }
}
