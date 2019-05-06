<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Type\Generator;

final class Generator
{
    private $template = <<<'EOD'
<?php
namespace Ixocreate\GeneratedDatabaseType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Ixocreate\Schema\Type\Type;
use Ixocreate\Schema\Type\TypeInterface;
use Ixocreate\Schema\Type\DatabaseTypeInterface;
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
        
        if ($value === null) {
            return $value;
        }
        
        return Type::create($value, $this->getName());
    }
    
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof DatabaseTypeInterface) {
            $value = $value->convertToDatabaseValue();
        } elseif ($value instanceof TypeInterface) {
            $value = (string) $value;
        }
        
        return parent::convertToDatabaseValue($value, $platform);
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
        return \sprintf(
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
        return '\Ixocreate\GeneratedDatabaseType\\' . $this->generateName($type);
    }
}
