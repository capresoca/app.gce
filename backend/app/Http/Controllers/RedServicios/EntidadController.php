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
use App\Models\Niif\GnTercero;
use App\Models\Aseguramiento\AsFormpagadore;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\ContratacionEstatal\CeAbogado;
use App\Models\ContratacionEstatal\CeAseguradora;
use App\Models\ContratacionEstatal\CeContratista;
use App\Models\ContratacionEstatal\CeInterventore;
use App\Models\ContratacionEstatal\CeProconminuta;
use App\Models\ContratacionEstatal\CeSupervisore;
use App\Models\Cartera\CrCliente;
use App\Models\Cartera\CrConceptoCuentasxcobrar;
use App\Models\Cartera\CrSaldosiniciale;
use App\Models\Cartera\CrVendedore;
use App\Models\General\GnEmpresa;
use App\Models\Niif\NfComdiadetalle;
use App\Models\Pagos\PgAnticipoCxp;
use App\Models\Pagos\PgAnticipo;
use App\Models\Pagos\PgCxpdetalle;
use App\Models\Pagos\PgCxp;
use App\Models\Pagos\PgProveedore;
use App\Models\Presupuesto\PrDependencia;
use App\Models\Presupuesto\PrEntidadResolucione;
use App\Models\Presupuesto\PrObligacione;
use App\Models\Presupuesto\PrOrdenesDePago;
use App\Models\Presupuesto\PrRp;
use App\Models\TalentoHumano\ThEmpleado;
use App\Models\TalentoHumano\ThFondo;
use App\Models\Tesoreria\TsBanco;
use App\Models\Tesoreria\TsCuedispersione;
use App\Models\Tesoreria\TsCompegreso;
use App\Models\Tesoreria\TsRecajadetalle;
use App\Models\Tesoreria\TsRecaja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnMunicipio;

class EntidadController extends Controller
{
    public function index()
    {
        
        if(Input::get('per_page')){
            return EntidadesResource::collection(RsEntidade::pimp()->with('tercero', 'municipio')
                ->orderBy('updated_at','desc')->whereNull('deleted_at')->paginate(Input::get('per_page')));
        }
        return EntidadResource::collection(RsEntidade::with('tercero', 'municipio')->pimp()
            ->whereNull('deleted_at')->orderBy('updated_at','desc')->get());

    }
    
    public function getEntidadesContrato(Request $request) {
        //Log::info('Afiliado: ', array($request->afiliado));
        //Log::info('HOla ', array($request));
       // $afiliado       = AsAfiliado::find($request->afiliado);
        //Log::info('textos', array($afiliado));
        /*$regimen        = $afiliado->as_regimene_id;
        $municipio      = empty($request->municipio) ? $afiliado->gn_municipio_id : $request->municipio;
       
        ->where('gn_municipiosede_id',$municipio)
        ->whereHas('minutas', function($query) use ($regimen) {
            $query->where('ce_tipocontrato_id',1)->whereHas('planes_cobertura', function ($query2) use ($regimen){
             $query2->where('regimen_atendido',$regimen);
             });
        })*/
        
        $registros = EntidadesResource::collection(RsEntidade::pimp()
            ->with('tercero', 'municipio')->orderBy('updated_at','desc')->whereNull('deleted_at')->paginate(Input::get('per_page')));
        //Log::info('Datos: ',array($registros));
        return $registros;
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
        $entidades_query = RsEntidade::Buscar($search, $tipo)->whereNull('deleted_at')
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
    
    public function migrar($nitactual, $nitnuevo) {
        try {
            DB::beginTransaction();
            //return response()->json($nitactual.'-'.$nitnuevo,200);
            $entidadActual    = RsEntidade::find($nitactual);
            $entidadActual->deleted_at      = date('Y-m-d H:i:s');
            $entidadActual->save();
            
            $entidadNueva     = RsEntidade::find($nitnuevo);
            
            DB::update("update as_afiliado_altocostos set rs_entidad_tratante_id = {$nitnuevo} WHERE rs_entidad_tratante_id = {$nitactual}");
            DB::update("update au_anexot31s set pAut = {$nitnuevo} WHERE pAut = {$nitactual}");
            DB::update("update ce_proconminutas set rs_entidad_id = {$nitnuevo},gn_tercero_id = {$entidadNueva->gn_tercero_id}  WHERE rs_entidad_id = {$nitactual}");
            DB::update("update cm_censos set ips_id = {$nitnuevo} WHERE ips_id = {$nitactual}");
            DB::update("update cm_concurrencias set rs_entidad_id = {$nitnuevo} WHERE rs_entidad_id = {$nitactual}");
            DB::update("update cm_concurrencias set rs_entorigen_id = {$nitnuevo} WHERE rs_entorigen_id = {$nitactual}");
            
            DB::update("update cm_conegresos set rs_entidad_destino_id = {$nitnuevo} WHERE rs_entidad_destino_id = {$nitactual}");
            DB::update("update cm_coningresos set rs_entidad_id = {$nitnuevo} WHERE rs_entidad_id = {$nitactual}");
            DB::update("update as_afiliados set rs_entidade_id = {$nitnuevo} WHERE rs_entidade_id = {$nitactual}");
            DB::update("update as_formafiliaciones set rs_ips_id = {$nitnuevo} WHERE rs_ips_id = {$nitactual}");
            DB::update("update as_novtrabeneficiarios set rs_entidade_id = {$nitnuevo} WHERE rs_entidade_id = {$nitactual}");
            DB::update("update as_nucfamiliares set rs_entidade_id = {$nitnuevo} WHERE rs_entidade_id = {$nitactual}");
            DB::update("update au_autorizaciones set entidad_origen_id = {$nitnuevo} WHERE entidad_origen_id = {$nitactual}");
            DB::update("update au_pqrsds set rs_entidad_id = {$nitnuevo} WHERE rs_entidad_id = {$nitactual}");
            DB::update("update au_referencias set entidad_origen_id = {$nitnuevo} WHERE entidad_origen_id = {$nitactual}");
            DB::update("update au_referencias set entidad_egreso_id = {$nitnuevo} WHERE entidad_egreso_id = {$nitactual}");
            DB::update("update au_refpresentaciones set rs_entidades_id = {$nitnuevo} WHERE rs_entidades_id = {$nitactual}");
            DB::update("update au_reftraslados set entidad_origen_id = {$nitnuevo} WHERE entidad_origen_id = {$nitactual}");
            DB::update("update au_reftraslados set entidad_destino_id = {$nitnuevo} WHERE entidad_destino_id = {$nitactual}");
            DB::update("update au_reftraslados set entidad_transporta_id = {$nitnuevo} WHERE entidad_transporta_id = {$nitactual}");
            
            DB::update("update cm_radicados set rs_entidad_id = {$nitnuevo} WHERE rs_entidad_id = {$nitactual}");
            DB::update("update rs_portabilidades set entidad_id = {$nitnuevo} WHERE entidad_id = {$nitactual}");
            DB::update("update rs_capinstaladas set rs_entidades_id = {$nitnuevo} WHERE rs_entidades_id = {$nitactual}");
            DB::update("update rs_cumentidades set rs_entidad_id = {$nitnuevo} WHERE rs_entidad_id = {$nitactual}");
            DB::update("update rs_cupsentidades set rs_entidad_id = {$nitnuevo} WHERE rs_entidad_id = {$nitactual}");
            DB::update("update rs_detredservicios set rs_entidades_id = {$nitnuevo} WHERE rs_entidades_id = {$nitactual}");
            DB::update("update rs_otrosentidades set rs_entidades_id = {$nitnuevo} WHERE rs_entidades_id = {$nitactual}");
            DB::update("update rs_servhabilitados set rs_entidad_id = {$nitnuevo} WHERE rs_entidad_id = {$nitactual}");
            DB::update("update gs_usuarios set rs_entidad_id = {$nitnuevo} WHERE rs_entidad_id = {$nitactual}");
            DB::update("update mp_prescripciones set rs_entidad_id = {$nitnuevo} WHERE rs_entidad_id = {$nitactual}");
            DB::update("update mp_prescripciones set farmacia_id = {$nitnuevo} WHERE farmacia_id = {$nitactual}");
            DB::update("update ts_concepto_notas set rs_entidad_id = {$nitnuevo} WHERE rs_entidad_id = {$nitactual}");
            
            //$consulta   = RsEntidade::where('gn_tercero_id',$entidad[0]->id)->get();
            //return response()->json($entidad[0]->identificacion,200);
            /*$nuevo      = new GnTercero();
            $campos     = $nuevo->getCamposCopiar();
            foreach ($campos as $c) {
                $nuevo->{$c}      = $entidad[0]->{$c};
            }
            $nuevo->identificacion      = $nitnuevo;
            $nuevo->save();*/
            /*foreach ($consulta as $con) {
                $con->gn_tercero_id     = $nuevo->id;
                $con->save();
            }*/
            
            /*RsEntidade::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            AsFormpagadore::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            AsPagadore::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            
            CeAbogado::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            CeAseguradora::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            CeContratista::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            CeInterventore::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            CeProconminuta::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            CeSupervisore::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            CrCliente::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            CrConceptoCuentasxcobrar::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            CrSaldosiniciale::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            CrVendedore::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            GnEmpresa::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            NfComdiadetalle::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            
            PgAnticipoCxp::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            PgAnticipo::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            PgCxpdetalle::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            PgCxp::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            PgProveedore::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            PrDependencia::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            PrEntidadResolucione::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            PrObligacione::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            PrOrdenesDePago::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            PrRp::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            RsEntidade::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            ThEmpleado::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            ThFondo::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            TsBanco::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            TsCompegreso::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            //TsCuedispersione::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
            TsRecajadetalle::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);
             TsRecaja::where('gn_tercero_id', $entidad[0]->id)->update(['gn_tercero_id' => $nuevo->id]);*/
            
            //$entidad[0]->delete();
            //DB::rollBack();
            DB::commit();
            return response()->json('Migracion completa',200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
    
    public function getEntidades(Request $request) {
        Log::info('Datos: ',array($request->idEntidad));
        //$complementos = collect();
        $registros  = DB::select("select id,concat(nombre,' - ',coalesce(cod_habilitacion,'')) as nombre 
                                    FROM rs_entidades where id NOT IN({$request->idEntidad}) AND deleted_at IS NULL ");
        return response()->json($registros,200);
        /*Log::info('Retorna: ',array($registros));
        $complementos->put('entidades', $registros);
        return response()->json([
            'data' => $complementos
        ], 200);*/
        
        //return ($registros);
    }

}
