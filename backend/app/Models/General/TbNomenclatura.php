<?php
namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class TbNomenclatura extends Model
{
    protected $hidden = ['created_at','updated_at'];
    protected $table = 'tb_nomenclatura';
    protected $primaryKey = 'nomenclatura';
}

