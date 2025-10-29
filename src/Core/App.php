<?php
namespace Src\Core;

class App
{
    protected Container $container;
    protected Router $router;

    public function __construct()
    {
        $this->container = new Container();
        $this->router = new Router($this->container);

        $this->loadConfig();
        $this->registerServices();
    }

    protected function loadConfig(): void
    {
        $configPath = dirname(__DIR__, 2) . '/config';

        $config = [
            'app' => require $configPath . '/app.php',
            'database' => require $configPath . '/database.php',
        ];

        $this->container->set('config', fn() => $config);
    }

    protected function registerServices(): void
    {
        // ✅ Lazy load database
        $this->container->set('db', function ($c) {
            $database = new Database($c);
            return $database->getConnection();
        });
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function container(): Container
    {
        return $this->container;
    }

    public function run(): void
    {
        $request = new Request();
        $response = new Response();

        $this->router->dispatch($request, $response);
    }
    public function getDb() {
        return $this->container->get('db'); 
    }
}
