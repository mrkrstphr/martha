<?php

use \Martha\Core\Job\Runner;
use \Martha\Core\System;

require __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['argc'] != 2) {
    die("Usage: php run.php buildId\n");
}

$buildId = $_SERVER['argv'][1];

$config = include __DIR__ . '/../config/autoload/system.local.php';

$app = Zend\Mvc\Application::init(require __DIR__ . '/../config/application.config.php');

$buildRepository = $app->getServiceManager()->get('BuildRepository');

$system = System::initialize(
    $app->getServiceManager()->get('Doctrine\ORM\EntityManager'),
    $config['martha']
);

$runner = new Runner($system, $buildRepository, $config['martha']);
$runner->run($buildId);
