<?php

namespace App\Models\Aseguramiento\BDUA;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TbPeriodoLiquidacion extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tb_periodo_liquidacion';
    protected $primaryKey = 'consecutivo_liquidacion';
    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];
}
