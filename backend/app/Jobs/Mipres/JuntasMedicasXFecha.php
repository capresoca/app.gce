<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 13/03/2019
 * Time: 2:37 PM
 */

namespace App\Jobs\Mipres;


use App\Models\General\GnEmpresa;
use App\Models\Mipres\MpComplementario;
use App\Models\Mipres\MpConfig;
use App\Models\Mipres\MpJuntaProfesional;
use App\Models\Mipres\MpMedicamento;
use App\Models\Mipres\MpNutricional;
use App\Models\Mipres\MpPrescripcione;
use GuzzleHttp\Exception\GuzzleException;

class JuntasMedicasXFecha
{
    protected $fecha;
    protected $regimen;
    protected $response;

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
            $this->getJuntasFromApi();
        }catch (GuzzleException $e) {
            throw $e;
        }
    }

    private function getJuntasFromApi()
    {
        try{

            $configuracionMipres = MpConfig::first();

            $empresa = GnEmpresa::first();
            $nit = $empresa->tercero->identificacion;
            $clienteMipres = new MipresClient();

            $this->response = $clienteMipres->request('GET',
                $configuracionMipres->base_url.'JuntaProfesionalXFecha/'
                .$nit.'/'.$configuracionMipres->getToken($this->regimen).'/'.$this->fecha);

        }catch (GuzzleException $e) {
            throw $e;
        }
    }

    public function getJuntas()
    {
        return json_decode($this->response->getBody()->getContents(),true);
    }

    public function store()
    {
        foreach ($this->getJuntas() as $junta) {
            $juntaProfesional = $junta['junta_profesional'];
            if(!ActualizaJuntaMedica::actualizar($juntaProfesional)) continue;
        }
    }



}