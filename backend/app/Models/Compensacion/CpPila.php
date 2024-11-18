<?php

namespace App\Models\Compensacion;

use Illuminate\Database\Eloquent\Model;

class CpPila extends Model
{
    protected $fillable = [
        'numero_registro',
        'tipo_registro',
        'codigo_formato',
        'nit',
        'digito_verificacion_nit',
        'razon_social_aportante',
        'tipo_documento_aportante',
        'identificacion_aportante',
        'digito_verificacion_aportante',
        'tipo_aportante',
        'direccion_correspondencia',
        'codigo_ciudad_municipio',
        'codigo_departamento',
        'telefono',
        'fax',
        'correo_electronico',
        'periodo_pago',
        'codigo_arl',
        'tipo_planilla',
        'fecha_pago_asociada',
        'fecha_pago',
        'numero_asociada',
        'numero_radicacion',
        'forma_presentacion',
        'codigo_sucursal',
        'nombre_sucursal',
        'numero_empleados',
        'numero_afiliados',
        'codigo_operador',
        'modalidad',
        'dias_mora',
        'numero_registros_salida',
        'fecha_matricula_mercantil',
        'codigo_departamento_34',
        'exonerado_pago_salud',
        'clase_aportante',
        'naturaleza_juridica',
        'tipo_persona',
        'fecha_archivo'
    ];

    public function pila_detalles() {
        return $this->hasMany(CpPiladetalle::class);
    }
}
