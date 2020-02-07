<?php

namespace App\Controllers\Auth\Activation;

use App\Views\View;
use App\Session\Flash;
use League\Route\Router;
use App\Mail\Mailer\Mailer;
use App\Controllers\Controller;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Mail\Auth\Activation\ActivationEmail;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class ActivationRecoverController extends Controller
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
     * [$mail description]
     * 
     * @var [type]
     */
    protected $mail;

    /**
     * [__construct description]
     * 
     * @param View   $view  [description]
     * @param Flash  $flash [description]
     * @param Router $route [description]
     * @param Mailer $mail  [description]
     */
    public function __construct(View $view, Flash $flash, Router $route, Mailer $mail)
    {
        $this->view = $view;
        $this->flash = $flash;
        $this->route = $route;
        $this->mail = $mail;
    }

    /**
     * Formulário de reenvio de código de validação
     * 
     * @param  ServerRequestInterface $request
     * @return view
     */
    public function index(ServerRequestInterface $request) : ResponseInterface
    {
        // Response
        $response = new Response;

        return $this->view->render($response, 'pages/auth/resend.twig');
    }

    /**
     * Envia um novo código/link de ativação
     * 
     * @param  ServerRequestInterface $request
     * @return [type]
     */
    public function action(ServerRequestInterface $request) : ResponseInterface
    {
        // Valida os dados do formulário
        $data = $this->validate($request, [
            'email' => ['required', 'email']
        ]);

        // Retorna o usuário
        $user = Sentinel::findByCredentials($data);

        // Verifica se existe um usuário com o e-mail informado e/ou se a conta já está ativa
        if ($user === null || Sentinel::getActivationRepository()->completed($user)) {
            $this->flash->now('status', 'Foi enviado um link de ativação para o e-mail indicado.');

            return redirect($this->route->getNamedRoute('auth.activation.recover')->getPath());
        }

        // Gera um novo código de ativação
        $activation = Sentinel::getActivationRepository()->create($user);

        // Envia o e-mail com o link de ativação
        $this->mail->to($user->email)->send(new ActivationEmail($user, $activation));

        return redirect($this->route->getNamedRoute('auth.activation.recover')->getPath());
    }
}
