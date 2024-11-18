<?php
namespace App\Models\RsRc;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;


class RecHistoriaCotizanteAportante extends Model implements Auditable {
    
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    
    protected $table = 'rec_historia_cotizante_aportante';
    protected $primaryKey = 'id_consecutivo';
    public $timestamps = false;
    
    protected $searchable = ['search'];
    
    protected $fillable = [
        'consecutivo_serial',
        'id_aportante',
        'id_tipo_cotizante',
        'fecha_inicio',
        'fecha_fin',
        'codigo_entidad',
    ];
}

