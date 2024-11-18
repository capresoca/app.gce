<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/10/2018
 * Time: 3:13 PM
 */

namespace App\Traits;


use Illuminate\Support\Facades\Log;

class ClaseB
{

    public function __invoke()
    {
        Log::Info('se esta ejecutando la clase B');
    }

}

