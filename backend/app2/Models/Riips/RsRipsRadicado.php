<?php

namespace App\Models\Riips;

use App\Models\CuentasMedicas\CmRadicado;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsRipsRadicado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $table = 'rs_rips_radicados';

    protected $fillable = [
        'cod_radicacion',
        'cod_radicacion_ct',
        'rs_entidad_id',
        'rs_contrato_id',
        'estado_validacion',
        'estado_radicacion',
        'tipo_facturacion'
    ];

    protected $searchable = ['search','rs_entidad_id'];
    protected $appends = ['radicado_confirmado'];

    public function RsEntidad () {
        return $this->belongsTo(RsEntidade::class,'rs_entidad_id');
    }

    public function getFileUrl($tipo){
        $rutaBase = 'RIPS/'.$this->cod_radicacion.'/archivos/';
        $nombre = $tipo.$this->cod_radicacion_ct.'.TXT';
        return $rutaBase.$nombre;
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE
            || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use($builder,$constraint){
                $query->whereHas('RsEntidad',function ($query) use($builder,$constraint){
                    $query->where('nombre',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('cod_habilitacion',$constraint->getOperator(),$constraint->getValue());
                })->orWhere('estado_validacion',$constraint->getOperator(),$constraint->getValue())
                    ->orWhere('cod_radicacion_ct',$constraint->getOperator(),$constraint->getValue())
                    ->orWhere('cod_radicacion',$constraint->getOperator(),$constraint->getValue())
                    ->orWhere('tipo_facturacion',$constraint->getOperator(),$constraint->getValue());
            });
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public function getRadicadoConfirmadoAttribute($key)
    {
        if ($this->attributes['estado_validacion'] !== 'Confirmado') return null;

        $radicado = CmRadicado::with('facturas')
                                ->where('rs_rip_radicado_id', $this->id)->first();
        return [
            'entidad' => $radicado->entidad,
            'totFacturas' => $radicado->facturas()->count(),
            'contrato' => isset($radicado->contrato) ? $radicado->contrato : 'Sin Contrato'
        ];
    }
}
