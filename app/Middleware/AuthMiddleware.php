<?php

namespace App\Middleware;

use Src\Core\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\RedirectResponse;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        if (empty($_SESSION['user'])) {
            return new RedirectResponse('/login');
        }

        return $next($request);
    }
}