<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function create(User $user, Trip $trip, User $driver): bool
    {
        return $trip->passenger_id === $user->id && $trip->driver_id === $driver->id;
    }
}
