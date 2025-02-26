<?php

namespace App\Services;

use App\Models\Car;
use App\Repositories\CarRepository;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class CarService
{
    private CarRepository $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    public function add(array $data, User $user): Car
    {
        $data['driver_id'] = $user->id;

        return $this->carRepository->create($data);
    }

    public function getUserCars(
        User $user,
        $perPage = 10,
    ): LengthAwarePaginator
    {
        return $this->carRepository->getByUser($user, $perPage);
    }

    public function delete(Car $car): void
    {
        $this->carRepository->delete($car);
    }

    public function update(Car $car, array $data): Car
    {
        return $this->carRepository->update($car, $data);
    }
}
