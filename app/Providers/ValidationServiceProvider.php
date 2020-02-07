<?php

namespace App\Providers;

use App\Models\User;
use Valitron\Validator;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

class ValidationServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // E-mail is unique
        Validator::addRule('emailIsUnique', function ($field, $value, $params, $fields) {
            $user = User::where('email', $value)
                ->where('email', '!=', $params[0] ?? null)
                ->first();

            if ($user) {
                return false;
            }

            return true;
        }, 'Esse {field} já está em uso');

        // Current password
        Validator::addRule('currentPassword', function ($field, $value, $params, $fields) {
            return Sentinel::getUserRepository()->validateCredentials(
                Sentinel::check(),
                ['password' => $value]
            );
        }, 'está incorreta');

        // Exists
        Validator::addRule('exists', function ($field, $value, $params, $fields) {
            $result = User::where($field, $value)->first();

            return $result !== null;
        }, 'Não há nenhuma conta registrada com este {field}');
    }
}
