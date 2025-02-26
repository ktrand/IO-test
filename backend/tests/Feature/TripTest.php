<?php

namespace Tests\Feature;

use App\Enums\TripStatuses;
use App\Models\Trip;
use App\Models\User;
use App\Enums\UserRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_trip()
    {
        $passenger = User::factory()->create(['role' => UserRoles::PASSENGER->value]);

        $this->actingAs($passenger);

        $response = $this->postJson('/api/trips', [
            'pickup_address' => 'Address 1',
            'destination_address' => 'Address 2',
            'preferences' => 'AC, Music',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('trips', [
            'passenger_id' => $passenger->id,
            'pickup_address' => 'Address 1',
            'destination_address' => 'Address 2',
            'status' => TripStatuses::PENDING
        ]);
    }

    public function test_user_can_view_trip_history()
    {
        $driver = User::factory()->create(['role' => UserRoles::DRIVER->value]);
        $passenger = User::factory()->create(['role' => UserRoles::PASSENGER->value]);

        $this->actingAs($passenger);

        $trip = Trip::factory()->create([
            'passenger_id' => $passenger->id,
            'driver_id' => $driver->id,
        ]);

        $response = $this->getJson('/api/trips');

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $trip->id]);
    }
}
