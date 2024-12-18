<?php

namespace App\Models\CuentasMedicas;

use App\Models\AuditorCuentas\AcAuditore;
use App\Models\ContratacionEstatal\CeProconminutaupcservicio;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CmGlosa extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'cm_glosas';

    protected $fillable = [
        'ac_auditor_id',
        'cm_facservicio_id',
        'cm_factura_id',
        'cm_manglosa_id',
        'fecha_glosa',
        'observacion',
        'tipo',
        'valor_glosa',
        'gs_usuario_id',
        'of_facservicio',
        'ce_proconminutaupcservicio_id'
    ];

//    protected $with = ['glosa'];

    public function respuestas ()
    {
        return $this->hasMany(CmRespuestasGlosa::class,'cm_glosa_id');
    }

    public function auditor () {
        return $this->belongsTo(AcAuditore::class,'ac_auditor_id');
    }

    public function glosa ()
    {
        return $this->belongsTo(CmManglosa::class,'cm_manglosa_id');
    }

    public function factura () {
        return $this->belongsTo(CmFactura::class,'cm_factura_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }

    public function minuta_servicio()
    {
        return $this->belongsTo(CeProconminutaupcservicio::class, 'ce_proconminutaupcservicio_id');
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        CmGlosa::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id = Auth::user()->id;
            }
            $table->fecha_glosa = Carbon::now()->format('Y-m-d');
        });

    }
}
