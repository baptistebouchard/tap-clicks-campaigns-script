<?php

require __DIR__ . '/vendor/autoload.php';

use Monolog\Formatter\LineFormatter;
use Script\Utils\Setuper;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

script($argv);

function script($argv)
{
    // Logger Setup
    $date = (new DateTime())->format('Y-m-d H:i:s');
    $fileLogger = new Logger('Tap-clicks-log-import');
    $fileLogger->pushHandler(new StreamHandler(__DIR__ . "/logs/script-{$date}.log", Logger::INFO));

    $output = "[%datetime%] - %level_name% - %message%\n";
    $formatter = new LineFormatter($output);
    $streamHandler = new StreamHandler('php://stdout', Logger::INFO);
    $streamHandler->setFormatter($formatter);
    $stdOutLogger = new Logger('Tap-clicks-console');
    $stdOutLogger->pushHandler($streamHandler);

    // Setup
    try {
        $stdOutLogger->info('Setup Script.' . "\n");
        $setuper = new Setuper($fileLogger, $stdOutLogger);
        $fileLogger->info('Process start.');
        $arguments = $setuper->getScriptArgs($argv);
        $config = $setuper->getConfig($arguments['env']);
        $ftpClient = $setuper->getFtpClient($arguments, $config);
        $entityManager = $setuper->getDoctrineEntityManager($arguments, $config);
        $importScript = $setuper->setupImportScripCommand($ftpClient, $entityManager);

    } catch (\Exception $exception) {
        $stdOutLogger->error($exception->getMessage());
        $stdOutLogger->error('Error during setup!');
        return;
    }

    // Run
    try {
        $importScript->run();
        $setuper->closeConnections($ftpClient, $entityManager);
    } catch (\Exception $exception) {
        $fileLogger->error($exception->getMessage());
        $stdOutLogger->error('Error during process! See logs for more infos');
        $setuper->closeConnections($ftpClient, $entityManager);

    } catch(Error $error) {
        $fileLogger->error($error->getMessage());
        $stdOutLogger->error('Error during process! See logs for more infos');
        return;
    }

    $fileLogger->info('Process run successful.');
    $stdOutLogger->info('All Done!');
}



