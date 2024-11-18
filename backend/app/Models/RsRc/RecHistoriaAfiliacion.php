<?php
namespace App\Models\RsRc;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;

class RecHistoriaAfiliacion extends Model implements Auditable {
    
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    
    protected $table = 'rec_historia_afiliacion';
    protected $primaryKey = 'id_consecutivo';
    public $timestamps = false;
    
    protected $searchable = ['search'];
    
    protected $fillable = [
        'id_afiliado',
        'consecutivo_serial',
        'codigo_entidad',
        'fecha_inicial',
        'fecha_final',
    ];
}

