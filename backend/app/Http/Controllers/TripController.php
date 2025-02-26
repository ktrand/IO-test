<?php

namespace App\Http\Controllers;

use App\Http\Requests\Trip\CreateRequest;
use App\Http\Requests\Trip\GetRequest;
use App\Http\Requests\Trip\UpdateRequest;
use App\Http\Resources\Trip\Collection;
use App\Http\Resources\Trip\Resource;
use App\Models\Trip;
use App\Services\TripService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TripController extends Controller
{
    public function __construct(private readonly TripService $tripService)
    {}

    /**
     * @OA\Post(
     *     path="/trips",
     *     summary="Создать новую поездку",
     *     description="Создаёт новую поездку и возвращает её данные.",
     *     tags={"Trips"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateTripRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Поездка успешно создана",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
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
     * @throws AuthorizationException
     */
    public function store(CreateRequest $request): Resource
    {
        $this->authorize('create', Trip::class);
        $trip = $this->tripService->create($request->validated(), $request->user());

        return new Resource($trip);
    }

    /**
     * @OA\Get(
     *     path="/trips",
     *     summary="Получить список поездок пользователя",
     *     description="Возвращает список поездок текущего пользователя с фильтрацией по водителю, пассажиру, статусу и дате.",
     *     tags={"Trips"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Количество элементов на странице",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Номер страницы",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="driver_id",
     *         in="query",
     *         description="Фильтр по ID водителя (только для пассажиров)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="passenger_id",
     *         in="query",
     *         description="Фильтр по ID пассажира (только для водителей)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Фильтр по статусу поездки",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Фильтр по дате создания поездки (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список поездок",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Trip")
     *             ),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Неавторизованный доступ",
     *     )
     * )
     */
    public function index(GetRequest $request): Collection
    {
        $trips = $this->tripService->getUserTrips(
            $request->user(),
            $request->per_page ?? 10,
            $request->all()
        );

        return new Collection($trips);
    }

    /**
     * @OA\Get(
     *     path="/trips/{id}",
     *     summary="Получить информацию о поездке",
     *     description="Возвращает данные конкретной поездки.",
     *     tags={"Trips"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="trip",
     *         in="path",
     *         description="ID поездки",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Данные поездки",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
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
     *         description="Поездка не найдена"
     *     )
     * )
     */
    public function show(Trip $trip): Resource
    {
        return new Resource($trip);
    }

    /**
     * @OA\Put(
     *     path="/trips/{id}",
     *     summary="Обновить информацию о поездке",
     *     description="Обновляет информацию о поездке, включая статус.",
     *     tags={"Trips"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="trip",
     *         in="path",
     *         description="ID поездки",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTripRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Поездка успешно обновлена",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
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
     *         description="Поездка не найдена"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(UpdateRequest $request, Trip $trip): Resource
    {
        $this->authorize('update', $trip);
        $trip = $this->tripService->update($request->user(), $trip, $request->validated());

        return new Resource($trip);
    }

    /**
     * @OA\Delete(
     *     path="/trips/{id}",
     *     summary="Удалить поездку",
     *     description="Удаляет поездку из системы.",
     *     tags={"Trips"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="trip",
     *         in="path",
     *         description="ID поездки",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Поездка успешно удалена",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Trip canceled")
     *         )
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
     *         description="Поездка не найдена"
     *     )
     * )
     * @throws AuthorizationException
     */
    public function destroy(Trip $trip): JsonResponse
    {
        $this->authorize('delete', Trip::class);
        $this->tripService->delete($trip);
        return response()->json(['message' => 'Trip canceled']);
    }
}
