<?php
namespace App\Models\Enums;

class ESiNo extends AEnumerado {
    
    const SI						= 'N_1';
    const NO						= 'N_2';
    const PENDIENTE_NEG						= 'N_3';
    
    public static function getValores () {
        return [
            self::SI 		=> new ESiNo(1, 'Si'),
            self::NO 		=> new ESiNo(0, 'No'),
            self::PENDIENTE_NEG 		=> new ESiNo(2, 'Pendiente NEG'),
        ];
    }
}
