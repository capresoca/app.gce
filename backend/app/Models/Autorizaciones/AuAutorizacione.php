<?php

namespace App\Models\Autorizaciones;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnArchivo;
use App\Models\RedServicios\RsCie10;
use App\Models\RedServicios\RsPlanescobertura;
use App\Models\RedServicios\RsEntidade;
use App\Models\RedServicios\RsServcontratado;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AuAutorizacione extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'as_afiliado_id',
        'as_regimen_id',
        'entidad_origen_id',
        'tipo_origen',
        'cie10_principal_id',
        'cie10_rel1_id',
        'cie10_rel2_id',
        'au_modservicio_id',
        'rs_contrato_id',
        'rs_servicio_id',
        'orden_medica',
        'historia_clinica',
        'oj_tutela_id',
        'alto_costo',
        'pyp',
        'atencion_materna',
        'enfermedad_trasmisible',
        'catastrofe',
        'estado',
        'fecha_orden',
        'tipo_destino'
    ];

    protected $appends = ['valor_total','valor_tot_user'];
    protected $searchable = ['search'];
    public function detalles()
    {
        return $this->hasMany(AuAutdetalle::class, 'au_autorizacion_id');
    }

    public function afiliado()
    {
        return $this->belongsTo(AsAfiliado::class, 'as_afiliado_id');
    }

    public function entidad_origen()
    {
        return $this->belongsTo(RsEntidade::class, 'entidad_origen_id');
    }

    public function servicio()
    {
        return $this->belongsTo(RsServcontratado::class, 'rs_servicio_id');
    }

    public function cie10_principal()
    {
        return $this->belongsTo(RsCie10::class, 'cie10_principal_id');
    }

    public function cie10_rel1()
    {
        return $this->belongsTo(RsCie10::class, 'cie10_rel1_id');
    }
    public function cie10_rel2()
    {
        return $this->belongsTo(RsCie10::class, 'cie10_rel1_id');
    }

    public function aprobaciones()
    {
        return $this->hasMany(AuAutaprobada::class, 'au_autorizacion_id');
    }

    public function solicitudes()
    {
        return $this->hasMany(AuAutsolicitude::class, 'au_autorizacion_id');
    }


    public function contrato()
    {
        return $this->belongsTo(RsPlanescobertura::class, 'rs_contrato_id');
    }

    public function historia_clinica()
    {
        return $this->belongsTo(GnArchivo::class, 'historia_clinica_id');
    }

    public function orden_medica()
    {
        return $this->belongsTo(GnArchivo::class, 'orden_medica_id');
    }

    public function getValorTotalAttribute () {
        $valores = AuAutdetalle::where('au_autorizacion_id', $this->id)->get();
        $valorTotal1 = 0;
        foreach ($valores as $resultado) {
            $valorTotal = $resultado->valor * $resultado->cantidad_aprobada;
            $valorTotal1 += $valorTotal;
        }
        return $valorTotal1;
    }

    public function getValorTotUserAttribute () {
//        return 'algo';
        $valores = AuAutdetalle::where('au_autorizacion_id', $this->id)->get();
        $valorTotal2 = 0;
        foreach ($valores as $resultado) {
            $valorTotal = $resultado->valor_usuario * $resultado->cantidad_aprobada;
            $valorTotal2 += $valorTotal;
        }
        return $valorTotal2;
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->whereHas('afiliado', function ($query) use ($constraint){
                $query->where('nombre_completo', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue());
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

}


