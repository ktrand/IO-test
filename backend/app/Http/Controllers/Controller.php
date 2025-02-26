<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      title="RideTech API",
 *      version="1.0.0",
 *      description="Документация API для Инноватсияи ояндасоз"
 * )
 *
 * @OA\Server(
 *      url="http://localhost:8088/api",
 *      description="API сервер"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
