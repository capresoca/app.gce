<?php
namespace App\Models\Enums;

class ETipoNovedad extends AEnumerado {
    
    const NOVEDAD						= 'N_1';
    const TRASLADO						= 'N_2';
    
    public static function getValores() {
        return [
            self::NOVEDAD 		=> new ETipoNovedad(1, 'Novedad'),
            self::TRASLADO 		=> new ETipoNovedad(2, 'Traslado'),
        ];
    }
}
