<?php
namespace Src\Core;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;
use Throwable;

class Router
{
    protected array $routes = [];
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    protected function executeAction(string|array $action, Request $request, Response $response, array $vars): Response|string
    {
        if (is_string($action) && str_contains($action, '@')) {
            [$controller, $method] = explode('@', $action);
            $controllerClass = "App\\Controllers\\$controller";

            if (!class_exists($controllerClass)) {
                throw new \RuntimeException("Controller not found: $controllerClass");
            }

            // Resolve from container (auto dependency injection)
            $controllerInstance = $this->container->get($controllerClass);

            if (!method_exists($controllerInstance, $method)) {
                throw new \RuntimeException("Method $method not found in controller $controllerClass");
            }

            // Call the controller method with request, response, and route vars
            return call_user_func([$controllerInstance, $method], $request, $response, $vars);
        }

        // If the route is defined as a callable (closure)
        if (is_callable($action)) {
            return call_user_func($action, $request, $response, $vars);
        }

        throw new \RuntimeException("Invalid route action for {$request->uri()}");
    }

    /**
     * Register a GET route
     */
    public function get(string $uri, string|array $action, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    /**
     * Register a POST route
     */
    public function post(string $uri, string|array $action, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    /**
     * Register any route type
     */
    protected function addRoute(string $method, string $uri, string|array $action, array $middleware = []): void
    {
        $this->routes[$method][$uri] = [
            'action' => $action,
            'middleware' => $middleware,
        ];
    }

    /**
     * Dispatch request to controller or callable
     */
    public function dispatch(Request $request, Response $response): void
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routes as $method => $routes) {
                foreach ($routes as $uri => $data) {
                    $r->addRoute($method, $uri, $data);
                }
            }
        });

        $routeInfo = $dispatcher->dispatch($request->method(), $request->uri());

        try {
            switch ($routeInfo[0]) {
                case Dispatcher::NOT_FOUND:
                    $response->setStatusCode(404)->setBody('404 Not Found')->send();
                    break;

                case Dispatcher::METHOD_NOT_ALLOWED:
                    $response->setStatusCode(405)->setBody('405 Method Not Allowed')->send();
                    break;

                case Dispatcher::FOUND:
                    $this->handleFoundRoute($routeInfo, $request, $response);
                    break;
            }
        } catch (Throwable $e) {
            $response
                ->setStatusCode(500)
                ->setBody('Internal Server Error: ' . $e->getMessage())
                ->send();
        }
    }

    /**
     * Handle a successful route match
     */

    protected function handleFoundRoute(array $routeInfo, Request $request, Response $response): void
    {
        $handlerData = $routeInfo[1];
        $vars = $routeInfo[2];

        $action = $handlerData['action'];
        $middlewares = $handlerData['middleware'] ?? [];

        // Build a middleware pipeline
        $next = function ($req, $res) use ($action, $vars) {
            return $this->executeAction($action, $req, $res, $vars);
        };

        foreach (array_reverse($middlewares) as $mwClass) {
            $middleware = $this->container->get($mwClass);

            $next = function ($req, $res) use ($middleware, $next) {
                if ($middleware instanceof Middleware) {
                    return $middleware->handle($req, $res, $next);
                }

                if ($middleware instanceof MiddlewareInterface) {
                    return $middleware->process($req, $next);
                }

                throw new \RuntimeException('Invalid middleware type: ' . get_class($middleware));
            };
        }

        $result = $next($request, $response);

        if ($result instanceof Response) {
            $result->send();
        } else {
            $response->setBody($result)->send();
        }
    }
}
