<?php
namespace App\Models\RsRc;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;

class ResAfiliacion extends Model implements Auditable {
    
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    
    protected $table = 'res_afiliacion';
    protected $primaryKey = 'consecutivo_id';
    public $timestamps = false;
    
    protected $searchable = ['search'];
    
    protected $fillable = [
        'afc_id',
        'fecha_inicio',
        'fecha_fin',
        'tipo_subsidio',
        'nivel',
        'grupo_poblacional',
        'entidad',
    ];
}

