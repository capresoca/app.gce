<?php

namespace App\Http\Controllers\GestionSeguridad;

use App\Models\General\GnArchivo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class GnArchivoContoller extends Controller
{
    public function index ($id, Request $request) {
        try {
            $disposition = Input::get('disposition');
            if($request->hasValidSignature()){
                $archivo = GnArchivo::findOrFail($id);
                return response()->make(Storage::get($archivo->ruta),200,
                    ["Content-Type" => "application/pdf",
                        'Content-Disposition' => $disposition ? $disposition : 'attachment'.'; filename="'.$archivo->nombre.'"'
                    ]);
            }else{
                return response()->view('errors.enlace_roto');
            }
        } catch (\Exception $exception) {
            return response()->view('errors.no_file');
        }
    }
}

