<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\CreateRequest;
use App\Http\Resources\Review\Collection;
use App\Http\Resources\Review\Resource;
use App\Models\Review;
use App\Models\User;
use App\Services\ReviewService;
use App\Services\TripService;
/**
 * @OA\Tag(
 *     name="Reviews",
 *     description="Операции с отзывами"
 * )
 */
class ReviewController extends Controller
{
    public function __construct(
        private readonly ReviewService $reviewService,
        private readonly TripService $tripService
    )
    {}

    /**
     * @OA\Post(
     *     path="/reviews/{driver_id}",
     *     summary="Создать отзыв о водителе",
     *     description="Создаёт новый отзыв для водителя.",
     *     tags={"Reviews"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="driver",
     *         in="path",
     *         description="ID водителя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateReviewRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Отзыв успешно создан",
     *         @OA\JsonContent(ref="#/components/schemas/Review")
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
    public function store(CreateRequest $request, User $driver): Resource
    {
        $data = $request->validated();
        $trip = $this->tripService->getById($data['trip_id']);
        $this->authorize('create', [Review::class, $trip, $driver]);
        $review = $this->reviewService->createReview($driver, $data);

        return new Resource($review);
    }

    /**
     * @OA\Get(
     *     path="/reviews/{driver_id}",
     *     summary="Получить список отзывов о водителе",
     *     description="Возвращает все отзывы о водителе.",
     *     tags={"Reviews"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="driver",
     *         in="path",
     *         description="ID водителя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список отзывов",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Review")),
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
    public function index(User $driver): Collection
    {
        $reviews = $this->reviewService->getReviewsByDriver($driver);

        return new Collection($reviews);
    }
}
