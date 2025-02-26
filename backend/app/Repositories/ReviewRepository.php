<?php

namespace App\Repositories;

use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReviewRepository
{
    public function create(array $data): Review
    {
        return Review::create($data);
    }

    public function getByDriver(int $driver_id): LengthAwarePaginator
    {
        return Review::where('driver_id', $driver_id)->paginate(10);
    }
}
