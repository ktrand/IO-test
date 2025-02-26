<?php

namespace App\Http\Requests\Trip;

use App\Enums\TripStatuses;

/**
 * @OA\Schema(
 *     schema="UpdateTripRequest",
 *     type="object",
 *     required={"status"},
 *     @OA\Property(
 *         property="status",
 *         description="Статус поездки",
 *         type="string",
 *         enum={"pending", "accepted", "in_progress", "completed", "cancelled"},
 *     ),
 * )
 */
class UpdateRequest extends CreateRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['status'] = 'in:' . implode(',', array_column(TripStatuses::cases(), 'value'));

        return $rules;
    }
}
