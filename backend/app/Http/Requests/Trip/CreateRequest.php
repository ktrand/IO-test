<?php

namespace App\Http\Requests\Trip;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateTripRequest",
 *     type="object",
 *     required={"pickup_address", "destination_address"},
 *     title="Запрос на создание поездки",
 *     description="Данные, необходимые для создания поездки",
 *     @OA\Property(
 *         property="pickup_address",
 *         type="string",
 *         maxLength=255,
 *         example="123 Main St, New York, NY"
 *     ),
 *     @OA\Property(
 *         property="destination_address",
 *         type="string",
 *         maxLength=255,
 *         example="456 Elm St, Los Angeles, CA"
 *     ),
 *     @OA\Property(
 *         property="preferences",
 *         type="string",
 *         nullable=true,
 *         example="No smoking, quiet ride"
 *     )
 * )
 */
class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pickup_address'      => 'required|string|max:255',
            'destination_address' => 'required|string|max:255',
            'preferences'         => 'nullable|string'
        ];
    }
}
