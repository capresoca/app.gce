<?php
namespace App\Models\RsRc;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;

class RecHistoriaGrupoFamiliar extends Model implements Auditable {
    
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    
    protected $table = 'rec_historia_grupo_familiar';
    protected $primaryKey = 'id_consecutivo';
    public $timestamps = false;
    
    protected $searchable = ['search'];
    
    protected $fillable = [
        'consecutivo_serial',
        'consecutivo_serial_cotizante',
        'id_parentesco',
        'fecha_inicio',
        'fecha_fin',
        'codigo_entidad',
    ];
}

