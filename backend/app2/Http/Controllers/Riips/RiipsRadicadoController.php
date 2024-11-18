<?php

namespace App\Http\Controllers\Riips;

use App\Http\Controllers\Controller;
use App\Http\Resources\Riips\RiipsResource;
use App\Models\Riips\RsRipsRadicado;
use App\Models\RsCum;
use Chumper\Zipper\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Validator;

class RiipsRadicadoController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return RiipsResource::collection(RsRipsRadicado::with('RsEntidad.tercero', 'RsEntidad.tipo')->where('estado_radicacion', '=', 'Validado')->orWhere('estado_radicacion', '=', 'Confirmado')->pimp()->orderBy('created_at', 'asc')->paginate(Input::get('per_page')));
        }

        return RiipsResource::collection(RsRipsRadicado::with('RsEntidad.tercero', 'RsEntidad.tipo')->where('estado_radicacion', '=', 'Validado')->orWhere('estado_radicacion', '=', 'Confirmado')->pimp()->orderBy('created_at', 'asc')->get());
        //try {
        //	return response()->json([
        //		'estado' => 'ok',
        //		'rips' => RsRipsRadicado::where('estado_radicacion', 'Validado')
        //            ->orWhere('estado_radicacion', 'Confirmado')
        //            ->with(['RsEntidad.tercero', 'RsEntidad.tipo'])->orderBy('created_at', 'ASC')->get(),
        //	]);
        //} catch (\Exception $e) {
        //	return response()->json([
        //		'message' => 'Error en el servidor',
        //		'errors' => $e->getMessage(),
        //	]);
        //}
    }

    public function store(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json([
                    'estado' => 'validador',
                    'errors' => $validatedData->errors(),
                ]);
            }
            $ripsRadicado = RsRipsRadicado::where('id', $request->id)->first();
            $ripsRadicado->estado_radicacion = 'Confirmado';
            $ripsRadicado->save();

            return response()->json([
                'estado' => 'ok',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Error en el servidor",
                "error" => $e->getMessage(),
                "trace" => $e->getTrace(),
            ]);
        }
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        try {
            $ripp = RsRipsRadicado::find($id);

            if ($ripp->estado_radicacion === 'Confirmado') {
                abort('422', 'No es posible cambiar el tipo de facturación');
            }

            $ripp->tipo_facturacion = $request->tipo_facturacion;
            $ripp->save();

            return response(new RiipsResource($ripp))->setStatusCode(201);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
    }

    public function getRipsValidadosZip($idRip)
    {
        try {

            $ripsRadicado = RsRipsRadicado::where('id', $idRip)->first();
            $existeArchivo = Storage::disk('s3')->exists('RIPS/'.$ripsRadicado->cod_radicacion.'/archivos/');

            if (! $existeArchivo) {
                return response()->json([
                    'state' => 'error',
                    'error' => 'No se encontró la ruta del RIPS con código '.$ripsRadicado->cod_radicacion,
                ], 404);
            }

            $ziper = new Zipper();
            $nombresDeArchivosEnS3 = Storage::disk('s3')->allFiles('RIPS/'.$ripsRadicado->cod_radicacion.'/archivos');

            if (empty($nombresDeArchivosEnS3)) {
                return response()->json([
                    'state' => 'error',
                    'error' => 'No existen archivos para descargar del RIPS con código '.$ripsRadicado->cod_radicacion,
                ], 404);
            }

            foreach ($nombresDeArchivosEnS3 as $rutaArchivo) {
                $temp = explode('/', $rutaArchivo);
                $nombreArchivoTXT = end($temp);

                $archivoTXT = Storage::disk('s3')->get($rutaArchivo);

                if (! $archivoTXT) {
                    return response()->json([
                        'state' => 'error',
                        'error' => 'Uno o varios de los archivos TXT del RIPS no se pudieron recuperar.',
                    ], 404);
                }

                $ziper->make(storage_path('app/public/tmp/ripstemp.zip'))->addString($nombreArchivoTXT, $archivoTXT);
            }

            $rutaArchivoZip = $ziper->getFilePath();

            $response = response()->download($rutaArchivoZip)->deleteFileAfterSend(true);

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en el servidor',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function getEntidadesRips()
    {
        $entidads = $this->getEntidadesFilter($this->consultaEntidadesValidadas(['Validado']));

        return response()->json(['data' => $entidads])->setStatusCode(200);
    }

    public function getEntysValidadasYConfirmadas()
    {
        $entidads = $this->getEntidadesFilter($this->consultaEntidadesValidadas(['Validado', 'Confirmado']));

        return response()->json(['data' => $entidads])->setStatusCode(200);
    }

    /**
     * @param $estados
     * @return array
     */
    private function consultaEntidadesValidadas($estados): array
    {
        $status = is_array($estados) ? ("'".implode("','", $estados)."'") : $estados;

        return DB::select("SELECT a.rs_entidad_id,
                                             b.id AS id_enditad,
                                             b.nombre,
                                             b.cod_habilitacion,
                                             c.id AS id_tercero,
                                             c.identificacion,
                                             c.razon_social,
                                             c.nombre_completo
                                    FROM rs_rips_radicados AS a
                                    LEFT JOIN rs_entidades AS b ON b.id = a.rs_entidad_id
                                    LEFT JOIN gn_terceros AS c ON c.id = b.gn_tercero_id
                                    WHERE a.estado_radicacion IN ({$status}) AND b.id IS NOT NULL
                                    GROUP BY a.rs_entidad_id");
    }

    /**
     * @param array $entidads
     * @return array
     */
    private function getEntidadesFilter(array $entidads): array
    {
        $data = array();
        foreach ($entidads as $key => $entidad) {
            if ($entidad->id_enditad !== null) {
                array_push($data, [
                    'id' => $entidad->id_enditad,
                    'nombre' => $entidad->nombre,
                    'cod_habilitacion' => $entidad->cod_habilitacion,
                    'tercero' => [
                        'id' => $entidad->id_tercero,
                        'identificacion' => $entidad->identificacion,
                        'razon_social' => $entidad->razon_social,
                        'nombre_completo' => $entidad->nombre_completo,
                    ],
                ]);
            }
        }

        return $data;
    }
}
