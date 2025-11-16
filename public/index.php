<?php

use Dotenv\Dotenv;
use Src\Core\App;


// Check if vendor/autoload.php exists
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die('Please run <code>composer install</code> first.');
}

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();
// define('BASE_PATH', dirname(__DIR__, 2));
define('BASE_PATH', dirname(__DIR__));

// Create the app
$app = new App();

// Load routes
(require dirname(__DIR__) . '/routes/route.php')($app);


require_once dirname(__DIR__) . '/src/Core/helpers.php';

// Run app
$app->run();
