<?php

namespace App\Models\Aseguramiento;

use App\Models\General\GnSexo;
use App\Models\General\GnTipdocidentidade;
use App\Models\Niif\NfCiiu;
use App\Traits\ToolsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AsFormtrasmov extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'tipo',
        'as_afiliado_id',
        'as_padre_id',
        'as_parentesco_id',
        'as_pagadore_id',
        'as_eps_id',
        'gn_tipdocidentidad_id',
        'identificacion',
        'nombre1',
        'nombre2',
        'apellido1',
        'apellido2',
        'fecha_expedicion',
        'fecha_nacimiento',
        'fecha_traslado',
        'fecha_radicacion',
        'fecha_anulacion',
        'estado',
        'gs_usucrea_id',
        'gs_usuconf_id',
        'gs_usuanu_id',
        'gn_sexo_id',
        'motivo_anula',
        'as_clasecotizante_id',
        'nf_ciiu_id'
    ];

    protected $searchable = ['search'];

    protected $appends = ['fecha_nacimiento_slash', 'fecha_traslado_slash', 'entidad_solicita', 'fecha_radicacion_array', 'pagador'];

    public function afiliado()
    {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function padre()
    {
        return $this->belongsTo(AsAfiliado::class, 'as_padre_id');
    }

    public function pagadores()
    {
        return $this->belongsToMany(AsPagadore::class, 'as_afiliado_pagador', 'as_formtrasmov_id', 'as_pagador_id')->withPivot('as_arl_id');
    }

    public function aportante()
    {
        return $this->belongsTo(AsPagadore::class, 'as_pagadore_id');
    }

    public function afiliadoPagador()
    {
        return $this->hasOne(AsAfiliadoPagador::class, 'as_formtrasmov_id');
    }

    public function parentesco()
    {
        return $this->belongsTo(AsParentesco::class, 'as_parentesco_id');
    }

    public function eps()
    {
        return $this->belongsTo(AsEps::class, 'as_eps_id');
    }

    public function traslatramites()
    {
        return $this->hasMany(AsTraslatramite::class, 'as_formtrasmov_id');
    }

    public function clasecotizante()
    {
        return $this->belongsTo(AsClasecotizante::class, 'as_clasecotizante_id');
    }

    public function ciiu()
    {
        return $this->belongsTo(NfCiiu::class, 'nf_ciiu_id');
    }

    public function beneficiarios()
    {
        return $this->belongsToMany(AsAfiliado::class, 'as_nucfamiliares', 'as_formtrasmov_id', 'as_beneficiario_id')->withPivot('as_parentesco_id');
    }

    public function tipoIdentidad()
    {
        return $this->belongsTo(GnTipdocidentidade::class, 'gn_tipdocidentidad_id');
    }

    public function nucleo_familiar()
    {
        return $this->hasMany(AsNucfamiliare::class, 'as_formtrasmov_id');
    }

    public function anexos()
    {
        return $this->hasMany(AsAnetramite::class, 'as_formtrasmov_id');
    }

    public function declaraciones()
    {
        return $this->hasMany(AsDecauttramite::class, 'as_formtrasmov_id');
    }

    public function sexo()
    {
        return $this->belongsTo(GnSexo::class, 'gn_sexo_id');
    }


    public function getFechaNacimientoSlashAttribute()
    {
        $fecha = new Carbon($this->fecha_nacimiento);

        return $fecha->format('d/m/Y');

    }

    public function getFechaTrasladoSlashAttribute()
    {
        $fecha = new Carbon($this->fecha_traslado);

        return $fecha->format('d/m/Y');

    }

    public function getFechaRadicacionArrayAttribute()
    {
        return ToolsTrait::fechaArray($this->fecha_radicacion);
    }

    public function getEntidadSolicitaAttribute()
    {
        $codigo_eps = $this->eps['cod_habilitacion'];
        if ($codigo_eps === 'EPS025') {
            return 'EPSC25';
        } else if ($codigo_eps === 'EPSC25') {
            return 'EPS025';
        } else {
            Log::error('El código de habilitación ' . $codigo_eps . ' con número de documento ' . $this->afiliado->identificacion . ' no pertenece a Capresoca.');
            return 'ERROR!_CODIGO_' . $codigo_eps . '_NO_ES_DE_CAPRESOCA';
        }
    }

    public function getPagadorAttribute()
    {
        $afiliadoPagador = AsAfiliadoPagador::where('as_afiliado_id', $this->as_afiliado_id)
            ->orderBy('id', 'desc')->first();

        if ($afiliadoPagador) {
            $pagador = $afiliadoPagador->pagador()->first();
            return $pagador;
        }
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('afiliado', function ($query) use ($constraint) {
                $query->where('nombre1', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('nombre2', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('estado', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue());

            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        AsFormtrasmov::saving(function ($table) {
            if (Auth::user()) {
                $table->gs_usucrea_id = Auth::user()->id;
            }
            $table->consecutivo = AsFormtrasmov::max('consecutivo') + 1;
        });

    }

}
