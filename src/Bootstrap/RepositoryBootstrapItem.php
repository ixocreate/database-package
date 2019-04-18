<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Bootstrap;

use Ixocreate\Application\Service\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Service\Configurator\ConfiguratorInterface;
use Ixocreate\Database\Repository\RepositoryConfigurator;

final class RepositoryBootstrapItem implements BootstrapItemInterface
{
    /**
     * @return ConfiguratorInterface
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new RepositoryConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'repository';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'repository.php';
    }
}
