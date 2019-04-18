<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Package\Database\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class RepositoryGenerator
 * @package Ixocreate\Package\Database\Generator
 */
class RepositoryGenerator extends AbstractGenerator
{
    protected static $template = '<?php
<fileHeader>
declare(strict_types=1);

<namespace>

use Ixocreate\Package\Database\Repository\AbstractRepository;
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
