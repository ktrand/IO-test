<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Car;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class CarRepository
{
    public function create(array $data): Car
    {
        return Car::create($data);
    }

    public function find(int $id): Car
    {
        return Car::findOrFail($id);
    }

    public function delete(Car $car): bool
    {
        return $car->delete();
    }

    public function getByUser(
        User $user,
        $perPage = 10,
    ): LengthAwarePaginator
    {
        return Car::where('driver_id', $user->id)
            ->paginate($perPage);
    }

    public function update(Car $car, array $data): Car
    {
        $car->update($data);
        return $car;
    }
}
