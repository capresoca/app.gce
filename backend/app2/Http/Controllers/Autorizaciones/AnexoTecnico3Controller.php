<?php

namespace App\Http\Controllers\Autorizaciones;

use App\Autorizaciones\Reforigenautorizacion;
use App\Http\Requests\Autorizaciones\Anexo3Request;
use App\Models\Autorizaciones\AuAnexot3;
use App\Models\Autorizaciones\AuEspecialidad;
use App\Models\Autorizaciones\AuOrigenAtencion;
use App\Models\Autorizaciones\Refcobertura;
use App\Models\Autorizaciones\Refcup;
use App\Models\Autorizaciones\Refespecialidad;
use App\Models\Autorizaciones\Refmodalidadservicio;
use App\Models\Autorizaciones\Refnivelatencion;
use App\Models\Autorizaciones\Refqx;
use App\Models\Autorizaciones\Refsersol;
use App\Models\Autorizaciones\RefUbic;
use App\Models\General\GnArchivo;
use App\Models\Autorizaciones\Refviasol;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class AnexoTecnico3Controller extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return  Resource::collection(
                AuAnexot3::with('afiliado','usuarioProceso')->pimp()
                    ->orderBy('updated_at')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            AuAnexot3::with('afiliado','usuarioProceso')->pimp()
                ->orderBy('updated_at')->get()
        );
    }
    
    public function show (AuAnexot3 $anexot3) {
        return new Resource($anexot3->load('afiliado','usuarioProceso','detalles','usuarioValida'));
    }

    public function store(Anexo3Request $request)
    {
        $anexo = new AuAnexot3();
        $anexo->fill($request->except('gn_archivo_id'));
        if ($request->historia_clinica !== null) {
            $anexo->gn_archivo_id = $this->subirArchivoB64($request->historia_clinica);
        }
        $anexo->save();
        $anexo->detalles()->createMany($request->detalles);
        $anexo->load('usuarioProceso','afiliado','detalles');
        $this->updateAfiliado($request, $anexo);
        return response()->json([new Resource($anexo)])->setStatusCode(201);
    }

    public function update(Anexo3Request $request, $id)
    {
//        $anexot3 = AuAnexot3::find($id);
    }



}
