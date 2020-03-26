<?php

namespace App\Http\Controllers\User;

use App\Repositories\Contracts\IUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * @var IUser
     */
    protected $users;

    public function __construct(IUser $users)
    {
        $this->users = $users;
    }

    public function index(): AnonymousResourceCollection
    {
        $users = $this->users->all();
        return UserResource::collection($users);
    }
}
