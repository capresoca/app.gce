<?php

namespace App\Models\Inventarios;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use App\Models\Niif\NfNiif;
use App\Models\Niif\NfConrtf;
use App\Models\Presupuesto\PrRubro;

class InGrupo extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
      'codigo',
      'nombre',
      'clase',
      'nf_niif_ingresos_id',
      'nf_niif_gastos_id',
      'nf_niif_venta_id',
      'nf_niif_rtfencompras_id',
      'nf_rtfs_paracompras_id',
      'ica',
      'iva',
      'pr_rubro_id'
    ];
    protected $searchable = ['search'];
    public function ingresos(){
        return $this->belongsTo(NfNiif::class,'nf_niif_ingresos_id');
    }
    public function gastos(){
        return $this->belongsTo(NfNiif::class,'nf_niif_gastos_id');
    }
    public function venta(){
        return $this->belongsTo(NfNiif::class,'nf_niif_venta_id');
    }
    public function rtfencompras(){
        return $this->belongsTo(NfNiif::class,'nf_niif_rtfencompras_id');
    }
    public function rtfparacompras(){
        return $this->belongsTo(NfConrtf::class,'nf_rtfs_paracompras_id');
    }
    public function rubro(){
        return $this->belongsTo(PrRubro::class,'pr_rubro_id');
    }
    protected function processSearchFilter(Builder $builder, Constraint $constraint) {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function($query) use ($constraint){
              $query->where('nombre',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('codigo', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }
}
