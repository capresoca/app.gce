<?php
namespace App\Models\RsRc;

use Jedrzej\Pimpable\PimpableTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ResHistoricoAfiliacion extends Model implements Auditable {
    
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    
    protected $table = 'res_historico_afiliacion';
    protected $primaryKey = 'consecutivo_id';
    public $timestamps = false;
    
    protected $searchable = ['search'];
    
    protected $fillable = [
        'afl_id',
        'tipo_identificacion',
        'numero_identificacion',
        'fecha_inicio',
        'fecha_fin',
        'entidad',
    ];
}

