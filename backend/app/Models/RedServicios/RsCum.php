<?php

namespace App\Models\RedServicios;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsCum extends Model
{
    use PimpableTrait;

    protected $searchable = ['producto', 'titular', 'expediente_cum', 'registro_sanitario', 'principio_activo' , 'search'];

    protected $fillable = [
        'expediente', 'producto',
        'titular', 'registro_sanitario',
        'fecha_expedicion', 'fecha_vencimiento',
        'estado_registro', 'expediente_cum',
        'consecutivo', 'cantidad_cum',
        'descripcion_comercial', 'estado_cum',
        'fecha_activo', 'fecha_inactivo',
        'muestra_medica', 'unidad',
        'atc', 'descripcion_atc',
        'via_administracion', 'concentracion',
        'principio_activo', 'unidad_medida',
        'cantidad', 'unidad_referencia',
        'forma_farmaceutica', 'nombre_rol',
        'tipo_rol', 'modalidad', 'codigo', 'clasificacion'
    ];
    protected $hidden = ['created_at', 'updated_at'];
//    protected $appends = ['codigo'];

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('producto', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('codigo', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('titular', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('registro_sanitario', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('principio_activo', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('clasificacion', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public function setCodigoAttribute ($value) {
        $this->attributes['codigo'] = $this->expediente_cum . '-' . $this->consecutivo;
    }

    public function setExpedienteCumAttribute ($value) {
        $this->attributes['expediente_cum'] = $this->expediente;
    }

    public function getFechaExpedicionAttribute () {
        $fecha1 = substr($this->attributes['fecha_expedicion'], 0, 10);
        $fechaConvert = new Carbon($fecha1);
        return $fechaConvert->format('Y-m-d');
    }

    public function setFechaExpedicionAttribute ($value) {
        $fecha1 = new Carbon($value);
        $fechaConvert = $fecha1->format('m/d/Y');
        $this->attributes['fecha_expedicion'] = $fechaConvert;
    }

    public function getFechaVencimientoAttribute () {
        $fecha2 = substr($this->attributes['fecha_vencimiento'], 0, 10);
        $fechaConvert2 = new Carbon($fecha2);
        return $fechaConvert2->format('Y-m-d');
    }

    public function setFechaVencimientoAttribute ($value) {
        $fecha2 = new Carbon($value);
        $fechaConvert2 = $fecha2->format('m/d/Y');
        $this->attributes['fecha_vencimiento'] = $fechaConvert2;
    }

    public function getFechaActivoAttribute () {
        $fecha3 = substr($this->attributes['fecha_activo'], 0, 10);
        $fechaConvert3 = new Carbon($fecha3);
        return $fechaConvert3->format('Y-m-d');
    }

    public function setFechaActivoAttribute ($value) {
        $fecha3 = new Carbon($value);
        $fechaConvert3 = $fecha3->format('m/d/Y');
        $this->attributes['fecha_activo'] = $fechaConvert3;
    }

    public function getFechaInactivoAttribute () {
        $fecha4 = substr($this->attributes['fecha_inactivo'], 0, 10);
        $fechaConvert4 = new Carbon($fecha4);
        return $fechaConvert4->format('Y-m-d');
    }

    public function setFechaInactivoAttribute ($value) {
        $fecha4 = new Carbon($value);
        $fechaConvert4 = $fecha4->format('m/d/Y');
        $this->attributes['fecha_inactivo'] = $fechaConvert4;
    }

//    public function getFechaVencimientoAttribute () {
//        $fecha2 = new Carbon($this->fecha_vencimiento);
//        return $fecha2->format('Y/m/d');
//    }

}
