<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;

class SCEmpleadoEstudio extends Model
{
    protected $table = 'sc_empleado_estudio';
    protected $primaryKey = 'empleado_estudio';
    protected $fillable = ['establecimiento','titulo','fecha','empleado'];
    public $timestamps = false;

    public function empleado()
    {
        return $this->belongsTo(SCEmpleado::class,'empleado');
    }
}
