<?php

namespace App\Views\Extensions;

use Twig_Extension;
use League\Route\Router;
use Twig_SimpleFunction;
use Psr\Http\Message\RequestInterface;

class PathExtension extends Twig_Extension
{
    protected $route;

    protected $request;

    public function __construct(Router $route, RequestInterface $request)
    {
        $this->route = $route;
        $this->request = $request;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('route', [$this, 'route']),
            new Twig_SimpleFunction('is_current_url', [$this, 'isCurrentUrl']),
        ];
    }

    public function route(string $string, array $arguments = null)
    {
        return $this->getUriFromName($string, $arguments);
    }

    public function getUriFromName(string $name, array $arguments = null)
    {
        $path = $this->route->getNamedRoute($name)->getPath();

        return preg_replace_callback("/\{([A-Za-z0-9]+)\:([A-Za-z0-9]+)\}/", function($matches) use($arguments) {
            return $arguments[$matches[1]];
        }, $path);
    }

    public function isCurrentUrl(string $routeName): bool
    {
        $currentUrl = $this->request->getUri()->getPath();
        
        $result = $this->route($routeName);

        return $result === $currentUrl;
    }
}
