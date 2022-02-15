<?php

namespace App\Http\Controllers\User;

use App\Repositories\Contracts\IUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use Illuminate\Http\Request;
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
        $users = $this->users->withCriteria([
            new EagerLoad(['designs'])
        ])->all();
        return UserResource::collection($users);
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        $designers= $this->users->search($request);
        return UserResource::collection($designers);
    }

    public function findByUsername($username)
    {
        $user = $this->users->findWhereFirst('username', $username);
        return new UserResource($user);
    }
}
