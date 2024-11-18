<?php

namespace App\Models\RedServicios;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class RsCancelportabilidade extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'rs_cancelportabilidades';
    protected $fillable = [
        'rs_portabilidade_id',
        'descripcion',
        'gs_usuario_id'
    ];

    public function getUsuarioAttribute($key)
    {
        $user = User::whereId($this->attributes['gs_usuario_id'])->first();

        return [
            'name' => $user['name'],
            'email' => $user['email']
        ];
    }
}
