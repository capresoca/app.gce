<?php
namespace App\Models\RsRc;

use Jedrzej\Pimpable\PimpableTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ResCondicionAfiliacionSubsidiado extends Model implements Auditable {
    
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    
    protected $table = 'res_condicion_afiliacion_subsidiado';
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

