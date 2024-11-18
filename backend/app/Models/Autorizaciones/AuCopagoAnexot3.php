<?php

namespace App\Models\Autorizaciones;

use App\Models\Aseguramiento\AsAfiliado;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AuCopagoAnexot3 extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'au_copago_anexot3s';

    protected $fillable = [
        'as_afiliado_id',
        'copago',
        'cuota_moderadora',
        'au_anext31_id',
        'fecha_autorizacion'
    ];

    public function afiliado () {
        return $this->belongsTo(AsAfiliado::class,'as_afiliado_id');
    }

    public function detalle_autorizacion () {
        return $this->belongsTo(AuAnexot3::class,'au_anext31_id');
    }
}
