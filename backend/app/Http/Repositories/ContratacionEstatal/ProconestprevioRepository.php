<?php
/**
 * Created by PhpStorm.
 * User: Jorge Eduardo
 * Date: 20/09/2018
 * Time: 9:13 AM
 */

namespace App\Http\Repositories\ContratacionEstatal;


use App\Http\Repositories\Repository;
use App\Models\ContratacionEstatal\CeProconestprevio;

class ProconestprevioRepository implements Repository
{

    public function guardar($requestArray)
    {
        if (!isset($requestArray['id'])) {
            $ce_proconestprevio = CeProconestprevio::create($requestArray);
            $ce_proconestprevio->actividades()->createMany($requestArray['actividades']);
            $ce_proconestprevio->forPagos()->createMany($requestArray['forPagos']);
            $ce_proconestprevio->garantias()->createMany($requestArray['garantias']);
        }
    }

    public function validar($requestArray)
    {
        // TODO: Implement validar() method.
    }
}