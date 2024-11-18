<?php

namespace App\Models\Aseguramiento;

use App\Traits\ToolsTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Carbon\Carbon;
use App\Models\General\GnArchivo;
use Jedrzej\Searchable\Constraint;

class AsSoltraslado extends Model
{
    use PimpableTrait;

    protected $searchable = ['bduaserial','as_regimen_id','estado','regimen:nombre','search'];

    protected $fillable = [
        'as_bduaproceso_id',
        'gn_archivo_id',
        'bduaserial',
        'as_afiliado_id',
        'as_eps_id',
        'fecha_solicita',
        'fecha_apropiacion',
        'respuesta',
        'as_cautraslado_id',
        'fecha_factible',
        'gs_usuresponde_id',
        'estado',
        'nombre1S2',
        'nombre2S2',
        'apellido1S2',
        'apellido2S2',
        'cod_departamentoS2',
        'cod_municipioS2',
        'tip_docu_cabfamiliaS2',
        'identificacion_cabfamiliaS2',
        'parentescoS2',
        'as_tramite_id',
        'as_regimen_id'
    ];
    protected $appends=['dias_eps'];

    public function afiliado(){
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function eps(){
        return $this->belongsTo(AsEps::class, 'as_eps_id');
    }
    
    public function tramite()
    {
        return $this->belongsTo(AsTramite::class,'as_tramite_id');
    }
    public function regimen()
    {
        return $this->belongsTo(AsRegimene::class,'as_regimen_id');
    }
    public function causa()
    {
        return $this->belongsTo(AsCautraslado::class,'as_cautraslado_id');
    }
    public function archivo()
    {
        return $this->belongsTo(GnArchivo::class,'gn_archivo_id');
    }
    
    public function setFechaSolicitaAttribute($value){
        $this->attributes['fecha_solicita'] = ToolsTrait::homologarFecha($value);
    }

    public function setFechaApropiacionAttribute($value){
        $this->attributes['fecha_apropiacion'] = ToolsTrait::homologarFecha($value);
    }

    public function getTransformRespuestaAttribute()
    {
        if($this->respuesta == 'Negado'){
            return 0;
        }
        if($this->respuesta == 'Aprobado'){
            return 1;
        }
        return '';
    }
    public function getFechaFactibleSlashAttribute()
    {
        return Carbon::parse($this->fecha_factible)->format('d/m/Y');
    }

    public function getDiasEpsAttribute(){
        return Carbon::parse($this->afiliado->fecha_afiliacion)->diffInDays($this->fecha_solicita);
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint){
                $query->whereHas('afiliado', function ($query) use ($constraint) {
                    $search = $constraint->getValue();
                    if($constraint->getOperator() === Constraint::OPERATOR_LIKE) {
                        $search = substr($constraint->getValue(),1,-1);
                    }
                    $query->where('identificacion', $search);
                    if(!is_numeric($search)){
                        $query->orWhere('nombre_completo', 'like','%'.$search.'%');
                    }
                });
            });
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }
}

