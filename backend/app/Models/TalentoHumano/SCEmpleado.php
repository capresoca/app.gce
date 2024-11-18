<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class SCEmpleado extends Model
{
    use PimpableTrait;
    
    protected $table = 'sc_empleado';
    protected $primaryKey = 'empleado';
    protected $fillable = [
        'tipo_identificacion',
        'numero_identificacion',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'direccion',
        'municipio',
        'sw_vivienda_propia',
        'email',
        'telefono',
        'celular',
        'libreta_militar',
        'fecha_expedicion_libreta',
        'distrito',
        'licencia_conduccion',
        'fecha_expedicion_licencia',
        'lugar_expedicion_licencia',
        'vigencia',
        'categoria',
        'fecha_nacimiento',
        'lugar_nacimiento',
        'sexo',
        'grupo_sanguineo',
        'rh',
        'estado_civil',
        'nro_hijos',
        'profesion',
        'talla_camisa',
        'talla_pantalon',
        'talla_calzado',
        'sw_usa_gafa',
        'certificado_judicial',
        'fecha_expedicion_certificado',
        'fecha_vigencia_certificado',
        'pasaporte',
        'fecha_vigencia_pasaporte',
        'observacion',
        'nombre_conyuge',
        'fecha_nacimiento_conyuge',
        'telefono_conyuge',
        'empresa_conyuge',
        'fecha_ingreso_conyuge',
        'cargo_actual_conyuge',
        'entidad_financiera',
        'municipio_entidad',
        'clase_cuenta',
        'numero_cuenta',
        'pension',
        'cesantia',
        'eps',
        'arp',
        'fondo_aporte',
        'caja',
        'icbf',
        'sena',
        'sw_nomina'
    ];
    public $timestamps = false;
    protected $searchable = ['search'];

    public function municipio()
    {
        return $this->belongsTo(GnMunicipio::class,'id');
    }

    public function municipioEntidad()
    {
        return $this->belongsTo(GnMunicipio::class,'id');
    }

    public function lugarNacimiento()
    {
        return $this->belongsTo(GnMunicipio::class,'id');
    }

    public function lugarExpediconLibreta()
    {
        return $this->belongsTo(GnMunicipio::class,'id');
    }

    public function profesion()
    {
        return $this->belongsTo(TbProfesion::class,'profesion');
    }

    public function pension()
    {
        return $this->belongsTo(TbFondo::class,'fondo');
    }

    public function cesantia()
    {
        return $this->belongsTo(TbFondo::class,'fondo');
    }

    public function eps()
    {
        return $this->belongsTo(TbFondo::class,'fondo');
    }

    public function arp()
    {
        return $this->belongsTo(TbFondo::class,'fondo');
    }

    public function fondoAporte()
    {
        return $this->belongsTo(TbFondo::class,'fondo');
    }

    public function caja()
    {
        return $this->belongsTo(TbFondo::class,'fondo');
    }

    public function icbf()
    {
        return $this->belongsTo(TbFondo::class,'fondo');
    }

    public function sena()
    {
        return $this->belongsTo(TbFondo::class,'fondo');
    }

    public function entidadFinanciera()
    {
        return $this->belongsTo(TbFondo::class,'fondo');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint){
              $query->where('empleado', $constraint->getOperator(), $constraint->getValue());
            })->orWhere(function($query) use ($constraint){
                $query->where('primer_nombre', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('primer_apellido', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('numero_identificacion', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }
}
