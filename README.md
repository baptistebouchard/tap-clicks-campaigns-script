# Tap Clicks Campaign Import Script

> This script imports and compute counts for Tap Clicks Campaigns Data

### Librairies Used

* Logs: [Monolog](https://github.com/Seldaek/monolog)
* FtpClient: [nicolab/php-ftp-client](https://github.com/Nicolab/php-ftp-client)
* ORM: [Doctrine](http://www.doctrine-project.org/)
* Yaml: [symfony/yaml](https://github.com/symfony/yaml)
* Tests: [PhpUnit](https://phpunit.de/)

### Install
You will need php-7.1 and A Mysql Database with the schema given already imported
```
php composer.phar install
```

### Config

You can modify ftp and db configuration in src/config/config.dev.yml


### Execute
Password are passed to the script while running it.
```
php ./script.php ftppw=[ftpPassword] dbpw=[dataBasePassword] env=dev
```

### Run Tests
```
php composer.phar test
```

### Logs
Logs are created for each run in ./logs folder, containing the date of run in the log file name.