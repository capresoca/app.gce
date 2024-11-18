<?php

namespace App\Traits;


use App\Models\Aseguramiento\AsAfitramite;
use App\Models\Aseguramiento\AsNovtramite;
use App\Models\Aseguramiento\AsTraslatramite;
use App\Models\RedServicios\RsPortabilidade;

trait EstadoAfiliadoTrait
{

    /**
     * @param int $afiliadoId
     * @return array
     * @throws \Exception
     */
    public static function consultantEstate(int $afiliadoId)
    {
        try {
            $asAfitramite = AsAfitramite::with('tramite')
                ->where('as_afiliado_id', '=', $afiliadoId)
                ->whereHas('tramite', function ($query) {
                    $query->where('estado', '=', 'Registrado')->orWhere('estado', '=', 'Radicado')->orWhere('estado', '=', 'Enviado');
                })->latest()->first();
            $asNovtramite = AsNovtramite::with('tramite')
                ->where('as_afiliado_id', '=', $afiliadoId)
                ->whereHas('tramite', function ($query) {
                    $query->where('estado', '=', 'Registrado')->orWhere('estado', '=', 'Radicado')->orWhere('estado', '=', 'Enviado');
                })->latest()->first();
            $asTrastramite = AsTraslatramite::with('tramite')
                ->where('as_afiliado_id', '=', $afiliadoId)
                ->whereHas('tramite', function ($query) {
                    $query->where('estado', '=', 'Registrado')->orWhere('estado', '=', 'Radicado')->orWhere('estado', '=', 'Enviado');
                })->latest()->first();
            $today_string = today()->toDateString();
            $rsPortabilidad = RsPortabilidade::where('as_afiliado_id', '=', $afiliadoId)
                ->where('estado','Aceptado')
                ->whereDate('fecha_inicio','<=',$today_string)
                ->whereDate('fecha_fin','>=',$today_string)->latest()->first();

            $procesos = [];

            if (isset($asAfitramite)) {
                array_push($procesos,
                    [
                        'title' => 'En Afiliación' . ($asAfitramite->tramite['tipo_tramite'] === 'Afiliacion Aportante' ? 'como Aportante' : ''),
                        'estado' => $asAfitramite->tramite['estado'],
                        'emoticon' => '🎟️'
                    ]
                );

            }
            if (isset($asNovtramite)) {
                array_push($procesos,
                            [
                                'title' => 'En Novedad',
                                'estado' => $asNovtramite->tramite['estado'],
                                'emoticon' => '🆕'
                            ]
                        );
            }

            if (isset($asTrastramite)){
                array_push($procesos,
                            [
                                'title' => 'En Traslado',
                                'estado' => $asTrastramite->tramite['estado'],
                                'emoticon' => '🔁'
                            ]
                        );
            }

            if (isset($rsPortabilidad)){
                array_push($procesos,
                    [
                        'title' => 'Con Portabilidad',
                        'estado' => ($rsPortabilidad->estado === 'Aceptado') ? 'Aprobada' : $rsPortabilidad->estado,
                        'emoticon' => '📑'
                    ]
                );
            }

            if (count($procesos) === 0) return [];

            return $procesos;

        } catch (\Exception $e) {
            throw $e;
        }
    }
}