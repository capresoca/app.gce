<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AuEncrespuesta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = ['au_encuesta_id','au_encpregunta_id','respuesta'];
    protected $hidden = ['created_at', 'updated_at'];

    public function encuesta () {
        return $this->belongsTo(AuEncuesta::class, 'au_encuesta_id');
    }

    public function pregunta () {
        return $this->belongsTo(AuEncpregunta::class, 'au_encpregunta_id');
    }
}
