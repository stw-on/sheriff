<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->can_manage_locations;
    }

    public function delete(User $user, Location $location)
    {
        return $user->can_manage_locations;
    }
}
