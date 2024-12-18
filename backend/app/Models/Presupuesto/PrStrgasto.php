<?php

namespace App\Models\Presupuesto;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use NumberToWords\NumberToWords;
use OwenIt\Auditing\Contracts\Auditable;

class PrStrgasto extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'estado',
        'fecha',
        'fecha_confirmacion',
        'concepto_anulacionint',
        'fecha_anulacion',
        'gs_usuario_conf_id',
        'gs_usuario_id',
        'periodo',
        'pr_entidad_resolucion_id'
    ];
    protected $searchable = ['search', 'periodo'];
    protected $appends = ['valor_presupuesto','valor_letra_presupuesto'];

    public function detalles()
    {
        return $this->hasMany(PrDetstrgasto::class, 'pr_strgasto_id');
    }

    public function cdps () {
        return $this->hasMany(PrCdp::class, 'pr_strgasto_id');
    }

    public function usuario () {
        return $this->belongsTo(User::class, 'gs_usuario_id');
    }

    public function usuarioConfirma () {
        return $this->belongsTo(User::class, 'gs_usuario_conf_id');
    }

    public function entidadResolucion ()
    {
        return $this->belongsTo(PrEntidadResolucione::class, 'pr_entidad_resolucion_id');
    }


    public function getValorLetraPresupuestoAttribute () {
        $numberWords = new NumberToWords();
        $currencyTrasnformer = $numberWords->getNumberTransformer('es');
        $valor = $this->valor_presupuesto;
        $valorEnLetra = $currencyTrasnformer->toWords($valor) . ' pesos';
        $letras =  strtoupper($valorEnLetra);
        return $letras;
    }

    public function getValorPresupuestoAttribute ()
    {
        $detalles = $this['detalles'];
        if (!$detalles->count()) return 0;
        $suma = 0;
        foreach ($detalles as $detalle) {
            $suma+=$detalle->valor_inicial;
        }
        return $suma;
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('entidadResolucion', function ($query) use ($constraint) {
                $query->where('nombre',$constraint->getOperator(), $constraint->getValue())
                    ->orWhere('codigo',$constraint->getOperator(), $constraint->getValue())
                    ->orWhere('resolucion',$constraint->getOperator(), $constraint->getValue());
            })->orWhere('periodo', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        PrStrgasto::creating(function ($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
//                $table->fecha = Carbon::now()->toDateTimeString();
            }
        });

    }
}
