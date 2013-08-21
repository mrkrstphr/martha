<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$config = require __DIR__ . '/../config/system.php';

$app['debug'] = true;

$app->get('/hook/build', function () use ($app, $config) {
    $notify = file_get_contents('sample-hook.js');
    $notify = str_replace(['var commit = ', '};'], ['', '}'], $notify);

    $notify = json_decode($notify, true);

    $hook = Martha\Core\Job\Trigger\GitHubWebHook\Factory::createHook($notify);
    $runner = new Martha\Core\Job\Runner($hook, $config);
    $runner->run();

    return 'Done!';
});

$app->run();
