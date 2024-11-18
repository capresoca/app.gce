<?php
namespace App\Models\RsRc;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;

class ResCondicionAfiliacion extends Model implements Auditable {
    
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    
    protected $table = 'res_condicion_afiliacion';
    protected $primaryKey = 'id_consecutivo';
    public $timestamps = false;
    
    protected $searchable = ['search'];
    
    protected $fillable = [
        'afc_id',
        'fecha_inicio',
        'fecha_fin',
        'tipo_afiliado',
        'zona',
        'estado',
        'entidad',
        'departamento',
        'municipio',
    ];
}

