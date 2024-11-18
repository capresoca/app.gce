<?php

namespace App\Models\Aseguramiento;

use App\GsRolUsuario;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AsMsubsidiado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $hidden = ['updated_at'];
    protected $fillable = ['fecha_archivo','nombre_archivo', 'numero_filas','gs_usuario_id','tipo'];
    protected $searchable = ['fecha_archivo','nombre_archivo'];

    public function usuario(){
        return $this->belongsTo(User::class, 'gs_usuario_id');

    }

}
