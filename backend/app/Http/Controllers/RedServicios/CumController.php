<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Requests\RedServicios\CumRequest;
use App\Http\Resources\RedServicios\CumResource;
use App\Models\RedServicios\RsCum;
use App\Traits\EnumsTrait;
use App\Traits\ToolsTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CumController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return CumResource::collection(
                RsCum::pimp()
                    ->paginate(Input::get('per_page'))
            );
        }

        return CumResource::collection(RsCum::pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function store(CumRequest $request)
    {
        $cum_data = $request->all();
        $cum_data['expediente_cum'] = ''; //Se agrega la propiedad para el setter.
        $cum_data['codigo'] = '';


        $rs_cum = RsCum::create($cum_data);
        return new CumResource($rs_cum);
    }

    public function show(RsCum $rs_cum)
    {
        return new CumResource($rs_cum);
    }

    public function update(CumRequest $request, RsCum $rs_cum)
    {
        $rs_cum->update($request->all());

        return (new CumResource($rs_cum))->response()->setStatusCode(202);

    }

    public function complementos()
    {

        return response()->json(
            [
                'unidades_referencia' => EnumsTrait::getDistincts('rs_cums','unidad_referencia'),
                'unidad' => EnumsTrait::getDistincts('rs_cums', 'unidad'),
                'unidades_medida' => EnumsTrait::getDistincts('rs_cums','unidad_medida'),
                'vias_administracion' => EnumsTrait::getDistincts('rs_cums','via_administracion'),
                'formas_farmaceticas' => EnumsTrait::getDistincts('rs_cums','forma_farmaceutica'),
                'modalidades' => EnumsTrait::getEnumValues('rs_cums', 'modalidad'),
                'concentraciones' => EnumsTrait::getEnumValues('rs_cums','concentracion'),
                'tipos_rol' => EnumsTrait::getEnumValues('rs_cums','tipo_rol')

            ]
        );
    }

}
