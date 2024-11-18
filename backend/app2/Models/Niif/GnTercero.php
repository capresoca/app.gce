<?php

namespace App\Models\Niif;

use App\Models\Aseguramiento\AsAfiliado;
use App\Models\General\GnDepartamento;
use App\Models\General\GnMunicipio;
use App\Models\General\GnTipdocidentidade;
use App\Models\Presupuesto\PrObligacione;
use App\Models\Presupuesto\PrOrdenesDePago;
use App\Models\Presupuesto\PrRp;
use App\Traits\ToolsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class GnTercero extends Model implements Auditable
{
    use PimpableTrait;
    use \OwenIt\Auditing\Auditable;

    protected $auditExclude = [];
    protected $fillable = [
        'gn_tipdocidentidad_id',
        'identificacion',
        'gn_munexpedicion_id',
        'nombre1',
        'nombre2',
        'apellido1',
        'apellido2',
        'razon_social',
        'direccion',
        'telefono_fijo',
        'celular',
        'correo_electronico',
        'gn_municipio_id',
        'tipo_retencion',
        'tipo_contribuyente',
        'tipo_persona',
        'tipo_tercero',
        'naturaleza_juridica',
        'ica',
        'porcentaje_ica',
        'autorretenedor',
        'agente_declarante',
        'nf_ciiu_id',
        'digito_verificacion',
        'ciudadania',
        'estado',
        'verificado',
        'nombre_completo'
    ];

    protected $appends = ['identificacion_completa', 'digito_verificacion', 'con_rps'];
    public $sortable = ['nombre1', 'apellido1', 'apellido2', 'nombre_completo'];
    public $searchable = ['search', 'nombre1', 'apellido1', 'apellido2', 'gn_tipdocidentidad_id', 'identificacion', 'nombre_completo'];

    public function rps()
    {
        return $this->hasMany(PrRp::class, 'gn_tercero_id', 'id');
    }

    public function obligaciones()
    {
        return $this->hasMany(PrObligacione::class, 'gn_tercero_id');
    }

    public function municipio()
    {
        return $this->belongsTo(GnMunicipio::class, 'gn_municipio_id');
    }

    public function municipio_expedicion()
    {
        return $this->belongsTo(GnMunicipio::class, 'gn_munexpedicion_id');
    }

    public function tipo_id()
    {
        return $this->belongsTo(GnTipdocidentidade::class, 'gn_tipdocidentidad_id');
    }

    public function afiliado()
    {
        return $this->hasOne(AsAfiliado::class, 'gn_tercero_id');
    }

    public function ciiu()
    {
        return $this->belongsTo(NfCiiu::class, 'nf_ciiu_id');
    }

    public function getConRpsAttribute()
    {
//        !isset($this->attributes['rps'])
        if ($this->rps()->count() === 0) return false;
        return true;
    }

    public function getIcaAttribute()
    {
        return $this->attributes['ica'] == 1;
    }

    public function getAgenteDeclaranteAttribute()
    {
        return $this->attributes['agente_declarante'] == 1;
    }

    public function getAutorretenedorAttribute()
    {
        return explode(',', $this->attributes['autorretenedor']);
    }

    //public function getNombreCompletoAttribute()
    //{
    //    if ($this->razon_social) {
    //        return strtoupper($this->razon_social);
    //    } else {
    //        $nombre_completo = '';
    //
    //        if ($this->nombre1) $nombre_completo .= ' ' . $this->nombre1;
    //
    //        if ($this->nombre2) $nombre_completo .= ' ' . $this->nombre2;
    //
    //        if ($this->apellido1) $nombre_completo .= ' ' . $this->apellido1;
    //
    //        if ($this->apellido2) $nombre_completo .= ' ' . $this->apellido2;
    //
    //        return strtoupper($nombre_completo);
    //    }
    //}

    public function getIdentificacionCompletaAttribute()
    {
        return $this->tipo_id->abreviatura . ' ' . $this->identificacion;
    }

    public function getDigitoVerificacionAttribute()
    {
        return ToolsTrait::calcularDigitoVerificacion($this->identificacion);
    }


    public function setAutorretenedorAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['autorretenedor'] = implode(',', $value);

        }
    }

    public function setTipoTerceroAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['tipo_tercero'] = implode(',', $value);
        }
    }

    public function getTipoTerceroAttribute()
    {
        return $this->attributes['tipo_tercero'] != null ? explode(',', $this->attributes['tipo_tercero']) : [];
    }

    public function setFechaNacimientoFAttribute($value)
    {
        $fecha = new Carbon($this->fecha_nacimiento);
        $fecha_f = $fecha->format('d\/m\/Y');
        $this->attributes['fecha_nacimiento_f'] = $fecha_f;

    }

    public function setNombreCompletoAttribute($value)
    {
        if ($this->attributes['nombre1'] === "" && ($this->attributes['razon_social'] !== "" || $this->attributes['razon_social'] !== null)) {
            $nombre_completo = $this->attributes['razon_social'];
        } else {
            $nombre_completo = $this->attributes['nombre1'];
            if ($this->apellido2)
                $nombre_completo .= ' ' . $this->attributes['nombre2'];

            if ($this->nombre1)
                $nombre_completo .= ' ' . $this->attributes['apellido1'];

            if ($this->nombre2)
                $nombre_completo .= ' ' . $this->attributes['apellido2'];
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


    public function scopeBuscar($query, $data)
    {
        return $query
            ->where('identificacion', 'like', '%' . $data . '%')
            ->orWhere('apellido1', 'like', '%' . $data . '%')
            ->orWhere('apellido2', 'like', '%' . $data . '%')
            ->orWhere('nombre1', 'like', '%' . $data . '%')
            ->orWhere('nombre2', 'like', '%' . $data . '%')
            ->orWhere('nombre_completo', 'like', '%' . $data . '%');


    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where(function ($builder) use ($constraint) {
                $builder->where('nombre1', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('nombre2', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido1', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('apellido2', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('razon_social', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('identificacion', $constraint->getOperator(), $constraint->getValue())
                    ->orWhere('nombre_completo', $constraint->getOperator(), $constraint->getValue());
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }

}


