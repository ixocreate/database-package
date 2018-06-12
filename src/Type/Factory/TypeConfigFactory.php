<?php
namespace KiwiSuite\Database\Type\Factory;

use KiwiSuite\Contract\ServiceManager\FactoryInterface;
use KiwiSuite\Contract\ServiceManager\ServiceManagerInterface;
use KiwiSuite\Contract\Type\DatabaseTypeInterface;
use KiwiSuite\Database\Type\TypeConfig;
use KiwiSuite\Entity\Type\TypeSubManager;

final class TypeConfigFactory implements FactoryInterface
{

    /**
     * @param ServiceManagerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ServiceManagerInterface $container, $requestedName, array $options = null)
    {
        /** @var TypeSubManager $typeSubmanager */
        $typeSubmanager = $container->get(TypeSubManager::class);

        $types = [];
        foreach ($typeSubmanager->getServices() as $service) {
            if (!\is_subclass_of($service, DatabaseTypeInterface::class, true)) {
                continue;
            }

            $types[$service] = [
                'baseType' => $service::baseDatabaseType()
            ];
        }

        return new TypeConfig($types);
    }
}
