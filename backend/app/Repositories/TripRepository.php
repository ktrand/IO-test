<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\UserRoles;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TripRepository
{
    public function create(array $data): Trip
    {
        return Trip::create($data);
    }

    public function find(int $id): Trip
    {
        return Trip::findOrFail($id);
    }

    public function update(Trip $trip, array $data): Trip
    {
        $trip->update($data);
        return $trip;
    }

    public function delete(Trip $trip): bool
    {
        return $trip->delete();
    }

    public function getByUser(User $user, int $perPage, array $filters = []): LengthAwarePaginator
    {
        $query = Trip::query();

        if ($user->role === UserRoles::PASSENGER) {
            $query->where('passenger_id', $user->id);
            if (!empty($filters['driver_id'])) {
                $query->where('driver_id', $filters['driver_id']);
            }
        } elseif ($user->role === UserRoles::DRIVER) {
            $query->where('driver_id', $user->id);
            if (!empty($filters['passenger_id'])) {
                $query->where('passenger_id', $filters['passenger_id']);
            }
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }

        return $query->paginate($perPage);
    }
}
