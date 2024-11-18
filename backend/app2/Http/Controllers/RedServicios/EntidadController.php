<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Requests\RedServicios\EntidadRequest;
use App\Http\Requests\RedServicios\ServhabilitaRequest;
use App\Http\Resources\RedServicios\EntidadesResource;
use App\Http\Resources\RedServicios\EntidadResource;
use App\Imports\CumsEntidadImport;
use App\Imports\CupsEntidadImport;
use App\Imports\OtrosEntidadImport;
use App\Models\RedServicios\RsCapinstalada;
use App\Models\RedServicios\RsCumentidade;
use App\Models\RedServicios\RsCupsentidade;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class EntidadController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return EntidadesResource::collection(RsEntidade::pimp()->with('tercero', 'municipio')
                ->orderBy('updated_at','desc')->paginate(Input::get('per_page')));
        }
        return EntidadResource::collection(RsEntidade::with('tercero', 'municipio')->pimp()
            ->orderBy('updated_at','desc')->get());

    }

    public function store(EntidadRequest $request)
    {
        $entidad = RsEntidade::create($request->all());
        $entidad->load('tercero', 'tipo','municipio','servicios_habilitados','servicios_primarios','servicios_generales','procedimientos','capinstaladas.tipo');
        return response()->json(
            [
                'entidad' => new EntidadResource($entidad),
                'entidad_o' => new EntidadesResource($entidad)
            ], 201
        );
    }

    public function show(RsEntidade $entidade)
    {
        $entidade->load('tercero', 'tipo','municipio','servicios_habilitados','servicios_primarios','servicios_generales','procedimientos','capinstaladas.tipo');
        return new EntidadResource($entidade);
    }


    public function update(EntidadRequest $request, RsEntidade $entidade )
    {
        $entidade->update($request->all());
        $entidade->load('tercero', 'tipo','municipio','servicios_habilitados','servicios_primarios','servicios_generales','procedimientos','capinstaladas.tipo');
        return response()->json(
            [
                'entidad' => new EntidadResource($entidade),
                'entidad_o' => new EntidadesResource($entidade)
            ], 202
        );
    }


    public function search($tipo = null,$per_page = 15, $search = '')
    {
        $entidades_query = RsEntidade::Buscar($search, $tipo)
            ->with('tercero')
            ->orderBy('updated_at','desc');

        if($per_page == 0){
            return EntidadResource::collection($entidades_query->get());
        }
        return EntidadesResource::collection($entidades_query->paginate($per_page));
    }


    public function habilitarServicios(RsEntidade $entidad, ServhabilitaRequest $request)
    {
        $entidad->servicios_habilitados()->sync($request->servicios);
        return response()->json('servicios habilitados correctamente',200);
    }


    public function addCups(Request $request)
    {
        $request->validate([
            'rs_cups_id' => 'required|exists:rs_cupss,id',
            'rs_entidad_id' => 'required|exists:rs_entidades,id',
        ]);

        RsCupsentidade::updateOrCreate(
            ['rs_cups_id' => $request->rs_cups_id,'rs_entidad_id' => $request->rs_entidad_id],
            ['tarifa' => $request->tarifa]
        );

        return response()->json(['Agregado correctamente'],201);
    }


    public function addCums(Request $request)
    {
        $request->validate([
            'rs_cum_id' => 'required|exists:rs_cums,id',
            'rs_entidad_id' => 'required|exists:rs_entidades,id',
        ]);

        RsCumentidade::updateOrCreate(
            ['rs_cum_id' => $request->rs_cum_id,'rs_entidad_id' => $request->rs_entidad_id],
            ['tarifa' => $request->tarifa]
        );

        return response()->json(['Agregado correctamente'],201);
    }

    public function addCapinstalada(Request $request)
    {
        $request->validate([
            'rs_tipcapinstalada_id' => 'required|exists:rs_tipcapinstaladas,id',
            'rs_entidades_id' => 'required|exists:rs_entidades,id',
        ]);
        $capinstalada = RsCapinstalada::updateOrCreate(
            ['rs_tipcapinstalada_id' => $request->rs_tipcapinstalada_id,'rs_entidades_id' => $request->rs_entidades_id],
            ['cantidad' => $request->cantidad]
        );

        $capinstalada->load('tipo');

        return (new Resource($capinstalada))->response()->setStatusCode('201');

    }


    public function removeCums(RsCumentidade $cumentidade){
        $cumentidade->delete();
        return response()->json()->setStatusCode('204');
    }


    public function removeCups(RsCupsentidade $cupentidade){
        $cupentidade->delete();
        return response()->json()->setStatusCode('204');
    }

    public function removeCapinstalada(RsCapinstalada $capinstalada)
    {
        $capinstalada->delete();
        return response()->json()->setStatusCode('204');
    }


    public function getCums($entidad_id)
    {
        $query =  RsCumentidade::where('rs_entidad_id',$entidad_id)->pimp()->with('cum');

        if($per_page = Input::get('per_page')){
            return Resource::collection($query->paginate($per_page));
        }

        return Resource::collection($query->get());

    }

    public function getCups($entidad_id)
    {
        $query =  RsCupsentidade::where('rs_entidad_id',$entidad_id)->pimp()->with('cup.parent.parent.parent','cup.maniss','cup.soat');

        if($per_page = Input::get('per_page')){
            return Resource::collection($query->paginate($per_page));
        }

        return Resource::collection($query->get());

    }

    public function getOtros(RsEntidade $entidade)
    {
        $query =  $entidade->otros()->pimp();

        if($per_page = Input::get('per_page')){
            return Resource::collection($query->paginate($per_page));
        }

        return Resource::collection($query->get());

    }

    public function importCups(RsEntidade $entidade)
    {
        $import = new CupsEntidadImport($entidade);
        Excel::import($import, request()->file('archivo'));

        $informeImportacion = $import->getInforme();

        if(!$informeImportacion['valido']){
            return response()->json('Estructura invalida',422);
        }

        return response()->json($informeImportacion,200);

    }

    public function importCums(RsEntidade $entidade)
    {
        $import = new CumsEntidadImport($entidade);
        Excel::import($import, request()->file('archivo'));

        $informeImportacion = $import->getInforme();


        if(!$informeImportacion['valido']){
            return response()->json('Estructura invalida',422);
        }

        return response()->json($informeImportacion,200);

    }

    public function importOtros(RsEntidade $entidade)
    {
        $import = new OtrosEntidadImport($entidade);
        Excel::import($import, request()->file('archivo'));
        $informeImportacion = $import->getInforme();

        if(!$informeImportacion['valido']){
            return response()->json('Estructura invalida',422);
        }

        return response()->json($informeImportacion,200);

    }

}
