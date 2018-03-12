<?php

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
    function generate(ClassMetadataInfo $metadata, $destinationPath, $overwrite = false) : ?string;

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