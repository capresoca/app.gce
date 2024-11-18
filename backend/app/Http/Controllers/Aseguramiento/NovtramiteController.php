<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\Repositories\Aseguramiento\NovtramiteRepository;
use App\Http\Requests\Aseguramiento\NovtramiteRequest;
use App\Http\Resources\Aseguramiento\NovtramiteResource;
use App\Http\Resources\Aseguramiento\NovtramitesResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\General\GnEmpresa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Log;

class NovtramiteController extends Controller
{
    protected $repoNovtramite;

    public function __construct(NovtramiteRepository $repoNovtramite)
    {
        $this->repoNovtramite = $repoNovtramite;
        //PermiteTrait::checkPermisos($this,11);
    }

    public function index()
    {
        if (Input::get('per_page')) {
            return NovtramitesResource::collection(
                AsNovtramite::with('afiliado', 'novedad', 'tramite')
                    ->pimp()
                    ->orderBy('updated_at', 'desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return NovtramitesResource::collection(AsNovtramite::with('afiliado', 'novedad', 'tramite')->pimp()->orderBy('updated_at', 'desc')->get());

    }

    public function store(NovtramiteRequest $request)
    {
        try {
            //throw new \Exception('No existe vigencia activa para la novedad');
            //Log::info('Datos vector llega: ',array($request));
            $novtramite = $this->repoNovtramite->guardar($request,null);
            if($novtramite==NULL) {
                return response()->json([
                    'message' => 'No existe vigencia para crear la novedad y procesarla',
                    'errors' => 'No existe vigencia para crear la novedad y procesarla',
                ], 500);
            }
            return new NovtramitesResource($novtramite);

        } catch (\Exception $e) {
            Log::info('Errores', array($e->getMessage()));
            DB::rollBack();
            return $e;
        }

    }


    /**
     * @param AsNovtramite $novtramite
     * @return NovtramiteResource
     */
    public function show(AsNovtramite $novtramite)
    {
        
        $novtramite->load([
            'afiliado.municipio',
            'afiliado.sexo',
            'afiliado.etnia',
            'afiliado.poblacion_especial',
            'afiliado.ips',
            'afiliado.afp',
            'afiliado.ccf',
            'novedad',
            'tramite'
        ]);
        return new NovtramiteResource($novtramite);
    }


    public function update(Request $request, AsNovtramite $novtramite)
    {
        if ($request->estado == 'Anulado') {

            if ($novtramite->tramite->as_bduaarchivo_id) {
                return response()->json(
                    [
                        'message' => 'No se puede anular, ya se genero el archivo plano de Novedades'
                    ]
                )->setStatusCode(409);
            }
            $novtramite->tramite->estado = $request->estado;
            $novtramite->tramite->detalle_anulacion = $request->detalle_anulacion;
            $novtramite->tramite->save();
        }

        return response()->json()->setStatusCode(202);
    }

    public function novedades_afiliado(AsAfiliado $afiliado)
    {
        //Log::info('Entra tramite ', array($afiliado));
        $novtramites = $afiliado->novtramites()->with(['tramite.gsUsuario', 'novedad'])->get();
        //Log::info('Lista Tramites: ', array($novtramites));
        return new Resource($novtramites);
    }
    
    public function getPdf(Request $request, $id) {
        try{
            //            return 'algo';

            $nov = AsNovtramite::find($id);
            $nov->load(['tramite']);
            $nov->load(['afiliado']);
            $nov->load(['afiliado.cabeza']);
            
            if($nov->afiliado->cabeza==NULL) {
                $nov->afiliado->cabeza = $nov->afiliado;
            }
            
            //$nov->load(['beneficiarios']);
            
            if(Input::get('html')){
                return view('dompdf.novedades');
            }
            //var_dump($nov); exit;
            // echo $nov->afiliado()->primerNombre; exit;
            $pdf = PDF::loadView('dompdf.novedades', ['novedad' => $nov]);
            $pdf->setPaper('letter', 'portrait');
            //echo 'llave: '. $formafiliacion->id; exit;
            
            
            return $pdf->stream('Formulario de Novedad '.$nov->afiliado->identificacion, ['Attachment' => 0]);
        }catch (\Exception $e){
            return $e;
        }
    }
    
    public function rutaPdf()
    {
        return URL::temporarySignedRoute('pdf_novedad', now()->addMinutes(60), [Input::get('id')]);
        
    }

}



