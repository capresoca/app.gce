<?php

namespace App\Capresoca\RecCompensacion;

use App\Models\Enums\EEstadoArchivoCompensacionRecaudo;
use App\Models\Enums\ESiNo;
use App\Models\RecCompensacion\RecLogBancarioDetalleAportanteSgp;
use App\Models\RecCompensacion\RecLogBancarioEncabezado;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleI;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIPe;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleIPTotale;
use App\Models\RecCompensacion\RecRecaudoPlanillaDetalleITotal;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Boolean;
use function MongoDB\BSON\toJSON;

/**
 * @author Jorge Eduardo Hernández Oropeza 10/06/2020
 * @copyright Creando Soluciones Informaticas LTDA
 *
 * Class ProcesoConciliacionPilaBanco
 * @package App\Capresoca\RecCompensacion
 */
class ProcesoConciliacionPilaBanco
{

    /**
     * @return array|Collection
     */
    public static function getConsecutivosLogBancarioExitosos()
    {
        $sql = DB::table('rec_log_bancario_encabezados')
            ->select('consecutivo_log_bancario_encabezado')
            ->where('estado', '=', 1)->get();

        $query = self::recorrer($sql->toArray(), "consecutivo_log_bancario_encabezado", []);

        return $query;
    }

    /**
     * @param $pensionados
     * @return Collection
     */
    public static function getConsecutivosRecaudoPlanillaExitosos($pensionados)
    {
        $sql = null;
        $query = null;

        if (($pensionados === true)) {
            $sql = RecRecaudoPlanillaDetalleIPe::select('consecutivo_recaudo_planilla_detalle_i_p', 'consecutivo_recaudo_encabezado')
                ->join('rec_recaudo_planilla_encabezados', 'rec_recaudo_planilla_encabezados.consecutivo_recaudo', '=', 'rec_recaudo_planilla_detalle_i_pes.consecutivo_recaudo_encabezado')
                ->where('rec_recaudo_planilla_encabezados.tipo_archivo', 'IP')
                ->where('rec_recaudo_planilla_encabezados.resultado', EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId())
                ->whereNull('rec_recaudo_planilla_detalle_i_pes.sw_conciliacion')
                ->orWhere('rec_recaudo_planilla_detalle_i_pes.sw_conciliacion', ESiNo::getIndice(ESiNo::NO)->getId())->get();

            // $query = self::recorrer($sql->toArray(), 'consecutivo_recaudo_encabezado', []);
            return $sql;

        } else {
            $sql = RecRecaudoPlanillaDetalleI::select('consecutivo_recaudo_planilla_detalle_i', 'consecutivo_recaudo_encabezado')
                ->join('rec_recaudo_planilla_encabezados', 'rec_recaudo_planilla_encabezados.consecutivo_recaudo', '=', 'rec_recaudo_planilla_detalle_is.consecutivo_recaudo_encabezado')
                ->where('rec_recaudo_planilla_encabezados.tipo_archivo', 'I')
                ->where('rec_recaudo_planilla_encabezados.resultado', EEstadoArchivoCompensacionRecaudo::getIndice(EEstadoArchivoCompensacionRecaudo::CARGUE_EXITOSO)->getId())
                ->whereNull('rec_recaudo_planilla_detalle_is.sw_conciliacion')
                ->orWhere('rec_recaudo_planilla_detalle_is.sw_conciliacion', ESiNo::getIndice(ESiNo::NO)->getId())->get();
            //dd($sql);
            //die;
            //$query = self::recorrer($sql->toArray(), 'consecutivo_recaudo_encabezado', []);

            return $sql;
        }

        // return $query;
    }

    /**
     * @param array $llavesEnc
     * @return Collection
     */
    public static function getRecRecaudoPlantillaDetalleITotalesConciliacion(array $llavesEnc): Collection
    {
        $columns = "consecutivo_recaudo_planilla_detalle_i_total, consecutivo_recaudo_encabezado, total_cotizacion, total_neto_upc_adicional";

        $items = collect();
        foreach ($llavesEnc as $key => $item) {
            $items->push($item['consecutivo_recaudo_encabezado']);
        }
        $status = is_array($items->toArray()) ? str_replace("'", "", ("'" . implode("','", $items->toArray()) . "'")) : $llavesEnc;

        $sql = json_decode(json_encode(
            DB::select("SELECT o.consecutivo_recaudo_planilla_detalle_i_total,
                                         o.consecutivo_recaudo_encabezado,
                                         o.total_cotizacion,
                                         o.total_neto_upc_adicional 
                                FROM rec_recaudo_planilla_detalle_i_totals AS o
                                WHERE 1 = 1 AND o.consecutivo_recaudo_encabezado IN ({$status})")
        ), true);

        $arr = explode(', ', $columns);

        $querie = self::recorrer($sql, '', $arr);

        return $querie;
    }

    /**
     * @param array $llavesEnc
     * @return Collection
     */
    public static function getRecRecaudoPlantillaDetalleIPTotalesConciliacion(array $llavesEnc): Collection
    {
        $columns = "consecutivo_planilla_detalle_i_p_total, consecutivo_recaudo_encabezado, total_cotizacion_obligatoria, total_upc_adicional";

        $items = collect();
        foreach ($llavesEnc as $key => $item) {
            $items->push($item['consecutivo_recaudo_encabezado']);
        }

        $status = is_array($items->toArray()) ? (implode("','", $items->toArray())) : $llavesEnc;

        $sql = json_decode(json_encode(DB::select("SELECT o.consecutivo_planilla_detalle_i_p_total,
                                         o.consecutivo_recaudo_encabezado,
                                         o.total_cotizacion_obligatoria,
                                         o.total_upc_adicional 
                                FROM rec_recaudo_planilla_detalle_i_p_totales AS o
                                WHERE 1 = 1 AND o.consecutivo_recaudo_encabezado IN ({$status})")
        ), true);

        $arr = explode(', ', $columns);

        $query = self::recorrer($sql, '', $arr);

        return $query;
    }

    /**
     * @param array $llavesEnc
     * @return Collection
     */
    public static function getLogBancarioDetalleAportanteSgpConciliacion(array $llavesEnc): Collection
    {
        $resultado = collect();

        $lista = DB::table('rec_log_bancario_detalle_aportante_sgps')
            ->where('tipo_registro', '=', 6)
            ->whereNull('consecutivo_recaudo_planilla_detalle_i')
            ->whereNull('consecutivo_recaudo_planilla_detalle_i_p')
            ->whereIn('consecutivo_encabezado', $llavesEnc)->get();

        foreach ($lista->toArray() as $key => $value) {
            $dto = new Collection();
            $dto->put('consecutivo_log_detalle_aportante', $value->consecutivo_log_detalle_aportante_sgp);
            $dto->put('consecutivo_encabezado', $value->consecutivo_encabezado);
            $dto->put('identificacion_aportante', $value->identificacion_aportante);
            $dto->put('numero_planilla_liquidacion', $value->numero_planilla_liquidacion);
            $dto->put('periodo_pago', $value->periodo_pago);
            $dto->put('numero_registros', $value->numero_registros);
            $dto->put('codigo_operador_informacion', $value->codigo_operador_informacion);
            $dto->put('valor_planilla', $value->valor_planilla);
            $dto->put('tipo_archivo', 2); // RECAUDO APORTANTE SGP
            $dto->put('sw_conciliacion', $value->sw_conciliacion);
            $resultado->push($dto);
        }

        return $resultado;
    }

    /**
     * @param array $llavesEnc
     * @return Collection
     */
    public static function getLogBancarioDetalleAportanteConciliacion(array $llavesEnc): Collection
    {
        $resultado = collect();

        $lista = DB::table('rec_log_bancario_detalle_aportantes')
            ->where('tipo_registro', '=', 6)
            ->whereNull('consecutivo_recaudo_planilla_detalle_i')
            ->whereNull('consecutivo_recaudo_planilla_detalle_i_p')
            ->whereIn('consecutivo_encabezado', $llavesEnc)->get();

        foreach ($lista->toArray() as $key => $value) {
            $dto = new Collection();
            $dto->put('consecutivo_log_detalle_aportante', $value->consecutivo_log_detalle_aportante);
            $dto->put('identificacion_aportante', $value->identificacion_aportante);
            $dto->put('numero_planilla_liquidacion', $value->numero_planilla_liquidacion);
            $dto->put('periodo_pago', $value->periodo_pago);
            $dto->put('numero_registros', $value->numero_registros);
            $dto->put('codigo_operador_informacion', $value->codigo_operador_informacion);
            $dto->put('valor_planilla', $value->valor_planilla);
            $dto->put('tipo_archivo', 1); // RECAUDO APORTANTE
            $dto->put('sw_conciliacion', $value->sw_conciliacion);
            $resultado->push($dto);
        }

        return $resultado;
    }

    /**
     * @param array $llavesEnc
     * @return Collection
     */
    public static function getRecRecaudoPlantillaDetalleIConciliacion(array $llavesEnc): Collection
    {
        $items = collect();
        foreach ($llavesEnc as $key => $item) {
            $items->push($item['consecutivo_recaudo_planilla_detalle_i']);
        }
        $status = is_array($items->toArray()) ? ("'" . implode("','", $items->toArray()) . "'") : $llavesEnc;
        str_replace("'", "", $status);

        $sw_conciliacion = ESiNo::getIndice(ESiNo::NO)->getId();
        $queries = DB::select(/** @lang text */ "SELECT o.* FROM rec_recaudo_planilla_detalle_is AS o
                                    WHERE 1 = 1 AND (o.sw_conciliacion IS NULL OR o.sw_conciliacion = {$sw_conciliacion})
                                    AND o.consecutivo_recaudo_planilla_detalle_i IN ({$status})");
        return new Collection($queries);
    }

    /**
     * @param array $llavesEnc
     * @return Collection
     */
    public static function getRecRecaudoPlantillaDetalleIPConciliacion(array $llavesEnc): Collection
    {
        $items = collect();
        foreach ($llavesEnc as $key => $item) {
            $items->push($item['consecutivo_recaudo_planilla_detalle_i_p']);
        }
        $status = is_array($items->toArray()) ? ("'" . implode("','", $items->toArray()) . "'") : $llavesEnc;
        str_replace("'", "", $status);

        $sw_conciliacion = ESiNo::getIndice(ESiNo::NO)->getId();
        ///dd($sw_conciliacion);
        $queries = DB::select(/** @lang text */ "SELECT o.* FROM rec_recaudo_planilla_detalle_i_pes AS o
                                    WHERE 1 = 1 AND (o.sw_conciliacion IS NULL OR o.sw_conciliacion = {$sw_conciliacion})
                                    AND o.consecutivo_recaudo_planilla_detalle_i_p IN ({$status})");
        //dd('09090', $queries);
        //die;

        return new Collection($queries);
    }

    /**
     * @param array $llavesHuerfanas
     * @return Collection
     */
    public static function conciliarPlanillasHuerfanas(array $llavesHuerfanas)
    {

        $resultado = collect();
        $recaudos = "";
        $lista = null;
        $identificadores = collect();

        foreach ($llavesHuerfanas as $key => $item) {
            $recaudos = $recaudos . ((int) $item['consecutivo_recaudo_encabezado']) . ', ';
        }
        $recaudos = substr($recaudos, 0, (strlen($recaudos) - 2));

        $lista = json_decode(json_encode(DB::select(/** @lang text */ "SELECT o.consecutivo_recaudo_planilla_detalle_i_total,
                                            o.consecutivo_recaudo_encabezado
                                    FROM rec_recaudo_planilla_detalle_i_totals AS o
                                    WHERE 1=1 AND o.total_cotizacion = 0 AND o.total_neto_aportes = 0 AND o.valor_mora_upc_adicional = 0 AND o.consecutivo_recaudo_encabezado IN ({$recaudos})")), true);

        if (count($lista) > 0) {
            $resultado->push($lista);
        }

        $lista = json_decode(json_encode(DB::select(/** @lang text */ "SELECT a.consecutivo_planilla_detalle_i_p_total,
                                                     a.consecutivo_recaudo_encabezado
                                            FROM rec_recaudo_planilla_detalle_i_p_totales AS a
                                            WHERE 1=1 AND a.total_ibc = 0 AND a.valor_cotizacion_obligatoria = 0
                                            AND a.total_aporte_fosyga = 0 AND a.consecutivo_recaudo_encabezado IN ({$recaudos})")), true);

        if (count($lista) > 0) {
            $resultado->push($lista);
        }


        $lista = json_decode(json_encode(DB::select(/** @lang text */ "SELECT b.consecutivo_recaudo_planilla_detalle_i_r_total,
                                                                                     b.consecutivo_recaudo_encabezado
                                                                            FROM rec_recaudo_planilla_detalle_i_r_totals AS b
                                                                            WHERE 1=1 AND b.total_cotizacion = 0 AND b.total_neto_aportes = 0
                                                                            AND b.total_neto_upc_adicional = 0 AND b.consecutivo_recaudo_encabezado IN ($recaudos)")), true);
        if (count($lista) > 0) {
            $resultado->push($lista);
        }

        return $resultado;
    }


    /**
     * @param array $resultado
     * @param string $var
     * @param array $array
     * @return array|Collection
     */
    private static function recorrer(array $resultado, string $var, array $array)
    {
        $lista = collect();
        // PENDIENTE MIENTRAS QUE SE ARREGLA EL ARCHIVO DE PILA
        foreach ($resultado as $itemKey => $value) {
            if (is_string($var) && (!empty($var))) {
                $lista->push($resultado[$itemKey]->$var);
            }

            if (is_array($array) && (!empty($array))) {
                $cero = $array[0];
                $uno = $array[1];
                $dos = $array[2];
                $tres = $array[3];
                $valueKey = $value[$cero];
                $valueRec = $value[$uno];
                $valueOne = ((double)$value[$dos]);
                $valueTwo = ((double)$value[$tres]);
                $lista->put($valueRec, [
                    'consecutivo' => $valueKey,
                    'valor' => ($valueOne + $valueTwo)
                ]);
            }
        }

        return $lista;
    }


    /**
     * @param Collection $collection
     * @param int $limit
     * @return array|[]|null
     */
    public static function getSubLista(Collection $collection, int $limit): array
    {
        $resultado = array();
        $inicio = 0;

        if ($collection->isEmpty()) return [];

        $size = $collection->count();
        $toArray = $collection->toArray();
        if ($limit >= $size) {
            if (!$collection->isEmpty()) {
                array_push($resultado, $toArray);
            }
            return $resultado;
        }

        $salir = false;
        while (!$salir) {
            $fin = $inicio + $limit;
            $array_splice = array_splice($toArray, $inicio, $fin);
            array_push($resultado, $array_splice);

            $inicio = $fin;
            $fin = $fin + $limit;
            if ($fin >= $size) {
                $fin = $limit;
                $array_splice = array_splice($toArray, $inicio, $fin);
                array_push($resultado, $array_splice);
                $salir = true;
            }
        }
        return $resultado;
    }
}