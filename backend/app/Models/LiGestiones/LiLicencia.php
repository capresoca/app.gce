<?php

namespace App\Models\LiGestiones;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use App\Models\RedServicios\RsEntidades2;
use App\Models\Aseguramiento\AsPagadore;
use App\Models\General\GnMunicipio;
use App\Models\General\GnDepartamento;
use App\Models\Autorizaciones\AuMedicosSolicitante;
use App\Models\RedServicios\RsCie10;
use App\Models\LiGestiones\LiEnvioEncabezado;

class LiLicencia extends Model
{
    use PimpableTrait;

    protected $primaryKey = 'consecutivo_licencia';
    protected $fillable = [
                            'primer_apellido',
                            'consecutivo_afiliado',
                            'consecutivo_ips',
                            'consecutivo_medico',
                            'nombre_profesional',
                            'cargo',
                            'diagnostico_principal',
                            'tipo_incapacidad',
                            'fecha_inicio',
                            'fecha_fin',
                            'fecha_expedicion', 
                            'numero_dias_incapacidad',
                            'tipo_parto',
                            'fecha_propable_parto',
                            'fecha_parto',
                            'semanas_gestacion',
                            'numero_hijos',
                            'estado_licencia',
                            'fecha_grabado',
                            'usuario_grabado',
                            'tipo_identificacion_afiliado',
                            'numero_identificacion_afiliado',
                            'estado_afiliado',
                            'estado_traslado',
                            'tipo_regimen_afiliado', 
                            'municipio_afiliado',
                            'departamento_afiliado',
                            'estado_rc',
                            'primer_nombre',
                            'segundo_nombre',
                            'primer_apellido',
                            'segundo_apellido',
                            'registro_profesional',
                            'consecutivo_aportante',
                            'fecha_probable_pago',
                            'fecha_solicitud',
                            'dias_acumulados',
                            'dias_nacimiento_prematuro',
                            'dias_gestacion', 
                            'tipo_prestacion_economica',
                            'serial_fosyga',
                            'tipo_identificacion_aportante',
                            'numero_identificacion_aportante',
                            'ibc',
                            'tipo_salario',
                            'usuario_solicitud_aportante',
                            'fecha_solicitud_aportante',
                            'consecutivo_solicitud_aportante',
                            'numero_solicitud_aportante',
                            'fecha_rechazo_eps_solicitud_aportante',
                            'usuario_rechazo_eps_solicitud_aportante',
                            'fecha_aprobado_eps_solicitud_aportante',
                            'usuario_aprobado_eps_solicitud_aportante', 
                            'email',
                            'telefono',
                            'celular',
                            'tipo_cotizante',
                            'consecutivo_envio_encabezado',
                            'consecutivo_tutela',
                            'valor_provision_incapacidad',
                            'fecha_inicio_total',
                            'fecha_fin_total', 
                            'dias_total',
                            'estado_gestion',
                            'concepto_gestion',
                            'justificacion_gestion',
                            'sw_arl_gestion',
                            'usuario_gestion',
                            'fecha_gestion',
                            'usuario_rechazo',
                            'fecha_rechazo', 
                            'motivo_rechazo',
                            'fecha_inicio_liquida',
                            'fecha_fin_liquida',
                            'dias_liquida',
                            'tipo_documento_fallecimiento_madre',
                            'numero_documento_fallecimiento_madre',
                            'numero_autorizacion_fallecimiento_madre',
                            'dias_faltantes_fallecimiento_madre'
                          ];
    protected $searchable = ['search'];

    public function aportante()
    {
        return $this->belongsTo(AsPagadore::class,'consecutivo_aportante');
    }

    public function municipio()
    {
        return $this->belongsTo(GnMunicipio::class,'municipio_afiliado');
    }

    public function departamento()
    {
        return $this->belongsTo(GnDepartamento::class,'departamento_afiliado');
    }

    public function rsentidades() // consecutivo_ips
    {
        return $this->belongsTo(RsEntidades2::class,'consecutivo_ips');
    }

    public function medicos()
    {
        return $this->belongsTo(AuMedicosSolicitante::class,'consecutivo_medico');
    }

    public function diagnosticoPrincipal()
    {
        return $this->belongsTo(RsCie10::class,'diagnostico_principal');
    }

    public function envioEncabezado()
    {
        return $this->belongsTo(LiEnvioEncabezado::class,'consecutivo_envio_encabezado');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('primer_apellido','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
