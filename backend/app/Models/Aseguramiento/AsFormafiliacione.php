<?php

namespace App\Models\Aseguramiento;

use App\Models\General\GnEmpresa;
use App\Models\General\GnMunicipio;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
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
        'nf_ciiu_id',
        'as_pagadore_id',
        'gs_usuario_id'
    ];

    protected $searchable = ['search','estado','fecha_radicacion', 'puntaje_sisben'];

    protected $sortable = ['fecha_radicacion','puntaje_sisben'];


    /**
     * @return array
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * @param array $fillable
     */
    public function setFillable($fillable)
    {
        $this->fillable = $fillable;
    }

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
    
    public function usuarioradica(){
        return $this->belongsTo(GsUsuario::class,'usuradica_id');
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
    
    public function beneficiariosTotal()
    {
        return $this->hasMany(AsAfiliado::class, 'cabfamilia_id','as_afiliado_id');
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
            $tipo_id = Input::get('tipo_id');
            $identificacion = Input::get('identificacion');
            $nombre1 = Input::get('nombre1');
            $nombre2 = Input::get('nombre2');
            $apellido1 = Input::get('apellido1');
            $apellido2 = Input::get('apellido2');
            $rangMin = Input::get('rangMin');
            $rangMax = Input::get('rangMax');
            $fechaMin = Input::get('fechaMin');
            $fechaMax = Input::get('fechaMax');
            $status= Input::get('estados');
            $municipioId = Input::get('gn_municipio_id');
            $ficha = Input::get('ficha');

            $builder->when($tipo_id, function ($query) use ($tipo_id) {
                $query->whereHas('afiliado', function ($query) use ($tipo_id){
                    $query->where('gn_tipdocidentidad_id', '=', (int) $tipo_id);
                })->orWhereHas('beneficiarios', function ($query) use ($tipo_id) {
                    $query->where('gn_tipdocidentidad_id', '=', (int)$tipo_id);
                });
            })->when($identificacion, function ($query) use ($identificacion) {
                $query->whereHas('afiliado', function ($query) use ($identificacion){
                    $query->where('identificacion', '=', $identificacion);
                })->orWhereHas('beneficiarios', function ($query) use ($identificacion) {
                    $query->where('identificacion', '=', $identificacion);
                });
            })->when($nombre1, function ($query) use ($nombre1) {
                $query->whereHas('afiliado', function ($query) use ($nombre1){
                    $query->where('nombre1', '=', $nombre1);
                })->orWhereHas('beneficiarios', function ($query) use ($nombre1) {
                    $query->where('nombre1', '=', $nombre1);
                });
            })->when($nombre2, function ($query) use ($nombre2) {
                $query->whereHas('afiliado', function ($query) use ($nombre2){
                    $query->where('nombre2', '=', $nombre2);
                })->orWhereHas('beneficiarios', function ($query) use ($nombre2) {
                    $query->where('nombre2', '=', $nombre2);
                });
            })->when($apellido1, function ($query) use ($apellido1) {
                $query->whereHas('afiliado', function ($query) use ($apellido1){
                    $query->where('apellido1', '=', $apellido1);
                })->orWhereHas('beneficiarios', function ($query) use ($apellido1) {
                    $query->where('apellido1', '=', $apellido1);
                });
            })->when($apellido2, function ($query) use ($apellido2) {
                $query->whereHas('afiliado', function ($query) use ($apellido2){
                    $query->where('apellido2', '=', $apellido2);
                })->orWhereHas('beneficiarios', function ($query) use ($apellido2) {
                    $query->where('apellido2', '=', $apellido2);
                });
            })->when($rangMin, function ($query) use ($rangMin) {
                $query->where('puntaje_sisben','>=',$rangMin);
            })->when($rangMax, function ($query) use ($rangMax) {
                $query->where('puntaje_sisben','<=',$rangMax);
            })->when($fechaMin, function ($query) use ($fechaMin) {
                $query->where('fecha_radicacion', '>=', $fechaMin);
            })->when($fechaMax, function ($query) use ($fechaMax) {
                $query->where('fecha_radicacion', '<=', $fechaMax);
            })->when($status, function ($query) use ($status) {
                $items = explode(',', $status);
                if (count($items) > 1) {
                    $query->whereIn('estado', $items);
                } else {
                    $query->where('estado', '=', (string) $items[0]);
                }
            })->when($municipioId, function ($query) use ($municipioId) {
                $query->where('gn_municipio_id', '=', $municipioId);
            })->when($ficha, function ($query) use ($ficha) {
                $query->where('ficha_sisben', '=', ((string) $ficha));
            })->where(function ($query) use ($constraint) {
                $query->whereHas('afiliado', function ($query) use ($constraint){
                    $this->getOrWhere($query, $constraint);
                })->orWhereHas('beneficiarios', function ($query) use ($constraint) {
                    $this->getOrWhere($query, $constraint);
                });
            });

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

    /**
     * @param $query
     * @param Constraint $constraint
     */
    protected function getOrWhere($query, Constraint $constraint): void
    {
        $query->where('nombre1', $constraint->getOperator(), $constraint->getValue())
            ->orWhere('nombre2', $constraint->getOperator(), $constraint->getValue())
            ->orWhere('apellido1', $constraint->getOperator(), $constraint->getValue())
            ->orWhere('apellido2', $constraint->getOperator(), $constraint->getValue())
            ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue());
    }
    
    
}

















