<?php

namespace App\Controllers;

use App\Views\View;
use App\Session\Flash;
use League\Route\Router;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class HomeController extends Controller
{
    /**
     * [$view description]
     * 
     * @var [type]
     */
    protected $view;

    /**
     * [$flash description]
     * 
     * @var [type]
     */
    protected $flash;
    
    /**
     * [$route description]
     * 
     * @var [type]
     */
    protected $route;

    /**
     * [__construct description]
     * 
     * @param View   $view  [description]
     * @param Flash  $flash [description]
     * @param Router $route [description]
     */
    public function __construct(View $view, Flash $flash, Router $route)
    {
        $this->view = $view;
        $this->flash = $flash;
        $this->route = $route;
    }
    
    /**
     * Página principal
     * 
     * @param  ServerRequestInterface $request [description]
     * @return [type]                          [description]
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        // Response
        $response = new Response;

        return $this->view->render($response, 'pages/home/index.twig');
    }
}
