<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\SignInResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Регистрация пользователя",
     *     description="Создаёт нового пользователя и выдаёт токен доступа.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Пользователь успешно зарегистрирован",
     *         @OA\JsonContent(ref="#/components/schemas/SignInResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */
    public function register(RegisterRequest $request): SignInResource
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $token = $user->createToken('api_token')->plainTextToken;
        $user->api_token = $token;

        return new SignInResource($user);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Аутентификация пользователя",
     *     description="Авторизует пользователя и выдаёт токен доступа.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный вход",
     *         @OA\JsonContent(ref="#/components/schemas/SignInResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неверный email или пароль"
     *     )
     * )
     */
    public function login(LoginRequest $request): SignInResource
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException('Неправильный логин или пароль');
        }

        $token = $user->createToken('api_token')->plainTextToken;
        $user->api_token = $token;

        return new SignInResource($user);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Выход пользователя",
     *     description="Удаляет текущий токен пользователя.",
     *     tags={"Auth"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Пользователь успешно разлогинен",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Вы успешно разлогинились")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Вы успешно разлогинились']);
    }
}
