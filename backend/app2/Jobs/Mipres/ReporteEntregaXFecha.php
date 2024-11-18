<?php


namespace App\Jobs\Mipres;


use App\Models\General\GnEmpresa;
use App\Models\Mipres\MpConfig;
use App\Models\Mipres\MpReporteentrega;
use GuzzleHttp\Exception\GuzzleException;


class ReporteEntregaXFecha
{
    private $regimen;
    private $fecha;
    private $configuracionMipres;

    public function __construct($fecha, $regimen)
    {
        try{

            $this->fecha = $fecha;
            $this->regimen = $regimen;
            $this->configuracionMipres = MpConfig::where('base_url','/WSSUMMIPRESNOPBS/api/')->first();
            $this->getReportesFromApi();
        }catch (GuzzleException $e) {
            throw $e;
        }
    }

    /**
     * @throws GuzzleException
     */
    private function getReportesFromApi()
    {
        try{

            $empresa = GnEmpresa::first();
            $nit = $empresa->tercero->identificacion;
            $clienteMipres = new MipresClient();

            $this->response = $clienteMipres->request('GET',
                $this->configuracionMipres->base_url.'ReporteEntregaXFecha/'
                .$nit.'/'.$this->configuracionMipres->getToken($this->regimen).'/'.$this->fecha);

        }catch (GuzzleException $e) {
            throw $e;
        }

    }

    public function store()
    {
        foreach ($this->getReportes() as $reporte) {
            $reporte['id'] = $reporte['IDReporteEntrega'];
            $reporte['suministro_id'] = $reporte['ID'];
            $reporte['deleted_at'] = $reporte['FecAnulacion'];
            MpReporteentrega::updateOrCreate([
                'id' => $reporte['id']
            ],$reporte);
        }
    }

    public function getReportes()
    {
        return json_decode($this->response->getBody()->getContents(),true);
    }
}


