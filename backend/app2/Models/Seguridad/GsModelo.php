<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;

class GsModelo extends Model
{
    use PimpableTrait;
    protected $searchable = ['nombre','descripcion', 'tabla','ruta','audits.auditable_type'];


    public function audits(){
        return $this->hasMany(Audit::class,'ruta', 'auditable_type');
    }

    public function children(){
        return $this->hasMany($this, 'padre');
    }

    public function parent(){
        return $this->belongsTo($this,'padre');


    }
}
