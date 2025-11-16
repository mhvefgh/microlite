<?php

namespace Src\Core;

use Src\Core\Request;
use Src\Core\Response;
use Src\Core\Model;
use Src\Core\Security\AuthUserProvider;
use Src\Core\Security\SessionManager;

/**
 * Core application bootstrapper.
 *
 * - Builds the DI container
 * - Loads configuration files (if present)
 * - Registers services (database is optional)
 * - Starts routing
 */
class App
{
    /** @var Container Dependency‑injection container */
    protected Container $container;

    /** @var Router HTTP router */
    protected Router $router;

    /**
     * Initialise container, router and load configuration.
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->router    = new Router($this->container);

        $this->loadConfig();
        $this->registerServices();
    }

    /**
     * Load `config/app.php` and `config/database.php` if they exist.
     * Missing files are silently ignored – an empty array is stored.
     */
    protected function loadConfig(): void
    {
        $configPath = dirname(__DIR__, 2) . '/config';

        $config = [
            // Application configuration (optional)
            'app'      => file_exists($configPath . '/app.php')
                ? require $configPath . '/app.php'
                : [],

            // Database configuration (optional)
            'database' => file_exists($configPath . '/database.php')
                ? require $configPath . '/database.php'
                : [],
        ];

        // Store the whole config tree in the container (lazy closure)
        $this->container->set('config', fn() => $config);
    }

    /**
     * Register global services.
     *
     * The `db` service is created **only** when a valid database
     * configuration is present.  If not, `null` is returned so the
     * application can run without a database.
     */
    protected function registerServices(): void
    {
        $this->container->set('db', function ($c) {
            $dbConfig = $c->get('config')['database'] ?? null;

            // No config or no database name → disable DB
            if (!$dbConfig || empty($dbConfig['database'])) {
                return null;
            }

            $database = new Database($c);
            return $database->getConnection();
        });
    }

    /**
     * Return the router instance (used by route files).
     *
     * @return Router
     */
    public function router(): Router
    {
        return $this->router;
    }

    /**
     * Return the DI container (useful for testing / extensions).
     *
     * @return Container
     */
    public function container(): Container
    {
        return $this->container;
    }

    /**
     * Boot the request lifecycle.
     *
     * - Initialise the global Medoo connection (if DB is configured)
     * - Dispatch the HTTP request through the router
     */
    public function run(): void
    {
        
        // Attach Medoo instance to the Model base class only when available
        $db = $this->container->get('db');
        if ($db !== null) {
            Model::setConnection($db);
        }
       
        AuthUserProvider::init();

        $request  = new Request();
        $response = new Response();

        $this->router->dispatch($request, $response);
    }
}