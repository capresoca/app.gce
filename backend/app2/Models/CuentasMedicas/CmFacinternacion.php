<?php

namespace App\Models\CuentasMedicas;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\Autorizaciones\AuAutorizacione;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CmFacinternacion extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'cm_facinternaciones';

    protected $fillable = [
        'as_afiliado_id',
        'au_autorizacion_id',
        'causa_externa',
        'cie10_muerte',
        'cie10_ingreso',
        'cie10_relacionado1',
        'cie10_relacionado2',
        'cie10_relacionado3',
        'cie10_salida',
        'cm_factura_id',
        'destino_salida',
        'documento',
        'estado_salida',
        'fecha_ingreso',
        'fecha_egreso',
        'tipo',
        'tipo_documento',
        'via_ingreso'
    ];

    public function afiliado () {
        return $this->belongsTo(AsAfiliado::class,'as_afiliado_id');
    }

    public function autorizacion () {
        return $this->belongsTo(AuAutorizacione::class,'au_autorizacion_id');
    }

    public function factura () {
        return $this->belongsTo(CmFactura::class, 'cm_factura_id');
    }

}
