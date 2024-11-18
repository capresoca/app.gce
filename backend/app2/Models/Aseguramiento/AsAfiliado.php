<?php

namespace App\Models\Aseguramiento;

use App\Compensacion\CpLiquidacione;
use App\Models\Autorizaciones\AuAutaprobada;
use App\Models\Autorizaciones\AuAutorizacione;
use App\Models\General\GnBarrio;
use App\Models\General\GnEmpresa;
use App\Models\General\GnMunicipio;
use App\Models\General\GnPaise;
use App\Models\General\GnSexo;
use App\Models\General\GnTipdocidentidade;
use App\Models\General\GnVereda;
use App\Models\General\GnZona;
use App\Models\Mipres\MpPrescripcione;
use App\Models\Mipres\MpTutela;
use App\Models\Niif\GnTercero;
use App\Models\Niif\NfCiiu;
use App\Models\OficinaJuridica\OjTutela;
use App\Models\RedServicios\RsAfiliadoServicio;
use App\Models\RedServicios\RsCuotacopago;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsMaestroips;
use App\Models\RedServicios\RsMaestroipsgrup;
use App\Models\RedServicios\RsMaestroipshistorico;
use App\Models\RedServicios\RsPortabilidade;
use App\Models\RedServicios\RsSalminimo;
use App\Models\RedServicios\RsServcontratado;
use App\RedServicios\RsServhabilitados;
use App\Traits\EmoticonEdadTrait;
use App\Traits\EstadoAfiliadoTrait;
use App\Traits\ToolsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AsAfiliado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use PimpableTrait;

    protected $searchable = [
        'search',
        'as_tipafiliado_id',
        'cabfamilia_id',
        'estado_afiliado:codigo',
        'estado_afiliado:nombre',
        'identificacion',
        'nombre_completo',
        'estado',
        'gn_tipdocidentidad_id',
        'as_regimene_id',
    ];

    protected $sortable = ['estado_afiliado:nombre', 'identificacion', 'nombre_completo'];

    protected $fillable = [
        'gn_tipdocidentidad_id',
        'identificacion',
        'nombre1',
        'nombre2',
        'apellido1',
        'apellido2',
        'nombre_completo',
        'direccion',
        'telefono_fijo',
        'celular',
        'correo_electronico',
        'gn_municipio_id',
        'as_regimene_id',
        'as_etnia_id',
        'cabfamilia_id',
        'as_parentesco_id',
        'ficha_sisben',
        'puntaje_sisben',
        'nivel_sisben',
        'upc',
        'ibc',
        'as_pobespeciale_id',
        'as_clasecotizante_id',
        'as_condicion_discapacidades_id',
        'as_arl_id',
        'as_afp_id',
        'rs_entidade_id',
        'estado',
        'as_ccf_id',
        'gn_zona_id',
        'gn_vereda_id',
        'fecha_nacimiento',
        'gn_sexo_id',
        'as_tipafiliado_id',
        'cabfamilianterior_id',
        'fecha_afiliacion',
        'fecha_retiro',
        'fecha_sgsss',
        'serial_bdua',
        'tipo_identificacion',
        'gn_barrio_id',
        'fecha_expedicion',
        'gn_paise_id',
        'es_nacimiento',
        'zona_dnp',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $with = ['sexo', 'etnia', 'ips', 'afp', 'ccf', 'poblacion_especial'];

    protected $appends = [
        'fecha_nacimiento_array',
        'estados_posibles',
        'fecha_nacimiento_slash',
        'codigo_entidad',
        'fecha_afiliacion_slash',
        'identificacion_completa',
        'edad',
        'edad_full',
        'portabilidad_activa',
        'ingreso_total',
        'moroso',
        'emoticon',
        'rango_contributivo',
        'mini_afiliado',
        'process_affiliates',
        'ips_grupo',
        'anterior_grupoips'
    ];

    //RELATIONS//
    public function tipo_id()
    {
        return $this->belongsTo(GnTipdocidentidade::class, 'gn_tipdocidentidad_id');
    }

    public function gn_paise_id()
    {
        return $this->belongsTo(GnPaise::class, 'gn_paise_id');
    }

    public function cabeza()
    {
        return $this->belongsTo(AsAfiliado::class, 'cabfamilia_id');
    }

    public function cabezaAnterior()
    {
        return $this->belongsTo(AsAfiliado::class, 'cabfamilianterior_id');
    }

    public function municipio()
    {
        return $this->belongsTo(GnMunicipio::class, 'gn_municipio_id');
    }

    public function tercero()
    {
        return $this->belongsTo(GnTercero::class, 'gn_tercero_id');
    }

    public function vereda()
    {
        return $this->belongsTo(GnVereda::class, 'gn_vereda_id');
    }

    public function sexo()
    {
        return $this->belongsTo(GnSexo::class, 'gn_sexo_id');
    }

    public function etnia()
    {
        return $this->belongsTo(AsEtnia::class, 'as_etnia_id');
    }

    public function poblacion_especial()
    {
        return $this->belongsTo(AsPobespeciale::class, 'as_pobespeciale_id');
    }

    public function ips()
    {
        return $this->belongsTo(RsEntidade::class, 'rs_entidade_id');
    }

    public function arl()
    {
        return $this->belongsTo(AsArl::class, 'as_arl_id');
    }

    public function afp()
    {
        return $this->belongsTo(AsArl::class, 'as_afp_id');
    }

    public function ccf()
    {
        return $this->belongsTo(AsCcf::class, 'as_ccf_id');
    }

    public function beneficiarios()
    {
        return $this->hasMany(AsAfiliado::class, 'cabfamilia_id');
    }

    public function clase_cotizante()
    {
        return $this->belongsTo(AsClasecotizante::class, 'as_clasecotizante_id');
    }

    public function tipo_afiliado()
    {
        return $this->belongsTo(AsTipafiliado::class, 'as_tipafiliado_id');
    }

    public function barrio()
    {
        return $this->belongsTo(GnBarrio::class, 'gn_barrio_id');
    }

    public function aportantes()
    {
        return $this->belongsToMany(AsPagadore::class, 'as_afiliado_pagador', 'as_afiliado_id', 'as_pagador_id')->withPivot([
                'fecha_vinculacion',
                'ibc',
            ]);
    }

    public function relaciones_laborales()
    {
        return $this->hasMany(AsAfiliadoPagador::class, 'as_afiliado_id');
    }

    public function estado_afiliado()
    {
        return $this->belongsTo(AsEstadoafiliado::class, 'estado');
    }

    public function zona()
    {
        return $this->belongsTo(GnZona::class, 'gn_zona_id');
    }

    public function ciiu()
    {
        return $this->belongsTo(NfCiiu::class, 'nf_ciiu_id');
    }

    public function condicion_discapacidad()
    {
        return $this->belongsTo(AsCondicionDiscapacidade::class, 'as_tipo_discapacidade_id');
    }

    public function parentesco()
    {
        return $this->belongsTo(AsParentesco::class, 'as_parentesco_id');
    }

    public function regimen()
    {
        return $this->belongsTo(AsRegimene::class, 'as_regimene_id');
    }

    public function condicion_terminacion()
    {
        return $this->belongsTo(AsCondterminacione::class, 'as_condterminacione_id');
    }

    public function getEdadAttribute()
    {
        return Carbon::createFromFormat('Y-m-d', $this->fecha_nacimiento)->diffInYears(today());
    }

    public function getEdadFullAttribute()
    {
        $direfencia = Carbon::createFromFormat('Y-m-d', $this->fecha_nacimiento)->diff(today());

        return "$direfencia->y años, $direfencia->m meses, $direfencia->d dias";
    }

    public function beneficiarios_activos()
    {
        return $this->hasMany(AsAfiliado::class, 'cabfamilia_id')->where('estado', 3);
    }

    public function afitramites()
    {
        return $this->hasMany(AsAfitramite::class);
    }

    public function novtramites()
    {
        return $this->hasMany(AsNovtramite::class);
    }

    public function traslatramites()
    {
        return $this->hasMany(AsTraslatramite::class);
    }

    public function soltraslados()
    {
        return $this->hasMany(AsSoltraslado::class);
    }

    public function getMiniAfiliadoAttribute($key)
    {
        $badges = collect();

        if ($this->altoCosto()->count()) {
            $badges->push([
                    'emoticon' => '💰',
                    'title' => 'Afiliado de alto costo',
                ]);
        }

        if (isset($this->process_affiliates)) {
            foreach ($this->process_affiliates as $affiliate) {
                $badges->push($affiliate);
            }
        }

        $tutela_mipres = MpTutela::where('as_afiliado_id', '=', $this->attributes['id'])->where('estado', '<>', 'Anulado')->latest()->first();
        if (isset($tutela_mipres)) {
            $badges->push([
                'emoticon' => '📜',
                'title' => 'Con Tutela',
            ]);
        }

        return [
            'id' => $this->attributes['id'],
            'identificacion_completa' => $this->identificacion_completa,
            'nombre_completo' => $this->nombre_completo,
            'emoticon' => $this->emoticon,
            'badges' => $badges->toArray(),
        ];
    }

    public function servicios_contratados()
    {
        return $this->belongsToMany(RsServcontratado::class, 'rs_afiliado_servicios', 'as_afiliado_id', 'rs_servcontratado_id');
    }

    public function getServiciosHabilitadosAttribute()
    {
        $afiServicios = DB::select("SELECT MAX(rs_afiliado_servicios.id) as id
                                        FROM  rs_afiliado_servicios
                                        inner join rs_servhabilitados on rs_servhabilitados.id = rs_servhabilitado_id
                                        inner join rs_servicios on rs_servicios.id = rs_servhabilitados.rs_servicio_id
                                        WHERE rs_afiliado_servicios.as_afiliado_id = '{$this->id}' AND rs_afiliado_servicios.rs_servportabilidad_id IS NULL
                                        group by rs_servicios.id");

        $afiPortabilidad = DB::select("SELECT MAX(afiServ.id) as id
                                        FROM  rs_afiliado_servicios as afiServ
                                        inner join rs_servhabilitados on rs_servhabilitados.id = afiServ.rs_servportabilidad_id
                                        inner join rs_servicios on rs_servicios.id = rs_servhabilitados.rs_servicio_id
                                        inner join rs_portabilidades as port on port.id = afiServ.rs_portabilidad_id
                                        WHERE afiServ.as_afiliado_id = $this->id and port.estado = 'Aceptado'
                                        group by rs_servicios.id");

        $servicios = count($afiPortabilidad) > 0 ? $afiPortabilidad : $afiServicios;

        $servhabilitados = collect();
        foreach ($servicios as $afiServicio) {
            if ((count($afiPortabilidad) > 0)) {
                $servhabilitados->push(RsAfiliadoServicio::find($afiServicio->id)->rs_servportabilidad_id);
            } else {
                $servhabilitados->push(RsAfiliadoServicio::find($afiServicio->id)->rs_servhabilitado_id);
            }
        }
        $habilitados = RsServhabilitados::whereIn('id', $servhabilitados)->with('entidad')->get();

        return $habilitados;
    }

    public function servicios_portabilidad()
    {
        return $this->belongsToMany(RsServcontratado::class, 'rs_afiliado_servicios', 'as_afiliado_id', 'rs_servcontratado_id')
            ->withPivot('rs_portabilidad_id');
    }

    public function portabilidades()
    {
        return $this->hasMany(RsPortabilidade::class, 'as_afiliado_id');
    }

    public function prescripciones()
    {
        return $this->hasMany(MpPrescripcione::class, 'as_afiliado_id');
    }

    public function tutelas()
    {
        return $this->belongsToMany(OjTutela::class, 'oj_afiliados_tutelas', 'as_afiliado_id', 'oj_tutela_id');
    }

    public function servicios_habilitados()
    {
        return $this->belongsToMany(RsServhabilitados::class, 'rs_afiliado_servicios', 'as_afiliado_id', 'rs_servhabilitado_id');
    }

    public function autaprobadas()
    {
        return $this->hasManyThrough(
            AuAutaprobada::class,
            AuAutorizacione::class,
            'as_afiliado_id',
            'au_autorizacion_id',
            'id', 'id');
    }

    public function liquidaciones()
    {
        return $this->hasManyThrough(
            CpLiquidacione::class,
            AsAfiliadoPagador::class,
            'as_afiliado_id',
            'relacion_laboral_id',
            'id', 'id');
    }

    public function getMorosoAttribute()
    {
        return $this->liquidaciones()->where('dias_pagados', 0)->count() > 0;
    }

    public function altoCosto()
    {
        return $this->hasMany(AsAfiliadoAltocosto::class, 'as_afiliado_id');
    }

    // RsServicios

    public function servicioIps()
    {
        return $this->hasOne(RsMaestroips::class, 'as_afiliado_id')
            ->with('grupoIps.prestadores.entidad')->where('rs_portabilidade_id', '=', null)->latest('id');
    }

    public function portable()
    {
        return $this->hasOne(RsMaestroips::class, 'as_afiliado_id')
            ->with('grupoIps.prestadores.entidad')->where('rs_portabilidade_id', '<>', null)->latest('id');
    }

    public function getIpsGrupoAttribute($key)
    {
        if (is_null($this->portabilidad_activa)) {
            $data = $this->servicioIps;
        } else {
            $data = $this->portable;
        }

        return $data;
    }

    //SETTERS//
    public function setNombreCompletoAttribute($value)
    {
        $nombre_completo = $this->attributes['nombre1'];
        if ($this->attributes['apellido2']) {
            $nombre_completo .= ' '.$this->attributes['nombre2'];
        }

        if ($this->attributes['nombre1']) {
            $nombre_completo .= ' '.$this->attributes['apellido1'];
        }

        if ($this->attributes['nombre2']) {
            $nombre_completo .= ' '.$this->attributes['apellido2'];
        }

        $this->attributes['nombre_completo'] = $nombre_completo;
    }

    public function setNombre1Attribute($value)
    {
        $this->attributes['nombre1'] = strtoupper($value);
    }

    public function setNombre2Attribute($value)
    {
        $this->attributes['nombre2'] = strtoupper($value);
    }

    public function setApellido1Attribute($value)
    {
        $this->attributes['apellido1'] = strtoupper($value);
    }

    public function setApellido2Attribute($value)
    {
        $this->attributes['apellido2'] = strtoupper($value);
    }

    public function setTipoIdentificacionAttribute($value)
    {
        $this->attributes['tipo_identificacion'] = $value;
    }

    public function setNivelSisbenAttribute($value)
    {
        if ($this->gn_zona_id === 1) {
            if ($this->puntaje_sisben > 51.57) {
                $this->attributes['nivel_sisben'] = 3;
            }
            if ($this->puntaje_sisben > 44.79) {
                $this->attributes['nivel_sisben'] = 2;
            }
            $this->attributes['nivel_sisben'] = 1;
        }
        if ($this->gn_zona_id === 2) {
            if ($this->puntaje_sisben > 37.8) {
                $this->attributes['nivel_sisben'] = 3;
            }
            if ($this->puntaje_sisben > 32.98) {
                $this->attributes['nivel_sisben'] = 2;
            }
            $this->attributes['nivel_sisben'] = 1;
        }
    }

    //GETTERS//
    public function getIngresoTotalAttribute()
    {
        return $this->relaciones_laborales->where('estado', 'Activo')->sum('ibc');
    }

    public function getRangoContributivoAttribute()
    {
        if ($this->as_regimene_id == 2) {
            return null;
        } else {
            $ingresos = $this->getIngresoTotalAttribute();
            $cuotaCopago = RsCuotacopago::join('rs_salminimos', 'rs_cuotacopagos.rs_salminimo_id', 'rs_salminimos.id')
                ->select('rs_cuotacopagos.*')
                ->where(DB::raw('rs_cuotacopagos.salminimos_inicio * rs_salminimos.valor'), '<=', $ingresos)
                ->where(DB::raw('rs_cuotacopagos.salminimos_fin * rs_salminimos.valor'), '>', $ingresos)
                ->where('rs_salminimos.anio', date('Y'))->first();

            return $cuotaCopago;
        }
    }

    public function rango()
    {
        $ingresos = $this->getIngresoTotalAttribute();
        $salario = RsSalminimo::where('anio', date('Y'))->with('cuotas_copagos')->first();
        // return $salario;
        if (isset($salario)) {
            foreach ($salario->cuotas_copagos as $valorRango) {
                if (($valorRango['limite_inferior_salario'] != null) &&
                    ($ingresos <= $valorRango['salminimos_fin'] * $salario['valor']) &&
                    ($ingresos >= $valorRango['salminimos_inicio'] * $salario['valor'])) {
                    return $valorRango['grupo'];
                }
            }
        }

        return $salario;
    }

    public function getNivSisAttribute()
    {
        if (! $this->nivel_sisben) {
            return 1;
        }

        return $this->nivel_sisben;
    }

    public function getPobEspAttribute()
    {
        if (! $this->as_pobespeciale_id) {
            return 5;
        }

        return $this->poblacion_especial->codigo;
    }

    public function getIdentificacionCompletaAttribute()
    {
        return $this->tipo_id['abreviatura'].' '.$this->identificacion;
    }

    public function getEstadosPosiblesAttribute()
    {
        $estadosQuery = AsEstadoAfiliado::where('codigo', '<>', $this->estado_afiliado->codigo);

        if ($this->estado_afiliado === 8) {
            return $estadosQuery->where('codigo', 'RE')->get();
        }
        if ($this->as_regimene_id === 1 && $this->estado_afiliado === 3) {
            return $estadosQuery->whereIn('codigo', ['SM', 'SD', 'RE'])->get();
        }

        return $estadosQuery->where('codigo', 'RE')->get();
    }

    public function getFechaNacimientoArrayAttribute()
    {
        return ToolsTrait::fechaArray($this->fecha_nacimiento);
    }

    public function getFechaNacimientoSlashAttribute()
    {
        $fecha = new Carbon($this->fecha_nacimiento);

        return $fecha->format('d/m/Y');
    }

    public function getFechaAfiliacionSlashAttribute()
    {
        $fecha = new Carbon($this->fecha_afiliacion);

        return $fecha->format('d/m/Y');
    }

    public function getCodigoEntidadAttribute()
    {
        $empresa = GnEmpresa::first();

        if (! $empresa) {
            return null;
        }

        if ($this->as_regimene_id === 1) {
            return $empresa->codhabilitacion_contrib;
        }

        return $empresa->codhabilitacion_subsid;
    }

    public function getPortabilidadActivaAttribute()
    {
        //return $this->estaPortado();
        return $this->portabilidades()->with('municipio_receptor')->where('estado', '=', 'Aceptado')->latest()->first();
    }

    //METODOS ADICIONALES//

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($query) use ($builder, $constraint) {
                $search = $constraint->getValue();
                if ($constraint->getOperator() === Constraint::OPERATOR_LIKE) {
                    $search = substr($constraint->getValue(), 1, -1);
                }
                $query->where('identificacion', 'like', $search.'%');
                if (! is_numeric($search)) {
                    $query->orWhere('nombre_completo', 'like', '%'.$search.'%');
                }
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

    public function scopeAfiliadoPorIdentificacion($query, $data)
    {
        return $query->where('identificacion', $data['identificacion'])->where('gn_tipdocidentidad_id', $data['tipoId']);
    }

    public function portabilidadesActivas()
    {
        $today_string = today()->toDateString();

        return $this->hasMany(
            RsPortabilidade::class,
            'as_afiliado_id')
            ->whereEstado('Aceptado')
            ->whereDate('fecha_inicio', '<=', $today_string)
            ->whereDate('fecha_fin', '>=', $today_string);
    }

    public function EsSubsidiado()
    {
        return $this->as_regimene_id === 2;
    }

    public function EsContributivo()
    {
        return $this->as_regimene_id === 1;
    }

    public function estaPortado()
    {
        return $this->portabilidadesActivas()->count() ? true : false;
    }

    public function getCopagoAnualAttribute()
    {
        return $this->autaprobadas()->where('au_autaprobadas.estado', 'Usada')
            ->whereDate('fecha', '>=', today()->firstOfYear()->toDateString())->sum('valor_usuario');
    }

    static public function contarActivos($municipio_id = null)
    {
        if ($municipio_id) {
            return self::whereEstado(3)->where('gn_municipio_id', $municipio_id)->count();
        }

        return self::whereEstado(3)->count();
    }

    public function getEmoticonAttribute()
    {
        return EmoticonEdadTrait::IdEmoticonsByEdad($this);
    }

    public function getProcessAffiliatesAttribute($key)
    {
        try {
            $afiliadoId = $this->attributes['id'];

            return EstadoAfiliadoTrait::consultantEstate($afiliadoId);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getAnteriorGrupoipsAttribute()
    {
        $ipsAnterior = RsMaestroipshistorico::with('grupoIps')
            ->where('as_afiliado_id', $this->attributes['id']);

        if (is_null($this->portabilidad_activa)) {
            $ipsAnterior->whereNull('rs_portabilidade_id');
        } else {
            $ipsAnterior->whereNotNull('rs_portabilidade_id');
        }

        $grupo = $ipsAnterior->groupBy('as_afiliado_id')->latest('id')->first();

        return $grupo['grupoIps'];
    }
}





