<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class AuMotQuejapqr extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'au_mot_quejapqrs';

    protected $fillable = [
        'codigo',
        'nombre'
    ];

}
