<?php


namespace App\Jobs\Mipres;


use App\Models\General\GnEmpresa;
use App\Models\Mipres\MpConfig;
use App\Models\Mipres\MpReporteSuministro;
use App\Models\Mipres\MpSuministro;
use GuzzleHttp\Exception\GuzzleException;

class ReporteSuministro
{
    protected $suministro;
    protected $regimen;
    protected $nit;
    protected $clienteMipres;

    public function __construct(MpReporteSuministro $suministro,$regimen)
    {
        $this->suministro = $suministro;
        $this->configuracionMipres = MpConfig::where('base_url','/WSSUMMIPRESNOPBS/api/')->first();
        $empresa = GnEmpresa::first();
        $this->regimen = $regimen;
        $this->nit = $empresa->tercero->identificacion;
        $this->clienteMipres = new MipresClient();
    }

    /**
     * @throws GuzzleException
     */
    public function send()
    {

        $suministroArray = [
            'ID' => $this->suministro->suministro_id,
            "UltEntrega" =>  $this->suministro->UltEntrega,
            "EntregaCompleta" =>  $this->suministro->EntregaCompleta,
            "CausaNoEntrega" =>  $this->suministro->CausaNoEntrega,
            "NoPrescripcionAsociada" =>  $this->suministro->NoPrescripcionAsociada,
            "ConTecAsociada" =>  $this->suministro->ConTecAsociada,
            "CantTotEntregada" =>  $this->suministro->CantTotEntregada,
            "NoLote" =>  $this->suministro->NoLote,
            "ValorEntregado" =>  $this->suministro->ValorEntregado
        ];

        $response = $this->clienteMipres->request(
            'PUT',
            $this->configuracionMipres->base_url.'Suministro/'.$this->nit.'/'.$this->configuracionMipres->getToken($this->regimen),
            [
                'json' => $suministroArray
            ]

        );

        return json_decode($response->getBody()->getContents(),true);

    }
}