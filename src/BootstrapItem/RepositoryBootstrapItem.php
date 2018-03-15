<?php
/**
 * kiwi-suite/database (https://github.com/kiwi-suite/database)
 *
 * @package kiwi-suite/database
 * @see https://github.com/kiwi-suite/database
 * @copyright Copyright (c) 2010 - 2017 kiwi suite GmbH
 * @license MIT License
 */

declare(strict_types=1);
namespace KiwiSuite\Database\BootstrapItem;

use KiwiSuite\Contract\Application\BootstrapItemInterface;
use KiwiSuite\Contract\Application\ConfiguratorInterface;
use KiwiSuite\Database\Repository\RepositoryConfigurator;

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
