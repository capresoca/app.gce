<?php

namespace App\Http\Controllers\AtencionUsuario;

use App\Http\Requests\AtencionUsuario\RespuestaPqrsdRequest;
use App\Models\AtencionUsuario\AuPqrsd;
use App\Models\AtencionUsuario\AuRespuestapqrsd;
use App\Traits\ArchivoTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RespuestaPqrsdController extends Controller
{
    public function store(AuPqrsd $pqrsd, RespuestaPqrsdRequest $request)
    {
        $respuesta = $pqrsd->respuesta()->create($request->except('files'));

        $this->subirAnexos($respuesta,$request->file('files'));

        if($respuesta->estado === 'Confirmada'){
            $pqrsd->fecha_respuesta = $request->fecha_respuesta;
            $pqrsd->estado = 'Respondido';
            $pqrsd->save();
        }

        $pqrsd->load('afiliado','tipo','funcionario','municipio','anexos');
        return response()->json($pqrsd,201);
    }


    public function update(AuPqrsd $pqrsd, AuRespuestapqrsd $respuesta, RespuestaPqrsdRequest $request)
    {
        $respuesta->update($request->except('files'));

        if($respuesta->estado === 'Confirmada'){
            $pqrsd->fecha_respuesta = $request->fecha_respuesta;
            $pqrsd->estado = 'Respondido';
            $pqrsd->save();
        }

        $this->subirAnexos($respuesta,$request->file('files'));
        $pqrsd->load('afiliado','tipo','funcionario','municipio','anexos');
        return response()->json($pqrsd,202);
    }

    private function subirAnexos(AuRespuestapqrsd $respuesta, $files)
    {
        if($files && count($files)){
            foreach ($files as $file) {
                $anexo = ArchivoTrait::subirArchivo($file, 'AtencionUsuario/PQRSDS/Respuestas');
                $respuesta->anexos()->attach($anexo->id);
            }
        }
    }
}
