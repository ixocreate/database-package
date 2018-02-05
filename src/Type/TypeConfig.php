<?php
namespace KiwiSuite\Database\Type;

final class TypeConfig implements \Serializable
{
    /**
     * @var array
     */
    private $types;

    /**
     * TypeConfig constructor.
     * @param TypeConfigurator $typeConfigurator
     */
    public function __construct(TypeConfigurator $typeConfigurator)
    {
        $this->types = $typeConfigurator->getTypes();
    }

    /**
     * @return array
     */
    public function getTypes() : array
    {
        return $this->types;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->types);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $this->types = unserialize($serialized);
    }
}
