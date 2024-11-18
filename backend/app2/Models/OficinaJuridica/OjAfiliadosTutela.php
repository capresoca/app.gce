<?php

namespace App\Models\OficinaJuridica;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnTipdocidentidade;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class OjAfiliadosTutela extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'oj_tutela_id',
        'as_afiliado_id',
        'gn_tipdocidentidad_id',
        'identificacion',
        'nombre1',
        'nombre2',
        'apellido1',
        'apellido2',
        'nombre_completo'
    ];

    protected $searchable = ['as_afiliado_id'];

    public function afiliado ()
    {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function tipo_id ()
    {
        return $this->belongsTo(GnTipdocidentidade::class, 'gn_tipdocidentidad_id');
    }
}
