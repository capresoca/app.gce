<?php

namespace App\Http\Resources\Autorizaciones;

use Illuminate\Http\Resources\Json\JsonResource;

class AnexoTecnico3Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resources = [
            'id' => $this->id,
            'ind' => $this->ind,
            'fOrdMed' => $this->fOrdMed,
            'nSolicitud' => $this->nSolicitud,
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'entidad_ips' => [
                'id' => $this->entidad_ips['id'],
                'nombre' => $this->entidad_ips['nombre'],
                'cod_habilitacion' => $this->entidad_ips['cod_habilitacion'],
            ],
            'afiliado' => isset($this->afiliado) ? [
                'id' => $this->afiliado['id'],
                'mini_afiliado' => $this->afiliado->mini_afiliado,
            ] : null,
            'usuarioProceso' => isset($this->usuarioProceso) ? [
                'id' => $this->usuarioProceso['id'],
                'name' => $this->usuarioProceso['name'],
                'email' => $this->usuarioProceso['email'],
            ] : null,
            'usuario_valida' => isset($this->usuarioValida) ? [
                'id' => $this->usuarioValida['id'],
                'name' => $this->usuarioValida['name'],
                'email' => $this->usuarioValida['email'],
            ] : null,
            'mini_entidades' => ($this->mini_entidades !== null || $this->mini_entidades !== []) ? $this->mini_entidades : [],
        ];

//        parent::toArray($request)
        return $resources;
    }
}
