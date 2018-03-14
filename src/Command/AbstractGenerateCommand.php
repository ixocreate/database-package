<?php

namespace KiwiSuite\Database\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use KiwiSuite\ApplicationConsole\Command\CommandInterface;
use KiwiSuite\Database\Generator\GeneratorInterface;
use KiwiSuite\Database\Type\TypeConfig;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\Mapping\Driver\DatabaseDriver;
use Doctrine\ORM\Tools\Console\MetadataFilter;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use KiwiSuite\Database\Generator\EntityGenerator;
use KiwiSuite\Database\Generator\RepositoryGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AbstractGenerateCommand
 * @package KiwiSuite\Database\Command
 */
abstract class AbstractGenerateCommand extends Command implements CommandInterface
{

    protected static $fileHeader = <<<FH
/**
 * kiwi-suite/admin (https://github.com/kiwi-suite/admin)
 *
 * @package kiwi-suite/admin
 * @see https://github.com/kiwi-suite/admin
 * @copyright Copyright (c) 2010 - 2018 kiwi suite GmbH
 * @license MIT License
 */
FH;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $master)
    {
        parent::__construct(static::getCommandName());
        $this->entityManager = $master;
    }

    abstract static protected function getType() : string;

    /**
     * @return GeneratorInterface[]
     */
    abstract protected function getGenerators() : array;

    public static function getCommandName()
    {
        return 'db:generate-' . static::getType();
    }

    protected function getDefaultDestinationPath() : ?string {

        if (basename(dirname(__DIR__, 3 )) === 'kiwi-suite' && basename(dirname(__DIR__, 4 )) === 'vendor') {
             $srcDir = dirname(__DIR__, 5 ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
             if (is_dir($srcDir)) {
                return $srcDir;
             }
        }
        return null;
    }

    protected function configure()
    {

        $destPath = $this->getDefaultDestinationPath();

         $this->setDescription('Generate '.static::getType().' classes and method stubs from your mapping information')
         ->addArgument('dest-path', $destPath ? InputArgument::OPTIONAL : InputArgument::REQUIRED, 'The path to generate your ' . static::getType() . ' classes.', $destPath)
         ->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Defines a namespace for the generated '.static::getType().' classes', 'App\\')
         ->addOption('filter', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'A string pattern used to match '.static::getType().' classes that should be processed.', ['\\\App']);
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
    public static function getFileHeader() {
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
        $destPath = realpath($input->getArgument('dest-path'));

        if ( ! file_exists($destPath)) {
            throw new \InvalidArgumentException(
                sprintf(static::getType() . " destination directory '<info>%s</info>' does not exist.", $input->getArgument('dest-path'))
            );
        }

        if ( ! is_writable($destPath)) {
            throw new \InvalidArgumentException(
                sprintf(static::getType() . " destination directory '<info>%s</info>' does not have write permissions.", $destPath)
            );
        }

        foreach($this->getGenerators() as $generator) {

            $databaseDriver->setNamespace($namespace . $generator->getNamespacePostfix());

            $cmf = new DisconnectedClassMetadataFactory();
            $cmf->setEntityManager($em);
            $metadatas = $cmf->getAllMetadata();
            $metadatas = MetadataFilter::filter($metadatas, $filter = $input->getOption('filter'));

            if (empty($metadatas)) {
                $ui->success('No Metadata Classes to process.');
                return;
            }

            $generator->setFileHeader(self::getFileHeader());
            foreach ($metadatas as $metadata) {
                /** @var $metadata ClassMetadataInfo */
                $generatedFile = $generator->generate($metadata, $destPath, true);
                if ($generatedFile) {
                    $ui->text(sprintf('Generated %s "<info>%s</info>"', static::getType(), $generatedFile));
                } else {
                    $ui->text(sprintf('Couln\'d generate %s "<info>%s</info>"', static::getType(), $generatedFile));
                }

            }
        }

    }

}