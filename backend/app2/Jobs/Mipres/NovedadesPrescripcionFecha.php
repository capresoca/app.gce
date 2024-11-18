<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15/02/2019
 * Time: 2:16 PM
 */

namespace App\Jobs\Mipres;


use App\Models\General\GnEmpresa;
use App\Models\Mipres\MpConfig;
use App\Models\Mipres\MpNovedade;
use App\Models\Mipres\MpPrescripcione;
use GuzzleHttp\Exception\GuzzleException;

class NovedadesPrescripcionFecha
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
            $this->getNovedadesFromApi();
        }catch (GuzzleException $e) {
            throw $e;
        }
    }

    /**
     * @throws GuzzleException
     */
    private function getNovedadesFromApi()
    {
        try{

            $configuracionMipres = MpConfig::first();


            $empresa = GnEmpresa::first();
            $nit = $empresa->tercero->identificacion;
            $clienteMipres = new MipresClient();

            $this->response = $clienteMipres->request('GET',
                $configuracionMipres->base_url.'NovedadesPrescripcion/'
                .$nit.'/'.$this->fecha.'/'.$configuracionMipres->getToken($this->regimen));

        }catch (GuzzleException $e) {
            throw $e;
        }

    }

    public function getNovedades()
    {
        return json_decode($this->response->getBody()->getContents(),true);
    }

    public function getStatus()
    {
        return $this->response->getStatusCode();
    }

    public function store()
    {
        foreach ($this->getNovedades() as $novedade) {
            $prescripcion = MpPrescripcione::where([
                'NoPrescripcion'    => $novedade['prescripcion_novedades']['NoPrescripcion'],
            ])->first();

            if(!$prescripcion) continue;

            $prescripcion->novedades()->create($novedade['prescripcion_novedades']);

            $prescripcion->EstPres = $this->getEstadoPrescripcion($novedade['prescripcion_novedades']);

            $prescripcion->save();

        }
    }

    private function getEstadoPrescripcion($prescripcion_novedades)
    {
        switch ($prescripcion_novedades['TipoNov']){
            case 1 :
                return 1;

            case 2 :
                return 2;

            default :
                return 4;
        }
    }


}