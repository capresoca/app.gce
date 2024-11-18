<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class PrDetstringreso extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'pr_stringreso_id',
        'pr_rubro_id',
        'pr_tipo_ingreso_id',
        'valor_inicial',
        'valor_credito',
        'valor_debito',
        'valor_ejecutado',
        'valor_recaudo'
    ];
    protected $appends = ['valor_final','valor_disponible'];

//    protected $with = ['rubro', 'tipoIngreso'];

    public function modificado () {
        return $this->hasOne(PrModDetPresupuestale::class, 'pr_detstringreso_id');
    }

    public function modificaciones () {
        return $this->hasMany(PrModDetPresupuestale::class, 'pr_detstringreso_id');
    }

    public function rubro ()
    {
        return $this->belongsTo(PrConcepto::class, 'pr_rubro_id');
    }

    public function stringreso () {
        return $this->belongsTo(PrStringreso::class, 'pr_stringreso_id');
    }

    public function tipoIngreso ()
    {
        return $this->belongsTo(PrTipoIngreso::class, 'pr_tipo_ingreso_id');
    }

    public function getValorFinalAttribute()
    {
        $modificaciones = $this->modificaciones;
//       !isset($this->modificado) ||
        if (!isset($modificaciones)) {
            return $this->attributes['valor_inicial'];
        } else {
            $adicion = $this->attributes['valor_credito'];
            $substraccion = $this->attributes['valor_debito'];

            if ($adicion !== 0 && $substraccion !== 0) {
                $resultado = ($this->attributes['valor_inicial'] + $adicion) - $substraccion;
            } elseif ($adicion !== 0 && $substraccion === 0) {
                $resultado = $this->attributes['valor_inicial'] + $adicion;
            } elseif ($adicion === 0 && $substraccion !== 0) {
                $resultado = $this->attributes['valor_inicial'] - $substraccion;
            } else {
                $resultado = $this->attributes['valor_inicial'];
            }
            return $resultado;
        }
//        if (!isset($this->modificado)) {
//            return $this->attributes['valor_inicial'];
//        } else {
//            if ($this->modificado['naturaleza'] === 'Crédito') {
//                $adicion = $this->attributes['valor_inicial'] + $this->modificado['valor_movimiento'];
//                return $adicion;
//            } elseif ($this->modificado['naturaleza'] === 'Debito') {
//                $substraccion = $this->attributes['valor_inicial'] - $this->modificado['valor_movimiento'];
//                return $substraccion;
//            }
//        }
    }

    public function getValorDisponibleAttribute () {
        if (!isset($this->attributes['valor_ejecutado'])) {
            $disponible = (double) $this->valor_final;
        } else {
            $disponible = ((double) $this->valor_final) - ((double) $this->attributes['valor_ejecutado']);
        }
        return $disponible;
    }
}
