<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;

class AsTipaportante extends Model
{
    protected $fillable = ['codigo','nombre'];
    protected $hidden = ['created_at','updated_at'];

}
