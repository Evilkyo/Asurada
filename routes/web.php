<?php

use Laminas\Diactoros\Response;
use App\Middleware\Authenticated;
use App\Middleware\RedirectIfGuest;
use Psr\Http\Message\ResponseInterface;
use App\Middleware\RedirectIfAuthenticated;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

$route->get('/', 'App\Controllers\HomeController')->setName('home');

$route->get('/dashboard', 'App\Controllers\DashboardController::index');

// $route->get('/teste/{email:word}', function (ServerRequestInterface $request, array $args) : ResponseInterface {
//     $response = new Response;

//     $users = Sentinel::getUserRepository()->findByCredentials($args['email']);

//     dump($users);
//     die();
    
//     return $response;
// })->setName('teste');

$route->group('', function ($route) {
    $route->get('/upload', 'App\Controllers\HomeController::upload')->setName('upload');
    $route->post('/upload', 'App\Controllers\HomeController::action');
});

// Conta
$route->group('/conta', function ($route) {
    // Alterar perfil
    $route->get('/', 'App\Controllers\Account\AccountController::index')->setName('account');
    $route->post('/', 'App\Controllers\Account\AccountController::action');

    // Alterar senha
    $route->get('/senha', 'App\Controllers\Account\AccountPasswordController::index')->setName('account.password');
    $route->post('/senha', 'App\Controllers\Account\AccountPasswordController::action');

})->middleware($container->get(RedirectIfGuest::class));

// Autenticação
$route->group('/auth', function ($route) use ($container) {
    // Login
    $route->get('/entrar', 'App\Controllers\Auth\SigInController::index')->setName('auth.signin');
    $route->post('/entrar', 'App\Controllers\Auth\SigInController::action');

    // Cadastro
    $route->get('/criar-conta', 'App\Controllers\Auth\SignUpController::index')->setName('auth.signup');
    $route->post('/criar-conta', 'App\Controllers\Auth\SignUpController::action');

    // Redefinir senha
    $route->get('/senha/recuperar', 'App\Controllers\Auth\Password\PasswordRecoverController::index')->setName('auth.password.recover');
    $route->post('/senha/recuperar', 'App\Controllers\Auth\Password\PasswordRecoverController::action');

    // Nova senha
    $route->get('/senha/redefinir', 'App\Controllers\Auth\Password\PasswordResetController::index')->setName('auth.password.reset');
    $route->post('/senha/redefinir', 'App\Controllers\Auth\Password\PasswordResetController::action'); 

    // Reenviar ativação
    $route->get('/reenviar-email-de-ativacao', 'App\Controllers\Auth\Activation\ActivationRecoverController::index')->setName('auth.activation.recover');
    $route->post('/reenviar-email-de-ativacao', 'App\Controllers\Auth\Activation\ActivationRecoverController::action');

    // Ativação de conta
    $route->get('/ativar/{id:number}/{code:alphanum_dash}', 'App\Controllers\Auth\Activation\ActivationController');
        
})->middleware($container->get(RedirectIfAuthenticated::class));
    
// Logout
$route->post('logout', 'App\Controllers\Auth\SignOutController')->setName('auth.signout');
