<?php

declare(strict_types=1);

use Lemon\Env;

return [
    'driver' => Env::get('DB_DRIVER'),

    // --- Sqlite specific ---
    'file' => Env::file('DB_FILE', 'sqlite', 'database'),

    'host' => Env::get('DB_HOST'),
    'port' => Env::get('DB_PORT'),
    'dbname' => Env::get('DB_NAME'),
    'user' => Env::get('DB_USER'),
    'password' => Env::get('DB_PASSWORD'),

    // --- Postgre specific ---
    'sslmode' => Env::get('DB_SSLMODE', 'prefer'),

    // --- Mysql specific ---
    'unix_socket' => Env::get('DB_UNIX_SOCKET', ''),
    'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
];
