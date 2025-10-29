<?php

return [
    'type' => $_ENV['DB_CONNECTION'] ?? 'mysql',
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'database' => $_ENV['DB_DATABASE'] ?? '',
    'username' => $_ENV['DB_USERNAME'] ?? '',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix' => $_ENV['DB_PREFIX'] ?? '',
    'port' => $_ENV['DB_PORT'] ?? 3306,
    'logging' => ($_ENV['DB_LOGGING'] ?? 'false') === 'true',
];
