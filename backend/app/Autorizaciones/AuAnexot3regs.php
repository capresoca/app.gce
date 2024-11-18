<?php

namespace App\Autorizaciones;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AuAnexot3regs extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'au_anexot3regs';
    protected $fillable = ['as_regimene_id','au_anexot3_id'];
}
