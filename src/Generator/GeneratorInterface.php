<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Package\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class AbstractGenerator
 * @package Ixocreate\Database\Package\Generator
 */
interface GeneratorInterface
{
    /**
     * @param ClassMetadataInfo $metadata
     * @param $destinationPath
     * @param bool $overwrite
     * @return string|null the generated absolute file path or null when not written
     */
    public function generate(ClassMetadataInfo $metadata, $destinationPath, $overwrite = false) : ?string;

    /**
     * @param ClassMetadataInfo[] $metadata
     * @return mixed
     */
    public function setFullMetadata(array $metadata);

    /**
     * @param string $fileHeader
     * @return $this
     */
    public function setFileHeader($fileHeader);

    /**
     * @param string $namespace
     * @return $this
     */
    public function setNamespace(string $namespace);

    /**
     * @param string $tablePrefix
     * @return $this
     */
    public function setTablePrefix(string $tablePrefix);

    public function getNamespacePostfix() : string;

    public function getFilenamePostfix(): string;
}
