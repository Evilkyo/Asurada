<?php

namespace App\Exceptions;

use Exception;
use Psr\Http\Message\RequestInterface;

class ValidationException extends Exception
{
    /**
     * [$request description]
     * 
     * @var [type]
     */
    protected $request;

    /**
     * [$errors description]
     * 
     * @var array
     */
    protected $errors = [];
    
    /**
     * [__construct description]
     * 
     * @param RequestInterface $request [description]
     * @param array            $errors  [description]
     */
    public function __construct(RequestInterface $request, array $errors)
    {
        $this->request = $request;
        $this->errors = $errors;
    }

    /**
     * Retorna o caminho atual
     * 
     * @return [type] [description]
     */
    public function getPath()
    {
        return $this->request->getServerParams()['HTTP_REFERER'];
    }

    /**
     * Retorna os dados antigos
     * 
     * @return [type] [description]
     */
    public function getOldInput()
    {
        return $this->request->getParsedBody();
    }

    /**
     * Retorna os erros
     * 
     * @return [type] [description]
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
