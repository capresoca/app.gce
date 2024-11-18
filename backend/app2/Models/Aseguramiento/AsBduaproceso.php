<?php

namespace App\Models\Aseguramiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Illuminate\Support\Str;

class AsBduaproceso extends Model
{
    use PimpableTrait;
    protected $searchable = ['consecutivo','fecha','tipo',''];

    protected $fillable = [
        'consecutivo',
        'fecha',
        'tipo',
        'gs_usuario_id',
        'created_at',
        'updated_at',
        'uuid'
    ];

    public function archivos(){
        return $this->hasMany(AsBduaarchivo::class, 'as_bduaproceso_id');
    }

    public static function boot()
    {
        parent::boot();

        AsBduaproceso::saving(function($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }
            $table->uuid = Str::uuid();

            $table->consecutivo = AsBduaproceso::max('consecutivo') + 1;
        });
    }
}
