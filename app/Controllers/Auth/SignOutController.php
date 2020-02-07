<?php

namespace App\Controllers\Auth;

use League\Route\Router;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class SignOutController
{
    /**
     * [$route description]
     * 
     * @var [type]
     */
    protected $route;

    /**
     * [__construct description]
     * 
     * @param Router $route [description]
     */
    public function __construct(Router $route)
    {
        $this->route = $route;
    }

    /**
     * Faz o logout do usuário
     * 
     * @param  ServerRequestInterface $request [description]
     * @return [type]                          [description]
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        Sentinel::logout();

        return redirect($this->route->getNamedRoute('home')->getPath());
    }
}
