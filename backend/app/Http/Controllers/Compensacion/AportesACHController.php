<?php

namespace App\Http\Controllers\Compensacion;

use App\Compensacion\CpAportesACH;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;

class AportesACHController extends Controller
{
    public function index()
    {
        $aportesQuery = CpAportesACH::pimp()->with('aportante');

        $aportes = $aportesQuery->paginate(request()->per_page);

        return Resource::collection($aportes);

    }
}
