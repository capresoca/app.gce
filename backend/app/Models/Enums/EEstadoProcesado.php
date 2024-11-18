<?php
namespace App\Models\Enums;

class EEstadoProcesado extends AEnumerado {
    
    const PROCESADO						= 'N_1';
    const NO_PROCESADO						= 'N_2';
    const PENDIENTE_NEG				= 'N_3';
    
    public static function getValores () {
        return [
            self::PROCESADO 		             => new EEstadoProcesado(1, 'Procesado'),
            self::NO_PROCESADO 		             => new EEstadoProcesado(0, 'No Procesado'),
            self::PENDIENTE_NEG 		 => new EEstadoProcesado(2, 'Pendiente NEG'),
        ];
    }
}
