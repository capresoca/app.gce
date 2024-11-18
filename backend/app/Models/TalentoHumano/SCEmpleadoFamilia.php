<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;

class SCEmpleadoFamilia extends Model
{
    protected $table = 'sc_empleado_familia';
    protected $primaryKey = 'empleado_familia';
    protected $fillable = ['parentesco','grado','nombre','direccion','telefono','fecha_nacimiento','empleado'];
    public $timestamps = false;

    public function empleado()
    {
        return $this->belongsTo(SCEmpleado::class,'empleado');
    }
}
