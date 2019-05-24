<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOLIT GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Type\Factory;

use Ixocreate\Database\Type\TypeConfig;
use Ixocreate\Schema\Type\DatabaseTypeInterface;
use Ixocreate\Schema\Type\TypeSubManager;
use Ixocreate\ServiceManager\FactoryInterface;
use Ixocreate\ServiceManager\ServiceManagerInterface;

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
        /** @var TypeSubManager $typeSubManager */
        $typeSubManager = $container->get(TypeSubManager::class);

        $types = [];
        foreach ($typeSubManager->getServices() as $service) {
            if (!\is_subclass_of($service, DatabaseTypeInterface::class, true)) {
                continue;
            }

            $types[$service] = [
                'baseType' => $service::baseDatabaseType(),
            ];
        }

        return new TypeConfig($types);
    }
}
