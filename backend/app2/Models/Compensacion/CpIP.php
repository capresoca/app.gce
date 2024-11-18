<?php

namespace App\Models\Compensacion;

use Illuminate\Database\Eloquent\Model;

class CpIP extends Model
{
    protected $table = 'cp_ip';
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
        'fecha_pago',
        'forma_presentacion',
        'codigo_sucursal',
        'nombre_sucursal',
        'numero_empleados',
        'numero_afiliados',
        'codigo_operador',
        'tipo_planilla_pensionado',
        'dias_mora',
        'numero_registros_salida'
    ];

    public function detalles()
    {
        return $this->hasMany(CpIpDetalle::class,'cd_ip_id');
    }
}
