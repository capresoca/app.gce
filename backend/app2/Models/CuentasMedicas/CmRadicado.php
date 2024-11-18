<?php

namespace App\Models\CuentasMedicas;

use App\Models\AuditorCuentas\AcRadicadosAsignado;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsPlanescobertura;
use App\Models\Riips\RsRipsRadicado;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class CmRadicado extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'consecutivo',
        'estado',
        'fecha',
        'fecha_radicado',
        'gs_usuario_id',
        'observaciones',
        'periodo_fin',
        'periodo_inicio',
        'radicado_entidad',
        'rs_contrato_id',
        'rs_entidad_id',
        'rs_rip_radicado_id',
        'enviar_glosas'
    ];
    protected $appends = ['consecutivo_radicado', 'avance_proceso', 'gn_tercero_id','nombre_estado','tipo_facturacion','total_glosas'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $searchable = ['search', 'entidad.nombre', 'consecutivo'];

    public function getSortableAttributes()
    {
        $estado = $this->estadolote->estado;
        return ["$estado"];
    }

    public function getTipoFacturacionAttribute($key)
    {
        return $this->ripRadicado['tipo_facturacion'];
    }

    public function estadolote () {
        return $this->hasOne(CmRadcambio::class,'cm_radicado_id')->latest('id');
    }

    public function getNombreEstadoAttribute($key)
    {
        if (is_null($this->estadolote)) return 'Radicado';

        $nombre = null;
        $aceptado = $this->estadolote->aceptado;
        $estado = $this->estadolote->estado;
        switch ($estado) {
            case 'Contabilidad':
                $nombre = $this->getEstadosRadicado($aceptado, $estado);
                break;
            case 'Tesoreria':
                $nombre = $this->getEstadosRadicado($aceptado, $estado);
                break;
            case 'Auditoria':
                $nombre = $this->getEstadosRadicado($aceptado, $estado);
                break;
            case 'CXP':
                $nombre = $this->getEstadosRadicado($aceptado, $estado);
                break;
            case 'Devuelta':
                $nombre = $this->getEstadosRadicado($aceptado, $estado);
                break;
        }

        return $nombre ?? $estado;

    }

    public function auditoresAsignados()
    {
        return $this->hasMany(AcRadicadosAsignado::class, 'cm_radicado_id');
    }

    public function ripRadicado()
    {
        return $this->belongsTo(RsRipsRadicado::class, 'rs_rip_radicado_id');
    }

    public function facturas()
    {
        if ($this->estadolote['estado'] === 'Devuelta') {
            return $this->hasMany(CmFactsdevuelta::class,'cm_radicado_id');
        }

        return $this->hasMany(CmFactura::class, 'cm_radicado_id');
    }

    public function contrato()
    {
        return $this->belongsTo(RsPlanescobertura::class, 'rs_contrato_id');
    }

    public function entidad()
    {
        return $this->belongsTo(RsEntidade::class, 'rs_entidad_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'gs_usuario_id');
    }

    public function getConsecutivoRadicadoAttribute()
    {
        $consecutivo = $this->consecutivo;
        return str_pad($consecutivo, 6, "0", STR_PAD_LEFT);
    }

    public function getGnTerceroIdAttribute($key)
    {
        if (isset($this->entidad)) return $this->entidad->gn_tercero_id;
    }

    public static function allRelations()
    {
        return [
            'entidad.tercero',
            'ripRadicado',
            'contrato.contrato.entidad',
            'contrato.contrato.tipo_contrato',
            'facturas.servicios',
            'usuario',
            'auditoresAsignados.auditor.usuario',
            'estadolote'
        ];
    }

    public function getAvanceProcesoAttribute()
    {
        $totalFacturas = count($this->facturas);
        if ($totalFacturas > 0) {
            $facts = [];
            foreach ($this->facturas as $key => $factura) {
                $serviciosA = CmFacservicio::with('glosas')
                    ->select('id', 'avalado')
                    ->where('cm_factura_id', '=', $factura['id'])->get();
                $serviciosB = CmFacservicio::with('glosas')
                    ->select('id', 'avalado')
                    ->where('cm_factura_id', '=', $factura['id'])
                    ->where('avalado','<>',null)->get();
                if ((count($serviciosA) === count($serviciosB)) &&
                    (($factura['estado'] !== '' || $factura['estado'] !== null)
                        && (($factura['estado'] !== 'Radicada')
                            || ($factura['estado'] !== 'Asignada') || ($factura['estado'] !== 'Auditando')))) {
                    array_push($facts, $factura);
                }
            }

            $result = 0;
            if (count($facts) > 0) {
                $valor = count($facts) / $totalFacturas;
                $result = round(($valor * 100), 0);
            }

            return count($facts) > 0 ? $result : 0;
        }
//        return isset($fact) ? (count($fact) !== $totalFacturas ? count($fact) : $totalFacturas) : 0;
    }

    public function getTotalGlosasAttribute($key)
    {
        $consultaTotGlosas = DB::selectOne("SELECT count(*) AS glosas FROM cm_glosas AS a
                            LEFT JOIN cm_facservicios AS b ON b.id = a.cm_facservicio_id
                            LEFT JOIN cm_facturas AS c ON c.id = b.cm_factura_id
                            LEFT JOIN cm_radicados AS d ON d.id = c.cm_radicado_id
                            WHERE d.consecutivo = '{$this->attributes['consecutivo']}' AND d.rs_rip_radicado_id IS NOT NULL");

        $consultaTotGlosas = json_decode(json_encode($consultaTotGlosas),true);

        return $consultaTotGlosas['glosas'];
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $id_contrato = Input::get('id_contrato');
            $builder->where(function ($query) use ($builder, $constraint) {
                $search = $constraint->getValue();
                if($constraint->getOperator() === Constraint::OPERATOR_LIKE){
                    $search = substr($constraint->getValue(),1,-1);
                }
                $query->where('cm_radicados.consecutivo', '=', $search)
                    ->orWhere('rs_rips_radicados.cod_radicacion_ct',$search);
                if (!is_numeric($search)) {
                    $query->orWhereHas('entidad', function ($query) use ($builder, $constraint) {
                        $query->where('nombre', $constraint->getOperator(), $constraint->getValue())
                            ->orWhere('cod_habilitacion', $constraint->getOperator(), $constraint->getValue());
                    })->orWhereHas('usuario', function ($query) use ($constraint) {
                        $query->where('name', $constraint->getOperator(), $constraint->getValue())
                            ->orWhere('identification', $constraint->getOperator(), $constraint->getValue());
                    })->where('rs_planescoberturas.nombre',$constraint->getValue(), $constraint->getValue());
                }
            })->when($id_contrato, function ($query) use ($constraint, $id_contrato) {
                $query->where('cm_radicados.rs_contrato_id', '=', $id_contrato);
            })->orWhereHas('estadolote', function ($query) use ($constraint) {
                $query->whereHas('status_check',function ($query) use ($constraint) {
                    $query->where('estado', $constraint->getOperator(), $constraint->getValue());
                });
            });
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        CmRadicado::creating(function ($table) {
            if (Auth::user()) {
                $table->gs_usuario_id = Auth::user()->id;
            }
            $table->consecutivo = CmRadicado::max('consecutivo') + 1;
            $table->fecha = Carbon::now()->toDateTimeString();
        });

    }

    /**
     * @param $aceptado
     * @param $estado
     * @return string
     */
    private function getEstadosRadicado($aceptado, $estado): string
    {
        return ($aceptado == '3') ? "Enviado a $estado" : ($aceptado == '2' ? "No aceptado en $estado" : $estado);
    }
}
