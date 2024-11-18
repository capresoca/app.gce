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
            $novtramite = $this->repoNovtramite->guardar($request);
            return new NovtramitesResource($novtramite);

        } catch (\Exception $e) {
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
        $novtramites = $afiliado->novtramites()->with(['tramite.gsUsuario', 'novedad'])->get();

        return new Resource($novtramites);
    }

}



