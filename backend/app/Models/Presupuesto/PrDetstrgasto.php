<?php

namespace App\Models\Presupuesto;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrDetstrgasto extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'pr_strgasto_id',
        'pr_rubro_id',
        'pr_tipo_gasto_id',
        'valor_credito',
        'valor_debito',
        'valor_ejecutado',
        'valor_inicial',
        'valor_recaudo',
        'rubro_sin_presupuesto'
    ];

    protected $appends = [
        'valor_final',
        'valor_disponible',
        'modificaciones_credito_total',
        'modificaciones_debito_total',
        'valor_disponible_formateado',
        'valor_acreditado_traslado',
        'valor_debitado_traslado',
        'valor_comprometido'
    ];

    protected $with = ['trasladosAcreditados','trasladosDebitados'];

    public function modificado () {
        return $this->hasOne(PrModDetPresupuestale::class, 'pr_detstrgasto_id');
    }

    public function modificaciones () {
        return $this->hasMany(PrModDetPresupuestale::class,'pr_detstrgasto_id');
    }

    public function trasladosAcreditados () {
        return $this->hasMany(PrDetallesTraslado::class, 'pr_detstrgasto_id');
    }

    public function trasladosDebitados () {
        return $this->hasMany(PrDetallesTraslado::class, 'pr_detstrgastodebitado_id');
    }

    public function rubro()
    {
        return $this->belongsTo(PrConcepto::class, 'pr_rubro_id');
    }

    public function tipoGasto ()
    {
        return $this->belongsTo(PrTiposGasto::class, 'pr_tipo_gasto_id');
    }

    public function strGasto () {
        return $this->belongsTo(PrStrgasto::class, 'pr_strgasto_id');
    }

    public function cdps () {
        return $this->hasMany(PrDetcdp::class, 'pr_detstrgasto_id');
    }
    //
    // Mutators
    public function getValorDebitadoTrasladoAttribute ()
    {
        $trasladosDebitados = $this->trasladosDebitados;
        if (!isset($trasladosDebitados)) return 0;
        $sumaDebitos = 0;
        foreach ($trasladosDebitados as $debitado) {
            $traslado = PrTraslado::where('id',$debitado->pr_traslado_id)->where('estado','Confirmado')->first();
            if ($traslado) {
                $sumaDebitos += $debitado->valor_movimiento;
            }
        }
        return $sumaDebitos;
    }

    public function getValorAcreditadoTrasladoAttribute ()
    {
        $trasladosAcreditados = $this->trasladosAcreditados;
        if (!isset($trasladosAcreditados)) return 0;
        $sumaCreditos = 0;
        foreach ($trasladosAcreditados as $acreditado) {
            $traslado = PrTraslado::where('id',$acreditado->pr_traslado_id)->where('estado','Confirmado')->first();
            if ($traslado) {
                $sumaCreditos += $acreditado->valor_movimiento;
            }
        }
        return $sumaCreditos;
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
    }

    public function getModificacionesCreditoTotalAttribute () {
        $credito = PrModDetPresupuestale::where('pr_detstrgasto_id',$this->id)->where(function ($query){
            $query->where('naturaleza', 'Crédito');
        })->sum('valor_movimiento');
        return $credito;
    }

    public function getModificacionesDebitoTotalAttribute () {
        $debito = PrModDetPresupuestale::where('pr_detstrgasto_id',$this->id)->where(function ($query){
            $query->where('naturaleza', 'Débito');
        })->sum('valor_movimiento');
        return $debito;
    }

    public function getValorDisponibleAttribute () {
        $valorFinal = (double) $this->valor_final;
        if ($this->valor_acreditado_traslado !== 0 && $this->valor_debitado_traslado !== 0) {
            $valor = ($valorFinal + $this->valor_acreditado_traslado) - $this->valor_debitado_traslado;
        } elseif ($this->valor_acreditado_traslado !== 0) {
            $valor = $valorFinal + $this->valor_acreditado_traslado;
        }  elseif ($this->valor_debitado_traslado !== 0) {
            $valor = $valorFinal - $this->valor_debitado_traslado;
        } else {
            $valor = $valorFinal;
        }
        if (!isset($this->attributes['valor_ejecutado'])) {
//            $disponible = (double) $this->valor_final;
            $disponible = $valor;
        } else {
            $disponible = ($valor) - ((double) $this->attributes['valor_ejecutado']);
//            $disponible = ((double) $this->valor_final) - ((double) $this->attributes['valor_ejecutado']);
        }
        return $disponible;
    }

    public function getValorDisponibleFormateadoAttribute () {
        return '$ '. number_format($this->valor_disponible, 2, ',', '.');
    }

    public function getValorComprometidoAttribute () {
        $cdps = $this->cdps;
        if (isset($cdps)) {
            $valor = 0;
            foreach ($cdps as $item) {
                $cdp = PrCdp::where('id',$item->pr_cdp_id)->first();
                if ($cdp->estado === 'Confirmada') {
                    $valor += $item->valor;
                }
            }
            return $valor;
        }
    }
}
