<?php

namespace Src\Core;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response\TextResponse;
use Throwable;

/**
 * Router class for handling HTTP routes with PSR-15 middleware support.
 * Uses FastRoute for routing and PSR-7 for request/response.
 */
class Router
{
    /** @var array<string, array> Registered routes */
    protected array $routes = [];

    /** @var Container Dependency injection container */
    protected Container $container;

    /**
     * Constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Register a GET route.
     *
     * @param string $uri
     * @param string|callable $action
     * @param array $middleware
     */
    public function get(string $uri, string|callable $action, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    /**
     * Register a POST route.
     *
     * @param string $uri
     * @param string|callable $action
     * @param array $middleware
     */
    public function post(string $uri, string|callable $action, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    /**
     * Add a route for any HTTP method.
     *
     * @param string $method
     * @param string $uri
     * @param string|callable $action
     * @param array $middleware
     */
    protected function addRoute(string $method, string $uri, string|callable $action, array $middleware = []): void
    {
        $this->routes[$method][$uri] = [
            'action' => $action,
            'middleware' => $middleware,
        ];
    }

    /**
     * Dispatch the incoming request.
     *
     * @param Request $request
     * @param Response $response
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
            match ($routeInfo[0]) {
                Dispatcher::NOT_FOUND => $this->sendNotFound($response),
                Dispatcher::METHOD_NOT_ALLOWED => $this->sendMethodNotAllowed($response),
                Dispatcher::FOUND => $this->handleFoundRoute($routeInfo, $request, $response),
            };
        } catch (Throwable $e) {
            $this->sendError($response, $e);
        }
    }

    /**
     * Handle a matched route.
     *
     * @param array $routeInfo
     * @param Request $request
     * @param Response $response
     */
    protected function handleFoundRoute(array $routeInfo, Request $request, Response $response): void
    {
        [$handlerData, $vars] = [$routeInfo[1], $routeInfo[2]];
        $action = $handlerData['action'];
        $middlewares = $handlerData['middleware'] ?? [];

        $psrRequest = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

        $finalHandler = function (ServerRequestInterface $req) use ($action, $request, $response, $vars): ResponseInterface {
            $result = $this->executeAction($action, $request, $response, $vars);

            // ✅ Laravel-style auto-render logic
            if ($result instanceof Response) {
                return new TextResponse($result->getBody(), $result->getStatusCode());
            }

            if (is_array($result)) {
                // Auto JSON
                return new TextResponse(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 200, [
                    'Content-Type' => 'application/json; charset=utf-8',
                ]);
            }

            if (is_string($result)) {
                // Auto HTML render if view file exists
                $trimmed = trim($result);
                if (str_starts_with($trimmed, 'view(') || file_exists(dirname(__DIR__, 2) . '/app/Views/' . $trimmed . '.php')) {
                    $html = View::render($trimmed);
                    return new TextResponse($html, 200, [
                        'Content-Type' => 'text/html; charset=utf-8',
                    ]);
                }

                return new TextResponse($result, 200, [
                    'Content-Type' => 'text/html; charset=utf-8',
                ]);
            }

            // Fallback
            return new TextResponse(var_export($result, true), 200, [
                'Content-Type' => 'text/plain; charset=utf-8',
            ]);
        };

        // ✅ middleware pipeline execution
        $pipeline = array_reduce(
            array_reverse($middlewares),
            function ($next, $middlewareClass) {
                $middleware = $this->container->get($middlewareClass);
                if (!$middleware instanceof MiddlewareInterface) {
                    throw new \RuntimeException('Middleware must implement PSR-15 MiddlewareInterface: ' . get_class($middleware));
                }
                return function (ServerRequestInterface $req) use ($middleware, $next) {
                    return $middleware->process($req, $next);
                };
            },
            $finalHandler,
        );

        $psrResponse = $pipeline($psrRequest);
        $this->emitPsrResponse($psrResponse);
    }

    /**
     * Execute controller action or closure.
     *
     * @param string|callable $action
     * @param Request $request
     * @param Response $response
     * @param array $vars
     * @return Response|string
     */
    protected function executeAction(string|callable $action, Request $request, Response $response, array $vars): Response|string
    {
        // Handle "Controller@method" format
        if (is_string($action) && str_contains($action, '@')) {
            [$controller, $method] = explode('@', $action);
            $controllerClass = "App\\Controllers\\$controller";

            // Ensure controller exists
            if (!class_exists($controllerClass)) {
                throw new \RuntimeException("Controller not found: $controllerClass");
            }

            // Resolve from container
            $instance = $this->container->get($controllerClass);

            // Ensure method exists
            if (!method_exists($instance, $method)) {
                throw new \RuntimeException("Method $method not found in $controllerClass");
            }

            // Execute the controller action
            $result = $instance->$method($request, $response, $vars);

            // If the result is a View instance, render it
            if ($result instanceof \Src\Core\View) {
                return (string) $result;
            }

            return $result;
        }

        // Handle closure-based routes
        if (is_callable($action)) {
            $result = $action($request, $response, $vars);

            // Support returning a View directly
            if ($result instanceof \Src\Core\View) {
                return (string) $result;
            }

            return $result;
        }

        throw new \RuntimeException('Invalid route action');
    }

    /**
     * Send 404 response.
     */
    protected function sendNotFound(Response $response): void
    {
        $response->status(404)->send('404 Not Found');
    }

    /**
     * Send 405 response.
     */
    protected function sendMethodNotAllowed(Response $response): void
    {
        $response->status(405)->send('405 Method Not Allowed');
    }

    /**
     * Send error response.
     */
    protected function sendError(Response $response, Throwable $e): void
    {
        $response->status(500)->send('Internal Server Error: ' . $e->getMessage());
    }

    /**
     * Emit PSR-7 response to client.
     */
    protected function emitPsrResponse(ResponseInterface $response): void
    {
        if (!headers_sent()) {
            http_response_code($response->getStatusCode());
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $i => $value) {
                    header("$name: $value", $i === 0);
                }
            }
        }
        echo $response->getBody();
    }
}
