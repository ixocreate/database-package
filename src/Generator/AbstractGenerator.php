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
 * Class AbstractGenerator
 * @package KiwiSuite\Database\Generator
 */
abstract class AbstractGenerator implements GeneratorInterface
{

    /**
     * @var string
     */
    protected $fileHeader = "";

    /**
     * @var int
     */
    protected $indentChar = " ";

    /**
     * @var int
     */
    protected $intents = 4;

    /**
     * @param string $content
     * @param string $path
     * @param bool $overwrite
     * @return bool
     */
    public function writeFile($content, $path, $overwrite=false)
    {
        $dir = \dirname($path);

        if (! \is_dir($dir)) {
            \mkdir($dir, 0775, true);
        }

        if (!\file_exists($path) || (\file_exists($path) && $overwrite)) {
            \file_put_contents($path, $content);
            \chmod($path, 0664);

            return true;
        }

        return false;
    }

    /**
     * Generates the namespace, if class do not have namespace, return empty string instead.
     *
     * @param string $fullClassName
     *
     * @return string $namespace
     */
    protected function getClassNamespace($fullClassName)
    {
        $namespace = \mb_substr($fullClassName, 0, \mb_strrpos($fullClassName, '\\'));
        return $namespace;
    }

    /**
     * @param $fullClassName
     * @return mixed
     */
    protected function getRepositoryClassNamespace($fullClassName)
    {
        return $this->replaceClassNamespaceType($fullClassName, 'Repository');
    }

    /**
     * @param $fullClassName
     * @return mixed
     */
    protected function getResourceClassNamespace($fullClassName)
    {
        return $this->replaceClassNamespaceType($fullClassName, 'Resource');
    }

    /**
     * @param $fullClassName
     * @return mixed
     */
    protected function getEntityClassNamespace($fullClassName)
    {
        return $this->replaceClassNamespaceType($fullClassName, 'Entity');
    }

    /**
     * @param $fullClassName
     * @return mixed
     */
    protected function getMetadataClassNamespace($fullClassName)
    {
        return $this->replaceClassNamespaceType($fullClassName, 'Metadata');
    }

    protected function replaceClassNamespaceType($fullClassName, $type)
    {
        $namespace = $this->getClassNamespace($fullClassName);
        return \preg_replace('/(Repository|Resource|Entity|Metadata)$/', $type, $namespace);
    }

    /**
     * @param string $fullClassName
     * @return string
     */
    protected function generateNamespace($fullClassName)
    {
        return 'namespace ' . $this->getClassNamespace($fullClassName) . ';';
    }

    /**
     * @param ClassMetadataInfo $metadata
     * @param $destinationPath
     * @param bool $overwrite
     * @return string|null the generated absolute file path
     */
    abstract public function generate(ClassMetadataInfo $metadata, $destinationPath, $overwrite=false) : ?string;

    /**
     * Generates the class name
     *
     * @param string $fullClassName
     *
     * @return string
     */
    protected function getClassName($fullClassName)
    {
        return \mb_substr($fullClassName, \mb_strrpos($fullClassName, '\\') + 1, \mb_strlen($fullClassName));
    }

    /**
     * @param $fullClassName
     * @return mixed|string
     */
    protected function getClassNameKiwi($fullClassName)
    {
        $classNamespace = $this->getClassNamespace($fullClassName);
        $className = $this->getClassName($fullClassName);

        $namespaceParts = \array_filter(\explode('\\', $classNamespace));

        foreach ($namespaceParts as $namespacePart) {
            if (\mb_strpos($className, $namespacePart) === 0) {
                $className = \preg_replace('/^' . \preg_quote($namespacePart, '/') . '/', '', $className);
            }
        }

        return $className;
    }

    /**
    * Generates the namespace statement, if class do not have namespace, return empty string instead.
    *
    * @param string $fullClassName the full repository class name
    *
    * @return string $namespace
    */
    protected function getEntityClassName($fullClassName)
    {
        return $this->getClassNameKiwi($fullClassName);
    }

    /**
     * @param string $fullClassName
     * @return string
     */
    protected function getEntityFQCN($fullClassName)
    {
        return $this->getEntityClassNamespace($fullClassName) . "\\" . $this->getEntityClassName($fullClassName);
    }

    /**
     * Generates the namespace statement, if class do not have namespace, return empty string instead.
     *
     * @param string $fullClassName the full repository class name
     *
     * @return string $namespace
     */
    protected function getRepositoryClassName($fullClassName)
    {
        return $this->getClassNameKiwi($fullClassName) . 'Repository';
    }

    /**
     * @param string $fullClassName
     * @return string
     */
    protected function getRepositoryFQCN($fullClassName)
    {
        return $this->getRepositoryClassNamespace($fullClassName) . "\\" . $this->getRepositoryClassName($fullClassName);
    }

    /**
     * Generates the namespace statement, if class do not have namespace, return empty string instead.
     *
     * @param string $fullClassName the full repository class name
     *
     * @return string $namespace
     */
    protected function getResourceClassName($fullClassName)
    {
        return $this->getClassNameKiwi($fullClassName) . 'Resource';
    }

    /**
     * @param string $fullClassName
     * @return string
     */
    protected function getResourceFQCN($fullClassName)
    {
        return $this->getResourceClassNamespace($fullClassName) . "\\" . $this->getResourceClassName($fullClassName);
    }

    /**
     * Generates the namespace statement, if class do not have namespace, return empty string instead.
     *
     * @param string $fullClassName the full repository class name
     *
     * @return string $namespace
     */
    protected function getMetadataClassName($fullClassName)
    {
        return $this->getClassNameKiwi($fullClassName) . 'Metadata';
    }

    /**
     * @param string $fullClassName
     * @return string
     */
    protected function getMetadataFQCN($fullClassName)
    {
        return $this->getMetadataClassNamespace($fullClassName) . "\\" . $this->getMetadataClassName($fullClassName);
    }

    /**
     * @param $content
     * @param $start
     * @param $ending
     * @return bool|null|string
     */
    protected function getStringBetween($content, $start, $ending)
    {
        $startPosition = \mb_strpos($content, $start);
        if ($startPosition === false) {
            return null;
        }

        $endPosition = \mb_strpos($content, $ending, $startPosition + \mb_strlen($start));
        if ($endPosition === false) {
            return null;
        }

        return \mb_substr($content, $startPosition, $endPosition);
    }

    /**
     * @param $content
     * @param $insert
     * @param $start
     * @param $ending
     * @return null|string
     */
    protected function insertStringBetween($content, $insert, $start, $ending)
    {
        $startPosition = \mb_strpos($content, $start);
        if ($startPosition === false) {
            return null;
        }

        $endPosition = \mb_strpos($content, $ending, $startPosition + \mb_strlen($start));
        if ($endPosition === false) {
            return null;
        }

        $cut = $startPosition + \mb_strlen($start);
        $prefix = \mb_substr($content, 0, $cut);
        $postfix = \mb_substr($content, $cut);

        return $prefix . $insert . $postfix;
    }

    protected function getFullyQualifiedTypeByMappingType($type)
    {
        return $type = \Doctrine\DBAL\Types\Type::getType($type);
    }

    /**
     * @param string $fileHeader
     * @return $this
     */
    public function setFileHeader($fileHeader)
    {
        $this->fileHeader = $fileHeader;
        return $this;
    }


    /**
     * @param int $indentChar
     * @return $this
     */
    public function setIndentChar($indentChar)
    {
        $this->indentChar = $indentChar;
        return $this;
    }

    /**
     * @param int $intents
     * @return $this
     */
    public function setIntents($intents)
    {
        $this->intents = $intents;
        return $this;
    }

    /**
     * @return string
     */
    protected function getIntention()
    {
        return \str_repeat($this->indentChar, $this->intents);
    }

    /**
     * @return string
     */
    public function getFileHeader(): string
    {
        return $this->fileHeader;
    }

    /**
     * @param ClassMetadataInfo $metadata
     * @return array
     */
    protected function generateFields(ClassMetadataInfo $metadata)
    {
        $fields = [];
        foreach ($metadata->fieldMappings as $mapping) {
            $fields[$mapping['columnName']] = $mapping;
            if (\mb_strpos($mapping['type'], '\\') !== false) {
                $fields[$mapping['columnName']]['type'] = $mapping['type'];
            } else {
                $fields[$mapping['columnName']]['type'] = \get_class(Type::getType($mapping['type']));
            }
        }
        return $fields;
    }
}
