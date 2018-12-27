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
namespace Ixocreate\Database\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class RepositoryGenerator
 * @package Ixocreate\Database\Generator
 */
class RepositoryGenerator extends AbstractGenerator
{
    protected static $template = '<?php
<fileHeader>
declare(strict_types=1);

<namespace>

use Ixocreate\Database\Repository\AbstractRepository;
use <entityFQCN>;

final class <className> extends AbstractRepository
{
    
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return <entityClassName>::class;
    }   
}

';

    /**
     * @param ClassMetadataInfo $metadata
     * @return string
     */
    public function generateCode(string $name, ClassMetadataInfo $metadata)
    {
        $variables = [
            '<fileHeader>'          => $this->getFileHeader(),
            '<namespace>'           => $this->generateNamespace(),
            '<className>'           => $this->getRepositoryClassName($name),
            '<entityClassName>'     => $this->getEntityClassName($name),
            '<entityFQCN>'          => $this->getEntityFQCN($name),
        ];

        return \str_replace(\array_keys($variables), \array_values($variables), static::$template);
    }

    /**
     * @return string
     */
    public function getNamespacePostfix(): string
    {
        return 'Repository\\';
    }

    /**
     * @return string
     */
    public function getFilenamePostfix(): string
    {
        return 'Repository';
    }
}
