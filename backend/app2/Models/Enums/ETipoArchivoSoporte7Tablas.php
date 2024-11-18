<?php
namespace App\Models\Enums;

class ETipoArchivoSoporte7Tablas extends AEnumerado {
    
    const AFILIADO						    = 'N_1';
    const APORTANTE                         = 'N_2';
    const HISTORIA_AFILIACION				= 'N_3';
    const HISTORIA_CONDICION_AFILIACION		= 'N_4';
    const HISTORIA_COTIZANTE_APORTANTE		= 'N_5';
    const HISTORIA_GRUPO_FAMILIAR			= 'N_6';
    const HISTORIA_IDENTIFICACION			= 'N_7';
    
    public static function getValores() {
        return [
            self::AFILIADO                          => new ETipoArchivoSoporte7Tablas(1, 'AFILIADOS'),
            self::APORTANTE 		                => new ETipoArchivoSoporte7Tablas(2, 'APORTANTES'),
            self::HISTORIA_AFILIACION               => new ETipoArchivoSoporte7Tablas(3, 'HISTORIA_AFILIACION'),
            self::HISTORIA_CONDICION_AFILIACION     => new ETipoArchivoSoporte7Tablas(4, 'HISTORIA_CONDICION_AFILIACION'),
            self::HISTORIA_COTIZANTE_APORTANTE 		=> new ETipoArchivoSoporte7Tablas(5, 'HISTORIA_COTIZANTE_APORTANTE'),
            self::HISTORIA_GRUPO_FAMILIAR           => new ETipoArchivoSoporte7Tablas(6, 'HISTORIA_GRUPO_FAMILIAR'),
            self::HISTORIA_IDENTIFICACION           => new ETipoArchivoSoporte7Tablas(7, 'HISTORIA_IDENTIFICACION'),
        ];
    }
}

