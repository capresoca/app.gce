<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsTipaltocosto extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['codigo','nombre','descripcion'];

    // Relaciones
    public function afiliado_altocostos() {
        return $this->hasMany(AsAfiliadoAltocosto:: class);
    }

}
