<?php

namespace App\Models\Niif;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class NfAnedeclaracione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['removable'];
    public function scopeBuscar($query, $data) {
        return $query
            ->where('codigo', 'like', '%'.$data . '%')
            ->orWhere('nombre', 'like', '%'. $data . '%');

    }

    public function niifs(){
        return $this->hasMany(NfNiif::class,'nf_anedeclaracione_id');
    }
}
