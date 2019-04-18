<?php
/**
 * @link https://github.com/ixocreate
 * @copyright IXOCREATE GmbH
 * @license MIT License
 */

declare(strict_types=1);

namespace Ixocreate\Database\Package\Command;

use Doctrine\ORM\EntityManagerInterface;
use Ixocreate\Application\Console\CommandInterface;
use Ixocreate\Database\Package\Generator\GeneratorInterface;
use Ixocreate\Entity\Package\Type\TypeSubManager;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\Driver\DatabaseDriver;
use Doctrine\ORM\Tools\Console\MetadataFilter;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AbstractGenerateCommand
 * @package Ixocreate\Database\Package\Command
 */
abstract class AbstractGenerateCommand extends Command implements CommandInterface
{
    protected static $fileHeader = <<<FH
FH;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var TypeSubManager
     */
    protected $typeSubManager;

    public function __construct(EntityManagerInterface $master, TypeSubManager $typeSubManager)
    {
        parent::__construct(static::getCommandName());
        $this->entityManager = $master;
        $this->typeSubManager = $typeSubManager;
    }

    abstract protected static function getType() : string;

    /**
     * @return GeneratorInterface[]
     */
    abstract protected function getGenerators() : array;

    public static function getCommandName()
    {
        return 'db:generate-' . static::getType();
    }

    protected function getDefaultDestinationPath() : ?string
    {
        /**
         * TODO: describe why this is still kiwi-suite or cleanup
         */
        if (\basename(\dirname(__DIR__, 3)) === 'kiwi-suite' && \basename(\dirname(__DIR__, 4)) === 'vendor') {
            $srcDir = \dirname(__DIR__, 5) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
            if (\is_dir($srcDir)) {
                return $srcDir;
            }
        }
        return null;
    }

    protected function configure()
    {
        $destPath = $this->getDefaultDestinationPath();

        $this->setDescription('Generate ' . static::getType() . ' classes and method stubs from your mapping information')
         ->addArgument('dest-path', $destPath ? InputArgument::OPTIONAL : InputArgument::REQUIRED, 'The path to generate your ' . static::getType() . ' classes.', $destPath)
         ->addOption('tableprefix', 'p', InputOption::VALUE_OPTIONAL, 'Defines the table prefix', 'App')
         ->addOption('namespace', 's', InputOption::VALUE_OPTIONAL, 'Defines a namespace for the generated ' . static::getType() . ' classes', 'App\\')
         ->addOption('table', 't', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The table name ' . static::getType() . ' classes should be generated.')
         ->addOption('filter', 'f', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'A string pattern used to match ' . static::getType() . ' classes that should be processed.', ['^App']);
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return string
     */
    public static function getFileHeader()
    {
        return static::$fileHeader;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ui = new SymfonyStyle($input, $output);

        $em = $this->getEntityManager();
        $databaseDriver = new DatabaseDriver(
            $em->getConnection()->getSchemaManager()
        );

        $em->getConfiguration()->setMetadataDriverImpl(
            $databaseDriver
        );

        $namespace = $input->getOption('namespace');

        // Process destination directory
        $destPath = \realpath($input->getArgument('dest-path'));

        if (! \file_exists($destPath)) {
            throw new \InvalidArgumentException(
                \sprintf(static::getType() . " destination directory '<info>%s</info>' does not exist.", $input->getArgument('dest-path'))
            );
        }

        if (! \is_writable($destPath)) {
            throw new \InvalidArgumentException(
                \sprintf(static::getType() . " destination directory '<info>%s</info>' does not have write permissions.", $destPath)
            );
        }

        $cmf = new DisconnectedClassMetadataFactory();
        $cmf->setEntityManager($em);
        $metadatas = $cmf->getAllMetadata();

        $tableFilter = $input->getOption('table');
        if (!empty($tableFilter)) {
            $filteredMetadata = [];
            $filterNames = [];
            foreach ($tableFilter as $table) {
                $filterNames[] = \str_replace('_', '', \ucwords($table, '_'));
            }
            foreach ($metadatas as $metadata) {
                if (\in_array($metadata->getName(), $filterNames)) {
                    $filteredMetadata[] = $metadata;
                }
            }
        } else {
            $filteredMetadata = MetadataFilter::filter($metadatas, $filter = $input->getOption('filter'));
        }

        if (empty($metadatas)) {
            $ui->success('No Metadata Classes to process.');
            return;
        }

        foreach ($this->getGenerators() as $generator) {
            $generator->setFullMetadata($metadatas);
            $generator->setFileHeader(self::getFileHeader());
            $generator->setNamespace($namespace);
            $generator->setTablePrefix($input->getOption('tableprefix'));
            foreach ($filteredMetadata as $metadata) {
                /** @var $metadata ClassMetadataInfo Couldn't */
                $generatedFile = $generator->generate($metadata, $destPath, true);
                if ($generatedFile) {
                    $ui->text(\sprintf('Generated %s "<info>%s</info>"', static::getType(), $generatedFile));
                } else {
                    $ui->text(\sprintf('Couldn\'t generate %s "<info>%s</info>"', static::getType(), $generatedFile));
                }
            }
        }
    }
}
