<?php

namespace App\Models\CuentasMedicas;

use App\Models\Aseguramiento\AsAfiliado;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CmFacnacimiento extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'as_afiliados_id',
        'cm_facturas_id',
        'control_prenatal',
        'diagnostico',
        'edad_gestacional',
        'fecha_nacimiento',
        'peso',
        'sexo'
    ];

    public function afiliado () {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliados_id');
    }

    public function factura () {
        return $this->belongsTo(CmFactura::class, 'cm_facturas_id');
    }

}
