<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Review",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="trip_id", type="integer"),
 *     @OA\Property(property="driver_id", type="integer"),
 *     @OA\Property(property="rating", type="integer"),
 *     @OA\Property(property="comment", type="string"),
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
            'id'         => $this->id,
            'trip'       => $this->trip,
            'driver_id'  => $this->driver_id,
            'rating'     => $this->rating,
            'comment'    => $this->comment,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
