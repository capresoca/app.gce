<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class TbValorCopago extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $primaryKey = 'copago';
    protected $fillable = [
        'descripcion',
        'anio',
        'valor_salario',
        'porcentaje_tope_autorizacion',
        'porcentaje_tope_ano',
        'porcentaje_tarifa_copago'
    ];
}
