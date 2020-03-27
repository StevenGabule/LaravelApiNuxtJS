<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Team $team)
    {
        return $user->isOwnerOfTeam($team);
    }

    public function delete(User $user, Team $team)
    {
        return $user->isOwnerOfTeam($team);
    }
}
