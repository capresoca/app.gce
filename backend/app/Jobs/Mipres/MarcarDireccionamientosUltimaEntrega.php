<?php


namespace App\Jobs\Mipres;


use App\Models\Mipres\MpDireccionamiento;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

abstract class MarcarDireccionamientosUltimaEntrega
{
    public static function run()
    {
        $query = "
            SELECT MAX(dir.id) id FROM mp_direccionamientos dir
            WHERE deleted_at IS null 
            GROUP BY dir.NoPrescripcion,dir.TipoTec,dir.ConTec
        ";

        $idsUltimosDireccionamientos = DB::select($query);
        $ids = Arr::pluck($idsUltimosDireccionamientos,'id');

        MpDireccionamiento::whereIn('id',$ids)->update(['UltEntrega' => 1]);
    }
}