<?php

$doctrineConfig = require __DIR__ . '/config/autoload/system.local.php';
$connectionConfig = $doctrineConfig['doctrine']['connection']['orm_default'];

$adapterMap = [
    'Doctrine\DBAL\Driver\PDOMySql\Driver' => 'mysql',
    'Doctrine\DBAL\Driver\PDOPgSql\Driver' => 'pgsql',
    'Doctrine\DBAL\Driver\PDOSqlite\Driver' => 'sqlite'
];

$config = $connectionConfig['params'];
$config['name'] = $connectionConfig['params']['dbname'];
$config['pass'] = $connectionConfig['params']['password'];
$config['adapter'] = isset($adapterMap[$connectionConfig['driverClass']]) ?
    $adapterMap[$connectionConfig['driverClass']] : '';

if (empty($config['adapter'])) {
    throw new Exception('Unsupported database driver: ' . $connectionConfig['driverClass']);
}

return [
    'paths' => [
        'migrations' => __DIR__ . '/config/migrations'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_database' => 'production',
        'production' => $config
    ]
];
