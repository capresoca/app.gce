<?php

namespace App\Models\Autorizaciones;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @author JORGE EDUARDO HERNANDEZ 06-04-2020
 * Class AuAnexot3ServEspeciale
 *
 * @package App\Models\Autorizaciones
 */

class AuAnexot3ServEspeciale extends Model
{
    protected $table = 'au_anexot3_serv_especiales';

    protected $fillable = [
        'gs_usuario_aprueba_id',
        'fecha_aprobacion',
        'observacion',
    ];

    public function usuario_aprueba()
    {
        return $this->belongsTo(User::class, 'gs_usuario_aprueba_id');
    }
}
