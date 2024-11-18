<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15/02/2019
 * Time: 5:13 PM
 */

namespace App\Jobs\Mipres;


use App\Capresoca\AfiliadoTrait;
use App\Models\General\GnEmpresa;
use App\Models\Mipres\MpConfig;
use App\Models\Mipres\MpTutela;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TutelasFecha
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
            $this->getTutelasFromApi();
        }catch (GuzzleException $e) {
            throw $e;
        }
    }

    private function getTutelasFromApi()
    {
        try{

            $configuracionMipres = MpConfig::first();


            $empresa = GnEmpresa::first();
            $nit = $empresa->tercero->identificacion;
            $clienteMipres = new MipresClient();

            $this->response = $clienteMipres->request('GET',
                $configuracionMipres->base_url.'Tutelas/'
                .$nit.'/'.$this->fecha.'/'.$configuracionMipres->getToken($this->regimen));

        }catch (GuzzleException $e) {
            throw $e;
        }
    }

    public function getTutelas()
    {
        return json_decode($this->response->getBody()->getContents(),true);
    }

    public function getStatus()
    {
        return $this->response->getStatusCode();
    }

    public function store()
    {
        foreach ($this->getTutelas() as $tutela) {
            $tutelaExists = MpTutela::where([
                'NoTutela'    => $tutela['tutela']['NoTutela']
            ])->first();


            if($tutelaExists) continue;


            try{
                $afiliado = $this->getAfiliado(
                    $tutela['tutela']['TipoIDPaciente'],
                    $tutela['tutela']['NroIDPaciente']
                );

                $afiliadoId = $afiliado->id;
            }catch (\Exception $e){
                $afiliadoId = null;
            }

            $tutela['tutela']['as_afiliado_id'] = $afiliadoId;
            $tutela['tutela']['paciente'] = $this->getNombrePaciente($tutela['tutela']);
            $tutela['tutela']['regimen'] = $this->regimen;

            try{
                DB::beginTransaction();

                $tutelaGuardada = MpTutela::create($tutela['tutela']);

                foreach ($tutela['medicamentos'] as $medicamento){
                    $medicamento['DescMedPrinAct'] = $medicamento['DscMedPA']; //Homologando la variable ya que esta diferente para tutelas
                    $medicamentoGuardado = $tutelaGuardada->medicamentos()->create($medicamento);

                    $medicamentoGuardado->PrincipiosActivos()->createMany($medicamento['PrincipiosActivos']);

                    $medicamentoGuardado->IndicacionesUNIRS()->createMany($medicamento['IndicacionesUNIRS']);

                }

                $tutelaGuardada->fallosAdicionales()->createMany($tutela['fallosAdicionales']);

                $tutelaGuardada->procedimientos()->createMany($tutela['procedimientos']);

                $tutelaGuardada->dispositivos()->createMany($tutela['dispositivos']);

                $tutelaGuardada->nutricionales()->createMany($tutela['productosnutricionales']);

                $tutelaGuardada->complementarios()->createMany($tutela['serviciosComplementarios']);

                DB::commit();
            }catch (\Exception $e){
                Log::error($e);
                DB::rollBack();
            }

        }
    }
    private function getNombrePaciente($tutela)
    {
        $nombre_completo = $tutela['PNPaciente'];
        if($tutela['SNPaciente'])
            $nombre_completo .= ' '.$tutela['SNPaciente'];

        if($tutela['PAPaciente'])
            $nombre_completo .= ' '.$tutela['PAPaciente'];

        if($tutela['SAPaciente'])
            $nombre_completo .= ' '.$tutela['SAPaciente'];

        return $nombre_completo;
    }

}