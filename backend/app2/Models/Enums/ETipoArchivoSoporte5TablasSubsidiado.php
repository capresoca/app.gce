<?php
namespace App\Models\Enums;

class ETipoArchivoSoporte5TablasSubsidiado extends AEnumerado {
    
    const AFILIADO                              = 'N_1';
    const AFILIACIONES                          = 'N_2';
    const HISTORIA_CONDICION_AFILIACION         = 'N_3';
    const HISTORIA_IDENTIFICACION               = 'N_4';
    const CONDICION_AFILIACIONES_SUBSIDIADO     = 'N_5';
    
    public static function getValores() {
        return [
            self::AFILIADO                          => new ETipoArchivoSoporte5TablasSubsidiado(1, 'AFILIADOS_S_E'),
            self::AFILIACIONES 		                => new ETipoArchivoSoporte5TablasSubsidiado(2, 'AFILIACIONES_S_E'),
            self::HISTORIA_CONDICION_AFILIACION     => new ETipoArchivoSoporte5TablasSubsidiado(3, 'CONDICIONES_AFILIACIONES_S_E'),
            self::HISTORIA_IDENTIFICACION           => new ETipoArchivoSoporte5TablasSubsidiado(4, 'HISTORICO_IDENTIFICACION_S_E'),
            self::CONDICION_AFILIACIONES_SUBSIDIADO => new ETipoArchivoSoporte5TablasSubsidiado(5, 'CONDICIONES_AFILIACIONES_SUBSIDIADO_E'),
        ];
    }
}

