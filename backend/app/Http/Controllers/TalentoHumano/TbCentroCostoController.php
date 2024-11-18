<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Models\TalentoHumano\TbArea;
use App\Models\TalentoHumano\TbCargo;
use App\Models\TalentoHumano\TbCentroConsto;
use App\Models\TalentoHumano\TbConfiguracionSalarial;
use App\Models\TalentoHumano\TbDependencia;
use App\Models\TalentoHumano\TbTipoContrato;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

/**
 * @author Jorge Hernández 27/04/2020
 * Creando Soluciones Informaticas Ltda
 */
class TbCentroCostoController extends Controller
{

    public function index()
    {
        if (Input::get('per_page')) {
            return Resource::collection(TbCentroConsto::pimp()->paginate(Input::get('per_page')));
        }
        return Resource::collection(TbCentroConsto::pimp()->get());
    }

    public function store(Request $request)
    {
        if (is_null($request->descripcion)) {
            throw new \Exception("El campo descripción es requerido.", 422);
        }

        $centro_costo = new TbCentroConsto();
        $centro_costo->fill($request->all());
        $centro_costo->save();

        return response()->json([
            'data' => collect($centro_costo),
            'msg' => 'se registro éxitosamente.'
        ], 201);
    }

    public function show(TbCentroConsto $tbCentroConsto)
    {
        return collect($tbCentroConsto);
    }

    public function update(Request $request, $tbCentroConsto)
    {
        if (is_null($request->descripcion)) {
            throw new \Exception("El campo descripción es requerido.", 422);
        }

        $centro_costo = TbCentroConsto::find($tbCentroConsto);
        $centro_costo->fill($request->all());
        $centro_costo->save();

        return response()->json([
            'data' => collect($centro_costo),
            'msg' => 'se actualizo éxitosamente.'
        ], 201);
    }

    public function destroy(TbCentroConsto $tbCentroConsto)
    {
        $tbCentroConsto->delete();

        return response()->json([
            'data' => '',
            'msg' => 'se elimino éxitosamente'
        ], 200);
    }

    public function getComTalentoHumano()
    {
        $complementos = collect();
        $complementos->put('centroscostos', TbCentroConsto::all());
        $complementos->put('areas', TbArea::all());
        $complementos->put('configuracionsalarial', TbConfiguracionSalarial::all());
        $complementos->put('tiposcontratos',TbTipoContrato::all());
        $complementos->put('dependencias',TbDependencia::all());
        $complementos->put('cargos',TbCargo::all());
        $complementos->put('complementoscontratos',[
            'estados' => [
                ['id' => 1, 'nombre' => 'Vigente'],
                ['id' => 0, 'nombre' => 'No Vigente']
            ],
            'tiposcotizantes' => [
                ['id' => 1, 'nombre' => 'Tipo 1']
            ],
            'tipliquidaciones' => [
                ['id' => 1, 'nombre' => 'Quincenal'],
                ['id' => 2, 'nombre' => 'Mensual']
            ],
            'tippagos' => [
                ['id' => 0, 'nombre' => 'Completo'],
                ['id' => 1, 'nombre' => 'Medio Mínino'],
                ['id' => 2, 'nombre' => 'Aprendiz Sena Lectivo'],
                ['id' => 3, 'nombre' => 'Aprendiz Sena Productivo']
            ],
            'jornadas' => [
                ['id' => 1, 'nombre' => 'Normal'],
                ['id' => 2, 'nombre' => 'Medio Tiempo']
            ],
            'formasdepagos' => [
                ['id' => 1, 'nombre' => 'Consignación'],
                ['id' => 2, 'nombre' => 'Efectivo'],
                ['id' => 3, 'nombre' => 'Transferencia']
            ],
            'causales_despido' => [
                ['id' => 0, 'nombre' => 'Sin Causal'],
                ['id' => 1, 'nombre' => 'Retiro Voluntario'],
                ['id' => 2, 'nombre' => 'Despido'],
                ['id' => 3, 'nombre' => 'Terminación Contrato']
            ],
            'subtipos_cotizantes' => [
                ['id' => 1, 'nombre' => 1],
                ['id' => 2, 'nombre' => 2],
                ['id' => 3, 'nombre' => 3],
                ['id' => 4, 'nombre' => 4],
                ['id' => 5, 'nombre' => 5],
                ['id' => 6, 'nombre' => 6],
                ['id' => 7, 'nombre' => 'SP']
            ]
        ]);
        return response()->json([
            'data' => $complementos
        ], 200);
    }
}
