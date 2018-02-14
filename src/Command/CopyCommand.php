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
namespace KiwiSuite\Database\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CopyCommand extends Command
{
    protected function configure()
    {
        $this->setName('db:copy');
        $this->setDescription('Copy migrations');

        $this->addOption('force', 'f');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = 'migrations';

        if (\file_exists($path)) {
            if (!\is_dir($path)) {
                throw new RuntimeException(sprintf("Migration directory '%s' is not a directory"));
            }
            if (\is_writable($path)) {
                throw new RuntimeException(sprintf("Migration directory '%s' is not a writable"));
            }
        } else {
            if (!\mkdir($path, 0777, true)) {
                throw new RuntimeException(sprintf("Unable to generate migration directory '%s'"));
            }
        }



    }
}
