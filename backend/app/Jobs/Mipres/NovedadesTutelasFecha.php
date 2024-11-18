<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/03/2019
 * Time: 5:56 PM
 */

namespace App\Jobs\Mipres;


use App\Models\General\GnEmpresa;
use App\Models\Mipres\MpConfig;
use App\Models\Mipres\MpPrescripcione;
use App\Models\Mipres\MpTutela;
use GuzzleHttp\Exception\GuzzleException;

class NovedadesTutelasFecha
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
                $configuracionMipres->base_url.'NovedadesTutelas/'
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
            $tutela = MpTutela::where([
                'NoTutela'    => $novedade['tutela_novedades']['NoPrescripcion'],
            ])->first();


            $tutelafinal = MpTutela::where([
                'NoTutela'    => $novedade['tutela_novedades']['NoPrescripcionF'],
            ])->first();

            if(!$tutela) continue;

            $novedade['tutela_novedades']['mp_tutelafinal_id'] = $tutelafinal->id;

            $tutela->novedades()->create($novedade['tutela_novedades']);

            $tutela->EstTut = $this->getEstadoPrescripcion($novedade['tutela_novedades']);

            $tutela->save();

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