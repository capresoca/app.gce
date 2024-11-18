<?php

namespace App\Models\CuentasMedicas;

use App\Models\RedServicios\RsCups;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CmConcup extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $searchable = ['search'];
    protected $fillable = [
        'cantidad',
        'valor',
        'observaciones',
        'rs_cup_id',
        'cm_convisita_id',
        'tipo_servicio',
        'causa',
        'causa_especifica',
        'fecha_desde',
        'fecha_hasta',
        'cm_manglosa_id'
    ];

    // Relaciones
    public function visita() {
        return $this->belongsTo(CmConvisita::class,'cm_convisita_id' );
    }
    public  function cup() {
        return $this->belongsTo(RsCups::class,'rs_cup_id' );
    }
    public function glosa() {
        return $this->belongsTo(CmManglosa::class, 'cm_manglosa_id');
    }
}
