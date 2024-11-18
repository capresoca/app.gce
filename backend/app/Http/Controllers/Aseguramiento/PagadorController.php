<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Capresoca\LeerCsv;
use App\Http\PermiteTrait;
use App\Http\Repositories\Aseguramiento\PagadorRepository;
use App\Http\Resources\Aseguramiento\AsPagadoresResource;
use App\Http\Resources\Aseguramiento\AsPagadorResource;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\Aseguramiento\AsTipaportante;
use App\Models\Niif\GnTercero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;
use Jedrzej\Searchable\Constraint;
use League\Csv\Reader;

class PagadorController extends Controller
{
    protected $repoPagador;
    use LeerCsv;

    /**
     * PagadorController constructor.
     * @param PagadorRepository $repoPagador
     */
    public function __construct(PagadorRepository $repoPagador)
    {
        $this->repoPagador = $repoPagador;
        PermiteTrait::checkPermisos($this,13);
    }

    public function index(){

        if(Input::get('per_page')){
            return AsPagadoresResource::collection(
                AsPagadore::pimp()
                    ->paginate(Input::get('per_page'))
            );
        }
        return AsPagadorResource::collection(AsPagadore::with('tercero')->pimp()->orderBy('updated_at','desc')->get());

    }


    public function search($per_page = 15, $search = '')
    {
        $query = AsPagadore::Buscar($search)
            ->with(['tercero'])
            ->orderBy('updated_at', 'desc');

        if($per_page == 0){
            return AsPagadorResource::collection($query->get());
        }

        return AsPagadoresResource::collection($query->paginate($per_page));
    }


    public function show(AsPagadore $pagadore){
        $pagadore->load('tercero');
        return new AsPagadorResource($pagadore);
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $pagadorRequest = $request->toArray();
            $this->repoPagador->validar($pagadorRequest);
            $pagador = $this->repoPagador->guardar($pagadorRequest);
            DB::commit();
            return response()->json([
                'message' => 'El pagador fue guardado con exito',
                'pagador' => new AsPagadoresResource($pagador)
            ],201);

        }catch (ValidationException $e)
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
    }

    public function findByTerceroId($tercero_id){
        $pagador = AsPagadore::where('gn_tercero_id',$tercero_id)->first();

        if($pagador){
            return new AsPagadorResource($pagador);
        }

        return response()->json([
            'message' => 'Este tercero no esta registrado como pagador'
        ],204);
    }


}
