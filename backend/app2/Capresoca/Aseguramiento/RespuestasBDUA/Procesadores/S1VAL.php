<?php


namespace App\Capresoca\Aseguramiento\RespuestasBDUA\Procesadores;


use App\Capresoca\Aseguramiento\RespuestasBDUA\ProcesadorV2;
use App\Capresoca\Aseguramiento\RespuestasBDUA\S1Trait;
use App\Models\Aseguramiento\AsBduaproceso;
use Illuminate\Support\Facades\DB;

class S1VAL extends ProcesadorV2
{
    protected $csvPath;
    protected $proceso;
    use S1Trait;

    public function __construct($csvPath, AsBduaproceso $proceso = null)
    {
        parent::__construct($csvPath, $proceso);
        $this->campos = "codeps,
        tipoIdentificacion,
        identificacion,
        primerApellido,
        segundoApellido,
        primerNombre,
        segundoNombre,
        fechaNacimiento,
        genero,
        tipoIdentificacion1,
        identificacion1,
        primerApellido1,
        segundoApellido1,
        primerNombre1,
        segundoNombre1,
        fechaNacimiento1,
        genero1,
        codigoDepartamento,
        codigoMunicipio,
        zona,
        fechaAfiliacionEps,
        tipoPobla,
        nsisben,
        tipoSubsidio";
    }

    public function procesar()
    {
        $tabla_s1Automatico_temporal = $this->cargarATabla('as_s1autvals');

        $this->createIndiceIdentificacion($tabla_s1Automatico_temporal);

        $this->validarDuplicados($tabla_s1Automatico_temporal);

        $this->validarAfiliadoExiste($tabla_s1Automatico_temporal);

        $this->afiliadoFallecido($tabla_s1Automatico_temporal);

        $this->updateTramite($tabla_s1Automatico_temporal);

        $this->insertTramiteS1Val($tabla_s1Automatico_temporal);
    }

    private function updateTramite(string $tabla_s1Automatico_temporal): void
    {
        DB::statement("
             UPDATE
             (
                SELECT MAX(z.id) AS id, z.tipo_tramite FROM
                 as_tramites AS z
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=z.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN {$tabla_s1Automatico_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE z.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL
                 GROUP BY b.as_afiliado_id
             )AS a 
             LEFT JOIN as_tramites as tr on tr.id = a.id
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN {$tabla_s1Automatico_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             SET tr.estado = 'Enviado'
             WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL AND c.estado!='3' and c.estado != 8
        ");
    }

    private function insertTramiteS1Val(string $tabla_s1Automatico_temporal)
    {
        DB::statement("
            INSERT INTO as_tramites_s1_val (as_tramite_id,fecha_afiliacion)
            SELECT a.id,
            concat(SUBSTRING(d.fechaAfiliacionEps,7,4),'-',SUBSTRING(d.fechaAfiliacionEps,4,2),'-',SUBSTRING(d.fechaAfiliacionEps,1,2)) AS fecha_afiliacion
            from
             (
                SELECT MAX(a.id) AS id, a.tipo_tramite FROM
                 as_tramites AS a
                 LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
                 LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
                 LEFT JOIN {$tabla_s1Automatico_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
                 WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL
                 GROUP BY b.as_afiliado_id
             )AS a 
             LEFT JOIN as_traslatramites AS b ON b.as_tramite_id=a.id
             LEFT JOIN as_afiliados AS c ON c.id = b.as_afiliado_id
             LEFT JOIN {$tabla_s1Automatico_temporal} AS d ON d.tipoIdentificacion=c.tipo_identificacion AND d.identificacion=c.identificacion
             WHERE a.tipo_tramite = 'Traslado Subsidiado' AND d.codeps IS NOT NULL
             ORDER BY c.identificacion 
        ");
    }

}

