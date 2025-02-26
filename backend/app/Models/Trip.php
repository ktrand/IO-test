<?php

namespace App\Models;

use App\Enums\TripStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @OA\Schema(
 *     schema="Trip",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="driver_id", type="integer"),
 *     @OA\Property(property="passenger_id", type="integer"),
 *     @OA\Property(property="status", type="string"),
 *     @OA\Property(property="date", type="string", format="date"),
 * )
 */
class Trip extends Model
{
    use HasFactory;
    protected $fillable = [
        'passenger_id', 'driver_id', 'pickup_address', 'destination_address', 'preferences', 'status'
    ];

    protected $casts = [
        'status' => TripStatuses::class
    ];

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function reviews(): HasOne
    {
        return $this->hasOne(Review::class);
    }
}
