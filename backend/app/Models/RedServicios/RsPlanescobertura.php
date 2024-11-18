<?php

namespace App\Models\RedServicios;

use App\Models\Aseguramiento\AsRegimene;
use App\Models\ContratacionEstatal\CeProconminuta;
use App\Models\General\GnMunicipio;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsPlanescobertura extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'gs_usuario_id',
        'ce_proconminuta_id',
        'nombre',
        'estado',
        'portabilidad',
        'regimen_atendido',
        'pr_detrp_id'
    ];

    protected $searchable = ['search','ce_proconminuta_id'];

    public function contrato()
    {
        return $this->belongsTo(CeProconminuta::class, 'ce_proconminuta_id');
    }

    public function servicios_generales()
    {
        return $this->belongsToMany(RsServicio::class, 'rs_servcontratados','rs_contrato_id','rs_servicio_id')
            ->wherePrimario(0)->withPivot(['porcentaje_afiliados','porcentaje_contratado']);
    }

    public function servicios_primarios()
    {
        return $this->belongsToMany(RsServicio::class, 'rs_servcontratados','rs_contrato_id','rs_servicio_id')
            ->wherePrimario(1)->withPivot(['porcentaje_afiliados','porcentaje_contratado']);
    }

    public function servicios()
    {
        return $this->belongsToMany(RsServicio::class, 'rs_servcontratados','rs_contrato_id','rs_servicio_id');
    }

    public function servicios_contratados()
    {
        return $this->hasMany(RsServcontratado::class, 'rs_contrato_id');
    }

    public function regimen()
    {
        return $this->belongsTo(AsRegimene::class, 'regimen_atendido');
    }

    public function scopeEstaActivo($query)
    {
        $today_string = today()->toDateString();
        return $query->whereEstado('Legalizado')
            ->whereDate('fecha_acta_inicio','<=',$today_string)
            ->whereDate('fecha_finalizacion','>=',$today_string);
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use($builder,$constraint){
                $query->whereHas('contrato.entidad', function ($query) use ($builder,$constraint){
                   $query->where('nombre', $constraint->getOperator(),$constraint->getValue());
                })
                    /*->whereHas('contrato', function ($query) use ($builder,$constraint) {
                        $query->where('numero_contrato',$constraint->getOperator(),$constraint->getValue());
                    } )*/
                    ->orWhere('nombre',$constraint->getOperator(),$constraint->getValue());
                //$query->where('objeto',$constraint->getOperator(),$constraint->getValue());
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    public static function boot()
    {
        parent::boot();

        RsPlanescobertura::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }

            //$table->valor_total = $table->valor;
        });

    }

}
