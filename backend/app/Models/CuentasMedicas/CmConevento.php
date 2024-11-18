<?php

namespace App\Models\CuentasMedicas;

use App\Models\General\GnArchivo;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmConevento extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'cm_convisita_id',
        'tipo',
        'fecha',
        'descripcion',
        'salud_publica',
        'cm_concurrencia_id',
        'cm_eventoadversoeps_id',
        'cm_eventoadversoips_id',
        'fecha_notificacion',
        'cm_manglosa_id'
    ];

    // Relaciones
    public function concurrencia() {
        return $this->belongsTo(CmConcurrencia::class,'cm_concurrencia_id');
    }
    public function historia_clinica() {
        return $this->belongsTo(GnArchivo::class,'historia_clinica_id');
    }
    public function evento_adverso_eps() {
        return $this->belongsTo(CmEventoadversoeps::class,'cm_eventoadversoeps_id');
    }
    public function evento_adverso_ips() {
        return $this->belongsTo(CmEventoadversoips::class,'cm_eventoadversoips_id');
    }
    public function glosa() {
        return $this->belongsTo(CmManglosa::class,'cm_manglosa_id');
    }

    public static function allRelations() {
        return ['historia_clinica','evento_adverso_eps','evento_adverso_ips','glosa'];
    }

    public function getCmConcurrenciaIdAttribute($value){return (int)$value;}

    public function getCmManglosaIdAttribute($value){return (int)$value;}

    public function getSaludPublicaAttribute($value){return (int)$value;}

    public function getCmEventoadversoepsIdAttribute($value) {return (integer) $value;}

    public function getCmEventoadversoipsIdAttribute($value){return (integer) $value;}
}
