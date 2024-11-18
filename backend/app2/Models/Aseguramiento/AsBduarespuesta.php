<?php

namespace App\Models\Aseguramiento;

use App\Models\General\GnArchivo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;

class AsBduarespuesta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'as_bduaarchivo_id',
        'tipo_respuesta',
        'gn_archivo_id',
        'total_registros'
    ];

    protected $appends = ['nombre_archivo'];

    protected $searchable = ['search'];

    public function archivo(){
        return $this->belongsTo(AsBduaarchivo::class, 'as_bduaarchivo_id');
    }

    public function path(){
        return $this->belongsTo(GnArchivo::class, 'gn_archivo_id');
    }

    public function getNombreArchivoAttribute(){
        $nombre_exploded = explode('.',$this->path->nombre);

        return $nombre_exploded[0];
    }

    public function pendientes()
    {
        return $this->hasMany(AsBduapendiente::class,'as_bduarespuesta_id');
    }

    public function registros()
    {
        return $this->hasMany(AsBduaregrespuesta::class,'as_bduarespuesta_id');
    }


    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only
        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $search = $constraint->getValue();

            $builder->whereHas('archivo',function ($query)use($search){
                $query->where('nombre','like','%'.$search.'%');
            });

            return true;
        }

        // default logic should be executed otherwise
        return false;
    }


}



