<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\IUser;
use App\Models\User;

class UserRepository extends BaseRepository implements IUser
{
    public function model()
    {
        return User::class;
    }
}
