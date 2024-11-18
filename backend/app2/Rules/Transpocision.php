<?php

namespace App\Rules;

use App\Traits\ToolsTrait;
use Illuminate\Contracts\Validation\Rule;

class Transpocision implements Rule
{

    /**
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $tool = new ToolsTrait();

        return $tool->validarSuperpocisionRangos($value);

    }


    public function message()
    {
        return 'Se presenta conflicto en los rangos';
    }
}
