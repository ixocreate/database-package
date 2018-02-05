<?php
namespace KiwiSuite\Database\Type\Generator;

final class Generator
{
    private $template = <<<'EOD'
<?php
namespace KiwiSuite\GeneratedDatabaseType;

final class %s extends %s
{
    
}

EOD;

    /**
     * @param string $type
     * @param string $databaseType
     * @return string
     */
    public function generate(string $type, string $databaseType) : string
    {
        return sprintf($this->template, $this->generateName($type), $databaseType);
    }

    /**
     * @param string $type
     * @return string
     */
    public function generateName(string $type) : string
    {
        return 'Type' . \sha1($type);
    }

    /**
     * @param string $type
     * @return string
     */
    public function generateFullQualifiedName(string $type) : string
    {
        return '\KiwiSuite\GeneratedDatabaseType\\' . $this->generateName($type);
    }
}
