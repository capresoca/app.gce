<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Resources\Aseguramiento\CautrasladoResource;
use App\Models\Aseguramiento\AsCautraslado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CautrasladoController extends Controller
{
    public function index()
    {
        return CautrasladoResource::collection(AsCautraslado::all());
    }
}
