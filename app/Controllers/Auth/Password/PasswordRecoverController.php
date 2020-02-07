<?php

namespace App\Controllers\Auth\Password;

use Exception;
use App\Views\View;
use App\Session\Flash;
use League\Route\Router;
use App\Mail\Mailer\Mailer;
use App\Controllers\Controller;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Mail\Auth\Password\PasswordRecoverEmail;

class PasswordRecoverController extends Controller
{
    /**
     * View
     * 
     * @var [type]
     */
    protected $view;

    /**
     * Route
     * 
     * @var [type]
     */
    protected $route;

    /**
     * Flash
     * 
     * @var [type]
     */
    protected $flash;
    
    /**
     * Mail
     * 
     * @var [type]
     */
    protected $mail;

    /**
     * [__construct description]
     * 
     * @param View   $view  [description]
     * @param Router $route [description]
     * @param Flash  $flash [description]
     * @param Mailer $mail  [description]
     */
    public function __construct(View $view, Router $route, Flash $flash, Mailer $mail) 
    {
        $this->view = $view;
        $this->route = $route;
        $this->flash = $flash;
        $this->mail = $mail;
    }

    /**
     * Formulário de requisição de nova senha
     * 
     * @param  ServerRequestInterface $request [description]
     * @return [type]                          [description]
     */
    public function index(ServerRequestInterface $request) : ResponseInterface
    {
        // Response
        $response = new Response;
        
        return $this->view->render($response, 'pages/auth/password/recover.twig');
    }

    /**
     * Gera um link/código para redefinição de senha
     * 
     * @param  ServerRequestInterface $request [description]
     * @return [type]                          [description]
     */
    public function action(ServerRequestInterface $request) : ResponseInterface
    {
        // Valida os dados do formulário
        $data = $this->validate($request, [
            'email' => ['required', 'email']
        ]);

        // Parâmetros necessários
        $params = array_only($data, ['email']);

        // Verifica se existe um usuário com o e-mail informado
        if ($user = User::whereEmail($params['email'])->first()) {
            // Gera um novo código de redefinição de senha
            $reminder = Sentinel::getReminderRepository()->create($user);

            // Remove todos os códigos de redefinição de senha expirados
            $expired = Sentinel::getReminderRepository()->removeExpired();

            // Envia um e-mail com o código/link para redefinição de senha
            $this->mail->to($user->email)->send(new PasswordRecoverEmail($user, $reminder->code));
        }
        
        $this->flash->now('status', 'Foi enviado um e-mail com instruções para redefinir sua senha.');

        return redirect($this->route->getNamedRoute('auth.password.recover')->getPath());
    }
}
