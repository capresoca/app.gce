<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Cartera\CrVendedore;
use App\Models\Niif\GnTercero;
use App\Models\Niif\NfNiif;
use App\Models\Cartera\CrCliente;
use OwenIt\Auditing\Contracts\Auditable;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;

class CrSaldosiniciale extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'consecutivo',
        'tipo_saldo',
        'cr_cliente_id',
        'gn_tercero_id',
        'cr_vendedor_id',
        'nf_niif_id',
        'numero_factura',
        'fecha_factura',
        'fecha_vencimiento',
        'saldo_factura'
    ];
    protected $searchable = ['search'];
    public function cliente(){
        return $this->belongsTo(CrCliente::class,'cr_cliente_id');
    }
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
              $query->where('consecutivo',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('numero_factura', $constraint->getOperator(), $constraint->getValue());
            });
            return true;
        }
        return false;
    }
}
