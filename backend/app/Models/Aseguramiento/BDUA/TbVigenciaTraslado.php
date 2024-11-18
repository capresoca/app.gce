<?php

namespace App\Models\Aseguramiento\BDUA;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TbVigenciaTraslado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tb_vigencia_traslado';
    protected $primaryKey = 'consecutivo_vigencia';
    public $timestamps = false;

}
