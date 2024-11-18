<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Requests\ContratacionEstatal\BienservicioRequest;
use App\Http\Resources\ContratacionEstatal\BienservicioResource;
use App\Http\Resources\ContratacionEstatal\BienserviciosResource;
use App\Models\ContratacionEstatal\CeBienservicio;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class BienservicioController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return BienserviciosResource::collection(
                CeBienservicio::with('clasbienservicio.fambienservicio.segbienservicio')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }

        return BienserviciosResource::collection(CeBienservicio::with('clasbienservicio.fambienservicio.segbienservicio')->pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function store(BienservicioRequest $request)
    {
        $ce_bienservicio = new CeBienservicio();
        $ce_bienservicio->fill($request->except('codigo'));
        $ce_bienservicio->codigo = $this->consecutivo($request->ce_clasbienservicio_id);
        $ce_bienservicio->save();
        $ce_bienservicio->load(['clasbienservicio.fambienservicio.segbienservicio']);
        return new BienserviciosResource($ce_bienservicio);
    }

    public function show(CeBienservicio $ce_bienservicio)
    {
        return new BienservicioResource($ce_bienservicio->load('clasbienservicio.fambienservicio.segbienservicio'));
    }

    public function update(BienservicioRequest $request, $id)
    {
//        $ce_bienservicio->update($request->all());
        $ce_bienservicio = CeBienservicio::find($id);
        $ce_bienservicio->fill($request->except('codigo'));
        $ce_bienservicio->codigo = $this->consecutivo($request->ce_clasbienservicio_id);
        $ce_bienservicio->save();
        $ce_bienservicio->load(['clasbienservicio.fambienservicio.segbienservicio']);

        return (new BienserviciosResource($ce_bienservicio))->response()->setStatusCode(202);

    }

    public function consecutivo ($item) {
        $consecutivo = CeBienservicio::where('ce_clasbienservicio_id', $item)->max('codigo');
        return $consecutivo;
    }
}
