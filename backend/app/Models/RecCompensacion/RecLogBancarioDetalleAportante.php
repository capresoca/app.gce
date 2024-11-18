<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @author Jorge Eduardo Hernández Oropeza 08/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecLogBancarioDetalleAportante
 * @package App\Models\RecCompensacion
 */
class RecLogBancarioDetalleAportante extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $primaryKey = 'consecutivo_log_detalle_aportante';
    protected $fillable = [
        'consecutivo_encabezado',
        'tipo_registro',
        'identificacion_aportante',
        'nombre_aportante',
        'codigo_banco_autorizador',
        'numero_planilla_liquidacion',
        'periodo_pago',
        'canal_pago',
        'numero_registros',
        'codigo_operador_informacion',
        'valor_planilla',
        'hora_minuto',
        'numero_secuencia',
        'reservado',
        'text_registro',
        'text_error',
        'consecutivo_recaudo_planilla_detalle_i',
        'tipo_error_conciliacion',
        'sw_conciliacion',
        'usuario_conciliacion',
        'fecha_conciliacion',
        'consecutivo_recaudo_planilla_detalle_i_p',
        'numero_cuenta'
    ];

    public function recRecaudoPlanillaDetalleP ()
    {
        return $this->belongsTo(RecRecaudoPlanillaDetalleIPe::class,'consecutivo_recaudo_planilla_detalle_i_p');
    }

    public function recRecaudoPlanillaDetalleI () {
        return $this->belongsTo(RecRecaudoPlanillaDetalleI::class,'consecutivo_recaudo_planilla_detalle_i');
    }

    public function recLogBancarioEncabezado () {
        return $this->belongsTo(RecLogBancarioEncabezado::class,'consecutivo_encabezado');
    }
}
