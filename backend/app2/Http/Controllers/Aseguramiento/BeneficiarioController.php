<?php

namespace App\Http\Controllers\Aseguramiento;

use App\Http\PermiteTrait;
use App\Http\Repositories\Aseguramiento\NucFamiliarRepository;
use App\Http\Requests\Aseguramiento\BeneficiarioRequest;
use App\Http\Resources\Aseguramiento\AsAfiliadosResource;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Aseguramiento\AsAfitramite;
use App\Models\Aseguramiento\AsFormafiliacione;
use App\Models\Aseguramiento\AsNucfamiliare;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class BeneficiarioController extends Controller
{
    protected $repoNucFamiliar;
    public function __construct(NucFamiliarRepository $repoNucFamiliar)
    {
        $this->repoNucFamiliar = $repoNucFamiliar;
        //PermiteTrait::checkPermisos($this,9);
    }

    public function store(BeneficiarioRequest $request)
    {
        try
        {

            if(!$request->id)
            {
                $nuc_familiar = AsNucfamiliare::create($request->all());
            } else
            {
                $nuc_familiar = AsNucfamiliare::findOrFail($request->id);
                $nuc_familiar->update($request->all());

            }

            $afiliado = AsAfiliado::findOrFail($nuc_familiar->as_beneficiario_id);
            $estadoRegistrado = 2;
            $afiliado->estado = $estadoRegistrado;
            $afiliado->save();

            $nuc_familiar->load('parentesco',
                'ips.tercero',
                'beneficiario');


            return response()->json(
                [
                    'nucfamiliar' => $nuc_familiar,
                    'afiliado' => $afiliado
                ], 201
            );

        } catch (ValidationException $e)
        {
            return response()->json([
                'message' => 'Los datos enviados son invalidos',
                'errors' => $e->validator
            ],422);
        } catch (\Exception $e)
        {
            return response()->json([
                'errors' => $e,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(AsNucfamiliare $beneficiario)
    {
        try{
            $afiliado = AsAfiliado::find($beneficiario->as_beneficiario_id);

            $procesoDeRegistro = 1;
            $afiliado->estado = $procesoDeRegistro;
            $afiliado->save();

            $beneficiario->delete();
            return response()->json(
                [
                   'message' => 'El beneficiario se ha removido del tramite'
                ]
            );
        }catch (\Exception $e){
            return response()->json([
                'errors' => $e,
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
