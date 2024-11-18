<?php

namespace App\Models\Aseguramiento;

use App\Models\General\GnEmpresa;
use App\Models\General\GnMunicipio;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AsFormafiliacione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'as_regimene_id',
        'as_tipafiliacione_id',
        'as_tipafiliado_id',
        'as_clasecotizante_id',
        'as_afiliado_id',
        'rs_ips_id',
        'gn_municipio_id',
        'ibc',
        'estado',
        'ficha_sisben',
        'puntaje_sisben',
        'recien_nacido',
        'no_radicado',
        'fecha_radicacion',
        'usuradica_id',
        'fecha_anulacion',
        'usuanula_id',
        'gs_usuario_id'
    ];

    protected $searchable = ['search','estado','fecha_radicacion', 'puntaje_sisben'];

    protected $sortable = ['fecha_radicacion','puntaje_sisben'];


    public function regimen()
    {
        return $this->belongsTo(AsRegimene::class,'as_regimene_id');
    }

    public function tipafiliacion()
    {
        return $this->belongsTo(AsTipafiliacione::class,'as_tipafiliacione_id');
    }

    public function afiliado(){
        return $this->belongsTo(AsAfiliado::class,'as_afiliado_id');
    }

    public function afitramite()
    {
        return $this->hasOne(AsAfitramite::class, 'as_formafiliacion_id');
    }



    public function conyuge(){
        return $this->belongsTo(AsAfiliado::class,'as_conyuge_id');
    }


    public function ips(){
        return $this->belongsTo(RsEntidade::class, 'rs_ips_id');
    }

    public function municipio(){
        return $this->belongsTo(GnMunicipio::class, 'gn_municipio_id');
    }

    public function claseCotizante(){
        return $this->belongsTo(AsClasecotizante::class, 'as_clasecotizante_id');
    }

    public function nucleo_familiar()
    {
        return $this->hasMany(AsNucfamiliare::class, 'as_formafiliacion_id');
    }

    public function beneficiarios()
    {
        return $this->belongsToMany(AsAfiliado::class,'as_nucfamiliares','as_formafiliacion_id','as_beneficiario_id')->withPivot('as_parentesco_id');
    }

    public function anexos()
    {
        return $this->hasMany(AsAnetramite::class, 'as_formafiliacion_id');
    }

    public function declaraciones()
    {
        return $this->hasMany(AsDecauttramite::class, 'as_formafiliacion_id');
    }


    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('afiliado', function ($query) use ($constraint){
                $query->where('nombre1', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('nombre2', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido1', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido2', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue());
            })->orWhereHas('beneficiarios', function ($query) use ($constraint) {
                $query->where('nombre1', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('nombre2', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido1', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido2', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue());
            }
            );

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }


    public static function boot()
    {
        parent::boot();

        AsFormafiliacione::saving(function($table){
            if(Auth::user()){
                $table->gs_usuario_id  = Auth::user()->id;
            }
        });
    }
}

















