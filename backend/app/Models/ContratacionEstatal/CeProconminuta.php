<?php

namespace App\Models\ContratacionEstatal;

use App\Models\General\GnArchivo;
use App\Models\Niif\GnTercero;
use App\Models\Presupuesto\PrCdp;
use App\Models\Presupuesto\PrRp;
use App\Models\RedServicios\RsPlanescobertura;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsNovcontrato;
use App\Traits\NumeroATexto;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use NumberToWords\NumberToWords;
use OwenIt\Auditing\Contracts\Auditable;

class CeProconminuta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use PimpableTrait;

    protected $fillable = [
        'fecha_contrato',
        'fecha_acta_inicio',
        'fecha_finalizacion',
        'numero_contrato',
        'rs_entidad_id',
        'objeto',
        'tipo',
        'plazo_meses',
        'plazo_dias',
        'valor',
        'valor_total',
        'modificaciones_plazo',
        'modificaciones_valor',
        'ce_procontractuale_id',
        'consecutivo',
        'fecha',
        'estado',
        'pr_cdp_id',
        'subsidiado',
        'contributivo',
        'portabilidad',
        'ce_tipocontrato_id',
        'minuta',
        'minuta_original',
        'gn_tercero_id'
    ];

    protected $with = ['rp.detalles.rubro','actaInicio'];

    protected $searchable = ['objeto','entidad:nombre','entidad:cod_habilitacion','numero_contrato','search','portabilidad','subsidiado','contributivo'];

    public function planes_cobertura()
    {
        return $this->hasMany(RsPlanescobertura::class,'ce_proconminuta_id');
    }

    public function entidad()
    {
        return $this->belongsTo(RsEntidade::class, 'rs_entidad_id');
    }

    public function tercero(){
        return $this->belongsTo(GnTercero::class, 'gn_tercero_id');
    }

    public function novedades()
    {
        return $this->hasMany(RsNovcontrato::class, 'ce_proconminuta_id');
    }

    /**
     * @author Jorge Hernandez Oropeza 01/06/2020
     *
     */
    public function prcdps ()
    {
        return $this->hasMany(CeProconminutaPrCdps::class,'ce_proconminuta_id');
    }

    public function proceso_contractual(){
        return $this->belongsTo(CeProcontractuale::class, 'ce_procontractuale_id');
    }

    public function cdp()
    {
        return $this->belongsTo(PrCdp::class,'pr_cdp_id');
    }

    public function rp()
    {
        return $this->hasOne(PrRp::class,'ce_proconminuta_id');
    }

    public function allRelations()
    {
        return [
            'entidad',
            'cdp.dependencia',
            'novedades',
            'planes_cobertura',
            'proceso_contractual.estudiosPrevios.garantias.garantia',
            'tipo_contrato',
            'archivoMinuta',
            'tercero',
            'prcdps.cdp'
        ];
    }

    public function archivoMinuta()
    {
        return $this->belongsTo(GnArchivo::class,'minuta_firmada');
    }

    public function actaInicio()
    {
        return $this->hasOne(RsNovcontrato::class,'ce_proconminuta_id')->where('tipo','Acta Inicio');
    }

    public function scopeSinRp($query)
    {
        return $query->doesntHave('rp');
    }

    public function scopeConCdp($query)
    {
        return $query->has('cdp');
    }

    public function getPlazoAttribute()
    {
        $mesesLetra = $this->plazo_meses === 1 ? 'Un' : NumeroATexto::convertir($this->plazo_meses);
        $plazo = $mesesLetra.' ('.$this->plazo_meses.')';
        $plazo .= $this->plazo_meses === 1 ? ' mes' : ' meses';

        if($this->plazo_dias){
            $diasLetra = $this->plazo_dias === 1 ? 'Un' : NumeroATexto::convertir($this->plazo_dias);
            $plazo .= ' y ' . $diasLetra.' ('.$this->plazo_dias.')';
            $plazo .= $this->plazo_dias === 1 ? ' dia' : ' dias';
        }

        return $plazo;
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'gs_usuario_id');
    }

    public function getValorLetrasAttribute()
    {
        $numberWords = new NumberToWords();
        $currencyTrasnformer = $numberWords->getNumberTransformer('es');
        $valorEnLetra = $currencyTrasnformer->toWords($this->valor);
        $valorEnLetra = ($this->valor % 1000000) === 0 ? $valorEnLetra.' de pesos' : ' pesos';
        return  strtoupper($valorEnLetra);

    }

    public function tipo_contrato() {
        return $this->belongsTo(CeTipocontrato::class, 'ce_tipocontrato_id');
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        CeProconminuta::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }else{
                $table->gs_usuario_id  = User::first()->id;
            }

            $table->consecutivo = CeProconminuta::max('consecutivo') + 1;
        });

    }

    public function scopeEstaActivo($query)
    {
        if(!Input::get('solo_activos')){
            return $query;
        }
        $today_string = today()->toDateString();
        return $query->whereEstado('Legalizado')
            ->whereDate('fecha_acta_inicio','<=',$today_string)
            ->whereDate('fecha_finalizacion','>=',$today_string);
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use($builder,$constraint){

                $query->whereHas('entidad',function ($query) use($builder,$constraint){
                    $query->where('nombre',$constraint->getOperator(),$constraint->getValue())
                        ->orWhere('cod_habilitacion',$constraint->getOperator(),$constraint->getValue())
                        ->orWhereHas('tercero', function ($query) use ($builder, $constraint){
                            $query->where('identificacion', $constraint->getOperator(),$constraint->getValue())
                                ->orWhere('razon_social',$constraint->getOperator(), $constraint->getValue());
                        });
                })
                    ->orWhere('objeto',$constraint->getOperator(),$constraint->getValue())
                    ->orWhere('numero_contrato',$constraint->getOperator(),$constraint->getValue());
            });
            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

}
