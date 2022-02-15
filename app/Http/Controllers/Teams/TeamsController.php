<?php

namespace App\Http\Controllers\Teams;

use App\Http\Resources\TeamResource;
use App\Repositories\Contracts\{IInvitation, IUser, ITeam};
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamsController extends Controller
{

    protected $teams;
    protected $users;
    protected $invitations;

    public function __construct(ITeam $teams, IUser $users, IInvitation $invitations)
    {
        $this->teams = $teams;
        $this->users = $users;
        $this->invitations = $invitations;
    }

    public function index(Request $request)
    {
    }

    public function store(Request $request): TeamResource
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:80', 'unique:teams,name']
        ]);

        $team = $this->teams->create([
            'owner_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    public function update(Request $request, $id): TeamResource
    {
        $team = $this->teams->find($id);

        $this->authorize('update', $team);

        $this->validate($request, [
            'name' => ['required', 'string', 'max:80', 'unique:teams,name,' . $id]
        ]);

        $team = $this->teams->update($id, [
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    public function findById($id): TeamResource
    {
        $team = $this->teams->find($id);
        return new TeamResource($team);
    }

    public function fetchUserTeams(): AnonymousResourceCollection
    {
        $team = $this->teams->fetchUserTeams();
        return TeamResource::collection($team);
    }

    public function findBySlug($slug)
    {
        $team = $this->teams->findWhereFirst('slug', $slug);
        return new TeamResource($team);
    }

    public function destroy($id)
    {
        $team = $this->teams->find($id);
        $this->authorize('delete', $team);

        $team->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }

    public function removeFromTeam($teamId, $userId)
    {
        // get the team
        $team = $this->teams->find($teamId);
        $user = $this->users->find($userId);

        // check that the user is not the owner
        if ($user->isOwnerOfTeam($team)) {
            return response()->json([
                'message' => 'You are the team owner'
            ], 401);
        }

        // check that the person sending the request
        // is either the owner of the team or the person
        // who wants to leave the team
        if (!auth()->user()->isOwnerOfTeam($team) && auth()->id() !== $user->id) {
            return response()->json([
                'message' => 'You cannot do this'
            ], 401);
        }

        $this->invitations->removeUserFromTeam($team, $userId);

        return response()->json(['message' => 'Success'], 200);
    }
}
