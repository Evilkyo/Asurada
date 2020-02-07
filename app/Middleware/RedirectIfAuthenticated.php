<?php

namespace App\Middleware;

use Zend\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class RedirectIfAuthenticated implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        if (!Sentinel::guest()) {
            $response = new Response();
            return redirect('/');
        }

        return $handler->handle($request);
    }
}
