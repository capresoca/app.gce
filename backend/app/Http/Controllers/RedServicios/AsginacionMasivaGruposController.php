<?php

namespace App\Http\Controllers\RedServicios;

use App\Capresoca\Aseguramiento\Servicios\ProcesadorServiciosMasivo;
use App\Http\Controllers\Aseguramiento\AfiliadoController;
use App\Http\Resources\RedServicios\RsAsignacionServiciosResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\RedServicios\RsAsignamasivo;
use App\Models\RedServicios\RsCarguegrupo;
use App\Models\RedServicios\RsMaestroips;
use App\Models\RedServicios\RsMaestroipsgrup;
use App\Models\RedServicios\RsMaestroipshistorico;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

class AsginacionMasivaGruposController extends Controller
{
    public function index()
    {
        if (Input::get('per_page')) {
            return RsAsignacionServiciosResource::collection(RsCarguegrupo::with('usuario')
                ->pimp()->orderBy('created_at', 'desc')->paginate(Input::get('per_page')));
        }

        return RsAsignacionServiciosResource::collection(RsCarguegrupo::with('usuario')
            ->pimp()->limit(100)->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $cargue = RsCarguegrupo::find($id);

        $item = null;
        $number = null;
        if ($cargue->gruposCargados()->count() > 0) {
            $item = $cargue->gruposCargados();
        } else {
            $item = $cargue->gruposAnteriores();
            $number = 2;
        }
        $grupos = $item->get();

        $usuario_cargue = [
            'name' => $cargue->usuario()->first()->name,
            'email' => $cargue->usuario()->first()->email,
            'id_proceso' => $cargue->id,
            'tipo' => $cargue->tipo,
            'fecha_proceso' => Carbon::parse($cargue->created_at)->format('Y-m-d H:m:s'),
        ];

        $datos = collect();

        foreach ($grupos as $key => $grupo) {
            $grupo_anterior = null;
            if (is_null($grupo->afiliado()->first()->portabilidad_activa) || (is_null($number))) {
                $grupo_anterior = [
                    'id' => $grupo->afiliado()->first()->anterior_grupoips['id'],
                    'descrip' => $grupo->afiliado()->first()->anterior_grupoips['descrip']
                ];
            }

            $datos->push([
                'mini_afiliado' => $grupo->afiliado()->first()->mini_afiliado,
                'grupo_anterior' => $grupo_anterior,
                'grupo_nuevo' => [
                    'id' => $grupo->grupoIps()->first()->id,
                    'descrip' => $grupo->grupoIps()->first()->descrip,
                ]
            ]);
        }

        return collect([
            'usuario' => $usuario_cargue,
            'datos' => $datos,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $file = $request->file('file')->getPathname();

            $fileName = $request->file('file')->getClientOriginalName();

            $tabla_temporal = $this->cargarATablaTemporal($file);

            $this->alterColumnTableTemp($tabla_temporal);

            $data = null;
            $errors = null;
            if (count($this->validarAfiliadoExiste($tabla_temporal)) > 0) {
                $errors = $this->validarAfiliadoExiste($tabla_temporal);
            } else {
                if (count($this->validacionGrupo($tabla_temporal)) > 0) {
                    $errors = $this->validacionGrupo($tabla_temporal);
                } else {
                    $data = $this->insertarGruposMasivos($tabla_temporal, $request, $fileName);
                }
            }

            DB::statement("DROP TABLE {$tabla_temporal}");

            return response()->json([
                'data' => new RsAsignacionServiciosResource($data),
                'errors' => collect($errors),
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'errors' => $exception->getMessage(),
            ], 500);
        }
    }

    protected function cargarATablaTemporal($file)
    {
        //ini_set('max_execution_time','-1');
        //$file = $this->request->file('file')->getPathname();
        $file = str_replace('\\', '/', $file);
        $nombre_tabla = 'tempMaestro'.Str::random(16).'s';
        DB::statement("SET GLOBAL local_infile = `ON`");
        DB::statement("CREATE TABLE {$nombre_tabla} (
                                    tipo_documento CHAR(2),
                                    identificacion VARCHAR(20),
                                    grupo_id INT (11))");

        DB::connection()->getPdo()->exec("
            LOAD DATA LOCAL INFILE '{$file}'
            INTO TABLE {$nombre_tabla}
            FIELDS TERMINATED BY ','
            LINES TERMINATED BY '\r\n'
            IGNORE 0 LINES (tipo_documento, identificacion, grupo_id)");

        DB::statement("SET GLOBAL local_infile = `OFF`");

        return $nombre_tabla;
    }

    protected function alterColumnTableTemp(string $tabla_temporal): void
    {
        DB::statement("ALTER TABLE {$tabla_temporal}
                            CHANGE COLUMN `tipo_documento` `tipo_documento` CHAR(2) NOT NULL COLLATE 'utf8_general_ci' FIRST,
                            CHANGE COLUMN `identificacion` `identificacion` VARCHAR(20) NOT NULL COLLATE 'utf8_general_ci' AFTER `tipo_documento`");
        DB::statement("ALTER TABLE {$tabla_temporal} ADD PRIMARY KEY (tipo_documento, identificacion)");
        DB::statement("ALTER TABLE {$tabla_temporal} ADD UNIQUE KEY (tipo_documento, identificacion)");
    }

    /**
     * @param string $tabla_temporal
     * @return array
     */
    protected function validarAfiliadoExiste(string $tabla_temporal): array
    {
        $respuestas = [];

        $afiliadosInexistentes = $this->getSelectTemp($tabla_temporal);

        foreach ($afiliadosInexistentes as $key => $inexistente) {
            $afiliado = DB::selectOne("SELECT a.id,
                                                  a.tipo_identificacion,
                                                  a.identificacion
                                           FROM as_afiliados AS a
                                           WHERE a.tipo_identificacion = '{$inexistente['tipo_documento']}' AND a.identificacion = '{$inexistente['identificacion']}'");

            if (! isset($afiliado)) {
                array_push($respuestas, [
                    'tipo' => 'Error',
                    'titulo' => 'No existe en la base de datos',
                    'afiliado' => $inexistente['tipo_documento'].' '.$inexistente['identificacion'],
                ]);
            }
        }

        return $respuestas;
    }

    /**
     * @param string $tabla_temporal
     * @return array
     */
    protected function validacionGrupo(string $tabla_temporal): array
    {
        $respuestas = [];

        $afiliados = $this->getSelectTemp($tabla_temporal);

        foreach ($afiliados as $afiliatum) {
            $afiliado = $this->consultarAfiliado($afiliatum);

            $idGrupo = (integer) $afiliatum['grupo_id'];
            $grupo = RsMaestroipsgrup::where('id', $idGrupo)->first();

            $portabilidad = DB::selectOne("SELECT MAX(id) AS id,
                                                        a.as_afiliado_id,
                                                        a.estado, COUNT(*) AS cant
                                                FROM rs_portabilidades AS a
                                                WHERE a.id = {$afiliado->id} AND a.estado = 'Aceptado'
                                                GROUP BY a.as_afiliado_id");

            if (isset($portabilidad)) {
                array_push($respuestas, [
                    'tipo' => 'Error (Portabilidad Activa)',
                    'titulo' => 'El afiliado tiene portabiliad.',
                    'nombre_grupo' => null,
                    'id_afiliado' => $afiliado->id,
                    'afiliado' => $afiliado['tipo_identificacion'].' '.$afiliado['identificacion'],
                ]);
            }

            if ($grupo->portable === 1) {
                array_push($respuestas, [
                    'tipo' => 'Error (Grupo Portable)',
                    'titulo' => 'El grupo es portable',
                    'nombre_grupo' => 'ID: '.$grupo['id'].'- NOMBRE: '.strtoupper($grupo['descrip']).' Portable: Si',
                    'id_afiliado' => $afiliado->id,
                    'afiliado' => $afiliado['tipo_identificacion'].' '.$afiliado['identificacion'],
                ]);
            }

            $coincide = DB::selectOne("SELECT a.id FROM as_afiliados AS a
                            LEFT JOIN gn_municipios AS b ON b.id = a.gn_municipio_id
                            WHERE a.id = {$afiliado->id} AND  b.gn_departamento_id = {$grupo['departamento_id']}");

            //a.gn_municipio_id = {$grupo['municipio_id']} AND
            if (! isset($coincide)) {
                array_push($respuestas, [
                    'tipo' => 'Error (No Coincide Municipio y/o Departamento)',
                    'titulo' => 'El municipio y departamento del grupo no coinciden con los del afiliado',
                    'nombre_grupo' => 'ID: '.$grupo['id'].'- NOMBRE: '.strtoupper($grupo['descrip']),
                    'id_afiliado' => $afiliado->id,
                    'afiliado' => $afiliado['tipo_identificacion'].' '.$afiliado['identificacion'],
                ]);
            }
        }

        return $respuestas;
    }

    /**
     * @param string $tabla_temporal
     * @param $request
     * @param $fileName
     * @return array
     */
    protected function insertarGruposMasivos(string $tabla_temporal, $request, $fileName): array
    {
        $afiliadosGrupos = $this->getSelectTemp($tabla_temporal);

        $cargue = RsCarguegrupo::with('gruposCargados')->create([
            'tipo' => $request->tipo,
            'total_registros' => count($afiliadosGrupos),
            'observacion' => $request->observacion ?? $fileName,
            'gs_usuario_id' => Auth::user()->id,
        ]);

        foreach ($afiliadosGrupos as $grupo) {

            $afiliado = $this->consultarAfiliado($grupo);

            $grupoIps = isset($afiliado->servicioIps) ? RsMaestroips::find($afiliado->servicioIps['id']) : new RsMaestroips();
            $this->saveModificacionGrupo($afiliado->servicioIps, $grupoIps);
            $grupoIps->id_grupoips = (integer) $grupo['grupo_id'];
            $grupoIps->rs_carguegrupoips_id = $cargue->id;
            $grupoIps->as_afiliado_id = $afiliado->id;
            $grupoIps->gs_usuario_id = Auth::user()->id;
            $grupoIps->save();
        }

        return ((array) $cargue->gruposCargados);
    }

    // Métodos Privados Fuera del Proceso.

    /**
     * @param string $tabla_temporal
     * @return array
     */
    private function getSelectTemp(string $tabla_temporal): array
    {
        $data = DB::select("SELECT a.* FROM {$tabla_temporal} AS a");
        $data = json_decode(json_encode($data), true);

        return $data;
    }

    /**
     * @param $grupo
     * @param \App\Models\RedServicios\RsMaestroips $grupoIps
     */
    private function saveModificacionGrupo($grupo, RsMaestroips $grupoIps)
    {
        if (isset($grupo)) {
            RsMaestroipshistorico::create([
                'id_grupoips' => $grupoIps->id_grupoips,
                'as_afiliado_id' => $grupoIps->as_afiliado_id,
                'rs_portabilidade_id' => $grupoIps->rs_portabilidade_id ?? null,
                'gs_usuario_id' => $grupoIps->gs_usuario_id,
                'rs_carguegrupoips_id' => $grupoIps->rs_carguegrupoips_id ?? null,
            ]);
        }
    }

    /**
     * @param $grupo
     * @return \App\Models\Aseguramiento\AsAfiliado|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    private function consultarAfiliado($grupo)
    {
        return AsAfiliado::with('sexo')->where('tipo_identificacion', $grupo['tipo_documento'])->where('identificacion', $grupo['identificacion'])->first();
    }
}
