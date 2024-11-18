<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Controllers\Controller;
use App\Http\Requests\RedServicios\RsAsignacionRequest;
use App\Models\General\GnMunicipio;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsMaestroips;
use App\Models\RedServicios\RsMaestroipsgrudet;
use App\Models\RedServicios\RsMaestroipsgrup;
use App\RedServicios\RsServhabilitados;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class AsignacionServiciosController extends Controller
{
    /**
     * @param $radicado
     * @return mixed
     */
    public static function getLoad($item)
    {
        return $item->load(RsMaestroipsgrup::allRelations());
    }

    public function index()
    {
        if (Input::get('per_page')) {
            return Resource::collection(RsMaestroipsgrup::with('municipio')
                ->pimp()->orderBy('updated_at', 'desc')->paginate(Input::get('per_page')));
        }

        return Resource::collection(RsMaestroipsgrup::with('municipio')->pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function store(RsAsignacionRequest $request)
    {
        try {

            $grupoIps = RsMaestroipsgrup::with('prestadores')->create([
                'municipio_id' => $request->municipio_id,
                'departamento_id' => $request->departamento_id,
                'descrip' => $request->descrip,
                'portable' => $request->portable,
            ]);

            $prestadores = $request->prestadores;
            $this->updateOrCreatePrestadores($prestadores, $grupoIps);

            self::getLoad($grupoIps);

            return response()->json([
                'data' => new Resource($grupoIps),
            ], 201);
        } catch (\Exception $exception) {
            return response()->json(['errors' => $exception->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $maestroipsgrup = RsMaestroipsgrup::find($id);

        return new Resource(self::getLoad($maestroipsgrup));
    }

    public function update(RsAsignacionRequest $request, $id)
    {
        try {

            $grupoIps = RsMaestroipsgrup::with('prestadores')->find($id);
            $grupoIps->update($request->except('prestadores'));

            $prestadores = $request->prestadores;
            if (! is_null($request['id'])) {
                $grupoIps->prestadores()->delete();
            }
            $this->updateOrCreatePrestadores($prestadores, $grupoIps);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id) {}

    public function getPrestadores($id_municipio)
    {
        try {
            $id_departamento = GnMunicipio::select('id','gn_departamento_id')->where('id',$id_municipio)->first()->gn_departamento_id;

            $entidades = RsEntidade::with('tercero')
                ->select('id', 'nombre', 'cod_habilitacion', 'direccion_sede', 'telefono_sede', 'gn_tercero_id'
                )->whereHas('municipio', function ($query) use ($id_departamento) {
                    $query->where('gn_departamento_id', $id_departamento);
                })->get();

            return response()->json([
                'data' => new Resource($entidades),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function getGruposPorMunicio($id_municipio)
    {
        try {
            $portable = (integer) Input::get('portable');
            $gruposIPS = RsMaestroipsgrup::with('municipio', 'prestadores.entidad')
                ->where('municipio_id', '=', $id_municipio);

            $data = null;
            if ($portable) {
                $data = $gruposIPS->where('portable',$portable)->get();
            } else {
                $data = $gruposIPS->where('portable', '=', 0)->get();
            }

            return new Resource($data);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @param $request
     * @param $prestadores
     * @param $grupoIps
     */
    private function updateOrCreatePrestadores($prestadores, RsMaestroipsgrup $grupoIps): void
    {

        foreach ($prestadores as $prestadore) {
            $codigo = RsEntidade::find($prestadore['entidad_id'])->cod_habilitacion;

            $grupoIps->prestadores()->create([
                'idIps' => $prestadore['entidad_id'],
                'gs_usuario_id' => auth()->user()->id,
                'primaria' => $prestadore['primaria'],
                'codigo' => $codigo,
            ]);
        }
    }
}
