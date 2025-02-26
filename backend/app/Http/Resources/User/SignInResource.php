<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="SignInResource",
 *     type="object",
 *     title="Ответ при успешной аутентификации",
 *     description="Данные о пользователе и токене доступа",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Иван Иванов"),
 *     @OA\Property(property="email", type="string", example="ivan@example.com"),
 *     @OA\Property(property="phone", type="string", example="+79876543210"),
 *     @OA\Property(property="role", type="string", enum={"driver", "passenger"}, example="driver"),
 *     @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
 * )
 */
class SignInResource extends Resource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $array = parent::toArray($request);
        $array['token'] = $this->api_token;

        return $array;
    }
}
