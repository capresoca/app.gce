<?php

namespace App\Models\Tesoreria;

use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCencosto;
use App\Models\RedServicios\RsPlanescobertura;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TsCompegreso extends Model
{
    protected $fillable = [
        'id',
        'fecha',
        'consecutivo',
        'gn_tercero_id',
        'forma_pago',
        'ts_cuenta_id',
        'ts_caja_id',
        'nf_cencosto_id',
        'descripcion',
        'rs_planescobertura_id',
        'gs_usuario_id',
        'gs_usuanula_id',
        'fecha_anula',
        'motivo_anula',
        'anticipo',
        'estado',
        'ts_compegresos_anticipo_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function tercero()
    {
        return $this->belongsTo(GnTercero::class,'gn_tercero_id');
    }

    public function cuentaBancaria()
    {
        return $this->belongsTo(TsCuenta::class,'ts_cuenta_id');
    }

    public function caja()
    {
        return $this->belongsTo(TsCaja::class,'ts_caja_id');
    }

    public function cencosto()
    {
        return $this->belongsTo(NfCencosto::class,'nf_cencosto_id');
    }

    public function planCobertura()
    {
        return $this->belongsTo(RsPlanescobertura::class,'rs_planescobertura_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }

    public function usuarioAnula()
    {
        return $this->belongsTo(User::class,'gs_usuanula_id');
    }


    public function conceptos()
    {
        return $this->hasMany(TsCompegresoConcepto::class, 'ts_compegreso_id');
    }

    public function getValorAttribute()
    {
        return $this->conceptos->sum('valor');
    }

    public static function allRelations()
    {
        return [
            'tercero',
            'cuentaBancaria',
            'caja',
            'cencosto',
            'planCobertura',
            'usuario',
            'usuarioAnula',
            'conceptos.concepto',
            'conceptos.detalles.cxp.niif',
            'conceptos.detalles.plan_cobertura',
            'conceptos.detalles.anticipo'
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
                $table->consecutivo = TsCompegreso::max('consecutivo') + 1;
            }
           //$table->consecutivo = !TsCompegreso::max('consecutivo') ? 1 : TsCompegreso::max('consecutivo');
        });
    }
}
