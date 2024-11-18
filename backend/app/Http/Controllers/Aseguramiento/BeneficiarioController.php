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
use Illuminate\Support\Facades\Log;
use App\Models\Aseguramiento\BDUA\AfAfiliadoMs;

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
            
            //Log::info('Beneficiarios: ', array($request));
            //exit;

            if(!$request->id)
            {
                $nuc_familiar = AsNucfamiliare::create($request->all());
            } else
            {
                $nuc_familiar = AsNucfamiliare::findOrFail($request->id);
                $nuc_familiar->update($request->all());
            }

            $afiliado = AsAfiliado::findOrFail($nuc_familiar->as_beneficiario_id);
            
            $ams = AfAfiliadoMs::where('consecutivo_afiliado',$nuc_familiar->as_beneficiario_id)->where('tipo_traslado','S')->first();
            
            Log::info('Objeto Af ms: ', array($ams));
            
            if(!empty($ams)) {
                $ams->tipo_traslado = 'MS';
                $ams->save();
            }
            
            $formularioA    = AsFormafiliacione::where('as_afiliado_id',$nuc_familiar->as_beneficiario_id)->first();
            
            if(empty($formularioA)) {
                $formularioC    = AsFormafiliacione::find($nuc_familiar->as_formafiliacion_id)->first();
                $formularioA    = new AsFormafiliacione();
                foreach($formularioC->getFillable() as $campo) {
                    $formularioA->{$campo}          = $formularioC->{$campo};
                }
                $formularioA->as_afiliado_id        = $nuc_familiar->as_beneficiario_id;
                $formularioA->rs_ips_id             = $nuc_familiar->rs_entidade_id;
                $afiliado->rs_entidade_id           = $nuc_familiar->rs_entidade_id;
                $formularioA->as_regimene_id        = $afiliado->as_regimene_id;
                $formularioA->as_tipafiliado_id     = $afiliado->as_tipafiliado_id;
                
                $formularioA->ibc                   = $afiliado->ibc;
                $formularioA->ficha_sisben          = $afiliado->ficha_sisben;
                $formularioA->puntaje_sisben        = $afiliado->puntaje_sisben;
                
                $formularioA->save();
            }
            
            //as_formafiliaciones
            
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
