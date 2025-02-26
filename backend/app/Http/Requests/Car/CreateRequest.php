<?php

namespace App\Http\Requests\Car;

use App\Http\Requests\AbstractRequest;

/**
 * @OA\Schema(
 *     schema="CreateCarRequest",
 *     type="object",
 *     required={"model", "brand", "license_plate"},
 *     @OA\Property(
 *         property="model",
 *         description="Модель автомобиля",
 *         type="string",
 *         maxLength=100
 *     ),
 *     @OA\Property(
 *         property="brand",
 *         description="Бренд автомобиля",
 *         type="string",
 *         maxLength=100
 *     ),
 *     @OA\Property(
 *         property="license_plate",
 *         description="Номерной знак автомобиля",
 *         type="string",
 *         maxLength=20
 *     )
 * )
 */
class CreateRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'model'         => 'required|string|max:100',
            'brand'         => 'required|string|max:100',
            'license_plate' => 'required|string|max:20|unique:cars,license_plate'
        ];
    }

    public function messages(): array
    {
        $messages['model.required'] = self::REQUIRED;
        $messages['model.string'] = self::STRING;
        $messages['model.max'] = self::getMaxMessage('model', 100);

        $messages['brand.required'] = self::REQUIRED;
        $messages['brand.string'] = self::STRING;
        $messages['brand.max'] = self::getMaxMessage('brand', 100);

        $messages['license_plate.required'] = self::REQUIRED;
        $messages['license_plate.string'] = self::STRING;
        $messages['license_plate.max'] = self::getMaxMessage('license_plate', 20);
        $messages['license_plate.unique'] = 'Номер машины доолжен быть уникальным';

        return $messages;
    }

}
