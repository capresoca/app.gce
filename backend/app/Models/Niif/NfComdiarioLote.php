<?php

namespace App\Models\Niif;

use App\Models\Niif\NfComdiario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\Resource;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use Nexmo\Call\Collection;

class NfComdiarioLote extends Model
{
    use PimpableTrait;

    protected $sortable = [''];
    protected $searchable = ['search'];
    protected $with = ['nfcomdiario'];
    protected $hidden = ['created_at', 'updated_at', 'nf_comdiarios_id', 'id'];

    public function nfcomdiario()
    {
        return $this->belongsTo(NfComdiario::class, 'nf_comdiarios_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($constraint) {
//                $query->where('nf_comdiarios_id', $constraint->getOperator(), $constraint->getValue());
                $query->whereHas('nfcomdiario', function ($query) use ($constraint) {
                    $query->where('detalle', $constraint->getOperator(), strtoupper($constraint->getValue()))
                        ->orWhere('consecutivo', $constraint->getOperator(), $constraint->getValue());
                });
            });
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
