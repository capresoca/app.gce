<?php

namespace App\Http\Controllers\CuentasMedicas;

use App\Http\Controllers\Controller;
use App\Http\Requests\CuentasMedicas\RespuestasGlosasRequest;
use App\Http\Resources\CuentasMedicas\RespuestasGlosasResource;
use App\Models\CuentasMedicas\CmRespuestasGlosa;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;

class RespuestasGlosasController extends Controller
{

    public function index()
    {
        if (Input::get('per_page')) {
            return RespuestasGlosasResource::collection(
                CmRespuestasGlosa::pimp()->orderBy('updated_at', 'desc')
                    ->paginate(Input::get('per_page')));
        }

        return RespuestasGlosasResource::collection(
            CmRespuestasGlosa::pimp()->orderBy('updated_at', 'desc')->get());
    }

    public function store(RespuestasGlosasRequest $request)
    {
        try {
            DB::beginTransaction();



            DB::commit();

        } catch (QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'errors' => [
                    [
                        'code' => $ex->getCode(),
                        'message' => $ex->getMessage()
                    ]
                ],
                'message' => 'Ocurrió un error al intentar crear el formulario'
            ], 400);
        } catch (ValidationException $ex) {
            DB::rollBack();
            return response()->json([
                'errors' => $ex->getMessage(),
                'message' => 'Hay errores en el formulario que debe corregir antes de poder crearse'
            ], 422);
        } catch (\Exception $ex) {
            DB::rollBack();
            if ($ex->getCode() == 0)
                abort(500, 'Ocurrió un error desconocido, póngase en contacto con el desarrollador. (Excepción: ' . $ex->getMessage() . ')');
            abort($ex->getCode(), $ex->getMessage());
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
