<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10/10/2018
 * Time: 9:29 AM
 */

namespace App\Capresoca\Aseguramiento\Glosas;


use App\Models\General\GnEmpresa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GN0030 extends ProcesadorGlosaBase implements ProcesadorGlosasInterface
{

    public function procesar()
    {
        Log::info('Glosa a GN030');
        //Afiliado no pertenece a la entidad / Régimen.
        //Verificar si la entidad es Capresoca, de ser el caso generar de nuevo la novedad indicando el régimen para que su envío se haga en el archivo plano correcto.
        //Si el afiliado está inscrito en el régimen contributivo de otra EPS, finalizar el trámite de la novedad.

        if($this->actualizarRegimen())
        {
            $this->crearRetramite();
            $this->corregida();
            return true;
        };

        return false;
    }

    private function actualizarRegimen()
    {
        $empresa = GnEmpresa::all()->first();
        if($this->columnas[1] === $empresa->codhabilitacion_subsid){
            $this->afiliado->as_regimene_id = 2;
            $this->afiliado->save();
            return true;
        }

        if($this->columnas[1] === $empresa->codhabilitacion_contrib){
            $this->afiliado->as_regimene_id = 1;
            $this->afiliado->save();
            return true;
        }

        return false;

    }
}