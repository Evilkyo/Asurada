<?php

namespace App\Controllers\Auth;

use Exception;
use App\Views\View;
use App\Session\Flash;
use League\Route\Router;
use App\Controllers\Controller;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class SigInController extends Controller
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
     * [__construct description]
     * 
     * @param View   $view  [description]
     * @param Router $route [description]
     * @param Flash  $flash [description]
     */
    public function __construct(View $view, Router $route, Flash $flash) 
    {
        $this->view = $view;
        $this->route = $route;
        $this->flash = $flash;
    }

    /**
     * Formulário de login
     * 
     * @param  ServerRequestInterface $request [description]
     * @return [type]                          [description]
     */
    public function index(ServerRequestInterface $request) : ResponseInterface
    {
        // Response
        $response = new Response;
        
        return $this->view->render($response, 'pages/auth/signin.twig', [
            'redirect' => $request->getQueryParams()['redirect'] ?? null
        ]);
    }

    /**
     * Efetua o login do usuário
     * 
     * @param  ServerRequestInterface $request [description]
     * @return [type]                          [description]
     */
    public function action(ServerRequestInterface $request) : ResponseInterface
    {
        // Valida os dados do formulário
        $data = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        try {
            // Efetua o login
            if (!$user = Sentinel::authenticate(
                    array_only($data, ['email', 'password']),
                    isset($data['persist'])
                )
            ) {
                throw new Exception('E-mail ou senha incorretos.');
            }
        } catch (Exception $e) {
            $this->flash->now('status', $e->getMessage());

            return redirect($this->route->getNamedRoute('auth.signin')->getPath());
        }
        
        return redirect(
            $data['redirect'] ? $data['redirect'] : $this->route->getNamedRoute('home')->getPath()
        );
    }
}
