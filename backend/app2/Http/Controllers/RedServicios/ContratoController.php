<?php

namespace App\Http\Controllers\RedServicios;

use App\Http\Requests\RedServicios\ContratoRequest;
use App\Http\Requests\RedServicios\CupsContratoRequest;
use App\Http\Requests\RedServicios\NovcontratoRequest;
use App\Http\Resources\RedServicios\ContratoResource;
use App\Imports\CumsContratoImport;
use App\Imports\CupsContratoImport;
use App\Imports\OtrosContratoImport;
use App\Models\Autorizaciones\Refcup;
use App\Models\ContratacionEstatal\CeProconminuta;
use App\Models\Niif\GnTercero;
use App\Models\RedServicios\RsPlanescobertura;
use App\Models\RedServicios\RsCum;
use App\Models\RedServicios\RsCumcontratados;
use App\Models\RedServicios\RsCups;
use App\Models\RedServicios\RsCupscontratados;
use App\Models\RedServicios\RsCupsentidade;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsOtroscontratado;
use App\Models\RedServicios\RsOtrosentidade;
use App\Models\RedServicios\RsSalminimo;
use function Aws\map;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class ContratoController extends Controller
{
    public function index()
    {
        if(Input::get('per_page')){
            return  ContratoResource::collection(
                RsPlanescobertura::with('contrato.entidad','regimen')->pimp()
                    ->orderBy('updated_at')
                    ->paginate(Input::get('per_page'))
            );
        }

        return Resource::collection(
            RsPlanescobertura::with('servicios_contratados.servicio', 'contrato.entidad')->pimp()->get()
        );
    }

    public function update(ContratoRequest $request, RsPlanescobertura $contrato)
    {
        $contrato->update($request->all());
        $contrato->load(
            [
                'servicios_generales',
                'servicios_primarios',
                'contrato.entidad.servicios_habilitados',
                'contrato.entidad.servicios_generales',
                'contrato.entidad.servicios_primarios',
                'contrato.rp.detalles.rubro'
            ]
        );
        return (new Resource($contrato))->response()->setStatusCode(202);
    }

    public function store(ContratoRequest $request)
    {
        $contrato = RsPlanescobertura::create($request->all());
        $contrato->load(['servicios_generales','servicios_primarios', 'contrato.entidad.servicios_habilitados',
            'contrato.entidad.servicios_generales', 'contrato.entidad.servicios_primarios','contrato.rp.detalles.rubro']);

        return (new Resource($contrato))->response()->setStatusCode(201);
    }


    public function show(RsPlanescobertura $contrato)
    {
        $contrato->load(['servicios_generales','servicios_primarios', 'contrato.entidad.servicios_habilitados',
            'contrato.entidad.servicios_generales', 'contrato.entidad.servicios_primarios','contrato.rp.detalles.rubro']);
        return new Resource($contrato);
    }

    public function addServicios(RsPlanescobertura $contrato, Request $request)
    {
        $servicios = array();
        foreach ($request->servicios as $servicio) {
            $servicios[$servicio['id']] = [
                'porcentaje_afiliados' => $servicio['porcentaje'],
                'porcentaje_contratado' => $servicio['porcentaje_contratado']
            ];
        }
        $contrato->servicios()->syncWithoutDetaching($servicios);

        return response()->json('servicios sincronizados correctamente',201);
    }

    public function addMunicipios(RsPlanescobertura $contrato, Request $request)
    {
        $contrato->municipios()->sync($request->municipios);

        return response()->json('Municipios sincronizados correctamente',201);
    }

    public function addCups(Request $request)
    {
        $request->validate([
            'rs_cups_id'        => 'required|exists:rs_cupss,id',
            'rs_contrato_id'    => 'required|exists:rs_planescoberturas,id',
            'tarifa'            => 'required'
        ]);

        $cup = RsCups::findOrFail($request->rs_cups_id);

        $cupContratado = RsCupscontratados::updateOrCreate(
            [
                'rs_cups_id'    => $request->rs_cups_id,
                'rs_contrato_id'=> $request->rs_contrato_id
            ],
            [
                'tarifa'        => $request->tarifa,
                'codigo'        => $cup->codigo,
                'descripcion'   => $cup->descripcion,
                'tarifa_entidad'=> $request->tarifa_entidad,
                'tipo_valor'    => $cup->tipo_pago,
                'nivel_autorizacion'=>  $cup->nivel_autorizacion
            ]
        );

        $this->createOrUpdateRefCups(['cup' => $cup, 'contratado' => $cupContratado],1);

        return response()->json(['Agregado correctamente'],201);
    }

    public function addCupsMasivo($contrato, CupsContratoRequest $request)
    {
        $salminimo = RsSalminimo::find($request->rs_salminimo_id);
        foreach ($request->cups as $cup) {

            $rsCupentidad = RsCupsentidade::findOrFail($cup);

            $rsCup = $rsCupentidad->cup;

            RsCupscontratados::updateOrCreate(
                [
                    'rs_cups_id'    => $rsCup->id,
                    'rs_contrato_id'=> $contrato
                ],
                [
                    'tarifa'            => $rsCup->calcularValor($request->tipo_manual,$salminimo,$request->porcentaje,$request->tarifa),
                    'codigo'            => $rsCup->codigo,
                    'descripcion'       => $rsCup->descripcion,
                    'tarifa_entidad'    => $rsCupentidad->tarifa_entidad,
                    'tipo_valor'        => $rsCup->tipo_valor,
                    'nivel_autorizacion'=> $rsCup->nivel_autorizacion,
                    'rs_salminimo_id'   => $request->rs_salminimo_id,
                    'tipo_manual'       => $request->tipo_manual,
                    'porcentaje'        => $request->porcentaje
                ]
            );
        }

        return response()->json(['Agregado correctamente'],201);
    }


    public function addCums(Request $request)
    {
        $request->validate([
            'rs_cum_id' => 'required|exists:rs_cums,id',
            'rs_contratos_id' => 'required|exists:rs_planescoberturas,id',
        ]);

        $cum = RsCum::findOrFail($request->rs_cum_id);


        $cumContratado = RsCumcontratados::updateOrCreate(
            ['rs_cum_id' => $request->rs_cum_id,'rs_contratos_id' => $request->rs_contratos_id],
            [
                'tarifa'            =>  $request->tarifa,
                'codigo'            =>  $cum->codigo,
                'descripcion'       =>  $cum->producto.' - '.$cum->titular,
                'nombre'            =>  $cum->descripcion_atc.' - '.$cum->principio_activo.' - ADMINISTRACIÓN '.$cum->via_administracion. ' - '.$cum->descripcion_comercial,
                'tarifa_entidad'    =>  $request->tarifa_entidad,
                'tipo_valor'        =>  $cum->tipo_valor
            ]
        );

        $this->createOrUpdateRefCups(['cum' => $cum, 'contratado' => $cumContratado],2);

        return response()->json(['Agregado correctamente'],201);
    }

    public function addOtro(Request $request)
    {
        $request->validate(
            [
                'rs_otrosentidad_id' => 'required|exists:rs_otrosentidades,id',
                'rs_contratos_id'    => 'required|exists:rs_planescoberturas,id'
            ]
        );

        $otroEntidad = RsOtrosentidade::findOrFail($request->rs_otrosentidad_id);

        $otroContratado = RsOtroscontratado::updateOrCreate(
            [
                'rs_otrosentidad_id' => $request->rs_otrosentidad_id,
                'rs_contratos_id'    => $request->rs_contratos_id
            ],
            [
                'tarifa'        =>  $request->tarifa,
                'codigo'        =>  $otroEntidad->codigo,
                'descripcion'        =>  $otroEntidad->nombre,
                'tarifa_entidad'=>  $otroEntidad->tarifa,
                'tipo_valor'    => $otroEntidad->tipo_valor
            ]
        );

        $this->createOrUpdateRefCups(['otro' => $otroEntidad, 'contratado' => $otroContratado],3);

        return response()->json(['Agregado correctamente'],201);
    }


    public function getCums($contrato_id)
    {
        $query =  RsCumcontratados::where('rs_contratos_id',$contrato_id)->pimp()->with('cum');

        if($per_page = Input::get('per_page')){
            return Resource::collection($query->paginate($per_page));
        }

        return Resource::collection($query->get());

    }

    public function getCups($contrato_id)
    {
        $query =  RsCupscontratados::where('rs_contrato_id',$contrato_id)->pimp()->with('cup');

        if($per_page = Input::get('per_page')){
            return Resource::collection($query->paginate($per_page));
        }

        return Resource::collection($query->get());

    }

    public function getOtros($contrato_id)
    {
        $query =  RsOtroscontratado::where('rs_contratos_id',$contrato_id)->pimp();

        if($per_page = Input::get('per_page')){
            return Resource::collection($query->paginate($per_page));
        }

        return Resource::collection($query->get());

    }


    public function removeCums(RsCumcontratados $cumcontratado){
        $cumcontratado->delete();
        return response()->json()->setStatusCode('204');
    }

    public function removeCups(RsCupscontratados $cupcontratado){
        $cupcontratado->delete();
        return response()->json()->setStatusCode('204');
    }

    public function removeOtro(RsOtroscontratado $otroscontratado)
    {
        $otroscontratado->delete();
        return response()->json()->setStatusCode('204');
    }


    public function importCups(RsPlanescobertura $contrato)
    {
        $import = new CupsContratoImport($contrato);
        Excel::import($import, request()->file('archivo'));

        $informeImportacion = $import->getInforme();

        if(!$informeImportacion['valido']){
            return response()->json('Estructura invalida',422);
        }

        return response()->json($informeImportacion,200);

    }

    public function importOtros(RsPlanescobertura $contrato)
    {
        $import = new OtrosContratoImport($contrato);

        Excel::import($import, request()->file('archivo'));

        $informeImportacion = $import->getInforme();

        if(!$informeImportacion['valido']){
            return response()->json('Estructura invalida',422);
        }

        return response()->json($informeImportacion,200);

    }

    public function importCums(RsPlanescobertura $contrato)
    {
        $import = new CumsContratoImport($contrato);
        Excel::import($import, request()->file('archivo'));

        $informeImportacion = $import->getInforme();

        if(!$informeImportacion['valido']){
            return response()->json('Estructura invalida',422);
        }

        return response()->json($informeImportacion,200);
    }

    public function destroy(RsPlanescobertura $contrato)
    {
        try{
            $contrato->delete();
            return response()->setStatusCode(202);
        }catch (\Exception $e){
            return $e;
        }
    }

    public function getContratosTercero (GnTercero $tercero)
    {
        $contratos = CeProconminuta::where('gn_tercero_id', $tercero)->get();
//        $contratos
         
        $contratos->fore;
        forEach ($contratos as $key => $contrato) {

        }
    }

    private function createOrUpdateRefCups ($data, $num) {
        $item = ($num == 1) ? $data['cup'] : ($num === 2 ? $data['cum'] : $data['otro']);
        $contrato = $data['contratado'];
        $insert = [
            'codigo'   => $item['codigo'],
            'homologo' => $item['codigo'],
            'descrip'  => ($num === 2) ? $item['producto'] : ($num == 1 ? $item['descripcion'] : $item['nombre']),
            'genero'   => 'A',
            'ambito'   => 1,
            'lInf'     => 0,
            'valor'    => $contrato['tarifa_entidad'],
            'lSup'     => 0,
            'copago'   => $contrato['tipo_valor'] === 'Copago' ? 1 : '',
            'AT'       => ($num == 1) ? 'P' : ($num === 2 ? 'M' : '1')
        ];
        $refcup = Refcup::where('codigo',$item['codigo'])->first();
        if (! isset($refcup)) {
            Refcup::create($insert);
        }
    }
}
