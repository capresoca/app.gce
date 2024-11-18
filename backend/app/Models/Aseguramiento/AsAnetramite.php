<?php

namespace App\Models\Aseguramiento;

use App\Models\General\GnArchivo;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsAnetramite extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

    public function anexo(){
        return $this->belongsTo(GnArchivo::class,'gn_archivo_id');
    }
}
