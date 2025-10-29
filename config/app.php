<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'MicroFramework',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => ($_ENV['APP_DEBUG'] ?? false) === 'true',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'UTC',
    'base_url' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
];
