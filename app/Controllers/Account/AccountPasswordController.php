<?php

namespace App\Controllers\Account;

use App\Views\View;
use App\Models\User;
use App\Session\Flash;
use League\Route\Router;
use App\Mail\Mailer\Mailer;
use App\Controllers\Controller;
use Laminas\Diactoros\Response;
use App\Mail\Account\PasswordUpdated;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class AccountPasswordController extends Controller
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

        return $this->view->render($response, 'pages/account/password/index.twig');
    }

    public function action(RequestInterface $request)
    {
        $data = $this->validate($request, [
            'password_current' => ['required', 'currentPassword'],
            'password' => ['required', ['lengthMin', 6]],
            'password_confirmation' => ['required', ['equals', 'password']],
        ]);

        Sentinel::getUserRepository()->update(
            Sentinel::check()->id,
            array_only($data, ['password', 'password_confirmation'])
        );

        $this->flash->now('status', 'Senha atualizada.');

        $this->mail->to(Sentinel::check()->email, Sentinel::check()->name)->send(new PasswordUpdated());

        return redirect($this->route->getNamedRoute('account.password')->getPath());
    }
}
