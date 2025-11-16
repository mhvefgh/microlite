<?php
class MiddlewareDispatcher
{
    private array $middlewares = [];

    public function add($middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function dispatch($request, $finalHandler)
    {
        $middlewareList = $this->middlewares;
        $next = $finalHandler;
        while ($mw = array_pop($middlewareList)) {
            $next = function ($req) use ($mw, $next) {
                if (is_callable($mw)) {
                    return $mw($req, $next);
                }
                if (is_object($mw) && method_exists($mw, 'handle')) {
                    return $mw->handle($req, $next);
                }
                throw new RuntimeException('Invalid middleware');
            };
        }
        return $next($request);
    }
}
