<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Requests\Aseguramiento\FormpagadoreRequest;
use App\Http\Resources\Aseguramiento\FormpagadoreResource;
use App\Models\Aseguramiento\AsFormpagadore;
use App\Models\Aseguramiento\AsTramite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class FormpagadoreController extends Controller
{

    public function index()
    {
        if (Input::get('per_page')) {
            return FormpagadoreResource::collection(AsFormpagadore::with('tercero', 'tipoAportante', 'tramite')
                ->pimp()->orderBy('updated_at','desc')->paginate(Input::get('per_page'))
            );
        }
        return FormpagadoreResource::collection(AsFormpagadore::pimp()->orderBy('updated_at','desc')->get());
    }


    public function store(FormpagadoreRequest $request)
    {
//        dd($request->all());
        try{
            DB::beginTransaction();
            $formpagadore = AsFormpagadore::create($request->all());
            $formpagadore->load('tercero', 'tipoAportante', 'tramite');

            if ($formpagadore->estado === 'Radicado') {
                $this->radicar($formpagadore);
            }
            DB::commit();
//            return new FormpagadoreResource($formpagadore);
            return response()->json([
                'message' => 'El pagador fue creado con exito ',
                'formpagadore' => new FormpagadoreResource($formpagadore)
            ],201);
        }catch(\Exception $e){
            DB::rollBack();
            return $e;
        }
    }

    public function show(AsFormpagadore $formpagadore)
    {
        $formpagadore->load('tercero', 'tipoAportante', 'tramite');
        return new FormpagadoreResource($formpagadore);
    }


    public function update(FormpagadoreRequest $request, AsFormpagadore $formpagadore)
    {
        try {
            DB::beginTransaction();
            $formpagadore->update($request->all());

            if ($formpagadore->estado === 'Radicado') {
                $this->radicar($formpagadore);
            }
            $formpagadore->load('tercero', 'tipoAportante', 'tramite');

            DB::commit();

            return response()->json([
                'message' => 'El pagador fue actualizado con exito ',
                'formpagadore' => new FormpagadoreResource($formpagadore)
            ],202);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function radicar (AsFormpagadore $formpagadore) {
        $fecha_radicar = $formpagadore->fecha_radicacion;
        $estadoR = $formpagadore->estado;
        $tramitePagador = new AsTramite();
        $tramitePagador->tipo_tramite = 'Afiliacion Aportante';
        $tramitePagador->clase_tramite = 'Manual';
        $tramitePagador->fecha_radicacion = $fecha_radicar;
        $tramitePagador->estado = $estadoR;
        $tramitePagador->gs_usuradica_id = 1;
        $tramitePagador->gs_usuario_id = 1;
        $tramitePagador->save();

        $formpagadore->as_tramite_id = $tramitePagador->id;
        $formpagadore->save();
    }
}
