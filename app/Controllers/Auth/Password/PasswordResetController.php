<?php

namespace App\Controllers\Auth\Password;

use Exception;
use App\Views\View;
use App\Models\User;
use App\Session\Flash;
use League\Route\Router;
use App\Mail\Mailer\Mailer;
use App\Controllers\Controller;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Database\Capsule\Manager;
use Psr\Http\Message\ServerRequestInterface;
use App\Mail\Account\UserPasswordResendEmail;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class PasswordResetController extends Controller
{
    /**
     * @var [type]
     */
    protected $view;

    /**
     * @var [type]
     */
    protected $route;

    /**
     * @var [type]
     */
    protected $flash;
    
    /**
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

        // Parâmetros necessários
        $params = array_only($request->getQueryParams(), ['email', 'code']);

        if (
            // Verifica se existe um usuário com o e-mail informado
            !$this->activationCodeExists(
                User::whereEmail($email = $params['email'] ?? null)->first(),
                $code = $params['code'] ?? null
            )
        ) {
            $this->flash->now('status', 'Código de redefinição de senha inválido ou expirado; por favor peça por um novo link');

            return redirect($this->route->getNamedRoute('home')->getPath());
        }

        return $this->view->render($response, 'pages/auth/password/reset.twig', compact('email', 'code'));
    }

    /**
     * Gera um novo código/link de redefinição de senha
     * 
     * @param  ServerRequestInterface $request [description]
     * @param  array                  $args    [description]
     * @return [type]                          [description]
     */
    public function action(ServerRequestInterface $request) : ResponseInterface
    {
        // Valida os dados do formulário
        $data = $this->validate($request, [
            'password' => ['required', ['lengthMin', 6]],
            'password_confirmation' => ['required', ['equals', 'password']],
        ]);

        // Parâmetros
        $params = array_only($data, ['email', 'code', 'password']);

        if (
            // Verifica se existe um usuário com o e-mail informado
            !$this->activationCodeExists(
                $user = User::whereEmail($params['email'] ?? null)->first(),
                $code = $params['code'] ?? null
            )
        ) {
            $this->flash->now('status', 'Código de redefinição de senha inválido ou expirado; por favor peça por um novo link');

            return redirect($this->route->getNamedRoute('home')->getPath());
        }

        // Finaliza a redefinição de senha
        Sentinel::getReminderRepository()->complete($user, $code, $params['password']);

        $this->flash->now('status', 'Sua senha foi redefinida e você já pode efetuar seu login.');

        return redirect($this->route->getNamedRoute('auth.signin')->getPath());
    }

    /**
     * Verifica se existe um código de redefinição de senha
     * 
     * @param  User   $user [description]
     * @param  [type] $code [description]
     * @return [type]       [description]
     */
    protected function activationCodeExists(?User $user, $code)
    {
        // Se não existir o usuário retorna falso
        if (!$user) {
            return false;
        }

        // Se não existir o código retorna falso
        if (!Sentinel::getReminderRepository()->exists($user, $code)) {
            return false;
        }

        return true;
    }
}
