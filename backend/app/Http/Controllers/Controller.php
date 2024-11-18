<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
*  @OAS\Server(
    url="http://localhost:8000/api",
 *     description="Servidor Capresoca"
 *     ),
 * @OAS\Info(
 *     title="API Backend Capresoca EPS",
 *     version="0.1.0",
 *     description="API backend Capresoca EPS",
 *     @OAS\Contact(
 *          name="Sistemas Inteligentes",
 *          url="http://sistemasinteligentes.com.co",
 *          email="support@wilfredo.com",
 *     )
 *   ),
 * @OAS\SecurityScheme(type="http", scheme="bearer", bearerFormat="JWT",securityScheme="JWT" )
*/

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
