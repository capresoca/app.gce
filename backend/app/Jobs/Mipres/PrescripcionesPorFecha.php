<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13/02/2019
 * Time: 4:56 PM
 */

namespace App\Jobs\Mipres;



use App\Capresoca\AfiliadoTrait;
use App\Models\General\GnEmpresa;
use App\Models\Mipres\MpConfig;
use App\Models\Mipres\MpMedicamentos;
use App\Models\Mipres\MpPrescripcione;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsEntidadesBase;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrescripcionesPorFecha
{
    protected $fecha;
    protected $regimen;
    protected $response;

    use AfiliadoTrait;
    /**
     * PrescripcionesPorFecha constructor.
     * @param $fecha
     * @param $regimen
     * @throws GuzzleException
     */
    public function __construct($fecha, $regimen)
    {
        try{

            $this->fecha = $fecha;
            $this->regimen = $regimen;
            $this->getPrescripcionesFromApi();
        }catch (GuzzleException $e) {
            throw $e;
        }
    }

    public function getPrescripciones()
    {
        return json_decode($this->response->getBody()->getContents(),true);
    }

    public function getStatus()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @throws GuzzleException
     */
    private function getPrescripcionesFromApi()
    {
        try{

            $configuracionMipres = MpConfig::first();


            $empresa = GnEmpresa::first();
            $nit = $empresa->tercero->identificacion;
            $clienteMipres = new MipresClient();

            $this->response = $clienteMipres->request('GET',
                $configuracionMipres->base_url.'Prescripcion/'
                .$nit.'/'.$this->fecha.'/'.$configuracionMipres->getToken($this->regimen));

        }catch (GuzzleException $e) {
            throw $e;
        }

    }

    public function store()
    {
        foreach ($this->getPrescripciones() as $prescripcion) {
            $prescripcionExists = MpPrescripcione::where([
                'NoPrescripcion'    => $prescripcion['prescripcion']['NoPrescripcion'],
                'FPrescripcion'     => $prescripcion['prescripcion']['FPrescripcion'],
                'CodHabIPS'         => $prescripcion['prescripcion']['CodHabIPS'],
            ])->first();



            if($prescripcionExists) continue;

            $entidad = RsEntidade::where('cod_habilitacion', $prescripcion['prescripcion']['CodHabIPS'])->first();


            $ipsReps = RsEntidadesBase::where('codigo',$prescripcion['prescripcion']['CodHabIPS'])->first();

            if($entidad){
                $prescripcion['prescripcion']['rs_entidad_id'] = $entidad->id;
            }

            if($ipsReps){
                $prescripcion['prescripcion']['ips'] = $ipsReps->nombre;
            }

            try{
                $afiliado = $this->getAfiliado(
                    $prescripcion['prescripcion']['TipoIDPaciente'],
                    $prescripcion['prescripcion']['NroIDPaciente']
                );

                $afiliadoId = $afiliado->id;
            }catch (\Exception $e){
                $afiliadoId = null;
            }

            $prescripcion['prescripcion']['as_afiliado_id'] = $afiliadoId;
            $prescripcion['prescripcion']['paciente'] = $this->getNombrePaciente($prescripcion['prescripcion']);
            $prescripcion['prescripcion']['regimen'] = $this->regimen;

            try{
                DB::beginTransaction();

                $prescripcionGuardada = MpPrescripcione::create($prescripcion['prescripcion']);

                foreach ($prescripcion['medicamentos'] as $medicamento){

                    $medicamentoGuardado = $prescripcionGuardada->medicamentos()->create($medicamento);

                    $medicamentoGuardado->PrincipiosActivos()->createMany($medicamento['PrincipiosActivos']);

                    $medicamentoGuardado->IndicacionesUNIRS()->createMany($medicamento['IndicacionesUNIRS']);

                }

                $prescripcionGuardada->procedimientos()->createMany($prescripcion['procedimientos']);

                $prescripcionGuardada->dispositivos()->createMany($prescripcion['dispositivos']);

                $prescripcionGuardada->nutricionales()->createMany($prescripcion['productosnutricionales']);

                $prescripcionGuardada->complementarios()->createMany($prescripcion['serviciosComplementarios']);

                DB::commit();
            }catch (\Exception $e){
                Log::error($e);
                DB::rollBack();
            }

        }
    }

    private function getNombrePaciente($prescripcion)
    {
        $nombre_completo = $prescripcion['PNPaciente'];
        if($prescripcion['SNPaciente'])
            $nombre_completo .= ' '.$prescripcion['SNPaciente'];

        if($prescripcion['PAPaciente'])
            $nombre_completo .= ' '.$prescripcion['PAPaciente'];

        if($prescripcion['SAPaciente'])
            $nombre_completo .= ' '.$prescripcion['SAPaciente'];

        return $nombre_completo;
    }
}