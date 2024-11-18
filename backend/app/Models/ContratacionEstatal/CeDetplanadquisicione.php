<?php

namespace App\Models\ContratacionEstatal;

use App\Models\Pagos\PgUniapoyo;
use App\Models\Presupuesto\PrRubro;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeDetplanadquisicione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'ce_bienservicio_id', 'ce_planadquisicione_id',
        'duracion', 'fecha_ofertas', 'fecha_proceso',
        'pg_uniapoyo_id', 'ce_modcontratacione_id', 'valor'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $appends = ['fecha_proceso_slash', 'fecha_ofertas_slash'];

    public function planadquisicione () {
        return $this->belongsTo(CePlanadquisicione::class, 'ce_planadquisicione_id');
    }

    public function bienServicio () {
        return $this->belongsTo(CeBienservicio::class, 'ce_bienservicio_id');
    }

    public function uniApoyo () {
        return $this->belongsTo(PgUniapoyo::class, 'pg_uniapoyo_id');
    }

    public function modContratacione () {
        return $this->belongsTo(CeModcontratacione::class, 'ce_modcontratacione_id');
    }

    public function rubros () {
//        return $this->hasMany(CeDetpaarubro::class, 'pr_rubro_id','ce_detplanadquisicione_id');
        return $this->belongsToMany(PrRubro::class, 'ce_detpaarubros','ce_detplanadquisicione_id','pr_rubro_id');
    }

    public function getFechaProcesoSlashAttribute ()
    {
        $fecha1 = new Carbon($this->fecha_proceso);
        return $fecha1->format('d/m/Y');
    }

    public function getFechaOfertasSlashAttribute ()
    {
        $fecha2 = new Carbon($this->fecha_ofertas);
        return $fecha2->format('d/m/Y');
    }
}
