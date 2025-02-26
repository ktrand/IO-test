<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateReviewRequest",
 *     type="object",
 *     required={"trip_id", "rating"},
 *     @OA\Property(
 *         property="trip_id",
 *         description="ID поездки, к которой относится отзыв",
 *         type="integer"
 *     ),
 *     @OA\Property(
 *         property="rating",
 *         description="Оценка от 1 до 5",
 *         type="integer",
 *         minimum=1,
 *         maximum=5
 *     ),
 *     @OA\Property(
 *         property="comment",
 *         description="Комментарий к отзыву",
 *         type="string",
 *         nullable=true
 *     )
 * )
 */
class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'trip_id' => 'required|exists:trips,id',
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ];
    }
}
