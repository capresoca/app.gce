<?php

namespace App\Models\Niif;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class NfNiif extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'codigo',
        'nombre',
        'nf_nivcuenta_id',
        'nf_clascuenta_id',
        'tipo', 'tipo_retencion',
        'nf_anedeclaracione_id',
        'nf_padre_id',
        'maneja_tercero',
        'disponibilidad',
        'maneja_ccosto',
        'estado'
    ];

//    protected $guarded = ['nivel','clascuenta'];
    protected $withable = ['*'];

    protected $searchable = ['search','nivel:codigo','nivel:auxiliar','codigo', 'nombre','tipo'];

    public function children(){
        return $this->hasMany(NfNiif::class, 'nf_padre_id');
    }

    public function padre(){
        return $this->belongsTo(NfNiif::class, 'nf_padre_id');
    }
    public function nivel(){
        return $this->belongsTo(NfNivcuenta::class, 'nf_nivcuenta_id');
    }

    public function clascuenta () {
        return $this->belongsTo(NfClascuenta::class, 'nf_clascuenta_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint) {
                    $query->where('nombre',$constraint->getOperator(), $constraint->getValue())
                        ->orWhere('codigo',$constraint->getOperator(), $constraint->getValue());
                });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
