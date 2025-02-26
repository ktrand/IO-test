<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Trip;
use App\Repositories\ReviewRepository;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class ReviewService
{
    public function __construct(private readonly ReviewRepository $reviewRepository)
    {}

    public function createReview(User $driver, array $data): Review
    {
        $data['driver_id'] = $driver->id;
        return $this->reviewRepository->create($data);
    }

    public function getReviewsByDriver(User $user): LengthAwarePaginator
    {
        return $this->reviewRepository->getByDriver($user->id);
    }
}
