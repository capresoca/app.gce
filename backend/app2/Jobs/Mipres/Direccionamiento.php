<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/03/2019
 * Time: 3:29 PM
 */

namespace App\Jobs\Mipres;


use App\Capresoca\AfiliadoTrait;
use App\Models\General\GnEmpresa;
use App\Models\Mipres\MpConfig;
use App\Models\Mipres\MpDireccionamiento;
use App\Models\Mipres\MpPrescripcione;
use App\Models\Mipres\MpTutela;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

class Direccionamiento
{
    protected $direccionamiento;
    protected $cliente;
    protected $regimen;
    protected $nit;
    protected $configuracionMipres;

    use AfiliadoTrait;

    /**
     * Direccionamiento constructor.
     * @param MpDireccionamiento $direccionamiento
     * @throws \Exception
     */
    public function __construct(MpDireccionamiento $direccionamiento = null)
    {
        if($direccionamiento){

            $this->direccionamiento = $direccionamiento;
            $this->regimen = $this->getRegimenDireccionamiento($direccionamiento);
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

            $response = $this->clienteMipres->request(
                'PUT',
                $this->configuracionMipres->base_url.'Direccionamiento/'.$this->nit.'/'.$this->configuracionMipres->getToken($this->regimen),
                [
                    'json' => $this->direccionamiento->toArray()
                ]

            );

            return json_decode($response->getBody()->getContents(),true);

    }

    public function getByFecha($fecha,$regimen)
    {
        try{
            $uri = $this->configuracionMipres->base_url.'DireccionamientoXFecha/'
                .$this->nit.'/'.$this->configuracionMipres->getToken($regimen).'/'.$fecha;
            //dd($uri);
            $this->response = $this->clienteMipres->request('GET', $uri)
                ;

        }catch (GuzzleException $e) {
            throw $e;
        }
    }

    /**
     * @param $id
     * @throws GuzzleException
     */
    public function anular()
    {
            $response = $this->clienteMipres->request(
                'PUT',
                $this->configuracionMipres->base_url . 'AnularDireccionamiento/' .
                $this->nit . '/' . $this->configuracionMipres->getToken($this->regimen) . '/' . $this->direccionamiento->IdDireccionamiento,
                [
                    'json' => $this->direccionamiento->toArray()
                ]

            );

            return $response;
    }

    /**
     * @param $direccionamiento
     * @return mixed
     * @throws \Exception
     */
    private function getRegimenDireccionamiento($direccionamiento)
    {
        if($direccionamiento->mp_tutela_id){
            return $direccionamiento->tutela->regimen;
        }

        if($direccionamiento->mp_prescripcion_id){
            return $direccionamiento->prescripcion->regimen;
        }
        throw new Exception('No tiene una prescripcion o tutela relacionada');
    }

    private function getDireccionamientos()
    {
        return json_decode($this->response->getBody()->getContents(),true);
    }

    public function store()
    {
        foreach ($this->getDireccionamientos() as $direccionamiento) {

            $prescripcion = MpPrescripcione::where('NoPrescripcion',$direccionamiento['NoPrescripcion'])->first();
            $tutela = MpTutela::where('NoTutela',$direccionamiento['NoPrescripcion'])->first();

            try{
                $afiliado = $this->getAfiliado(
                    $direccionamiento['TipoIDPaciente'],
                    $direccionamiento['NroIDPaciente']
                );

                $afiliadoId = $afiliado->id;
            }catch (\Exception $e){
                $afiliadoId = null;
            }



            $direccionamientoBD = MpDireccionamiento::updateOrCreate([
                'IdDireccionamiento' => $direccionamiento['IDDireccionamiento']
            ],[
                'mp_prescripcion_id'    => $prescripcion ? $prescripcion->id : null,
                'mp_tutela_id'          => $tutela ? $tutela->id : null,
                'NoPrescripcion'        => $direccionamiento['NoPrescripcion'],
                'as_afiliado_id'        => $afiliadoId,
                'TipoTec' => $direccionamiento['TipoTec'],
                'ConTec' => $direccionamiento['ConTec'],
                'TipoIDPaciente' => $direccionamiento['TipoIDPaciente'],
                'NoIDPaciente' => $direccionamiento['NoIDPaciente'],
                'NoEntrega' => $direccionamiento['NoEntrega'],
                'NoSubEntrega' => $direccionamiento['NoSubEntrega'],
                'TipoIDProv' => $direccionamiento['TipoIDProv'],
                'NoIDProv' => $direccionamiento['NoIDProv'],
                'CodMunEnt' => $direccionamiento['CodMunEnt'],
                'FecMaxEnt' => $direccionamiento['FecMaxEnt'],
                'CantTotAEntregar' => $direccionamiento['CantTotAEntregar'],
                'DirPaciente' => $direccionamiento['DirPaciente'],
                'CodSerTecAEntregar' => $direccionamiento['CodSerTecAEntregar'],
                'suministro_id' => $direccionamiento['ID'],
                'created_at' => $direccionamiento['FecDireccionamiento'].':00',
                'deleted_at' => $direccionamiento['FecAnulacion'] ? $direccionamiento['FecAnulacion'].':00' :null,
                'EstDireccionamiento' => $direccionamiento['EstDireccionamiento']
            ]);
        }
    }
}