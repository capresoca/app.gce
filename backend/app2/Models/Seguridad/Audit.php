<?php

namespace App\Models\Seguridad;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;

class Audit extends Model
{
    //
    use PimpableTrait;

    protected $searchable = ['user_id','event','auditable_type', 'auditable_id','ip_address','created_at'];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];

    protected $with = ['usuario','modelo'];

    public function usuario(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function modelo(){
        return $this->belongsTo(GsModelo::class,'auditable_type','ruta');
    }
}
