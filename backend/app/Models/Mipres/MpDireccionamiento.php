<?php

namespace App\Models\Mipres;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class MpDireccionamiento extends Model
{
    use SoftDeletes, PimpableTrait;

    protected $fillable = [
        'IdDireccionamiento',
        'mp_prescripcion_id',
        'mp_tutela_id',
        'NoPrescripcion',
        'TipoTec',
        'ConTec',
        'TipoIDPaciente',
        'NoIDPaciente',
        'as_afiliado_id',
        'NoEntrega',
        'NoSubEntrega',
        'TipoIDProv',
        'NoIDProv',
        'rs_entidade_id',
        'CodMunEnt',
        'gn_munentidad_id',
        'FecMaxEnt',
        'CantTotAEntregar',
        'DirPaciente',
        'CodSerTecAEntregar',
        'mp_medicamento_id',
        'mp_coplementario_id',
        'mp_nutricional_id',
        'mp_dispositivo_id',
        'mp_procedimiento_id',
        'user_id',
        'suministro_id',
        'created_at',
        'deleted_at',
        'EstDireccionamiento',
        'deleted_by'
    ];

    protected $searchable = ['FecMaxEnt','TipoTec','search'];

    public function prescripcion()
    {
        return $this->belongsTo(MpPrescripcione::class, 'mp_prescripcion_id');
    }

    public function tutela()
    {
        return $this->belongsTo(MpTutela::class,'mp_tutela_id');
    }

    public function entidad()
    {
        return $this->belongsTo(RsEntidade::class,'rs_entidade_id');
    }

    public function afiliado()
    {
        return $this->belongsTo(AsAfiliado::class,'as_afiliado_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(MpNotificacion::class,'mp_direccionamiento_id');
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        MpDireccionamiento::creating(function ($table){
            if(Auth::user()){
                $table->user_id  = Auth::user()->id;
            }
        });

    }

    public function scopeWithAll($query)
    {
        return $query->with([
            'prescripcion.afiliado',
            'entidad',
            'prescripcion.entidad',
            'tutela.afiliado',
            'notificaciones',
            'reportes'
        ]);
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use($builder,$constraint){
                $search = $constraint->getValue();
                if($constraint->getOperator() === Constraint::OPERATOR_LIKE){
                    $search = substr($constraint->getValue(),1,-1);
                }
                $query->orWhere('NoPrescripcion', 'like','%'.$search.'%')->orWhere('NoIDPaciente', 'like','%'.$search.'%');
            });

            return true;

        }


        // default logic should be executed otherwise
        return false;
    }

    public function reportes()
    {
        return $this->hasMany(MpReporteentrega::class,'suministro_id','suministro_id');
    }

}
