<?php

use Dotenv\Dotenv;
use Src\Core\App;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// Create the app
$app = new App();

// Load routes
(require dirname(__DIR__) . '/routes/route.php')($app);

// Run app
$app->run();
