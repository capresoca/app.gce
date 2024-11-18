<?php

namespace App\Traits;

use Exception;

trait DVTrait
{
    public static function calcularDigitoVerificacion(String $nit) {
        try {

            $badDigits = ['/,/', '.', ' ', '-', ','];

            $nit = str_replace($badDigits, '', $nit);

            $nit = rtrim($nit);

            $digitos = array_reverse(str_split($nit));

            if (!$nit) {
                throw new Exception('Debe ingresar un Nit ');
            }

            $primos = [3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71];

            $sum = 0;

            foreach ($digitos as $key => $digito) {
                $sum += $digito * $primos[$key];
            }

            $residuo = $sum % 11;

            return ($residuo > 1) ? 11 - $residuo : $residuo;
        } catch (\Exception $e) {
            throw $e;
        }

    }
}