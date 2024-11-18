<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;

class AsTipnovedade extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

    public function campos(){
        return $this->belongsToMany(AsCampo::class, 'as_campnovedades','as_tipnovedad_id','as_campo_id')->orderBy('codigo');
    }
}
