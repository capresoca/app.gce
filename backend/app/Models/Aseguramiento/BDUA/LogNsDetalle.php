<?php

namespace App\Models\Aseguramiento\BDUA;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LogNsDetalle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'log_ns_detalle';
    protected $primaryKey = 'consecutivo_log_ns_detalle';
    public $timestamps = false;

    protected $fillable = [
        'consecutivo_log_ns',
        'codigo_novedad',
        'tipo_identificacion_afiliado',
        'numero_identificacion_afiliado',
        'fecha_inicio_novedad',
        'consecutivo_ns',
        'informacion_ns',
    ];

    public function cabecera()
    {
        return $this->belongsTo(LogNsEncabezado::class,'consecutivo_log_ns');
    }

}
