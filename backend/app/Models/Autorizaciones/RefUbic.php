<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RefUbic extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'refubic';
    protected $primaryKey = 'codigo';
    public $incrementing = false;

    protected $fillable = [
        'codigo',
        'descrip',
        'Res744'
    ];

}
