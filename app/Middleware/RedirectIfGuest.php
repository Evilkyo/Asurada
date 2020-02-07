<?php

namespace App\Middleware;

use App\Session\Flash;
use League\Route\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class RedirectIfGuest implements MiddlewareInterface
{
    protected $flash;
    
    protected $route;

    public function __construct(Flash $flash, Router $route)
    {
        $this->flash = $flash;
        $this->route = $route;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $response = $handler->handle($request);

        if (Sentinel::guest()) {
            $this->flash->now('status', 'Por favor faça o login antes de continuar');

            return redirect(
                $this->route->getNamedRoute('auth.login')->getPath() . 
                '?' .
                http_build_query(['redirect' => $request->getUri()->getPath()])
            );
        }

        return $response;
    }
}
