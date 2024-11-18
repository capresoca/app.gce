<?php

namespace App\Models\ContratacionEstatal;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CeProconconvocatoria extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use PimpableTrait;

    protected $fillable = [
        'ce_procontractuale_id', 'fecha_apertura', 'fecha_cierre'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function proContractual () {
        return $this->belongsTo(CeProcontractuale::class, 'ce_procontractuale_id');
    }
}
