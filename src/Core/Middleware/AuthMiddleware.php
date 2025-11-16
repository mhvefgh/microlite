<?php

use Src\Core\Response;

class AuthMiddleware
{
    private $authChecker;
    private $loginRoute;

    public function __construct(callable $authChecker, string $loginRoute = '/login')
    {
        $this->authChecker = $authChecker;
        $this->loginRoute = $loginRoute;
    }

    public function handle($request, $next)
    {
        $checker = $this->authChecker;
        if (!$checker()) {
            // Not authenticated - redirect to login
            $resp = new Response();
            $resp->redirect($this->loginRoute, 302);
        }
        return $next($request);
    }
    
}
