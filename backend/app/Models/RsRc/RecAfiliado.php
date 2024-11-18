<?php
namespace App\Models\RsRc;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;

class RecAfiliado extends Model implements Auditable {
    
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    
    protected $table = 'rec_afiliado';
    protected $primaryKey = 'consecutivo_serial';
    public $timestamps = false;
    
    protected $searchable = ['search'];
    
    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'sexo',
        'codigo_entidad',
        'fecha_afiliacion',
        'fecha_nacimiento',
    ];
}

