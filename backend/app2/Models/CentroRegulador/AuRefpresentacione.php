<?php

namespace App\Models\CentroRegulador;

use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AuRefpresentacione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'au_referencia_id',
        'estado',
        'fecha_aceptacion',
        'fecha_llegada',
        'fecha_presentacion',
        'fecha_salida',
        'medico_acepta',
        'rs_entidades_id',
        'seleccionada'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function referencia () {
        return $this->belongsTo(AuReferencia::class, 'au_referencia_id');
    }

    public function entidad () {
        return $this->belongsTo(RsEntidade::class, 'rs_entidades_id');
    }
}
