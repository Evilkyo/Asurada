<?php

namespace App\Controllers;

use Valitron\Validator;
use Laminas\Diactoros\Response;
use Psr\Http\Message\RequestInterface;
use App\Exceptions\ValidationException;

abstract class Controller
{
    public function validate(RequestInterface $request, array $rules)
    {
        $validator = new Validator($request->getParsedBody());

        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            throw new ValidationException($request, $validator->errors());
        }

        return $request->getParsedBody();
    }
}
