<?php

namespace App\Models\Autorizaciones;

use App\Autorizaciones\Reforigenautorizacion;
use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnArchivo;
use App\Models\OficinaJuridica\OjTutela;
use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsEntidade;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AuAnexot3 extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'au_anexot3s';

    protected $fillable = [
        'nSolicitud',
        'fecha',
        'hora',
        'fOrdMed',
        'codigoIps',
        'orgAten',
        'serSol',
        'prior',
        'ubic',
        'serv',
        'cama',
        'jusCli',
        'gn_archivo_id',
        'dPrin',
        'dRel1',
        'dRel2',
        'as_afiliado_id',
        'fS',
        'gs_usuario_id',
        'fS1',
        'oriAut',
        'viaSol',
        'au_medico_id',
        'tInd',
        'tNum',
        'tExt',
        'tCel',
        'lesp',
        'docs',
        'registrador',
        'gs_usuariovalida_id',
        'ind',
        'oj_tutela_id',
        'sw_espera',
        'au_anexot3_serv_especiale_id'
    ];

    protected $searchable = ['search','ind','sw_espera'];
    protected $appends =
        [
            'entidad_ips',
            'origen_atencion',
            'ubicacion_paciente',
            'servicio_solicitado',
            'diagnostico_principal',
            'diagnostico_reluno',
            'diagnostico_reldos',
            'origen_autorizacion',
            'via_solicitud',
            'mini_entidades',
            'valor_tot_user',
            'fecha_vencimiento',
            'servicios_autorizados'
        ];

    public function detalles()
    {
        return $this->hasMany(AuAnexot31::class, 'au_anexot3s_id');
    }

    public function afiliado()
    {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function tutela()
    {
        return $this->belongsTo(OjTutela::class, 'oj_tutela_id');
    }

    public function usuarioProceso()
    {
        return $this->belongsTo(User::class, 'gs_usuario_id');
    }

    public function usuarioValida()
    {
        return $this->belongsTo(User::class, 'gs_usuariovalida_id');
    }

    public function historia_clinica()
    {
        return $this->belongsTo(GnArchivo::class, 'gn_archivo_id');
    }

    public function medico()
    {
        return $this->belongsTo(AuMedicosSolicitante::class, 'au_medico_id');
    }

    public function especialidad()
    {
        return $this->belongsTo(AuEspecialidad::class, 'lesp');
    }

    public function anulada()
    {
        return $this->hasOne(AuAt3Nul::class, 'au_anexot3_id');
    }

    public function getEntidadIpsAttribute($key)
    {
        return $this->attributes['codigoIps'] !== ''
            ? RsEntidade::with('tercero')
                ->where('cod_habilitacion', '=', $this->attributes['codigoIps'])->first() : null;
    }

    public function getOrigenAtencionAttribute()
    {
        return $this->attributes['orgAten'] !== ''
            ? AuOrigenAtencion::whereId((integer)$this->attributes['orgAten'])->first() : null;
    }

    public function getUbicacionPacienteAttribute()
    {
        return $this->attributes['ubic'] !== ''
            ? RefUbic::whereCodigo($this->attributes['ubic'])->first() : null;
    }

    public function getServicioSolicitadoAttribute()
    {
        return $this->attributes['serSol'] !== ''
            ? Refsersol::whereCodigo($this->attributes['serSol'])->first() : null;
    }

    public function getDiagnosticoPrincipalAttribute($key)
    {
        return $this->attributes['dPrin'] !== ''
            ? RsCie10::whereCodigo($this->attributes['dPrin'])->first() : null;
    }

    public function getDiagnosticoRelunoAttribute($key)
    {
        return $this->attributes['dRel1'] !== ''
            ? RsCie10::whereCodigo($this->attributes['dRel1'])->first() : null;
    }

    public function getDiagnosticoReldosAttribute($key)
    {
        return $this->attributes['dRel2'] !== ''
            ? RsCie10::whereCodigo($this->attributes['dRel2'])->first() : null;
    }

    public function getOrigenAutorizacionAttribute($key)
    {
        return $this->attributes['oriAut'] !== ''
            ? Reforigenautorizacion::whereCodigo($this->attributes['oriAut'])->first() : null;
    }

    public function getViaSolicitudAttribute()
    {
        return $this->attributes['viaSol'] !== ''
            ? Refviasol::whereCodigo($this->attributes['viaSol'])->first() : null;
    }

    public function getFechaVencimientoAttribute()
    {
        return is_null($this->attributes['fS1']) ? null
            : strftime('%d/%m/%Y', strtotime(Carbon::parse($this->attributes['fS1'])->addMonths(6)));
    }

    public function getServiciosAutorizadosAttribute()
    {
        $servicios = $this->detalles()->with('entidad','negacion')->get();

        $datos = collect();

        foreach ($servicios as $key => $servicio) {
            $datos->push(
                [
                    'no_autorizacion' => $servicio->id,
                    'entidad_destino' => isset($servicio->entidad) ? ($servicio->entidad['cod_habilitacion'] . ' ' . $servicio->entidad['nombre']) : null,
                    'codigo'          => $servicio->codigo,
                    'descrip'         => $servicio->descrip,
                    'cant_solicitada' => $servicio->cant,
                    'cant_autorizada' => $servicio->cAut,
                    'estado'          => $servicio->ind,
                    'observacion'     => $servicio->obs,
                    'negacion'        => $servicio->negacion
                ]
            );
        }

        return $datos;
    }

    public function solicitud_aprobado () {
        return $this->belongsTo(AuAnexot3ServEspeciale::class,'au_anexot3_serv_especiale_id');
    }

    public function getValorTotUserAttribute()
    {
//        return 'algo';
        $valores = AuAnexot31::where('au_anexot3s_id', $this->id)->get();
        $valorTotal2 = 0;
        foreach ($valores as $resultado) {
            if ($this->esAprobada($resultado)) {
                $valorTotal = ($resultado->valor * $resultado->cAut);
                $valorTotal2 += $valorTotal;
            }
        }
        return $valorTotal2;
    }

    public function getMiniEntidadesAttribute($key)
    {
        if ($this->attributes['ind'] !== '3') return [];
        $entidades = [];
        foreach ($this->detalles as $key => $item) {
            if ($this->esAprobada($item) && isset($item['pAut'])) {
                array_push($entidades, [
                    'id' => $item['entidad']['id'],
                    'cod_habilitacion' => $item['entidad']['cod_habilitacion'],
                    'nombre' => $item['entidad']['nombre']
                ]);
            }
        }
        return $entidades;
    }

    public function scopeIsEntidad($query)
    {
        $query->whereHas('usuarioProceso', function ($query) {
            $query->whereNotNull('rs_entidad_id')->where('tipo', 'Entidad');
        });
    }

    public function scopeFuncionario($query)
    {
        $query->whereHas('usuarioProceso', function ($query) {
            $query->where('rs_entidad_id', '=', null)->where('tipo', 'Funcionario');
        });
    }

    public static function allRelations()
    {
        return [
            'afiliado.regimen',
            'afiliado.municipio',
            'afiliado.zona',
            'afiliado.barrio',
            'afiliado.vereda',
            'tutela',
            'usuarioProceso',
            'historia_clinica',
            'detalles.contrato',
            'detalles.especialidad',
            'detalles.entidad.tercero',
            'detalles.usuario',
            'detalles.negacion',
            'usuarioValida',
            'medico',
            'especialidad',
            'detalles.usuarioValida',
            'anulada',
            'solicitud_aprobado.usuario_aprueba'
        ];
    }

    /**
     * @param $item
     * @return bool
     */
    private function esAprobada($item): bool
    {
        return $item['ind'] === 1 && ($item['cAut'] !== null || $item['cAut'] !== 0);
    }


    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $solicitud = Input::get('espera');

            $builder->where(function ($query) use ($constraint) {
                $query->whereHas('afiliado', function ($query) use ($constraint) {
                    $search = $constraint->getValue();
                    if ($constraint->getOperator() === Constraint::OPERATOR_LIKE) {
                        $search = substr($constraint->getValue(), 1, -1);
                    }
                    $query->where('identificacion', $search);
                    if (!is_numeric($search)) {
                        $query->orWhere('nombre_completo', 'like', '%' . $search . '%');
                    }
                })->orWhereHas('usuarioProceso', function ($query) use ($constraint) {
                    $query->where('name', $constraint->getOperator(), $constraint->getValue());
                });
            })->when($solicitud, function ($query) use ($constraint, $solicitud) {
                $query->where('sw_espera',(integer) $solicitud);
            })->orWhere('codigoIps', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::creating(function ($table) {
            $table->fecha = today()->toDateString();
            $table->hora = now()->toTimeString();
            $table->gs_usuario_id = Auth::user()->id;
            $table->fS = Carbon::now()->toDateTimeString();
            $table->nSolicitud = AuAnexot3::max('nSolicitud') + 1;
        });

        self::updating(function ($table) {
            if ($table->ind === '2' || $table->ind === '3') {
                $table->gs_usuariovalida_id = Auth::user()['id'];
                if ($table->ind === '3') $table->fS1 = Carbon::now()->toDateTimeString();
            }
        });

    }


}
