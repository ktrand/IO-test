<?php

namespace App\Http\Controllers;

use App\Http\Requests\Car\CreateRequest;
use App\Http\Requests\Car\GetRequest;
use App\Http\Requests\Car\UpdateRequest;
use App\Http\Resources\Car\Collection;
use App\Http\Resources\Car\Resource;
use App\Models\Car;
use App\Services\CarService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Cars",
 *     description="Операции с автомобилями"
 * )
 */
class CarController extends Controller
{
    public function __construct(private readonly CarService $carService)
    {}

    /**
     * @OA\Post(
     *     path="/cars",
     *     summary="Создать новый автомобиль",
     *     description="Создаёт новый автомобиль и возвращает его данные.",
     *     tags={"Cars"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateCarRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Автомобиль успешно создан",
     *         @OA\JsonContent(ref="#/components/schemas/Car")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Доступ запрещён"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */
    public function store(CreateRequest $request): Resource
    {
        $this->authorize('create', Car::class);
        $car = $this->carService->add($request->validated(), $request->user());

        return new Resource($car);
    }

    /**
     * @OA\Put(
     *     path="/cars/{car}",
     *     summary="Обновить данные автомобиля",
     *     description="Обновляет информацию о выбранном автомобиле.",
     *     tags={"Cars"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="car",
     *         in="path",
     *         description="ID автомобиля для обновления",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCarRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Данные автомобиля обновлены",
     *         @OA\JsonContent(ref="#/components/schemas/Car")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Доступ запрещён"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Автомобиль не найден"
     *     )
     * )
     */
    public function update(UpdateRequest $request, Car $car): Resource
    {
        $this->authorize('update', $car);
        $car = $this->carService->update($car, $request->validated());

        return new Resource($car);
    }

    /**
     * @OA\Get(
     *     path="/cars",
     *     summary="Получить список автомобилей пользователя",
     *     description="Возвращает список автомобилей текущего пользователя.",
     *     tags={"Cars"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Количество элементов на странице",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список автомобилей",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Car")),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="per_page", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     )
     * )
     */
    public function index(GetRequest $request): Collection
    {
        $cars = $this->carService->getUserCars(
            $request->user(),
            $request->per_page
        );

        return new Collection($cars);
    }

    /**
     * @OA\Delete(
     *     path="/cars/{car}",
     *     summary="Удалить автомобиль",
     *     description="Удаляет автомобиль по ID.",
     *     tags={"Cars"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="car",
     *         in="path",
     *         description="ID автомобиля для удаления",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Автомобиль удалён"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Доступ запрещён"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Автомобиль не найден"
     *     )
     * )
     */
    public function destroy(Car $car): JsonResponse
    {
        $this->authorize('delete', $car);
        $this->carService->delete($car);

        return response()->json(['message' => 'Car deleted']);
    }
}
