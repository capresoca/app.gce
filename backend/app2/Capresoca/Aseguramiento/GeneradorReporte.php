<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/08/2018
 * Time: 9:33 AM
 */

namespace App\Capresoca\Aseguramiento;


use App\Models\Aseguramiento\AsBduaarchivo;
use App\Models\General\GnArchivo;
use App\Models\General\GnEmpresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Date\Date;
use Laracsv\Export;

class GeneradorReporte
{
    protected $bduaarchivo;

    protected $csv;

    protected $path;

    protected $codentidad;

    protected $nombreCSV;

    protected $exporter;

    public function __construct(AsBduaarchivo $bduaarchivo)
    {
        $this->bduaarchivo = $bduaarchivo;
        switch ($bduaarchivo->tipo->tipo_tramite) {
            case 'Novedad Subsidiado':
                $this->generarNovedadesCSV();
                break;
            case 'Novedad Contributivo':
                $this->generarNovedadesCSV();
                break;

            case 'Afiliacion':
                $this->generarAfiliacionesCSV();
                break;

            case 'MC':
                $this->generarMC();
                break;

            case 'Traslado Subsidiado':
                $this->generarTrasladosSubsidiado();
                break;
            case 'Traslado Contributivo':
                $this->generarTrasladosContributivo();
                break;
            case 'R4':
                $this->generarRespuestaSolicitudTrasladosContributibo();
                break;
            case 'S4':
                $this->generarRespuestaSolicitudTrasladosSubsidiado();
                break;


        }
    }

    protected function generarAfiliacionesCSV()
    {
        $result = DB::select("SELECT      
                 if(a.as_regimene_id='1','EPSC25','EPS025') AS CODIGOEPS,
                 c.abreviatura AS Tipo_documento,
                 a2.identificacion,
                 a2.nombre1,
                 a2.nombre2,
                 a2.apellido1,
                 a2.apellido2,
                 DATE_FORMAT(a2.fecha_nacimiento, '%d/%m/%Y') as fechaNacimiento,
                 e.abreviatura AS genero,
                 SUBSTRING(d.codigo,1,2) AS codigoDepartamento,
                 SUBSTRING(d.codigo,3,3) AS codigoMunicipio,
                 g.codigo AS zona,
                 f.codigo AS GrupoPoblacional,
                 a2.nivel_sisben,
                 h.cod_habilitacion as ipsPrimaria
            FROM as_formafiliaciones            AS a1 
            LEFT JOIN as_afiliados              AS a ON a.cabfamilia_id = a1.as_afiliado_id
            LEFT JOIN as_afiliados              AS a2 ON a2.id = a.id
            LEFT JOIN as_estadoafiliados        AS b ON b.id = a.estado
            LEFT JOIN as_afitramites            AS b1 ON b1.as_afiliado_id = a2.id
            LEFT JOIN gn_tipdocidentidades      AS c ON c.id = a2.gn_tipdocidentidad_id
            LEFT JOIN gn_municipios             AS d ON d.id = a2.gn_municipio_id
            LEFT JOIN gn_sexos                  AS e ON e.id = a2.gn_sexo_id
            LEFT JOIN as_pobespeciales              AS f ON f.id = a.as_pobespeciale_id
            LEFT JOIN gn_zonas                  AS g ON g.id = a.gn_zona_id
            LEFT JOIN rs_entidades              AS h ON h.id = a.rs_entidade_id
            WHERE a1.estado IN ('Registrado','Radicado') AND a.as_regimene_id = 2 AND a.gn_tipdocidentidad_id IN (3,4);");
        $csvExporter = new Export();

        $afiliados = collect($result);

        $csvExporter->build($afiliados, [
            'CODIGOEPS',
            'Tipo_documento',
            'identificacion',
            'nombre1',
            'nombre2',
            'apellido1',
            'apellido2',
            'fechaNacimiento',
            'genero',
            'codigoDepartamento',
            'codigoMunicipio',
            'zona',
            'GrupoPoblacional',
            'nivel_sisben',
            'ipsPrimaria'
        ], ['header' => false]);
        $this->exporter = $csvExporter;
    }

    protected function generarMC()
    {
        $afiliados = $this->getAfiliados();
        $csvExporter = new Export();
        $csvExporter->beforeEach(function ($afiliado) {
            if (isset($afiliado['apellido1'])) {
                $afiliado['apellido1'] = str_replace('"', '', $afiliado['apellido1']);
            }
            if (isset($afiliado['apellido2'])) {
                $afiliado['apellido2'] = str_replace('"', '', $afiliado['apellido2']);
            }
            if (isset($afiliado['nombre1'])) {
                $afiliado['nombre1'] = str_replace('"', '', $afiliado['nombre1']);
            }
            if (isset($afiliado['nombre2'])) {
                $afiliado['nombre2'] = str_replace('"', '', $afiliado['nombre2']);
            }
        });
        $csvExporter->build($afiliados, [
            'codigo_entidad',
            'cabeza.tipo_id.abreviatura',
            'cabeza.identificacion',
            'tipo_id.abreviatura',
            'identificacion',
            'apellido1',
            'apellido2',
            'nombre1',
            'nombre2',
            'fecha_nacimiento_slash',
            'sexo.abreviatura',
            '',
            'tipo_afiliado.codigo_planos',
            'as_parentesco_id',
            'condicion_discapacidad.abreviatura',
            'municipio.departamento.codigo',
            'municipio.codigo_planos',
            'zona.codigo',
            'fecha_afiliacion_slash',
            '',
            '',
            '',
            'ips.cod_habilitacion'
        ], ['header' => false]);
        $this->exporter = $csvExporter;

    }

    protected function generarNovedadesCSV()
    {
        $tramites = $this->bduaarchivo->tramites;
        $tramites->load('novedad.afiliado.municipio.departamento');
        $csvExporter = new Export();
//        $csvExporter->beforeEach(function ($tramite) {
//            if (isset($tramite['novedad']['afiliado']['apellido1'])) {
//                $tramite['novedad']['afiliado']['apellido1'] = str_replace('"', '', $tramite['novedad']['afiliado']['apellido1']);
//            }
//            if (isset($tramite['novedad']['afiliado']['apellido2'])) {
//                $tramite['novedad']['afiliado']['apellido2'] = str_replace('"', '', $tramite['novedad']['afiliado']['apellido2']);
//            }
//            if (isset($tramite['novedad']['afiliado']['nombre1'])) {
//                $tramite['novedad']['afiliado']['nombre1'] = str_replace('"', '', $tramite['novedad']['afiliado']['nombre1']);
//            }
//            if (isset($tramite['novedad']['afiliado']['nombre2'])) {
//                $tramite['novedad']['afiliado']['nombre2'] = str_replace('"', '', $tramite['novedad']['afiliado']['nombre2']);
//            }
//        });
        $csvExporter->build(
            $tramites,
            [
                'consecutivo',
                'novedad.codigo_entidad',
                'novedad.afiliado.tipo_id.abreviatura',
                'novedad.afiliado.identificacion',
                'novedad.afiliado.apellido1',
                'novedad.afiliado.apellido2',
                'novedad.afiliado.nombre1',
                'novedad.afiliado.nombre2',
                'novedad.afiliado.fecha_nacimiento_slash',
                'novedad.afiliado.municipio.departamento.codigo',
                'novedad.afiliado.municipio.codigo_planos',
                'novedad.novedad.codigo',
                'novedad.fecha_ini_novedad_slash',
                'novedad.v1',
                'novedad.v2',
                'novedad.v3',
                'novedad.v4',
                'novedad.v5',
                'novedad.v6',
                'novedad.v7'
            ],
            ['header' => false]);
        $this->exporter = $csvExporter;

    }

    protected function guardarCSV()
    {
        $this->setPath();
        Storage::put($this->path, $this->csv->getContent());
        $archivo = GnArchivo::create(
            [
                'ruta' => $this->path,
                'nombre' => $this->nombreCSV,
                'extension' => 'txt'
            ]
        );
        $this->bduaarchivo->archivo_id = $archivo->id;
        $this->bduaarchivo->save();
        $this->archivo = $archivo;
    }

    private function setPath()
    {
        $today = Date::today();
        $empresa = GnEmpresa::first();

        if ($this->bduaarchivo->tipo === 'Novedad Subsidiado') {
            $tipo_reporte = 'NS';
            $tipo_tramite = 'Novedades';
            $cod_entidad = $empresa->codhabilitacion_subsid;
        }
        if ($this->bduaarchivo->tipo === 'Novedad Contributivo') {
            $tipo_reporte = 'NC';
            $tipo_tramite = 'Novedades';
            $cod_entidad = $empresa->codhabilitacion_contrib;
        }
        if ($this->bduaarchivo->tipo === 'Afiliacion Subsidiado') {
            $tipo_reporte = 'MS';
            $tipo_tramite = 'Afiliaciones';
            $cod_entidad = $empresa->codhabilitacion_subsid;
        }
        if ($this->bduaarchivo->tipo === 'Afiliacion Contributivo') {
            $tipo_reporte = 'MC';
            $tipo_tramite = 'MC';
            $cod_entidad = $empresa->codhabilitacion_contrib;
        }

        $this->nombreCSV = $tipo_reporte . $cod_entidad . $today->format('dmY') . '.txt';
        $this->path = 'Aseguramiento/' . $tipo_tramite . '/' . $today->year . '/' . $today->format('F') . '/' . $this->nombreCSV;
    }


    public function getAfiliados()
    {
        $afiliados = collect();
        $tramites = $this->bduaarchivo->tramites()
            ->where('estado', 'Radicado')
            ->with(
                [
                    'afiliacion.afiliado.municipio.departamento',
                    'afiliacion.afiliado.zona',
                    'afiliacion.afiliado.sexo',
                    'afiliacion.afiliado.poblacion_especial',
                    'afiliacion.afiliado.ips',
                    'afiliacion.afiliado.condicion_discapacidad',
                    'afiliacion.afiliado.cabeza.tipo_id',
                    'afiliacion.afiliado.parentesco',
                    'afiliacion.afiliado.etnia',
                    'afiliacion.afiliado.tipo_afiliado'
                ]
            )->get();
        foreach ($tramites as $tramite) {
            if ($tramite->afiliacion) {
                $afiliados->push($tramite->afiliacion->afiliado);
            }
        }
        return $afiliados;

    }

    public function getCsv()
    {
        return $this->exporter->getCsv();
    }

    public function download($nombre)
    {
        return $this->exporter->download($nombre);
    }

    private function generarTrasladosSubsidiado()
    {
        $tramites = $this->bduaarchivo->tramites;
        $tramites->load('traslado.afiliado.municipio.departamento',
            'traslado.sexo',
            'traslado.afiliado.municipio.departamento',
            'traslado.afiliado.poblacion_especial',
            'traslado.formulario',
            'traslado.afiliado.zona');

        $csvExporter = new Export();
        $csvExporter->beforeEach(function ($tramite) {
            if (isset($tramite['traslado']['afiliado']['apellido1'])) {
                $tramite['traslado']['afiliado']['apellido1'] = str_replace('"', '', $tramite['traslado']['afiliado']['apellido1']);
            }
            if (isset($tramite['traslado']['afiliado']['apellido2'])) {
                $tramite['traslado']['afiliado']['apellido2'] = str_replace('"', '', $tramite['traslado']['afiliado']['apellido2']);
            }
            if (isset($tramite['traslado']['afiliado']['nombre1'])) {
                $tramite['traslado']['afiliado']['nombre1'] = str_replace('"', '', $tramite['traslado']['afiliado']['nombre1']);
            }
            if (isset($tramite['traslado']['afiliado']['nombre2'])) {
                $tramite['traslado']['afiliado']['nombre2'] = str_replace('"', '', $tramite['traslado']['afiliado']['nombre2']);
            }
            if (isset($tramite['traslado']['formulario']['apellido1'])) {
                $tramite['traslado']['formulario']['apellido1'] = str_replace('"', '', $tramite['traslado']['formulario']['apellido1']);
            }
            if (isset($tramite['traslado']['formulario']['apellido2'])) {
                $tramite['traslado']['formulario']['apellido2'] = str_replace('"', '', $tramite['traslado']['formulario']['apellido2']);
            }
            if (isset($tramite['traslado']['formulario']['nombre1'])) {
                $tramite['traslado']['formulario']['nombre1'] = str_replace('"', '', $tramite['traslado']['formulario']['nombre1']);
            }
            if (isset($tramite['traslado']['formulario']['nombre2'])) {
                $tramite['traslado']['formulario']['nombre2'] = str_replace('"', '', $tramite['traslado']['formulario']['nombre2']);
            }
        });
        $csvExporter->build(
            $tramites,
            [
                'traslado.formulario.entidad_solicita',
                'traslado.afiliado.tipo_id.abreviatura',
                'traslado.afiliado.identificacion',
                'traslado.afiliado.apellido1',
                'traslado.afiliado.apellido2',
                'traslado.afiliado.nombre1',
                'traslado.afiliado.nombre2',
                'traslado.afiliado.fecha_nacimiento_slash',
                'traslado.afiliado.sexo.abreviatura',
                'traslado.formulario.tipoIdentidad.abreviatura',
                'traslado.formulario.identificacion',
                'traslado.formulario.apellido1',
                'traslado.formulario.apellido2',
                'traslado.formulario.nombre1',
                'traslado.formulario.nombre2',
                'traslado.formulario.fecha_nacimiento_slash',
                'traslado.formulario.sexo.abreviatura',
                'traslado.afiliado.municipio.departamento.codigo',
                'traslado.afiliado.municipio.codigo_planos',
                'traslado.afiliado.zona.codigo',
                'traslado.formulario.fecha_traslado_slash',
                'traslado.afiliado.pob_esp',
                'traslado.afiliado.niv_sis',
                'traslado.tipo_traslado'
            ],
            ['header' => false]);

        $this->exporter = $csvExporter;
    }

    private function generarTrasladosContributivo()
    {
        $tramites = $this->bduaarchivo->tramites;
        $tramites->load('traslado.afiliado.municipio.departamento',
            'traslado.formulario.sexo',
            'traslado.formulario.eps',
            'traslado.afiliado.municipio.departamento',
            'traslado.afiliado.poblacion_especial',
            'traslado.afiliado.zona');

        $csvExporter = new Export();
        $csvExporter->beforeEach(function ($tramite) {
            if (isset($tramite['traslado']['afiliado']['apellido1'])) {
                $tramite['traslado']['afiliado']['apellido1'] = str_replace('"', '', $tramite['traslado']['afiliado']['apellido1']);
            }
            if (isset($tramite['traslado']['afiliado']['apellido2'])) {
                $tramite['traslado']['afiliado']['apellido2'] = str_replace('"', '', $tramite['traslado']['afiliado']['apellido2']);
            }
            if (isset($tramite['traslado']['afiliado']['nombre1'])) {
                $tramite['traslado']['afiliado']['nombre1'] = str_replace('"', '', $tramite['traslado']['afiliado']['nombre1']);
            }
            if (isset($tramite['traslado']['afiliado']['nombre2'])) {
                $tramite['traslado']['afiliado']['nombre2'] = str_replace('"', '', $tramite['traslado']['afiliado']['nombre2']);
            }
            if (isset($tramite['traslado']['formulario']['apellido1'])) {
                $tramite['traslado']['formulario']['apellido1'] = str_replace('"', '', $tramite['traslado']['formulario']['apellido1']);
            }
            if (isset($tramite['traslado']['formulario']['apellido2'])) {
                $tramite['traslado']['formulario']['apellido2'] = str_replace('"', '', $tramite['traslado']['formulario']['apellido2']);
            }
            if (isset($tramite['traslado']['formulario']['nombre1'])) {
                $tramite['traslado']['formulario']['nombre1'] = str_replace('"', '', $tramite['traslado']['formulario']['nombre1']);
            }
            if (isset($tramite['traslado']['formulario']['nombre2'])) {
                $tramite['traslado']['formulario']['nombre2'] = str_replace('"', '', $tramite['traslado']['formulario']['nombre2']);
            }
        });
        $csvExporter->build(
            $tramites,
            [
                'traslado.formulario.entidad_solicita',
                'traslado.afiliado.tipo_id.abreviatura',
                'traslado.afiliado.identificacion',
                'traslado.afiliado.apellido1',
                'traslado.afiliado.apellido2',
                'traslado.afiliado.nombre1',
                'traslado.afiliado.nombre2',
                'traslado.afiliado.fecha_nacimiento_slash',
                'traslado.afiliado.sexo.abreviatura',
                'traslado.formulario.eps.cod_habilitacion',
                'consecutivo_pad',
                'traslado.formulario.tipo_traslado',
                'traslado.afiliado.cabeza.tipo_id.abreviatura',
                'traslado.afiliado.cabeza.identificacion',
                'traslado.formulario.tipoIdentidad.abreviatura',
                'traslado.formulario.identificacion',
                'traslado.formulario.apellido1',
                'traslado.formulario.apellido2',
                'traslado.formulario.nombre1',
                'traslado.formulario.nombre2',
                'traslado.formulario.fecha_nacimiento_slash',
                'traslado.formulario.sexo.abreviatura',
                'traslado.formulario.clasecotizante.codigo',
                'traslado.tipo_afiliado.codigo_planos',
                'traslado.formulario.parentesco.codigo',
                'traslado.afiliado.condicion_discapacidad.abreviatura',
                'traslado.afiliado.municipio.departamento.codigo',
                'traslado.afiliado.municipio.codigo_planos',
                'traslado.afiliado.zona.codigo',
                'traslado.formulario.fecha_traslado_slash',
                'traslado.formulario.aportante.tercero.tipo_id.abreviatura',
                'traslado.formulario.aportante.tercero.identificacion',
                'traslado.formulario.ciiu.codigo'
            ],
            ['header' => false]
        );
        $this->exporter = $csvExporter;
    }

    private function generarRespuestaSolicitudTrasladosContributibo()
    {

        $tramites = $this->bduaarchivo->tramites;
        $tramites->load('solicitudTraslado.afiliado.tipo_id',
            'solicitudTraslado.eps',
            'solicitudTraslado.causa');
        $csvExporter = new Export();
        $csvExporter->build(
            $tramites,
            [
                'solicitudTraslado.bduaserial',
                'solicitudTraslado.eps.cod_habilitacion',
                'solicitudTraslado.afiliado.tipo_id.abreviatura',
                'solicitudTraslado.afiliado.identificacion',
                'consecutivo_pad',
                'solicitudTraslado.transform_respuesta',
                'solicitudTraslado.causa.codigo',
                'solicitudTraslado.fecha_factible_slash'
            ],
            ['header' => false]);
        $this->exporter = $csvExporter;
    }

    private function generarRespuestaSolicitudTrasladosSubsidiado()
    {

        $tramites = $this->bduaarchivo->tramites;
        $tramites->load('solicitudTraslado.afiliado.tipo_id',
            'solicitudTraslado.eps',
            'solicitudTraslado.causa');
        $csvExporter = new Export();
        $csvExporter->build(
            $tramites,
            [
                'solicitudTraslado.bduaserial',
                'solicitudTraslado.eps.cod_habilitacion',
                'solicitudTraslado.afiliado.tipo_id.abreviatura',
                'solicitudTraslado.afiliado.identificacion',
                'solicitudTraslado.transform_respuesta',
                'solicitudTraslado.causa.codigo',
                'solicitudTraslado.fecha_factible_slash'
            ],
            ['header' => false]);
        $this->exporter = $csvExporter;
    }


}

