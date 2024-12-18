<?php

namespace App\Models\Autorizaciones;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class AuAutsolicitude extends Model
{
    use PimpableTrait;

    protected $fillable = [
            'au_autorizacion_id',
            'consecutivo',
            'fecha',
            'gs_usuario_id',
            'estado',
            'justificacion_negacion'
        ];

    protected $searchable = ['search'];

    public function detalles()
    {
        return $this->hasMany(AuAutdetalle::class, 'au_autsolicitud_id');
    }

    public function autorizacion()
    {
        return $this->belongsTo(AuAutorizacione::class, 'au_autorizacion_id');
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        AuAutsolicitude::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }

            $table->consecutivo = AuAutsolicitude::max('consecutivo') + 1;
        });

    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
                $builder->join('au_autorizaciones','au_autorizaciones.id','=','au_autsolicitudes.au_autorizacion_id')
                    ->join('as_afiliados','as_afiliados.id','=','au_autorizaciones.as_afiliado_id')
                    ->select(DB::raw('*,au_autsolicitudes.id as id,au_autsolicitudes.estado as estado'));

                $builder->where(function ($query) use($constraint){
                    $query->where('as_afiliados.nombre_completo', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('as_afiliados.identificacion', $constraint->getOperator(), $constraint->getValue());
                });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
