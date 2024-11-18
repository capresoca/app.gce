<?php

namespace App\Http\Resources\Aseguramiento;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnMunicipio;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function MongoDB\BSON\toJSON;
use App\Models\TbVigenciaTraslado;

class AsAfiliadosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ubicacion  = $this->ubicacion($this->gn_municipio_id);
        // $vigencia   = $this->ubicacion($this->consecutivo_vigencia); // presenta error
        return [
            'id' => $this->id,
            'nombre_completo' => $this->nombre_completo,
            'emoticon' => $this->emoticon,
            'emoticon_nombre_completo' => $this->emoticon . '-' . $this->nombre_completo,
            'identificacion' => $this->identificacion_completa,
            'ficha_sisben' => $this->ficha_sisben,
            'puntaje_sisben' => $this->puntaje_sisben,
            'nivel_siben' => $this->nivel_sisben,
            'gn_tercero_id' => $this->gn_tercero_id,
            'estado' => $this->estado_afiliado->nombre,
            'regimen' => $this->regimen ? $this->regimen->codigo .' - '. $this->regimen->nombre : '',
            'mini_afiliado' => $this->mini_afiliado,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'ubicacion' => $ubicacion,
            'edad' => $this->edad,
            'novedad_aplicada' => $this->novedad_aplicada,
            'consecutivo_vigencia' => $this->consecutivo_vigencia,
            // 'mensaje_error'         => 'EL AFILIADO TIENE NOVEDADES PENDIENTES EN EL PERIODO '.mb_strtoupper($vigencia==null ? '' : $vigencia->descripcion)
        ];
    }

    public function ubicacion ($idMunicipio)
    {
        $municipio = GnMunicipio::where('id','=',$idMunicipio)->get();     
        $ubicacion = $municipio[0]->nombre. ', ' . $municipio[0]->departamento->nombre;
        return $ubicacion;       
    }
    
    public function vigencia ($idVigencia)
    {
        $municipio = TbVigenciaTraslado::where('consecutivo_vigencia','=',$idVigencia)->get();
        $ubicacion = empty($municipio) ? NULL : $municipio[0]->descripcion;
        return $ubicacion;
    }
}
