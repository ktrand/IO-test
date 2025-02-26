<?php

namespace App\Http\Requests\Trip;

use App\Enums\TripStatuses;
use App\Enums\UserRoles;
use App\Http\Requests\AbstractRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class GetRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(
            $this->paginateRules(),
            [
                'status'        => 'nullable|in:' . implode(',', array_column(TripStatuses::cases(), 'value')),
                'date' => 'nullable|date_format:Y-m-d',
                'passenger_id'  => [
                    'nullable',
                    'integer',
                    Rule::exists('users', 'id')->where('role', UserRoles::PASSENGER->value)
                ],
                'driver_id'     => [
                    'nullable',
                    'integer',
                    Rule::exists('users', 'id')->where('role', UserRoles::DRIVER->value)
                ],
            ]
        );
    }

    public function messages(): array
    {
        $paginateMessages = $this->paginateMessages();
        return array_merge(
            $paginateMessages,
            [
                'status.in'            => 'Недопустимый статус. Возможные значения: ' . implode(', ', array_column(TripStatuses::cases(), 'value')),
                'passenger_id.exists'  => 'Выбранный пассажир не существует или не имеет роли PASSENGER.',
                'driver_id.exists'     => 'Выбранный водитель не существует или не имеет роли DRIVER.',
            ]
        );
    }
}
