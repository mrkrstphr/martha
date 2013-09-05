<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$settingPath = __DIR__ . '/config/autoload/system.local.php';

if (!file_exists($settingPath)) {
    throw new Exception('Please setup your config/autoload/system.local.php file');
}

$settings = require $settingPath;

if (!isset($settings['martha']['doctrine']['params'])) {
    throw new Exception('Please setup your database connection information in config/autoload/system.local.php');
}

$isDevMode = false;

$config = Setup::createYAMLMetadataConfiguration($settings['martha']['doctrine']['mappings'], $isDevMode);
$em = EntityManager::create($settings['martha']['doctrine']['params'], $config);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(
    array(
        'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
        'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
    )
);
