<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 8/03/2019
 * Time: 4:12 PM
 */

namespace App\Jobs\Mipres;


use App\Models\General\GnEmpresa;
use App\Models\Mipres\MpConfig;
use GuzzleHttp\Exception\GuzzleException;

class GeneradorTokenProveedor
{
    public function __construct()
    {
        try {
            $this->generarToken();
        } catch (GuzzleException $e) {
        }
    }

    private function generarToken()
    {
        $configuracionMipres = MpConfig::first();

        $empresa = GnEmpresa::first();
        $nit = $empresa->tercero->identificacion;
        $clienteMipres = new MipresClient();



        try {
            $tokenSubsidiado = $clienteMipres->request('GET',
                '/WSSUMMIPRESNOPBS/api/' . 'GenerarToken/'
                . $nit . '/' . $configuracionMipres->getToken('Subsidiado'));

            $tokenContributivo = $clienteMipres->request('GET',
                '/WSSUMMIPRESNOPBS/api/' . 'GenerarToken/'
                . $nit . '/' . $configuracionMipres->getToken('Contributivo'));

            MpConfig::updateOrCreate([
                'base_url' => '/WSSUMMIPRESNOPBS/api/'
            ],[
                'dominio'           => 'https://wsmipres.sispro.gov.co',
                'token_subsididado' => str_replace('"','',$tokenSubsidiado->getBody()->getContents()),
                'token_contributivo'=> str_replace('"','',$tokenContributivo->getBody()->getContents()),
            ]);

        } catch (GuzzleException $e) {
            throw $e;
        }

    }
}