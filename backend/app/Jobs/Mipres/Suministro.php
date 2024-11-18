<?php


namespace App\Jobs\Mipres;


use App\Models\General\GnEmpresa;
use App\Models\Mipres\MpConfig;
use App\Models\Mipres\MpSuministro;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class Suministro
{
    protected $suministro;
    protected $regimen;

    /**
     * Suministro constructor.
     * @param MpSuministro $suministro
     * @throws Exception
     */
    public function __construct(MpSuministro $suministro = null)
    {
        if($suministro){
            $this->suministro = $suministro;
            $this->regimen = $this->getRegimenDireccionamiento($suministro);
        }
        $this->configuracionMipres = MpConfig::where('base_url','/WSSUMMIPRESNOPBS/api/')->first();
        $empresa = GnEmpresa::first();
        $this->nit = $empresa->tercero->identificacion;
        $this->clienteMipres = new MipresClient();
    }

    /**
     * @throws GuzzleException
     */
    public function send()
    {

        $this->suministro->ID = $this->suministro->suministro_id;
        $response = $this->clienteMipres->request(
            'PUT',
            $this->configuracionMipres->base_url.'Suministro/'.$this->nit.'/'.$this->configuracionMipres->getToken($this->regimen),
            [
                'json' => $this->suministro->toArray()
            ]

        );

        return json_decode($response->getBody()->getContents(),true);

    }

    private function getRegimenDireccionamiento(MpSuministro $suministro)
    {
        if($suministro->prescripcion){
            return $suministro->prescripcion->regimen;
        }

        if($suministro->tutela){
            return $suministro->tutela->regimen;
        }
        throw new Exception('No tiene una prescripcion o tutela relacionada');
    }
}