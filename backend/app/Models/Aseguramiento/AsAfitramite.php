<?php

namespace App\Models\Aseguramiento;

use App\Models\General\GnEmpresa;
use App\Models\Niif\GnTercero;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AsAfitramite extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $guarded = ['tramite'];
    protected $hidden = ['created_at','updated_at'];

    public function tramite()
    {
        return $this->belongsTo(AsTramite::class,'as_tramite_id');
    }


    public function afiliado(){
        return $this->belongsTo(AsAfiliado::class,'as_afiliado_id');
    }

}
