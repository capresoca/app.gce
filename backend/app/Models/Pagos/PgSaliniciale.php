<?php

namespace App\Models\Pagos;

use App\Models\Niif\NfNiif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class PgSaliniciale extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;

    protected $fillable = [
        'consecutivo',
        'fecha_anticipo',
        'fecha_documento',
        'fecha_vencimiento',
        'fecha',
        'nf_niif_id',
        'no_documento',
        'pg_proveedore_id',
        'tipo',
        'valor',
        'observaciones',
        'detalle_anulacion',
        'estado'
    ];

    public $sortable = ['consecutivo','fecha_causacion'];
    public $searchable = ['fecha_causacion','consecutivo','detalle','created_at'];

    protected $hidden = ['updated_at', 'created_at'];

    public function niif () {
        return $this->belongsTo(NfNiif::class,'nf_niif_id');
    }

    public function proveedor () {
        return $this->belongsTo(PgProveedore::class,'pg_proveedore_id');
    }

    public function setFechaAttribute ($value)
    {
        $this->attributes['fecha'] = $value . substr(now(), 10);
    }

    public function getFechaAttribute ()
    {
        return substr($this->attributes['fecha'], 0, -8);
    }

    public function setFechaDocumentoAttribute ($value)
    {
        if ($this->tipo === 'CXP') {
            $this->attributes['fecha_documento'] = $value . substr(now(), 10);
        }
    }

    public function getFechaDocumentoAttribute ()
    {
        if ($this->tipo === 'CXP') {
            return substr($this->attributes['fecha_documento'], 0, -8);
        }
    }

    public function setFechaVencimientoAttribute ($value)
    {
        if ($this->tipo === 'CXP') {
            $this->attributes['fecha_vencimiento'] = $value . substr(now(), 10);
        }
    }

    public function getFechaVencimientoAttribute ()
    {
        if ($this->tipo === 'CXP') {
            return substr($this->attributes['fecha_vencimiento'], 0, -8);
        }
    }

    public function setFechaAnticipoAttribute ($value)
    {
        if ($this->tipo === 'Anticipo') {
            $this->attributes['fecha_anticipo'] = $value . substr(now(), 10);
        }
    }

    public function getFechaAnticipoAttribute ()
    {
        if ($this->tipo === 'Anticipo') {
            return substr($this->attributes['fecha_anticipo'], 0, -8);
        }
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        PgSaliniciale::saving(function ($table){
//            if(Auth::user()){
//                $table->gs_usuario_id  = Auth::user()->id;
//            }
            $table->consecutivo = PgSaliniciale::max('consecutivo') + 1;
        });

    }
}