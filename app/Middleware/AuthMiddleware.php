<?php
namespace App\Middleware;

use Src\Core\Middleware;
use Src\Core\Request;
use Src\Core\Response;

class AuthMiddleware extends Middleware
{
    public function handle(Request $request, Response $response, callable $next): Response
    {
        if (!$request->isAuthenticated()) {
            return $response->setStatusCode(401)->setBody("Unauthorized - Please log in");
        }

        return $next($request, $response);
    }
}
