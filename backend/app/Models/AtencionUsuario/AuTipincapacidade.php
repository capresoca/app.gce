<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Model;

class AuTipincapacidade extends Model
{
    protected $fillable = ['codigo','tipo','porcentaje','max_dias','tipo_dias'];

    protected $appends = ['removable'];

    public function incapacidades()
    {
        return $this->hasMany(AuIncapacidade::class, 'au_tipincapacidades_id');
    }

    public function prestacion()
    {
        return $this->belongsTo(AuTipprestacione::class, 'au_tipprestacion_id');
    }

    public function getRemovableAttribute()
    {
        return $this->incapacidades()->count() ? false : true ;
    }
}
