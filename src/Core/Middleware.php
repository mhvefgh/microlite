<?php
namespace Src\Core;

abstract class Middleware
{
    /**
     * Handle an incoming request.
     * Must call $next($request, $response) to continue the chain.
     */
    abstract public function handle(Request $request, Response $response, callable $next): Response;
}
