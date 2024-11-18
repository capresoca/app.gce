<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;

class SCEmpleadoLaboral extends Model
{
    protected $table = 'sc_empleado_laboral';
    protected $primaryKey = 'empleado_laboral';
    protected $fillable = ['empresa','cargo','fecha_ingreso','fecha_retiro','direccion','telefono','empleado'];
    public $timestamps = false;

    public function empleado()
    {
        return $this->belongsTo(SCEmpleado::class,'empleado');
    }
}
