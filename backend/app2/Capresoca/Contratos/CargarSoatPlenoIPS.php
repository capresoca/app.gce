<?php


namespace App\Capresoca\Contratos;


use App\Models\ContratacionEstatal\CeProconminuta;
use App\Models\CuentasMedicas\CmMansoat;
use App\Models\RedServicios\RsEntidade;
use Illuminate\Support\Facades\DB;

class CargarSoatPlenoIPS
{
    public static function cargar(){
        $entidades = RsEntidade::join('ce_proconminutas', 'rs_entidades.id', 'ce_proconminutas.rs_entidad_id')
            ->distinct()
            ->select('rs_entidades.*')
            ->where('rs_tipentidade_id', 1)
            ->get();
        $soatPleno = CmMansoat::join('rs_cupss', 'cm_mansoats.id', 'rs_cupss.cm_mansoat_id')
            ->select('rs_cupss.id', DB::raw('ROUND((cm_mansoats.unidades * (828116 / 30) * ((100 + 0)/100)),-2) as tarifa'))
            ->get();

        foreach ($entidades as $entidad){
            $tarifas =  $soatPleno->map(function ($item, $key) use ($entidad) {
               return [
                   'rs_entidad_id' => $entidad->id,
                   'rs_cups_id' => $item['id'],
                   'tarifa' => $item['tarifa']
               ];
            });
            $entidad->procedimientos()->attach($tarifas);
        }
    }
}