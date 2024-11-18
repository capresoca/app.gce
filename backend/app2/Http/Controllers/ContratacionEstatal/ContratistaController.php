<?php

namespace App\Http\Controllers\ContratacionEstatal;

use App\Http\Repositories\ContratacionEstatal\ContratistaRepository;
use App\Http\Requests\ContratacionEstatal\ContratistaRequest;
use App\Http\Resources\ContratacionEstatal\ContratistaResource;
use App\Http\Resources\ContratacionEstatal\ContratistasResource;
use App\Models\ContratacionEstatal\CeContratista;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;

class ContratistaController extends Controller
{
    protected $repoContratista;

    public function __construct(ContratistaRepository $repoContratista)
    {
        $this->repoContratista = $repoContratista;
    }

    public function index()
    {
        if(Input::get('per_page')){
            return ContratistasResource::collection(
                CeContratista::with('tercero', 'muncComercio', 'natJuridica')->pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return ContratistaResource::collection(CeContratista::with('tercero', 'muncComercio', 'natJuridica')->pimp()->orderBy('updated_at','desc')->get());
    }

    public function search($per_page = 15, $search = '')
    {
        $query = CeContratista::Buscar($search)
            ->with(['tercero'])
            ->orderBy('updated_at', 'desc');

        if($per_page == 0){
            return ContratistaResource::collection($query->get());
        }

        return ContratistasResource::collection($query->paginate($per_page));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $ce_contratistaRequest = $request->toArray();
            $this->repoContratista->validar($ce_contratistaRequest);
            $ce_contratista = $this->repoContratista->guardar($ce_contratistaRequest);
            DB::commit();
            return response()->json([
                'message' => 'El contratista fue guardado con exito',
                'contratista' => new ContratistasResource($ce_contratista)
            ],201);
        } catch (ValidationException $e)
        {
            DB::rollBack();
            return response()->json([
                'message' => 'Los datos enviados son invalidos',
                'errors' => $e->validator
            ],422);
        } catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'errors' => $e->getTrace(),
                'message' => 'Error en el servidor'
            ], 500);
        }
//        $ce_contratista = CeContratista::create($request->all());
//        $ce_contratista->load('tercero', 'muncComercio', 'natJuridica');
//        return new ContratistasResource($ce_contratista);
    }


    public function show(CeContratista $ce_contratista)
    {
        $contratista = $ce_contratista->load('tercero', 'muncComercio', 'natJuridica');
        return new ContratistaResource($contratista);
    }

    public function findByTerceroId($tercero_id){
        $ce_contratista = CeContratista::where('gn_tercero_id',$tercero_id)->first();

        if($ce_contratista){
            return new ContratistaResource($ce_contratista);
        }

        return response()->json([
            'message' => 'Este tercero no esta registrado como contratista.'
        ],204);
    }
}
