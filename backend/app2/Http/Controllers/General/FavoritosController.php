<?php

namespace App\Http\Controllers\General;

use App\GsComponente;
use App\GsFavorito;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FavoritosController extends Controller
{
    public function __invoke($componente_id)
    {
        $favorito = GsFavorito::where([
            'gs_componente_id' => $componente_id,
            'gs_usuario_id' => Auth::user()->id
        ])->first();

        if($favorito){
            $favorito->delete();
            return response()->json('Se elimino',202);
        }

        GsFavorito::create([
            'gs_componente_id' => $componente_id,
            'gs_usuario_id' => Auth::user()->id
        ]);

        return response()->json('Se Creo',201);
    }
}
