<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser;

class User extends EloquentUser
{
    protected $guarded = [];

    public function fullName()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function path()
    {
        return '/perfil/' . $this->slug;
    }

    public function avatar()
    {
        return 'https://www.gravatar.com/avatar/' . md5($this->email) . '?s=48&d=mm';
    }
}
