<?php

namespace App\Http\Controllers\Pagos;

use App\Http\Requests\Pagos\PgAnticipoRequest;
use App\Http\Resources\Pagos\PgAnticipoResource;
use App\Models\Pagos\PgAnticipo;
use App\Models\Pagos\PgAnticipoCxp;
use App\Models\Pagos\PgCxp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;

class PgAnticipoController extends Controller
{

    public function index()
    {
        if(Input::get('per_page')){
            return  Resource::collection(
                PgAnticipoCxp::with('tercero','anticipo','usuario')->pimp()
                    ->orderBy('consecutivo','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return Resource::collection(
            PgAnticipoCxp::with('tercero','anticipo','usuario')->pimp()->orderBy('consecutivo','desc')->get()
        );
    }

    public function store(PgAnticipoRequest $request)
    {
        $pg_anticipo_cxp = new PgAnticipoCxp();
        $pg_anticipo_cxp->fill($request->except('detalles'));
        $pg_anticipo_cxp->save();
    }

    public function show(PgAnticipoCxp $pg_anticipo_cxp)
    {
        return new Resource($pg_anticipo_cxp->load('detalles','anticipo','tercero','cencosto','usuario'));
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function getAnticipos ()
    {
        if(Input::get('per_page'))
        {
            $pg_anticipos = PgAnticipo::with('cencosto','tercero','cuenta','comEgreso','saldoIncial')
                ->pimp()
                ->orderBy('consecutivo', 'desc')
                ->paginate(Input::get('per_page'));
            return PgAnticipoResource::collection($pg_anticipos);
        }
        return PgAnticipoResource::collection(
            PgAnticipo::with('cencosto','tercero','cuenta','comEgreso','saldoIncial')
                ->pimp()->orderBy('consecutivo', 'desc')->get());
    }

    public function getCxpsOfTercero ($idTercero) {
        $cxps = [];
        $pgCxpTerceros = PgCxp::with(
            'cencosto',
            'tercero',
            'proveedor.tercero',
            'proveedor.niif',
            'niif',
            'detalles.conpago.niif.clascuenta',
            'detalles.uniapoyo',
            'detalles.cxp',
            'detalles.tercero',
            'detalles.cencosto',
            'detalles.niif',
            'detalles.conrtf'
        )->whereGnTerceroId($idTercero)->get();
        foreach ($pgCxpTerceros as $cxpTercero) {
            if ($cxpTercero->estado === 'Confirmado') {
                array_push($cxps, $cxpTercero);
            }
        }
        return response()->json([
            'facturas' => new Resource($cxps)
        ], 200);
    }
}
