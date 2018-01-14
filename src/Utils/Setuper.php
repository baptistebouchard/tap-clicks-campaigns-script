<?php
/**
 * Created by PhpStorm.
 * User: baptistebouchard
 * Date: 18-01-14
 * Time: 11:50
 */

namespace Script\Utils;

use Doctrine\ORM\ORMException;
use FtpClient\FtpClient;
use FtpClient\FtpException;
use InvalidArgumentException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Script\ImportScriptCommand;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Contains all the utils to setup the script.
 * Class Setuper
 * @package Script\Utils
 */
class Setuper
{
    private $fileLogger;
    private $stdOutLogger;

    /**
     * Setuper constructor.
     * @param $fileLogger
     * @param $stdOutLogger
     */
    public function __construct($fileLogger, $stdOutLogger)
    {
        $this->fileLogger = $fileLogger;
        $this->stdOutLogger = $stdOutLogger;
    }


    /**
     * @param string $env
     * @return mixed
     */
    public function getConfig($env = 'dev')
    {
        try {
            return Yaml::parseFile(__DIR__ . "/../Config/config.{$env}.yml");
        } catch(ParseException $exception) {
            $this->stdOutLogger->error('Config Parsing Error!');
            throw $exception;
        }
    }

    /**
     * @return array
     * Args Mapping
     */
    public function getScriptArgs($argv)
    {
        $arguments = [];

        foreach ($argv as $arg) {
            $keyValues = explode('=', $arg);
            if (count($keyValues) === 2) {
                $arguments[$keyValues[0]] = $keyValues[1];
            }
        }

        return $arguments;
    }

    /**
     * @param $arguments
     * @param $config
     * @return FtpClient
     * @throws FtpException | InvalidArgumentException
     */
    public function getFtpClient($arguments, $config)
    {
        if (empty($arguments['ftppw']) || empty($config['ftp'])) {
            $this->stdOutLogger->error('Invalid Ftp config!');
            throw new InvalidArgumentException('Invalid Ftp config!');
        }

        try {
            $ftpClient = new FtpClient();
            $ftpClient->connect($config['ftp']['host']);
            $ftpClient->login($config['ftp']['identifier'], $arguments['ftppw']);
        } catch (FtpException $exception) {
            $this->stdOutLogger->error('Ftp setup error!');
            throw $exception;
        }

        return $ftpClient;
    }


    /**
     * @param $arguments
     * @param $config
     * @return EntityManager
     * @throws ORMException | InvalidArgumentException
     */
    public function getDoctrineEntityManager($arguments, $config)
    {
        try {

            // DB connection Initialization
            // Create a simple "default" Doctrine ORM configuration for Annotations

            if (empty($arguments['dbpw']) || empty($config['database'])) {
                throw new InvalidArgumentException('DB password is required in script arguments.');
            }

            $isDevMode = true;
            $dbConfig = Setup::createConfiguration($isDevMode);
            $paths = [__DIR__ . '/src/Entities'];
            $driver = new AnnotationDriver(new AnnotationReader(), $paths);

            AnnotationRegistry::registerLoader('class_exists');
            $dbConfig->setMetadataDriverImpl($driver);

            $conn = $config['database'];
            $conn['password'] = $arguments['dbpw'];

            return EntityManager::create($conn, $dbConfig);

        } catch (ORMException $exception) {

            $this->stdOutLogger->info('DB setup error!');
            throw $exception;

        }
    }

    /**
     * Depency Injection for Import Script
     * @param $ftpClient
     * @param $entityManager
     * @return ImportScriptCommand
     */
    public function setupImportScripCommand($ftpClient, $entityManager)
    {
        $serviceUtils = new ServiceUtils();
        $dataUtils = new DataUtils();
        $dataManager = new DataManager($entityManager, $dataUtils);
        $countManager = new CountManager($entityManager, $dataUtils);
        $objectMapper = new ObjectMapper();

        $importScript = new ImportScriptCommand(
            $dataManager,
            $countManager,
            $ftpClient,
            $objectMapper,
            $serviceUtils,
            $this->fileLogger,
            $this->stdOutLogger
        );

        return $importScript;
    }

    public function closeConnections($ftpClient, $entityManager)
    {
        $ftpClient->close();
        $this->stdOutLogger->info('ftp connection close!');

        $entityManager->getConnection()->close();
        $this->stdOutLogger->info('db connection close!');
    }

}