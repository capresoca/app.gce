<?php
namespace App\Models\RsRc;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Pimpable\PimpableTrait;

class ResAfiliado extends Model implements Auditable {
    
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    
    protected $table = 'res_afiliacion';
    protected $primaryKey = 'id_consecutivo';
    public $timestamps = false;
    
    protected $searchable = ['search'];
    
    protected $fillable = [
        'afl_id',
        'primer_apellido',
        'segundo_apellido',
        'primer_nombre',
        'segundo_nombre',
        'fecha_nacimiento',
        'genero',
        'entidad',
    ];
}

