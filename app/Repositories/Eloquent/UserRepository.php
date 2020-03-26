<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\IUser;
use App\Models\User;

class UserRepository implements IUser 
{
    public function all() {
        return User::all();
    }
}
