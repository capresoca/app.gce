<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 30/05/2018
 * Time: 8:46 AM
 */

namespace App\Http\Repositories;


interface Repository
{
    public function guardar($requestArray);
    public function validar($requestArray);
}