<?php


namespace App\Models\Enums;

class EEstadoArchivoCompensacionRecaudo extends AEnumerado
{
    const CARGUE_EXITOSO                        = 'N_1';
    const CARGUE_CON_INCONSISTENCIAS            = 'N_2';
    const CARGUE_CON_INCONSISTENCIAS_CONTENIDO  = 'N_3';
    const CARGUE_ACTIVO                         = 'N_4';

    public static function getValores()
    {
        return [
            self::CARGUE_EXITOSO                          => new EEstadoArchivoCompensacionRecaudo(1,'Archivo compensación recaudo exitoso.'),
            self::CARGUE_CON_INCONSISTENCIAS              => new EEstadoArchivoCompensacionRecaudo(0,'Archivo compensación recaudo inconsistencias.'),
            self::CARGUE_CON_INCONSISTENCIAS_CONTENIDO    => new EEstadoArchivoCompensacionRecaudo(2,'Archivo compensación recaudo inconsistencias de contenido.'),
            self::CARGUE_ACTIVO                           => new EEstadoArchivoCompensacionRecaudo(3,'Archivo compensación recaudo inconsistencias cargue activo.')
        ];
    }
}