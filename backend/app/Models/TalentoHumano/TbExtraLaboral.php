<?php

namespace App\Models\TalentoHumano;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class TbExtraLaboral extends Model
{
    use PimpableTrait;

    protected $table = 'tb_extra_laboral';
    protected $primaryKey = 'consecutivo_extra_laboral';
    protected $fillable = ['descripcion','tipo_documento_nomina','tipo_valor_nomina','consecutivo_cuenta_debito','consecutivo_cuenta_credito'];
    public $timestamps = false;
    protected $searchable = ['search'];

    public function consecutivoCuentaDebito()
    {
        return $this->belongsTo(NfNiif::class,'id');
    }

    public function consecutivoCuentaCredito()
    {
        return $this->belongsTo(NfNiif::class,'id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('consecutivo_extra_laboral','like', $constraint->getValue());
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }
}
