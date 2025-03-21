<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Matchpoint API", version="1.0")
 * @OA\Server(url="http://localhost:8000")
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class BaseController extends Controller 
{ 
    //
}
