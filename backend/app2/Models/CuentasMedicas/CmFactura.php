<?php

namespace App\Models\CuentasMedicas;

use App\Models\AuditorCuentas\AcAuditore;
use App\Models\Riips\RsRipsRadicado;
use App\User;
use Brexis\LaravelWorkflow\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class CmFactura extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    use WorkflowTrait;

    protected $table = 'cm_facturas';

    protected $fillable = [
        'cm_radicado_id',
        'consecutivo',
        'estado',
        'fecha',
        'no_contrato',
        'plan_beneficio',
        'valor_comision',
        'valor_compartido',
        'valor_descuentos',
        'valor_neto',
        'gs_usuario_avala_id',
        'feha_repcapita',
        'gs_usuario_capita',
    ];

    protected $searchable = ['search', 'radicado:rs_entidad_id', 'valor_neto'];

    protected $sortable = ['estado', 'valor_neto', 'radicado:rs_entidad_id'];

    protected $appends = [
        'transiciones',
        'entidad',
        'modalidad',
        'valor_capita'
    ];

    public function glosas()
    {
        return $this->hasMany(CmGlosa::class, 'cm_factura_id', 'id');
    }

    public function radicado()
    {
        return $this->belongsTo(CmRadicado::class, 'cm_radicado_id');
    }

    public function servicios()
    {
        return $this->hasMany(CmFacservicio::class, 'cm_factura_id');
    }

    public function nacimientos()
    {
        return $this->hasMany(CmFacnacimiento::class, 'cm_facturas_id');
    }

    public function internaciones()
    {
        return $this->hasMany(CmFacinternacion::class, 'cm_factura_id');
    }

    public function usuarioAvala()
    {
        return $this->belongsTo(User::class, 'gs_usuario_avala_id');
    }

    public function auditores()
    {
        return $this->belongsToMany(AcAuditore::class, 'cm_asignacion_facturas', 'cm_factura_id', 'ac_auditor_id');
    }

    public static function allRelations()
    {
        return [
            'radicado.entidad',
            'radicado.ripRadicado',
            'radicado.usuario',
            'radicado.contrato',
            'radicado.auditoresAsignados',
            'servicios.glosas.glosa',
            'servicios.glosas.auditor',
            'servicios.glosas.factura',
            'servicios.altosCostos.altoCosto',
            'glosas.glosa',
            'glosas.minuta_servicio',
            'usuarioAvala',
            'auditores.usuario',
        ];
    }

    public function getAfiliado()
    {

        $servicio = $this->servicios->first();
        if ($servicio) {
            if ($servicio->afiliado) {
                return $servicio->afiliado['tipo_identificacion'].' '.$servicio->afiliado['identificacion'].' '.$servicio->afiliado['nombre_completo'];
            } else {
                return $servicio['tipo_documento'].' '.$servicio['documento'].' NO REGISTRA EN BD';
            }
        } else {
            return $servicio['tipo_documento'].' '.$servicio['documento'].' NO REGISTRA EN BD';
        }
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $montos = Input::get('montos');
            $radicado = Input::get('id_radicado');
            $builder->where(function ($query) use ($constraint) {
                $query->whereHas('radicado', function ($query) use ($constraint) {
                    $query->whereHas('entidad', function ($query) use ($constraint) {
                        $query->where('cod_habilitacion', $constraint->getOperator(), $constraint->getValue())
                            ->orWhere('nombre', $constraint->getOperator(), $constraint->getValue())
                            ->orWhereHas('tercero', function ($query) use ($constraint) {
                            $query->where('identificacion', $constraint->getOperator(), $constraint->getValue());
                        });
                    })->orWhereHas('ripRadicado', function ($query) use ($constraint) {
                        $query->where('tipo_facturacion', $constraint->getOperator(), $constraint->getValue());
                    });
                })->orWhere('consecutivo', $constraint->getOperator(), strtoupper($constraint->getValue()))
                    ->orWhere('plan_beneficio', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('estado', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('valor_neto', $constraint->getOperator(), $constraint->getValue());
            })->when($montos, function ($query) use ($constraint, $montos) {
                $items = explode(',', $montos);
                if (count($items) > 1) {
                    sort($items);
                    $primer = CmFacturaMonto::find((int) reset($items));
                    $ultimo = CmFacturaMonto::find((int) end($items));
                    $query->whereBetween('valor_neto', [$primer['valor_rango1'], $ultimo['valor_rango2']]);
                } else {
                    $monto = CmFacturaMonto::find((int) $items[0]);
                    $query->whereBetween('valor_neto', [$monto['valor_rango1'], $monto['valor_rango2']]);
                }
            })->when($radicado, function ($query) use ($constraint, $radicado) {
                $query->whereHas('radicado', function ($query) use ($constraint, $radicado) {
                    $query->where('id', '=', $radicado);
                });
            });

            return true;
        }

        return false;
    }

    public function getTransicionesAttribute()
    {
        $transitions = [];
        foreach ($this->workflow_transitions() as $key => $transition) {
            $name = $transition->getName();
            if ($this->workflow_can($name)) {
                array_push($transitions, $name);
            }
        }

        return $transitions;
    }

    public function getEntidadAttribute($key)
    {
        $entidad = CmRadicado::with('entidad.tipo')
            ->whereId($this->attributes['cm_radicado_id'])->first();

        return $entidad['entidad'];
    }

    public function getServContratadosAttribute()
    {
        $servicios = $this->servicios()
            ->with('glosas.glosa', 'altosCostos.altoCosto')
            ->where('tipo', '=','Capita');

        return $servicios;
    }

    public function getModalidadAttribute($key)
    {
        $radicado = $this->radicado()->first()->rs_rip_radicado_id;
        $rips = RsRipsRadicado::select('id','tipo_facturacion')->where('id',$radicado)->first()->tipo_facturacion;

        return $rips;
    }

    public function getValorCapitaAttribute()
    {
        if ($this->modalidad !== 'Capita') {return 0;};
        return ((double) ($this->attributes['valor_neto'] - $this->attributes['valor_compartido']));
    }

    public function getServcapitasAttribute($key)
    {
        if ($this->radicado->tipo_facturacion !== 'Capita') {
            return [];
        }

        $services = DB::select("SELECT a.tipo,
                                            COUNT(*) AS cantidad,
                                            FORMAT(SUM(a.valor_unitario*a.cantidad),0) AS valor_total	
                                    FROM cm_facservicios AS a
                                    WHERE a.cm_factura_id = '{$this->attributes['id']}' AND a.tipo = 'Capita'
                                    GROUP BY a.tipo");

        return json_decode(json_encode($services), true);
    }
    
}
