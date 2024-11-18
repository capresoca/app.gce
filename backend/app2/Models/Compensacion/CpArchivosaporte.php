<?php

namespace App\Models\Compensacion;

use App\Models\General\GnArchivo;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Pimpable\PimpableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class CpArchivosaporte extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['archivo_financiero_id','archivo_pila_id'];

    public function archivoFinanciero()
    {
        return $this->belongsTo(GnArchivo::class, 'archivo_financiero_id');
    }

    public function archivoPila()
    {
        return $this->belongsTo(GnArchivo::class, 'archivo_pila_id');
    }

    public static function boot()
    {
        parent::boot();

        CpArchivosaporte::creating(function ($table){
            $table->estado = 'Procesando';
        });

    }
}
