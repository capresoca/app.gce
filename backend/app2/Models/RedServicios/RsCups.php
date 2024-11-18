<?php

namespace App\Models\RedServicios;

use App\Models\CuentasMedicas\CmManisss;
use App\Models\CuentasMedicas\CmMansoat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class RsCups extends Model implements Auditable
{
    protected $table = 'rs_cupss';

    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $searchable = ['codigo','descripcion','search','cupscategoria:subgrupo:rs_cupsgrupo_id','cm_mansoat_id','estancia'];

    protected $fillable = [
        'rs_cupscategoria_id',
        'codigo',
        'descripcion',
        'genero',
        'ambito',
        'estancia',
        'cobertura',
        'duplicado',
        'vida',
        'cie10_relacionados',
        'frecuencia_uso',
        'nivel_autorizacion',
        'pos',
        'cm_mansoat_id',
        'cm_maniss_id'
    ];

    public function cupscategoria () {
        return $this->belongsTo(RsCupscategoria::class, 'rs_cupscategoria_id');
    }

    public function parent()
    {
        return $this->cupscategoria();
    }
    public function soat ()
    {
        return $this->belongsTo(CmMansoat::class, 'cm_mansoat_id');
    }

    public function maniss () {
        return $this->belongsTo(CmManisss::class, 'cm_maniss_id');
    }

    public function setGeneroAttribute($value)
    {
        if ($value === 'Masculino') {
            $this->attributes['genero'] = 'M';
        } else if($value === 'Femenino') {
            $this->attributes['genero'] = 'F';
        } else if ($value === 'Sin restricción') {
            $this->attributes['genero'] = 'Z';
        }
    }

    public function getGeneroAttribute () {
        if ($this->attributes['genero'] === 'M') {
            return $this->attributes['genero'] = 'Masculino';
        } else if ($this->attributes['genero'] === 'F') {
            return $this->attributes['genero'] = 'Femenino';
        } else if ($this->attributes['genero'] === 'Z') {
            return $this->attributes['genero'] = 'Sin restricción';
        }
    }

    public function setAmbitoAttribute ($value)
    {
        if ($value === 'Ambulatorio') {
            $this->attributes['ambito'] = 'A';
        } else if ($value === 'Hospitalario') {
            $this->attributes['ambito'] = 'H';
        } else if ($value === 'Urgencias') {
            $this->attributes['ambito'] = 'U';
        } else if ($value === 'Domiciliario') {
            $this->attributes['ambito'] = 'D';
        } else if ($value === 'Sin restricción') {
            $this->attributes['ambito'] = 'Z';
        }
    }

    public function getAmbitoAttribute () {
        if ($this->attributes['ambito'] === 'A') {
            return $this->attributes['ambito'] = 'Ambulatorio';
        } else if ($this->attributes['ambito'] === 'H') {
            return $this->attributes['ambito'] = 'Hospitalario';
        } else if ($this->attributes['ambito'] === 'U') {
            return $this->attributes['ambito'] = 'Urgencias';
        } else if ($this->attributes['ambito'] === 'D') {
            return $this->attributes['ambito'] = 'Domiciliario';
        } else if ($this->attributes['ambito'] === 'Z') {
            return $this->attributes['ambito'] = 'Sin restricción';
        }
    }

    public function setDuplicadoAttribute ($value)
    {
        if ($value === 'Procedimento que se realiza una vez en el año') {
            $this->attributes['duplicado'] = 'A';
        } else if ($value === 'Procedimento que se realiza una vez en el día') {
            $this->attributes['duplicado'] = 'D';
        } else if ($value === 'Sin restricción') {
            $this->attributes['duplicado'] = 'Z';
        }
    }

    public function getDuplicadoAttribute () {
        if ($this->attributes['duplicado'] === 'A') {
            return $this->attributes['duplicado'] = 'Procedimento que se realiza una vez en el año';
        } else if ($this->attributes['duplicado'] === 'D') {
            return $this->attributes['duplicado'] = 'Procedimento que se realiza una vez en el día';
        } else if ($this->attributes['duplicado'] === 'Z') {
            return $this->attributes['duplicado'] = 'Sin restricción';
        }
    }

    public function setEstanciaAttribute($value)
    {
        if ($value === 'El procedimiento debe tener asociado un numero de dias de estanca mayor a cero') {
            $this->attributes['estancia'] = 'E';
        } else if ($value === 'Sin restricción') {
            $this->attributes['estancia'] = 'Z';
        }
    }

    public function getEstanciaAttribute () {
        if ($this->attributes['estancia'] === 'E') {
            return $this->attributes['estancia'] = 'El procedimiento debe tener asociado un numero de dias de estanca mayor a cero';
        } else if ($this->attributes['estancia'] === 'Z') {
            return $this->attributes['estancia'] = 'Sin restricción';
        }
    }

    public function setVidaAttribute ($value)
    {
        if ($value === 'Procedimento que  se realiza una vez en la vida') {
            $this->attributes['vida'] = 'V';
        } else if ($value === 'Sin restricción') {
            $this->attributes['vida'] = 'Z';
        }
    }

    public function getVidaAttribute () {
        if ($this->attributes['vida'] === 'V') {
            return $this->attributes['vida'] = 'Procedimento que  se realiza una vez en la vida';
        } else if ($this->attributes['vida'] === 'Z') {
            return $this->attributes['vida'] = 'Sin restricción';
        }
    }

    public function setCoberturaAttribute ($value)
    {
//        NOPBS: No hace parte del Plan de Beneficios
        if ($value === 'Hace parte del Plan de Beneficios') {
            $this->attributes['cobertura'] = 'PBS';
        } else if ($value === 'No hace parte del Plan de Beneficios') {
            $this->attributes['cobertura'] = 'NOPBS';
        } else {
            $this->attributes['cobertura'] = $value;
        }
    }

    public function getCoberturaAttribute ()
    {
//        NOPBS: No hace parte del Plan de Beneficios
        if ($this->attributes['cobertura'] === 'PBS') {
            return $this->attributes['cobertura'] = 'Hace parte del Plan de Beneficios';
        } else if ($this->attributes['cobertura'] === 'NOPBS') {
            return $this->attributes['cobertura'] = 'No hace parte del Plan de Beneficios';
        } else {
            return $this->attributes['cobertura'];
        }
    }

    protected function scopeWithAscendencia($query){
        return $query->with('parent.parent.parent');
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('descripcion', $constraint->getOperator(), strtoupper($constraint->getValue()))
                ->orWhere('codigo', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }

    /**
     * @param $manual
     * @param RsSalminimo|null $salminimo
     * @param int $porcentaje
     * @return mixed
     * @throws \Exception
     */
    public function calcularValor($manual, RsSalminimo $salminimo = null, $porcentaje = 0,$tarifa)
    {
        switch (strtoupper($manual)){
            case 'SOAT':
                return $this->calcularSOAT($salminimo,$porcentaje);

            case 'ISS':
                return $this->calcularISS($porcentaje);

            case 'INSTITUCIONAL':
                return $tarifa;
            default:
                throw new \Exception('El manual no es valido');

        }
    }

    private function calcularSOAT($salminimo, $porcentaje)
    {
        $porcentaje = (100 + $porcentaje)/100;
        $valor = ($salminimo->valor/30) * $porcentaje * $this->soat->unidades;

        return round($valor/100)*100;
    }

    private function calcularISS($porcentaje)
    {
        $porcentaje = (100 + $porcentaje)/100;
        $valor = $porcentaje * $this->maniss->valor;
        return round($valor/100)*100;
    }


}


