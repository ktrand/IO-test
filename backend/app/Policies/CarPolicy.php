<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\Car;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->role === UserRoles::DRIVER;
    }
    public function update(User $user, Car $car): bool
    {
        return $user->id === $car->driver_id;
    }

    public function delete(User $user, Car $car): bool
    {
        return $user->id === $car->driver_id;
    }
}
