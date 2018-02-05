<?php
namespace KiwiSuite\Database\Type\Generator;

final class Generator
{
    private $template = <<<'EOD'
<?php
namespace KiwiSuite\GeneratedDatabaseType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use KiwiSuite\Entity\Type\Type;
use KiwiSuite\Entity\Type\TypeInterface;
use %s as BaseType;

final class %s extends BaseType
{
    public function getName()
    {
        return '%s';
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
    
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        
        return Type::create($value, $this->getName());
    }
    
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof TypeInterface) {
            $value = $value->getValue();
        }
        
        return  parent::convertToDatabaseValue($value, $platform);
    }
}

EOD;

    /**
     * @param string $type
     * @param string $databaseType
     * @return string
     */
    public function generate(string $type, string $databaseType) : string
    {
        return sprintf(
            $this->template,
            $databaseType,
            $this->generateName($type),
            $type
        );
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
