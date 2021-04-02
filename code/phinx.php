<?php

use FrankDeBoerOnline\Configuration\Configuration;

include __DIR__ . '/.config/config.php';

Configuration::$dotenv_loaded = false;
Configuration::get();

# Set migration paths
$migrationPaths = [
    '%%PHINX_CONFIG_DIR%%/.config/db/migrations'
];

if(!in_array($_ENV['DEPLOY_ENVIRONMENT'], ['acceptance', 'production'])) {
    $migrationPaths[] = '%%PHINX_CONFIG_DIR%%/tests/db/migrations';
}



# Set seed paths
$seedPaths = [];
if(!in_array($_ENV['DEPLOY_ENVIRONMENT'], ['acceptance', 'production'])) {
    $seedPaths[] = '%%PHINX_CONFIG_DIR%%/.config/db/seeds';
    $seedPaths[] = '%%PHINX_CONFIG_DIR%%/tests/db/seeds';
}

$environmentSettings = [
    'adapter' => $_ENV['DB_TYPE'],
    'host' => $_ENV['DB_HOST'],
    'name' => $_ENV['DB_DATABASE'],
    'user' => $_ENV['DB_USERNAME'],
    'pass' => $_ENV['DB_PASSWORD'],
    'port' => $_ENV['DB_PORT'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
];

return
    [
        'paths' => [
            'migrations' => $migrationPaths,
            'seeds' => $seedPaths
        ],

        'environments' =>
            [
                'default_database' => 'testing',
                'default_migration_table' => 'phinxlog',

                'testing' => [
                    'adapter' => 'sqlite',
                    'host' => 'localhost',
                    'name' => '%%PHINX_CONFIG_DIR%%/tests/db/data/test',
                    'user' => 'ignored',
                    'pass' => 'ignored',
                    'port' => 1234,
                    'charset' => 'utf8'
                ],

                'dev' => $environmentSettings,

                'acc' => $environmentSettings,

                'prod' => $environmentSettings,
            ],
    ];
