<?php

namespace App\Models\Pagos;

use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCencosto;
use App\Models\Niif\NfNiif;
use App\Models\Presupuesto\PrObligacione;
use App\Models\Tesoreria\TsConceptoegresoDetalle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use NumeroALetras;
use OwenIt\Auditing\Contracts\Auditable;

class PgCxp extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
//    protected $guarded = ['detalles','proveedor','niif'];
    protected $fillable = [
        'consecutivo',
        'fecha_causacion',
        'pg_proveedore_id',
        'no_factura',
        'fecha_factura',
        'fecha_vencimiento',
        'nf_niif_id',
        'gn_tercero_id',
        'nf_cencosto_id',
        'observaciones',
        'modulo',
        'estado',
        'detalle_anulacion',
        'cxp_plazo'
    ];

    public $sortable = ['consecutivo','fecha_causacion'];
    public $searchable = ['search','fecha_causacion','consecutivo','detalle','created_at','modulo','gn_tercero_id'];

    protected $appends = ['valor_moneda','valor_credito','valor_debito','valor_debito_moneda','valor_credito_moneda','valor_mon','valor_sin_formato','saldo'];

    public function detalles () {
        return $this->hasMany(PgCxpdetalle::class,'pg_cxp_id');
    }
    public function obligacion () {
        return $this->hasOne(PrObligacione::class,'pg_cxp_id');
    }

    public function tercero () {
        return $this->belongsTo(GnTercero::class, 'gn_tercero_id');
    }

    public function proveedor () {
        return $this->belongsTo(PgProveedore::class,'pg_proveedore_id');
    }

    public function niif () {
        return $this->belongsTo(NfNiif::class,'nf_niif_id');
    }

    public function cencosto () {
        return $this->belongsTo(NfCencosto::class, 'nf_cencosto_id');
    }

    //Relación a los detalles de comprobante de egreso
    public function totalAbonosComprobantes()
    {
        return TsConceptoegresoDetalle::join('ts_compegreso_conceptos','ts_compegreso_conceptos.id','ts_compegreso_concepto_id')
                                        ->join('ts_compegresos','ts_compegresos.id','ts_compegreso_id')
                                        ->where([
                                            'ts_compegresos.estado' => 'Confirmado',
                                            'ts_conceptoegreso_detalles.pg_cxp_id' => $this->id
                                        ])->sum('ts_conceptoegreso_detalles.valor');

    }

    //Relación a los detalles de comprobante de egreso
    public function totalAnticipos()
    {
        return PgAnticipoCxp::join('pg_anticipocxps_dets','pg_anticipo_cxps.id','pg_anticipocxps_dets.pg_anticipo_cxp_id')
                                ->where([
                                    'pg_cxp_id' => $this->id,
                                    'estado' => 'Confirmado'
                                ])->sum('valor_abono');

    }

    public function getSaldoAttribute()
    {
        return $this->valor_sin_formato - $this->totalAbonosComprobantes() - $this->totalAnticipos();
    }

//    public function getSearchableAttributes()
//    {
//        return ['fecha_causacion','consecutivo','detalle','created_at'];
//->orWhere('fecha_causacion', $constraint->getOperator(), $constraint->getValue())
//    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('proveedor.tercero', function ($query) use ($constraint) {
                $query->where('nombre1',$constraint->getOperator(), $constraint->getValue())
                    ->orWhere('nombre2',$constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido1',$constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido2',$constraint->getOperator(), $constraint->getValue())
                    ->orWhere('identificacion',$constraint->getOperator(), $constraint->getValue());
            })->orWhere('consecutivo', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('no_factura', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public function getValorDebitoMonedaAttribute () {
        return '$ '. number_format($this->valor_debito, 2, ',', '.');
    }

    public function getValorCreditoMonedaAttribute () {
        return '$ '. number_format($this->valor_credito, 2, ',', '.');
    }

    public function getValorDebitoAttribute () {

        return PgCxpdetalle::where('pg_cxp_id',$this->id)
            ->where(function ($query) {
                $query->where('naturaleza', 1);
            })->sum('valor');
    }

    public function getValorCreditoAttribute () {

        return PgCxpdetalle::where('pg_cxp_id',$this->id)
            ->where(function ($query) {
                $query->where('naturaleza', 0);
            })->sum('valor');
    }

    public function getValorMonedaAttribute () {
        return '$ '. number_format($this->valor_debito - $this->valor_credito, 2, ',', '.');
    }

    public function getValorSinFormatoAttribute ()
    {
        return (double) ($this->valor_debito - $this->valor_credito);
    }

    public function getValorMonAttribute () {
        $valor = $this->valor_debito - $this->valor_credito;
        $letras = NumeroALetras::convertir($valor, 'PESOS', 'CENTAVOS');
        return $letras;

    }

    public function setFechaCausacionAttribute ($value)
    {
        $this->attributes['fecha_causacion'] = $value . substr(now(), 10);
    }

    public function setFechaFacturaAttribute ($value)
    {
        $this->attributes['fecha_factura'] = $value . substr(now(), 10);
    }

    public function setFechaVencimientoAttribute ($value)
    {
        $this->attributes['fecha_vencimiento'] = $value . substr(now(), 10);
    }

    public function getFechaCausacionAttribute ()
    {
        return substr($this->attributes['fecha_causacion'], 0, -9);
    }

    public function getFechaFacturaAttribute ()
    {
        return substr($this->attributes['fecha_factura'], 0, -9);
    }

    public function getFechaVencimientoAttribute ()
    {
        return substr($this->attributes['fecha_vencimiento'], 0, -9);
    }



}
