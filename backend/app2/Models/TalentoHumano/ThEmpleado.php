<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;
use App\Models\Niif\GnTercero;
use App\Models\TalentoHumano\ThGruposEmpleado;
use App\Models\TalentoHumano\ThSubgruposEmpleado;
use App\Models\TalentoHumano\ThRetiro;
use App\Models\General\GnMunicipio;
use App\Models\TalentoHumano\ThCargosEmpleado;
use App\Models\TalentoHumano\ThProfesionalismo;
use App\Models\Niif\NfNiif;
use App\Models\Tesoreria\TsBanco;
use App\Models\Aseguramiento\AsEps;
use App\Models\Aseguramiento\AsAfp;
use App\Models\Aseguramiento\AsCcf;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class ThEmpleado extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'codigo',
        'gn_tercero_id',
        'th_grupo_empleados_id',
        'th_subgrupo_empleado_id',
        'factura',
        'contrato',
        'meses',
        'salario',
        'alto_riesgo',
        'area',
        'estado',
        'fecha_retiro',
        'th_retiro_id',
        'fecha_retiro_pensiones',
        'fecha_nacimiento',
        'gn_municipio_nacimiento_id',
        'genero',
        'estado_civil',
        'fecha_ingreso',
        'hijos',
        'fecha_ingresoent',
        'personas_acargo',
        'resolucion',
        'tipo_sangre',
        'prima_tecnica',
        'registro_das',
        'sueldo_inicial',
        'variable',
        'sueldo_anterior',
        'fecha_sueldo_anterior',
        'sueldo_nuevo',
        'fecha_sueldo_nuevo',
        'fecha_incantiguedad',
        'fecha_ultbonificacion',
        'fecha_ultnavidad',
        'fecha_ultservicios',
        'fecha_iniciovacaciones',
        'fecha_finalvacaciones',
        'fecha_ingresovacaciones',
        'fecha_periodoliquidadovacaciones',
        'ultimas_vacaciones_pagadas',
        'inc_antiguedad',
        'inc_inicial',
        'inc_anterior',
        'inc_nuevo',
        'ded_vivienda',
        'fecha_vencvivienda',
        'ded_ult12',
        'ded_salud_year',
        'ded_est_med_prep',
        'fecha_vencest',
        'proc_rtf',
        'rtf_por',
        'tipo_vinculacion',
        'jornada_laboral',
        'jornada_asignada',
        'ley_50',
        'meses_completos',
        'sindicato',
        'ibc',
        'dias',
        'horas',
        'th_cargo_inicial_id',
        'th_cargo_actual_id',
        'th_profesionalismo_id',
        'forma_pago',
        'corporacion',
        'nf_niif_id',
        'tipo_cuenta',
        'afiliado_fna',
        'ts_banco_id',
        'retroactividad',
        'Sucursal',
        'tarifa',
        'as_eps_id',
        'fecha_eps',
        'fondo_salud',
        'fecha_salud',
        'as_afps_id',
        'fecha_pension',
        'as_afps_vol1_id',
        'fecha_pensionvol1',
        'as_afps_vol2_id',
        'fecha_pensionvol2',
        'as_afps_vol3_id',
        'fecha_pensionvol3',
        'cesantias',
        'fecha_cesantias',
        'as_ccfs_id',
        'fecha_caja',
        'afiliado_pac',
        'subsidiado_pac',
        'suspender_pac',
        'fecha_afiliacionpac',
        'fecha_suspencionpac'
    ];
    protected $searchable = ['search'];
    public function tercero(){
        return $this->belongsTo(GnTercero::class,'gn_tercero_id');
    }
    public function grupoempleado(){
        return $this->belongsTo(ThGruposEmpleado::class,'th_grupo_empleados_id');
    }
    public function subgrupoempleado(){
        return $this->belongsTo(ThSubgruposEmpleado::class,'th_subgrupo_empleado_id');
    }
    public function retiro(){
        return $this->belongsTo(ThRetiro::class,'th_retiro_id');
    }
    public function municipios(){
        return $this->belongsTo(GnMunicipio::class,'gn_municipio_nacimiento_id');
    }
    public function cargoinicial(){
        return $this->belongsTo(ThCargosEmpleado::class,'th_cargo_inicial_id');
    }
    public function cargoactual(){
        return $this->belongsTo(ThCargosEmpleado::class,'th_cargo_actual_id');
    }
    public function profesionalismo(){
        return $this->belongsTo(ThCargosEmpleado::class,'th_profesionalismo_id');
    }
    public function niif(){
        return $this->belongsTo(NfNiif::class,'nf_niif_id');
    }
    public function banco(){
        return $this->belongsTo(TsBanco::class,'ts_banco_id');
    }
    public function eps(){
        return $this->belongsTo(AsEps::class,'as_eps_id');
    }
    public function afps(){
        return $this->belongsTo(AsAfp::class,'as_afps_id');
    }
    public function afpsvoluntaria1(){
        return $this->belongsTo(AsAfp::class,'as_afps_vol1_id');
    }
    public function afpsvoluntaria2(){
        return $this->belongsTo(AsAfp::class,'as_afps_vol2_id');
    }
    public function afpsvoluntaria3(){
        return $this->belongsTo(AsAfp::class,'as_afps_vol3_id');
    }
    public function ccfs(){
        return $this->belongsTo(AsCcf::class,'as_ccfs_id');
    }
    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint){
              $query->where('codigo', $constraint->getOperator(), $constraint->getValue());
            })->orWhere(function($query) use ($constraint){
                $query->whereHas('tercero',function ($query) use ($constraint) {
                    $query->where('nombre1', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('nombre2', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('apellido1', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('apellido2', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue());
                });
            });
            return true;
        }
        return false;
    }
}