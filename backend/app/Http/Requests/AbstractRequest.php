<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

abstract class AbstractRequest extends FormRequest
{
    protected const REQUIRED = 'Поле обязательно';
    protected const INTEGER = 'Поле должно быть целочисленного типа';
    protected const ARRAY = 'Поле должно быть массивом';
    protected const BOOLEAN = 'Поле должно быть логического типа';
    protected const DECIMAL = 'Поле должно быть десятичным числом, 2 знака после запятой';
    protected const NUMERIC = 'Поле должно числовым';
    protected const UNSIGNED_MIN = 'Поле должно быть больше 0';
    protected const UNSIGNED_MAX = 'Поле должно быть меньше 4294967295';
    protected const DATE = 'Поле должно быть датой';
    protected const STRING = 'Поле должно быть строкой';
    protected const TIME = 'Поле должно временем H:i';
    protected const CONFIRMED = 'Подтверждение пароля не совпадает';
    protected const PHONE = 'Неверный формат номера телефона.  Пример: +992927654321';

    protected function paginateRules(): array
    {
        return [
            'per_page' => ['integer', 'min:1'],
            'page' => ['integer', 'min:1'],
        ];
    }

    protected function paginateMessages(): array
    {
        return [
            'per_page.integer' => 'Размер страницы должен быть целым числом',
            'per_page.min' => 'Размер страницы должен быть больше 0',
            'page.integer' => 'Номер страницы быть целым числом',
            'page.min' => 'Номер страницы должен быть больше 0',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw ValidationException::withMessages($validator->errors()->toArray());
    }

    public static function getMaxMessage(string $field, $maxLength): string
    {
        return "Значение поля {$field} слишком длинное. Сократите его до {$maxLength} символов или меньше.";
    }

    public static function getMinMessage(string $field, $minLength): string
    {
        return "Минимальная длина поле {$field} - {$minLength} символов.";
    }

    public static function getInMessage(string $field, array $array): string
    {
        return "{$field} должен быть один из: " . implode(', ', $array);
    }
}
