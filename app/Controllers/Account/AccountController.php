<?php

namespace App\Controllers\Account;

use App\Views\View;
use App\Session\Flash;
use League\Route\Router;
use App\Mail\Mailer\Mailer;
use Zend\Diactoros\Response;
use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class AccountController extends Controller
{
    protected $view;

    protected $route;

    protected $mail;
    
    protected $flash;

    public function __construct(View $view, Router $route, Mailer $mail, Flash $flash)
    {
        $this->view = $view;
        $this->route = $route;
        $this->mail = $mail;
        $this->flash = $flash;
    }

    public function index() : ResponseInterface
    {
        $response = new Response;
        
        return $this->view->render($response, 'pages/account/index.twig');
    }

    public function action(RequestInterface $request)
    {
        $data = $this->validate($request, [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email', ['emailIsUnique', Sentinel::check()->email]]
        ]);

        Sentinel::check()->update(
            array_only($data, [
                'first_name', 'last_name', 'email'
            ])
        );

        $this->flash->now('status', 'Perfil atualizado.');

        return redirect($this->route->getNamedRoute('account')->getPath());
    }
}
