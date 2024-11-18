<?php

namespace App\Models\CuentasMedicas;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CmConcondclinica extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['nombre','descripcion'];

    // Relaciones
    public function visitas() {
        return $this->belongsToMany(CmConvisita::class )->withTimestamps();
    }

}
