<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use App\Models\Trip;
use Illuminate\Auth\Access\HandlesAuthorization;

class TripPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->role === UserRoles::PASSENGER;
    }
    public function update(User $user, Trip $trip): bool
    {
        if ($user->id === $trip->passenger_id || $user->id === $trip->driver_id) {
            return true;
        }

        if (is_null($trip->driver_id) || $user->role === UserRoles::DRIVER) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Trip $trip): bool
    {
        return $user->id === $trip->passenger_id;
    }
}
