<?php
namespace App\Models\RsRc;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;

class RecAportante extends Model implements Auditable {
    
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    
    protected $table = 'rec_aportante';
    protected $primaryKey = 'consecutivo_aportante';
    public $timestamps = false;
    
    protected $searchable = ['search'];
    
    protected $fillable = [
        'tipo_identificacion',
        'numero_identificacion',
        'digito_verificacion',
        'razon_social',
    ];
}

