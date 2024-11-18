<?php

namespace App\Http\Controllers\Niif;

use App\Http\PermiteTrait;
use App\Http\Repositories\TerceroRepository;
use App\Http\Requests\General\TerceroRequest;
use App\Http\Resources\General\GnTerceroResource;
use App\Http\Resources\General\GnTercerosResource;
use App\Models\Niif\GnTercero;
use App\Traits\ToolsTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class TerceroController extends Controller
{
    protected $repoTercero;

    public function __construct(TerceroRepository $repoTercero)
    {
        $this->repoTercero = $repoTercero;
        //PermiteTrait::checkPermisos($this,1);
    }

    public function index()
    {

        if(Input::get('per_page')){
            return GnTercerosResource::collection(
                GnTercero::pimp()
                    ->orderBy('updated_at','desc')
                    ->paginate(Input::get('per_page'))
            );
        }
        return GnTerceroResource::collection(GnTercero::pimp()->orderBy('updated_at','desc')->limit(1000)->get());

    }

    public function search($per_page = 15, $search = '')
    {
        $terceros = GnTercero::Buscar($search)
            ->orderBy('updated_at')
            ->paginate($per_page);



        return GnTercerosResource::collection($terceros);
    }


    public function store(Request $request)
    {
        try
        {
            $terceroArray = $request->all();
            $this->repoTercero->validar($terceroArray);

            $tercero = $this->repoTercero->guardar($terceroArray);

            return response()->json([
                'message' => 'El tercero fue guardado con exito',
                'tercero' => new GnTerceroResource($tercero)
            ],201);

        } catch (ValidationException $e)
        {
            return response()->json([
                'errors' => $e->validator,
                'message' => 'Lo datos enviados son invalidos'
            ],422);
        } catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Se presento un error en el servidor',
                'trace' => $e->getMessage()
            ], 500);
        }

    }


    public function show(GnTercero $tercero)
    {
        return new GnTerceroResource($tercero);
    }


    public function update(Request $request, $id)
    {
        try
        {
            $this->repoTercero->validar($request->all());

            $tercero = $this->repoTercero->guardar($request->all(), $id);

            return response()->json([
                'message' => 'El tercero fue editado con exito',
                'data' => $tercero
            ],201);

        } catch (ValidationException $e)
        {
            return response()->json([
                'errors' => $e->validator,
                'message' => 'Lo datos enviados son invalidos'
            ],422);

        } catch (\Exception $e)
        {
            return response()->json([
                'message' => 'Se presento un error en el servidor',
                'errors' => $e->getMessage()
            ], 500);
        }

    }


    public function exists(Request $request){

        $rules = [];

        if($request->id){
            $rules['identificacion'] = [Rule::unique('gn_terceros')->ignore($request->id)];
        }else{
            $rules['identificacion'] = ['unique:gn_terceros'];
        }
        $validador = Validator::make($request->all(),$rules);

        if($validador->fails()){
            $tercero = GnTercero::where('identificacion', $request->identificacion)->first();

            return response()->json([
                'exists' => true,
                'message'=> 'Este documento de identificación ya se encuentra registrado',
                'tercero' => $tercero
            ],200);
        }

        $dv = ToolsTrait::calcularDigitoVerificacion($request->identificacion);

        return response()->json([
            'dv' => $dv,
            'exists' => false,
            'message'=> 'El tercero no se encuentra registrado'
        ],200);
    }

    public function getTercerosConRps ()
    {
        try {
            $terceros = GnTercero::whereHas('rps', function ($query) {
                $query->where('estado','Confirmado');
            })->get();

            return response()->json([
                'msg' => $terceros->count() ? 'Terceros con rps' : 'Aún no se ha contemplado registros.',
                'data' => new Resource($terceros)
            ], 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function getTercerosConObligaciones ()
    {
        try {
            $terceros = GnTercero::whereHas('obligaciones', function ($query) {
                $query->where('estado','Confirmada');
            })->get();

            return response()->json([
                'msg' => $terceros->count() ? 'Terceros con obligaciones.' : 'Aún no se ha contemplado registros.',
                'data' => new Resource($terceros)
            ], 200);

        } catch (\Exception $exception) {
            return $exception;
        }
    }
}
