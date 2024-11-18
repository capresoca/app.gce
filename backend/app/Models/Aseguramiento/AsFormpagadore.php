<?php

namespace App\Models\Aseguramiento;

use App\Models\Niif\GnTercero;
use App\Models\Aseguramiento\AsTipaportante;
use App\Models\Aseguramiento\AsTramite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AsFormpagadore extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'as_tipaportante_id',
        'as_tramite_id',
        'digito_verificacion',
        'estado',
        'gn_tercero_id',
        'sector_aportante',
        'fecha_radicacion'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    protected $searchable = ['search'];

    public function tercero () {
        return $this->belongsTo(GnTercero::class, 'gn_tercero_id');
    }

    public function tipoAportante () {
        return $this->belongsTo(AsTipaportante::class, 'as_tipaportante_id');
    }

    public function tramite () {
        return $this->belongsTo(AsTramite::class, 'as_tramite_id');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('tercero', function ($query) use ($constraint){
                $query->where('nombre1', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('nombre2', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido1', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido2', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue());
            });

            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public function setFechaRadicacionAttribute ($value)
    {
        $this->attributes['fecha_radicacion'] = $value . substr(now(), 10);
    }

    public function getFechaRadicacionAttribute ()
    {
        return substr($this->attributes['fecha_radicacion'], 0, -8);
    }
}
