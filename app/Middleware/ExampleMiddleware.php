<?php
namespace App\Middleware;

use Src\Core\Middleware;
use Src\Core\Request;

class ExampleMiddleware extends Middleware {
    public function handle(Request $request, callable $next) {
        // simple example: add header (can't set headers here without response object)
        // continue
        return $next($request);
    }
}
