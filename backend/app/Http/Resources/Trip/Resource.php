<?php

namespace App\Http\Resources\Trip;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'passenger_id'        => $this->passenger_id,
            'driver_id'           => $this->driver_id,
            'pickup_address'      => $this->pickup_address,
            'destination_address' => $this->destination_address,
            'preferences'         => $this->preferences,
            'status'              => $this->status,
            'created_at'          => $this->created_at?->toIso8601String(),
            'updated_at'          => $this->updated_at?->toIso8601String(),
        ];
    }
}
