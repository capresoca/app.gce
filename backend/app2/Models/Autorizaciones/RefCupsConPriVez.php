<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class RefCupsConPriVez extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'refcupsconprives';
}
