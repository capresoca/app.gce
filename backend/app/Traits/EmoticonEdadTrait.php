<?php
/**
 * Created by PhpStorm.
 * User: Jorge Eduardo
 * Date: 3/04/2019
 * Time: 5:43 PM
 */

namespace App\Traits;


use Carbon\Carbon;

trait EmoticonEdadTrait {

    public static function IdEmoticonsByEdad ($value) {
        if(!$value->fecha_nacimiento || !$value->sexo) return '';
        $edad = Carbon::parse($value->fecha_nacimiento)->age;
        $sexo = $value->sexo->nombre;
        $emoticons = [' 👶 ',' 🧒 ',' 👦 ',' 👧 ',' 👨 ',' 👩 ',' 👨‍🦳 ',' 🧓 ',' 👵 ',' 👴 '];
        if ($edad <= 5) {
            return $emoticons[0];
        }
        if ($edad <= 12) {
            return $emoticons[1];
        }
        if ($edad <= 17) {
            return $sexo === 'Masculino' ? $emoticons[2] : $emoticons[3];
        }
        if ($edad <= 55) {
            return $sexo === 'Masculino' ? $emoticons[4] : $emoticons[5];
        }
        if ($edad <= 64) {
            return $sexo === 'Masculino' ? $emoticons[6] : $emoticons[7];
        }
        return $sexo === 'Masculino' ? $emoticons[9] : $emoticons[8];
    }

}