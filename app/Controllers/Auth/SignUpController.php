<?php

namespace App\Controllers\Auth;

use Exception;
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

class SignUpController extends Controller
{
    /**
     * [$view description]
     * 
     * @var [type]
     */
    protected $view;

    /**
     * [$route description]
     * 
     * @var [type]
     */
    protected $route;

    /**
     * [$flash description]
     * 
     * @var [type]
     */
    protected $flash;

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
     * Formulário de cadastro
     * 
     * @param  ServerRequestInterface $request [description]
     * @return [type]                          [description]
     */
    public function index(ServerRequestInterface $request) : ResponseInterface
    {
        // Response
        $response = new Response;

        return $this->view->render($response, 'pages/auth/signup.twig');
    }
    
    /**
     * Efetua o registro do usuário
     * 
     * @param  ServerRequestInterface $request [description]
     * @return [type]                          [description]
     */
    public function action(ServerRequestInterface $request) : ResponseInterface
    {
        // Valida os dados do formulário
        $data = $this->validate($request, [
            'email' => ['required', 'email', 'emailIsUnique'],
            'first_name' => ['required'],
            'last_name' => ['required'],
            'password' => ['required', ['lengthMin', 6]],
            'password_confirmation' => ['required', ['equals', 'password']],
        ]);

        try {
            // Efetua o registro
            $user = Sentinel::register(
                array_only($data, ['email', 'first_name', 'last_name', 'password', 'password_confirmation'])
            );

            // Retorna o usuário recém cadastrado
            $credentials = Sentinel::findById($user->id);

            // Gera um código de ativação
            $activation = Sentinel::getActivationRepository()->create($user);

            // Remove todos os códigos de ativação expirados
            $expired = Sentinel::getActivationRepository()->removeExpired();

            // Envia um e-mail com o código/link de ativação
            $this->mail->to($credentials->email)->send(new ActivationEmail($credentials, $activation));

        } catch (Exception $e) {
            $this->flash->now('status', 'Algo deu errado.');

            return redirect($this->route->getNamedRoute('auth.signup')->getPath());
        }

        $this->flash->now('status', 'Por favor, verifique seu e-mail para ativar sua conta.');

        return redirect($this->route->getNamedRoute('home')->getPath());
    }
}
