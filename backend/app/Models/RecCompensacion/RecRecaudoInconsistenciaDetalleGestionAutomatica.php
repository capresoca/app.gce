<?php

namespace App\Models\RecCompensacion;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @author Jorge Eduardo Hernández Oropeza 08/05/2020
 * Creando Soluciones Informaticas LTDA
 *
 * Class RecRecaudoInconsistenciaDetalleGestionAutomatica
 * @package App\Models\RecCompensacion
 */


class RecRecaudoInconsistenciaDetalleGestionAutomatica extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'rec_recaudo_inconsistencia_gestion_automaticas';

}
