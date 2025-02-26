<?php
namespace Database\Factories;

use App\Models\Trip;
use App\Models\User;
use App\Enums\UserRoles;
use App\Enums\TripStatuses;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        return [
            'passenger_id' => User::where('role', UserRoles::PASSENGER->value)->inRandomOrder()->first()->id,  // Случайный пассажир
            'driver_id' => User::where('role', UserRoles::DRIVER->value)->inRandomOrder()->first()->id,     // Случайный водитель
            'pickup_address' => $this->faker->address,             // Случайный адрес
            'destination_address' => $this->faker->address,        // Случайный адрес назначения
            'preferences' => $this->faker->text(100),              // Случайные предпочтения
            'status' => $this->faker->randomElement(TripStatuses::cases())->value,  // Случайный статус из TripStatuses
        ];
    }
}
