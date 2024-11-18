<?php

namespace App\Models\Aseguramiento;

use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Model;

class AsNucfamiliare extends Model
{
    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

    public function beneficiario(){
        return $this->belongsTo(AsAfiliado::class, 'as_beneficiario_id');
    }

    public function ips(){
        return $this->belongsTo(RsEntidade::class, 'rs_entidade_id');
    }

    public function parentesco(){
        return $this->belongsTo(AsParentesco::class,'as_parentesco_id');
    }


}
