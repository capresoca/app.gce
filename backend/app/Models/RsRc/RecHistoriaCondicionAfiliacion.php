<?php
namespace App\Models\RsRc;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Pimpable\PimpableTrait;

class RecHistoriaCondicionAfiliacion extends Model implements Auditable {
    
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    
    protected $table = 'rec_historia_condicion_afiliacion';
    protected $primaryKey = 'id_consecutivo';
    public $timestamps = false;
    
    protected $searchable = ['search'];
    
    protected $fillable = [
        'id_afiliado',
        'tipo_afiliado',
        'zona_afiliacion',
        'estado_afiliacion',
        'id_departamento',
        'id_municipio',
        'fecha_inicio',
        'fecha_fin',
    ];
}

