<?php

namespace KiwiSuite\Database\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class ResourceGenerator
 * @package KiwiSuite\Database\Generator
 */
class ResourceGenerator extends AbstractGenerator
{

    protected static $template = '<?php

namespace <namespace>;

use KiwiSuite\Admin\Resource\ResourceInterface;
use <repositoryFQCN>;

final class <resourceClassName> implements ResourceInterface
{
    
    public static function name(): string
    {
        return "<resourceName>";
    }

    public function repository(): string
    {
        return <repositoryClassName>::class;
    }

    public function icon(): string
    {
        return "fa";
    }

    public function createMessage(): ?string
    {
        return null;
    }

    public function updateMessage(): ?string
    {
        return null;
    }

    public function deleteMessage(): ?string
    {
        return null;
    }

    public function indexAction(): ?string
    {
        return null;
    }
    
}

';

    /**
     * @param ClassMetadataInfo $metadata
     * @return string
     */
    public function generateCode(ClassMetadataInfo $metadata) {

        $variables = [
            '<namespace>'           => $this->getResourceClassNamespace($metadata->name),
            '<repositoryClassName>' => $this->getRepositoryClassName($metadata->name),
            '<repositoryFQCN>'      => $this->getRepositoryFQCN($metadata->name),
            '<resourceClassName>'   => $this->getResourceClassName($metadata->name),
            '<resourceName>'     => strtolower($this->getClassNameKiwi($metadata->name)),
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
              . str_replace('\\', \DIRECTORY_SEPARATOR, $this->getResourceFQCN($metadata->name)) . '.php';

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
        return 'Resource\\';
    }

}