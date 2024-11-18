<?php

namespace App\Models\Pagos;

use App\Models\Niif\GnTercero;
use App\Models\Niif\NfNiif;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Searchable\Constraint;
use Illuminate\Database\Eloquent\Builder;

class PgProveedore extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;
//    protected $guarded = ['niif', 'tercero'];
    protected $fillable = [
        'gn_tercero_id', 'nf_niif_id', 'tipo_proveedor', 'plazo'
    ];
    protected $searchable = ['search','niif:nombre'];

    public function niif (){
        return $this->belongsTo(NfNiif::class,'nf_niif_id');
    }

    public function tercero(){
        return $this->belongsTo(GnTercero::class,'gn_tercero_id');
    }

    public function getTipoProveedorAttribute(){
        return explode(',', $this->attributes['tipo_proveedor']);
    }

    public function setTipoProveedorAttribute($value)
    {
        if(is_array($value)) {
            $this->attributes['tipo_proveedor'] = implode(',', $value);
        }
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {

            $builder->where(function($query) use ($constraint){
                $query->whereHas('tercero',function ($query) use ($constraint) {
                    $query->where('nombre1', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('nombre2', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('apellido1', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('apellido2', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('nombre_completo', $constraint->getOperator(), $constraint->getValue());
                });
            })->orWhere(function($query) use ($constraint){
                $query->whereHas('niif', function ($query) use ($constraint){
                   $query->where('nombre','like',$constraint->getValue());
                });
            });

            return true;
        }


        // default logic should be executed otherwise
        return false;
    }
}
