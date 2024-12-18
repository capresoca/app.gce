<?php

namespace App\Models\Pagos;

use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCencosto;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class PgAnticipoCxp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'consecutivo',
        'pg_anticipo_id',
        'fecha_documento',
        'gs_usuario_id',
        'gn_tercero_id',
        'nf_cencosto_id',
        'estado',
        'observacion',
        'gs_usuario_anula_id',
        'fecha_anulacion',
        'motivo_anula'
    ];

    public function detalles () {
        return $this->hasMany(PgAnticipocxpsDet::class,'pg_anticipo_cxp_id');
    }

    public function tercero () {
        return $this->belongsTo(GnTercero::class,'gn_tercero_id');
    }

    public function anticipo () {
        return $this->belongsTo(PgAnticipo::class,'pg_anticipo_id');
    }

    public function cencosto () {
        return $this->belongsTo(NfCencosto::class,'nf_cencosto_id');
    }

    public function usuario () {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }

    public function usuarioAnula()
    {
        return $this->belongsTo(User::class,'gs_usuario_anula_id');
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        PgAnticipoCxp::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
                $table->consecutivo = PgAnticipoCxp::max('consecutivo') + 1;
            }
            if (Auth::user() && $this->estado === 'Anulado') {
                $table->gs_usuario_anula_id = Auth::user()->id;
            }
        });
        PgAnticipoCxp::updating(function ($table) {
            if (Auth::user() && $this->estado === 'Anulado') {
                $table->gs_usuario_anula_id = Auth::user()->id;
            }
        });

    }
}
