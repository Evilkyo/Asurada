<?php

namespace App\Controllers;

use App\Views\View;
use App\Image\Upload;
use App\Session\Flash;
use League\Route\Router;
use Laminas\Diactoros\Response;
use Intervention\Image\ImageManager;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UploadedFileInterface;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class HomeController extends Controller
{
    protected $view;

    protected $image;

    protected $flash;
    
    protected $route;

    public function __construct(View $view, ImageManager $image, Flash $flash, Router $route)
    {
        $this->view = $view;
        $this->image = $image;
        $this->flash = $flash;
        $this->route = $route;
    }
    
    public function index(RequestInterface $request) : ResponseInterface{
        $response = new Response;

        return $this->view->render($response, 'pages/home/index.twig');
    }

    public function upload(RequestInterface $request) : ResponseInterface
    {
        $response = new Response;

        return $this->view->render($response, 'form.twig');
    }

    public function action(RequestInterface $request) : ResponseInterface
    {
        $response = new Response;

        $data = $this->validate($request, [
            // 'name' => ['required'],
            // 'file' => ['image']
        ]);


        $file = $request->getUploadedFiles()['file'];

        // get the image extension
        $extension = '.'.get_file_extension($file->getClientFilename());

        // allowed extensions
        $allowed_extensions = array(".jpg","jpeg",".png",".gif");

        // Validation for allowed extensions .in_array() function searches an array for a specific value.
        if (!in_array($extension,$allowed_extensions)) {
            $this->flash->now('status', 'Tipo de arquivo não suportado');

            return redirect($this->route->getNamedRoute('upload')->getPath());
        } else {
            $filename = str_slug($data['name']) . '-' . time() . '.' .get_file_extension($file->getClientFilename());
            $location = uploads_path(). $filename;
            $file->moveTo(uploads_path($filename));

            try {
                $this->image->make($location)->resize(null, 450, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($location);
            } catch (\Exception $e) {
                return $response->withStatus(422);
            }

            return redirect($this->route->getNamedRoute('home')->getPath());
        }
    }
}
