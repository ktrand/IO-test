<?php

namespace App\Http\Resources\Car;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Car",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="driver_id", type="integer"),
 *     @OA\Property(property="model", type="string"),
 *     @OA\Property(property="brand", type="string"),
 *     @OA\Property(property="license_plate", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Resource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'driver_id'     => $this->driver_id,
            'model'         => $this->model,
            'brand'         => $this->brand,
            'license_plate' => $this->license_plate,
            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
