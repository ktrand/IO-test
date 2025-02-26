<?php

namespace App\Http\Requests\Car;

use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="UpdateCarRequest",
 *     type="object",
 *     required={"license_plate"},
 *     @OA\Property(
 *         property="license_plate",
 *         description="Номерной знак автомобиля",
 *         type="string",
 *         maxLength=20
 *     )
 * )
 */
class UpdateRequest extends CreateRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['license_plate'] = [
            'required',
            'string',
            'max:20',
            Rule::unique('cars', 'license_plate')->ignore($this->route('car'))
        ];

        return $rules;
    }
}
