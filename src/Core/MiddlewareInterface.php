<?php
namespace Src\Core;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

interface MiddlewareInterface
{
    /**
     * PSR-15 compatible middleware.
     */
    public function process(ServerRequestInterface $request, callable $next): ResponseInterface;
}
