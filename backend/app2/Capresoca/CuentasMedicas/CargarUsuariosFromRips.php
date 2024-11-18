<?php


namespace App\Capresoca\CuentasMedicas;


use App\Capresoca\AfiliadoTrait;
use App\Models\CuentasMedicas\CmFacinternacion;
use App\Models\CuentasMedicas\CmRadicado;
use App\Models\CuentasMedicas\CmRadusuario;
use App\Models\General\GnMunicipio;
use App\Models\Riips\RsRipsRadicado;
use App\Traits\ToolsTrait;
use Illuminate\Support\Facades\Log;

class CargarUsuariosFromRips extends RipsCsvToBD
{
    protected $radicado;

    use AfiliadoTrait;

    public function __construct(RsRipsRadicado $rips, CmRadicado $radicado)
    {
        $this->radicado = $radicado;

        parent::__construct($rips, 'US');

    }

    public function store()
    {
		
		foreach (array_chunk($this->getData(),1000) as $t) {
			CmRadusuario::insert($t);
		}		

        //CmRadusuario::insert($this->getData());
    }

    private function getData()
    {
        $usuarios = array();

        foreach ($this->data as $rowUsuario) {
            try{

                try{

                    $afiliado = $this->getAfiliado($rowUsuario[0],$rowUsuario[1]);
                    $afiliado_id = $afiliado->id;

                }catch (\Exception $exception){
                    $afiliado_id = null;
                }

                try{
                    $municipio = GnMunicipio::whereCodigo($rowUsuario[11].$rowUsuario[12])->firstOrFail();
                    $departamento_id = $municipio->departamento->id;
                    $municipio_id = $municipio->id;

                }catch (\Exception $exception){
                    $departamento_id = null;
                    $municipio_id = null;
                }

                $usuario = [
                    'cm_radicado_id'    => $this->radicado->id,
                    'as_afiliado_id'    => $afiliado_id,
                    'tipo_documento'    => $rowUsuario[0],
                    'documento'         => $rowUsuario[1],
                    'apellido1'         => $rowUsuario[4],
                    'apellido2'         => $rowUsuario[5],
                    'nombre1'           => $rowUsuario[6],
                    'nombre2'           => $rowUsuario[7],
                    'edad'              => $rowUsuario[8],
                    'medida_edad'       => $rowUsuario[9],
                    'genero'            => $rowUsuario[10],
                    'departamento'      => $departamento_id,
                    'municipio'         => $municipio_id
                ];

                array_push($usuarios, $usuario);

            }catch (\Exception $exception){
                Log::error($exception);
                continue;
            }
        }
        return $usuarios;
    }

}