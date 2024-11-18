<?php

namespace App\Models\CuentasMedicas;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CmRadusuario extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'zona',
        'tipo_documento',
        'nombre2',
        'nombre1',
        'municipio',
        'medida_edad',
        'genero',
        'edad',
        'documento',
        'departamento',
        'cm_radicado_id',
        'as_afiliado_id',
        'apellido2',
        'apellido1',
        'as_afiliado_id'
    ];
}
