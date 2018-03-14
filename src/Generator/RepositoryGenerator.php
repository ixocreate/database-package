<?php

namespace KiwiSuite\Database\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class RepositoryGenerator
 * @package KiwiSuite\Database\Generator
 */
class RepositoryGenerator extends AbstractGenerator
{

    protected static $template = '<?php

namespace <namespace>;


use KiwiSuite\Database\Repository\AbstractRepository;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use <entityFQCN>;
use <metadataFQCN>;

final class <repositoryClassName> extends AbstractRepository
{
    
    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return <entityClassName>::class;
    }
    
    public function loadMetadata(ClassMetadataBuilder $builder): void
    {
        $metadata = (new <metadataClassName>($builder));
    }
    
}

';

    /**
     * @param ClassMetadataInfo $metadata
     * @return string
     */
    public function generateCode(ClassMetadataInfo $metadata) {

        $variables = [
            '<namespace>'           => $this->getRepositoryClassNamespace($metadata->name),
            '<repositoryClassName>' => $this->getRepositoryClassName($metadata->name),
            '<entityClassName>'     => $this->getEntityClassName($metadata->name),
            '<entityFQCN>'          => $this->getEntityFQCN($metadata->name),
            '<metadataFQCN>'        => $this->getMetadataFQCN($metadata->name),
            '<metadataClassName>'   => $this->getMetadataClassName($metadata->name),
        ];

        return str_replace(array_keys($variables), array_values($variables), static::$template);

    }

    /**
     * @param ClassMetadataInfo $metadata
     * @param string $destinationPath
     * @param bool $overwrite
     * @return string|null
     */
    function generate(ClassMetadataInfo $metadata, $destinationPath, $overwrite = false) : ?string
    {
        $content = $this->generateCode($metadata);
        $content = str_replace('<indent>', $this->getIntention(), $content);

        $path = $destinationPath . DIRECTORY_SEPARATOR
              . str_replace('\\', \DIRECTORY_SEPARATOR, $this->getRepositoryFQCN($metadata->name)) . '.php';

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
        return 'Repository\\';
    }

}