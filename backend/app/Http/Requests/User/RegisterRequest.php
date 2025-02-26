<?php

namespace App\Http\Requests\User;

use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     type="object",
 *     required={"name", "email", "phone", "password", "role"},
 *     title="Запрос на регистрацию",
 *     description="Данные для регистрации нового пользователя",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Иван Иванов"),
 *     @OA\Property(property="email", type="string", format="email", example="ivan@example.com"),
 *     @OA\Property(property="phone", type="string", maxLength=20, example="+79876543210"),
 *     @OA\Property(property="password", type="string", format="password", minLength=6, example="password123"),
 *     @OA\Property(property="role", type="string", enum={"driver", "passenger"}, example="driver")
 * )
 */
class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:' . implode(',', array_column(UserRoles::cases(), 'value')),
        ];
    }
}
