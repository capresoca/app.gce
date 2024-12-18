<?php

namespace App\Models\Autorizaciones;

use App\Models\Aseguramiento\AsTramite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class AuMedicosSolicitante extends Model
{
    use PimpableTrait;

    protected $fillable = [
        'codigo',
        'descripcion',
        'au_especialidad_id',
        'gs_usuario_id'
    ];

    protected $searchable = ['search'];

    public function especialidad()
    {
        return $this->belongsTo(AuEspecialidad::class,'au_especialidad_id');
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
                $query->where('codigo', 'like',$search.'%');
                if(!is_numeric($search)){
                    $query->orWhere('descripcion', 'like','%'.$search.'%');
                }

            });
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        AuMedicosSolicitante::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }
        });

    }

}
