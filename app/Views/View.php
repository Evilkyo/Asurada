<?php

namespace App\Views;

use Psr\Http\Message\ResponseInterface;
use Twig_Environment;

class View
{
    protected $twig; 

    protected $defaultVariables = [];

    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function fetch($template, $data = [])
    {
        $data = array_merge($this->defaultVariables, $data);

        return $this->twig->render($template, $data);
    }

    public function render(ResponseInterface $response, $template, $data = [])
    {
         $response->getBody()->write($this->fetch($template, $data));

         return $response;
    }

    public function share(array $data)
    {
        foreach ($data as $key => $value) {
            $this->twig->addGlobal($key, $value);
        }
    }
}
