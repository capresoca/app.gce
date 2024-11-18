<?php

namespace App\Models\Aseguramiento;

use App\Models\General\GnArchivo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class AsDecauttramite extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];
    protected $hidden = ['created_at','updated_at'];

    public function declaracion(){
        return $this->belongsTo(AsDeclaracione::class,'as_declaracione_id');
    }

    public function anexo(){
        return $this->belongsTo(GnArchivo::class, 'gn_archivo_id');
    }

}
