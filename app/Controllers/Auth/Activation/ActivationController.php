<?php

namespace App\Controllers\Auth\Activation;

use App\Views\View;
use App\Models\User;
use App\Session\Flash;
use League\Route\Router;
use App\Mail\Mailer\Mailer;
use App\Mail\ActivationEmail;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class ActivationController
{
    protected $view;

    protected $route;

    protected $flash;

    protected $mail;

    public function __construct(View $view, Router $route, Flash $flash, Mailer $mail) 
    {
        $this->view = $view;
        $this->route = $route;
        $this->flash = $flash;
        $this->mail = $mail;
    }

    public function __invoke(ServerRequestInterface $request, array $args) : ResponseInterface
    {
        // Encontra o usuário pelo id
        $user = Sentinel::findById($args['id']);

        // Verifica se a conta não foi ativada previamente
        if (Sentinel::getActivationRepository()->completed($user)) {
            $this->flash->now('status', 'Sua conta já está ativada.');

            return redirect($this->route->getNamedRoute('home')->getPath());

        // Verifica se o código de ativação é inválido
        } elseif (!Sentinel::getActivationRepository()->get($user, $args['code'])) {
            $this->flash->now('error', 'Código de ativação inválido ou expirado.');

            return redirect($this->route->getNamedRoute('home')->getPath());
        }

        // Finaliza a ativação do usuário
        Sentinel::getActivationRepository()->complete($user, $args['code']);
        
        // Deleta os códigos de ativação expirados
        Sentinel::getActivationRepository()->removeExpired();

        $this->flash->now('status', 'Sua conta foi ativada com sucesso e você já pode efetuar o login.');

        return redirect($this->route->getNamedRoute('auth.signin')->getPath());
    }
}
