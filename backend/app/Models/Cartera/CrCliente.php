<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Cartera\CrVendedore;
use App\Models\Niif\GnTercero;
use App\Models\Niif\NfNiif;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class CrCliente extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
		'codigo',
		'nombre',
		'gn_tercero_id',
		'nf_niif_id',
		'cr_vendedor_id',
		'plazo',
		'cupo',
		'descuento',
		'contacto',
		'maneja_iva',
		'tipo_cliente',
		'estado'
    ];
    protected $searchable = ['search'];
    public function vendedor(){
        return $this->belongsTo(CrVendedore::class,'cr_vendedor_id');
    }
    public function tercero(){
        return $this->belongsTo(GnTercero::class,'gn_tercero_id');
    }
    public function niif(){
        return $this->belongsTo(NfNiif::class,'nf_niif_id');
    }
    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint){
            	$query->where('nombre',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('codigo', $constraint->getOperator(), $constraint->getValue());
            })->orWhere(function($query) use ($constraint){
                $query->whereHas('tercero',function ($query) use ($constraint) {
                    $query->where('nombre1', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('nombre2', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('apellido1', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('apellido2', $constraint->getOperator(), $constraint->getValue())
                        ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue());
                });
            });
            return true;
        }
        return false;
    }
}
