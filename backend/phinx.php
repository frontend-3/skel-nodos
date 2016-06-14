<?php
/**
 * This config file will read the database values from Skelsus' local.php
 * config file, so there's nothing extra to configure.
 * 
 * @version v1.0.0
 * @author Jaime Wong <jaime.wong@nodosdigital.pe>
 */

$configFile = __DIR__ . '/config/autoload/database.php';
if (!file_exists($configFile)) {
    echo "No $configFile file found! Have you configured your database yet?";
    exit;
}

$config = include($configFile);

return [
    'paths' => [
        'migrations' => __DIR__ . '/migrations',
        'seeds'      => __DIR__ . '/migrations/seeds',
    ],
    
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'default',
        'default' => [
            'adapter' => 'mysql',
            'host'    => $host,
            'name'    => $database,
            'user'    => $username,
            'pass'    => $password,
            'port'    => (isset($port) ?: 3306),
            'charset' => 'utf8',
        ],
    ],
];
