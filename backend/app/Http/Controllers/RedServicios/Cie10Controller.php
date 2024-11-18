<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Requests\RedServicios\Cie10Request;
use App\Http\Resources\RedServicios\Cie10Resource;
use App\Models\RedServicios\RsCie10;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class Cie10Controller extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return Cie10Resource::collection(
                RsCie10::pimp()->paginate(Input::get('per_page'))
            );
        }
        return Cie10Resource::collection(RsCie10::pimp()->orderBy('updated_at', 'desc')->get());
    }


    public function store(Cie10Request $request)
    {
        try {
            $rs_cie10 = RsCie10::create($request->all());

            return response()->json([
                'message' => 'Se ha registrado correctamente.',
                'data' => new Cie10Resource($rs_cie10)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }

    public function show(RsCie10 $rs_cie10)
    {
        return new  Cie10Resource($rs_cie10);
    }

    public function update(Cie10Request $request, RsCie10 $rs_cie10)
    {
        try {
            $rs_cie10->update($request->all());

            return response()->json([
                'message' => 'Se ha actualizado correctamente.',
                'data' => new Cie10Resource($rs_cie10)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);
        }
    }

    public function destroy($id) {}

    public function importarPorArchivoPlano (Request $request) {
        try {
            if (!($request->file('cie10Plano')->getClientOriginalExtension() === 'TXT' || $request->file('cie10Plano')->getClientOriginalExtension() === 'txt')) {
                return response()->json([
                    "state" => "validador",
                    "errors" => 'La extension del archivo no es correcta. Solo se acepta .txt'
                ]);
            }
            $cie10Archivo = file($request->file('cie10Plano')->path(),  FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            DB::beginTransaction();
            for ($i = 0; $i < count($cie10Archivo); $i++) {
                $cie10Descomprimido = explode(',', utf8_encode($cie10Archivo[$i]));
                if (count($cie10Descomprimido) !== 6) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'El número de columnas del archivo NO es valido, número de columnas: '.count($cie10Descomprimido).' columnas aceptadas: 6 Registro'.($i+1)
                    ]);
                }
                if ($cie10Descomprimido[0] === '' && strlen($cie10Descomprimido[0]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'El código del CIE10 es obligatorio. Registro: '.($i+1)
                    ]);
                }
                if ($cie10Descomprimido[1] === '' && strlen($cie10Descomprimido[1]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'El código de 3 digitos es obligatorio. Registro: '.($i+1)
                    ]);
                }
                if ($cie10Descomprimido[2] === '' && strlen($cie10Descomprimido[2]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'La descripción del código CIE10 es obligatoria. Registro: '.($i+1)
                    ]);
                }
                if ($cie10Descomprimido[3] === '' && strlen($cie10Descomprimido[3]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'El genero al que aplica el código es obligatorio obligatorio. Registro: '.($i+1)
                    ]);
                }else{
                    if (!($cie10Descomprimido[3] === 'A' || $cie10Descomprimido[3] === 'M' || $cie10Descomprimido[3] === 'F')) {
                        DB::rollBack();
                        return response()->json([
                            "state" => "validador",
                            "errors" => 'El dato del genero no esta entre el rango de valores permitidos. Registro: '.($i+1)
                        ]);
                    }
                }
                if ($cie10Descomprimido[4] === '' && strlen($cie10Descomprimido[4]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'La edad mínima del código CIE10 es obligatoria. Registro: '.($i+1)
                    ]);
                }
                if ($cie10Descomprimido[5] === '' && strlen($cie10Descomprimido[5]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'La edad máxima del código CIE10 es obligatoria. Registro: '.($i+1)
                    ]);
                }
                $cie10 = new RsCie10();
                $cie10->codigo = $cie10Descomprimido[0];
                $cie10->codigo_tres = $cie10Descomprimido[1];
                $cie10->descripcion = $cie10Descomprimido[2];
                $cie10->genero = $cie10Descomprimido[3];
                $cie10->edad_min = $cie10Descomprimido[4];
                $cie10->edad_max = $cie10Descomprimido[5];
                $cie10->save();
            }
            DB::commit();
            return response()->json([
                "state" => "save",
                "cie10" => count($cie10Archivo)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => "Error en el servidor."
            ]);
        }
    }

    public function importarPorArchivoCsv(Request $request)
    {
        try {
            if (!($request->file('cie10Csv')->getClientOriginalExtension() === 'CSV' || $request->file('cie10Csv')->getClientOriginalExtension() === 'csv')) {
                return response()->json([
                    "state" => "validador",
                    "errors" => 'La extension del archivo no es correcta. Solo se aceptan archivos .CSV'
                ]);
            }
            $cie10Archivo = file($request->file('cie10Csv')->path(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            DB::beginTransaction();
            for ($i=0; $i < count($cie10Archivo); $i++) {
                $cie10Descomprimido = explode(';', utf8_encode($cie10Archivo[$i]));
                if (count($cie10Descomprimido) !== 6) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'El número de columnas del archivo NO es válido, número de columnas: '.count($cie10Descomprimido).' columnas aceptadas: 6 Registro'.($i+1)
                    ]);
                }
                if ($cie10Descomprimido[0] === '' && strlen($cie10Descomprimido[0]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'El código del CIE10 es obligatorio. Registro: '.($i+1)
                    ]);
                }
                if ($cie10Descomprimido[1] === '' && strlen($cie10Descomprimido[1]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'El código de 3 digitos es obligatorio. Registro: '.($i+1)
                    ]);
                }
                if ($cie10Descomprimido[2] === '' && strlen($cie10Descomprimido[2]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'La descripción del código CIE10 es obligatoria. Registro: '.($i+1)
                    ]);
                }
                if ($cie10Descomprimido[3] === '' && strlen($cie10Descomprimido[3]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'El género al que aplica el código es obligatorio obligatorio. Registro: '.($i+1)
                    ]);
                }else{
                    if (!($cie10Descomprimido[3] === 'A' || $cie10Descomprimido[3] === 'M' || $cie10Descomprimido[3] === 'F')) {
                        DB::rollBack();
                        return response()->json([
                            "state" => "validador",
                            "errors" => 'El dato del género no esta entre el rango de valores permitidos. Registro: '.($i+1)
                        ]);
                    }
                }
                if ($cie10Descomprimido[4] === '' && strlen($cie10Descomprimido[4]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'La edad mínima del código CIE10 es obligatoria. Registro: '.($i+1)
                    ]);
                }
                if ($cie10Descomprimido[5] === '' && strlen($cie10Descomprimido[5]) === 0) {
                    DB::rollBack();
                    return response()->json([
                        "state" => "validador",
                        "errors" => 'La edad máxima del código CIE10 es obligatoria. Registro: '.($i+1)
                    ]);
                }
                $cie10 = new RsCie10();
                $cie10->codigo = $cie10Descomprimido[0];
                $cie10->codigo_tres = $cie10Descomprimido[1];
                $cie10->descripcion = $cie10Descomprimido[2];
                $cie10->genero = $cie10Descomprimido[3];
                $cie10->edad_min = $cie10Descomprimido[4];
                $cie10->edad_max = $cie10Descomprimido[5];
                $cie10->save();
            }
            DB::commit();
            return response()->json([
                "state" => "save",
                "cums" => count($cie10Archivo)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "message" => "Error en el servidor"
            ]);
        }
    }
}
