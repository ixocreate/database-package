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

use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class AbstractGenerator
 * @package KiwiSuite\Database\Generator
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
     * @param string $fileHeader
     * @return $this
     */
    public function setFileHeader($fileHeader);

    /**
     * @param int $indentChar
     * @return $this
     */
    public function setIndentChar($indentChar);

    /**
     * @param int $intents
     * @return $this
     */
    public function setIntents($intents);

    /**
     * @return string
     */
    public function getNamespacePostfix() : string;
}
