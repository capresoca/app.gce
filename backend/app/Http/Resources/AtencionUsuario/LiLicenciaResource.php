<?php

namespace App\Http\Resources\AtencionUsuario;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Aseguramiento\AsPagadore;

class LiLicenciaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {       
        $fechaCreacion = date("Y-m-d", strtotime($this->created_at));
        // $aportante = $this->aportante($this->consecutivo_aportante);

        return [
            'solicitud' => $this->consecutivo_licencia,
            'fecha_solicitud' => $fechaCreacion,
            'identificacion' => $this->tipo_identificacion_afiliado. ' - ' . $this->numero_identificacion_afiliado,
            'afiliado' => $this->primer_nombre. ' ' . $this->segundo_nombre. ' ' .$this->primer_apellido. ' ' . $this->segundo_apellido,
            'aportante' => $this->aportante->razon_social,
            'identificacion_aportante' => $this->aportante->identificacion,
            'tipo_incapacidad' => $this->tipo_incapacidad,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'estado' => $this->estados($this->estado_licencia)
        ];
    }

    private function estados($idEstado) 
    {
        $nombresEstados = ['Radicada', 'Aprobado', 'Negado', 'En progreso', 'Anulada', 'Rechazado'];
        for ($i=0; $i < count($nombresEstados); $i++) { 
            if ($idEstado === ($i +1) ) {
                return $nombresEstados[$i];
            }
        }
    }

    private function aportante($idAportante)
    {
        $nombre = AsPagadore::where('id', $idAportante)->select('razon_social')->get();
        return $nombre[0]->razon_social;
    }
}
