<?php
declare(strict_types=1);

namespace Ixocreate\Database;

use Ixocreate\Application\Bootstrap\BootstrapItemInterface;
use Ixocreate\Application\Configurator\ConfiguratorInterface;

final class DatabaseBootstrapItem implements BootstrapItemInterface
{

    /**
     * @return mixed
     */
    public function getConfigurator(): ConfiguratorInterface
    {
        return new DatabaseConfigurator();
    }

    /**
     * @return string
     */
    public function getVariableName(): string
    {
        return 'database';
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return 'database.php';
    }
}
