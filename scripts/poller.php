<?php

require __DIR__ . '/../vendor/autoload.php';

$config = include __DIR__ . '/../config/autoload/system.local.php';

$app = Zend\Mvc\Application::init(require __DIR__ . '/../config/application.config.php');

$buildRepository = $app->getServiceManager()->get('BuildRepository');

$queue = new \Martha\Core\Job\Queue(
    $buildRepository,
    $config
);
$queue->run();
