<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 3/02/2020
 * Time: 11:43 AM
 */

namespace App\Capresoca\Aseguramiento\ArchivosBDUA;

use Illuminate\Support\Facades\DB;
use Laracsv\Export;
use App\Libraries\Util;

class BDUAExporter
{
    protected $exporter;
    protected $filename;
    protected $periodo;

    use BDUAQuerysTrait;

    public function __construct($tipo,$periodo = NULL)
    {
        $this->periodo      = $periodo;
        $this->$tipo();
    }

    private function MS()
    {
        $result = DB::select( $this->getMSQuery($this->periodo) );
        $csvExporter = new Export();

        $afiliados = collect($result);

        $csvExporter->build($afiliados, [
            'CODIGOEPS',
            'Tipo_documento',
            'identificacion',
            'apellido1',
            'apellido2',
            'nombre1',
            'nombre2',
            'fechaNacimiento',
            'genero',
            'codigoDepartamento',
            'codigoMunicipio',
            'zona',
            'fechaRadicacion',
            'GrupoPoblacional',
            'nivel_sisben',
            'ipsPrimaria',
            'cond_discapacidad',
            'tipIdCabFam',
            'idenCabFam',
            'parantesco',
            'cond_discapacidad'
        ], ['header' => false]);
        $this->exporter = $csvExporter;
        $this->filename = 'MSEPS025'.today()->format('dmY').'.txt';
    }

    private function NS()
    {
        DB::statement("set @row_number = 0");
        $result             = DB::select( $this->getNSQuery($this->periodo));
        //$queryLlaves        = DB::select($this->getNSQueryParaUpdatePeriodo());
        /*$arrayLlaves        = array();
        foreach ($queryLlaves as $q) {
            $arrayLlaves[]      = $q->id;
        }
        
        $partesArray        = array_chunk($arrayLlaves, 300);*/
        //'cD',
        //'cM',
        $csvExporter = new Export();
        $novedades = collect($result);
        $csvExporter->build($novedades, [
            'consecutivo',
            'CODIGOEPS',
            'Tipo_documento',
            'identificacion',
            'apellido1',
            'apellido2',
            'nombre1',
            'nombre2',
            'fechaNacimiento',
            'departamento',
            'municipio',
            'codigoNovedad',
            'fechaInicio',
            'nuevoDato1',
            'nuevoDato2',
            'nuevoDato3',
            'nuevoDato4',
            'nuevoDato5',
            'nuevoDato6',
            'nuevoDato7'
        ], ['header' => false]);
        $this->exporter = $csvExporter;
        $this->filename = 'NSEPS025'.today()->format('dmY').'.txt';
        
    }

    private function MC()
    {
        $result = DB::select( $this->getMCQuery($this->periodo) );
        $csvExporter = new Export();
        $afiliados = collect($result);
        $csvExporter->build($afiliados, [
            'CODIGOEPS',
            'Tipo_documento_cotizante',
            'identCotizante',
            'tipIndenAfiliado',
            'identAfiliado',
            'apellido1',
            'apellido2',
            'nombre1',
            'nombre2',
            'fecha_nacimiento',
            'genero',
            'tipo_Afiloiado',
            'tipo_afiliado',
            'parentesco',
            'cond_discapacidad',
            'codigoDepartamento',
            'codigoMunicipio',
            'zona',
            'fechaRadicacion',
            'tipoIdenAportante',
            'identAportante',
            'actividad',
            'ipsPrimaria'
        ], ['header' => false]);
        $this->exporter = $csvExporter;
        $this->filename = 'MCEPSC25'.today()->format('dmY').'.txt';
    }

    private function NC()
    {
        DB::statement("set @row_number = 0");
        $result = DB::select( $this->getNCQuery($this->periodo) );
        $csvExporter = new Export();
        $novedades = collect($result);
        $csvExporter->build($novedades, [
            'consecutivo',
            'CODIGOEPS',
            'Tipo_documento',
            'identificacion',
            'apellido1',
            'apellido2',
            'nombre1',
            'nombre2',
            'fechaNacimiento',
            'cD',
            'cM',
            'codigoNovedad',
            'fechaInicio',
            'nuevoDato1',
            'nuevoDato2',
            'nuevoDato3',
            'nuevoDato4',
            'nuevoDato5',
            'nuevoDato6'
        ], ['header' => false]);
        $this->exporter = $csvExporter;
        $this->filename = 'NCEPSC25'.today()->format('dmY').'.txt';
    }

    private function S1()
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $result = DB::select($this->getS1Query($this->periodo));
        $traslados = collect($result);
        $csvExporter = new Export(NULL,'CAMBIO');
        if(Util::CAMPOS_ADICIONALES_S1) {
            $csvExporter->build($traslados,[
                'CODIGOEPS',
                'Tipo_documento',
                'identificacion',
                'PrimerApellido',
                'SegundoApellido',
                'PrimerNombre',
                'SegundoNombre',
                'fechaNacimiento',
                'genero',
                'tipoIdent',
                'identificacion1',
                'apellido1',
                'apellido2',
                'nombre1',
                'nombre2',
                'fechaNacimiento1',
                'genero1',
                'cD',
                'cM',
                'zona',
                'Fecha_Afiliacion',
                'tipoPoblacion',
                'nivel_sisben',
                'tipo_traslado',
                'tipIdCabFam',
                'idenCabFam',
                'parantezco'
            ], ['header' => false]);
        } else {
            $csvExporter->build($traslados,[
                'CODIGOEPS',
                'Tipo_documento',
                'identificacion',
                'PrimerApellido',
                'SegundoApellido',
                'PrimerNombre',
                'SegundoNombre',
                'fechaNacimiento',
                'genero',
                'tipoIdent',
                'identificacion1',
                'apellido1',
                'apellido2',
                'nombre1',
                'nombre2',
                'fechaNacimiento1',
                'genero1',
                'cD',
                'cM',
                'zona',
                'Fecha_Afiliacion',
                'tipoPoblacion',
                'nivel_sisben',
                'tipo_traslado'
            ], ['header' => false]);
        }
        $this->exporter = $csvExporter;
        $this->filename = 'S1EPS025'.today()->format('dmY').'.txt';
    }

    private function R1()
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $result = DB::select($this->getR1Query($this->periodo));
        $traslados = collect($result);
        $csvExporter = new Export();
        $csvExporter->build($traslados,[
            'CODIGOEPS1',
            'Tipo_documento',
            'identificacion',
            'PrimerApellido',
            'SegundoApellido',
            'PrimerNombre',
            'SegundoNombre',
            'fecha_nacimiento',
            'genero',
            'CODIGOEPS2',
            'consecutivo',
            'tipo_traslado',
            'tipIdeCabFam',
            'IdenCabFam',
            'tipoIdent1',
            'identificacion1',
            'PrimerApellido1',
            'SegundoApellido1',
            'PrimerNombre1',
            'SegundoNombre1',
            'fechaNacimiento1',
            'genero1',
            'tipo_Cotizante',
            'tipo_afiliado',
            'parentezco',
            'cond_discapacidad',
            'cD',
            'cM',
            'zona',
            'Fecha_Afiliacion',
            'tipoIdenAportante',
            'idenAportante',
            'actividad'
        ], ['header' => false]);

        $this->exporter = $csvExporter;
        $this->filename = 'R1EPSC25'.today()->format('dmY').'.txt';

    }

    private function R4()
    {
        $result = DB::select($this->getR4Query($this->periodo));
        $solicitudes = collect($result);
        $csvExporter = new Export();
        $csvExporter->build($solicitudes,[
            'bduaserial',
            'cod_eps',
            'tipo_iden',
            'identificacion',
            'aprobado',
            'cautraslado',
            'fecha_factible'
        ], ['header' => false]);

        $this->exporter = $csvExporter;
        $this->filename = 'R4EPS025'.today()->format('dmY').'.txt';
    }

    private function S4()
    {
        $result = DB::select($this->getS4Query($this->periodo));
        $solicitudes = collect($result);
        $csvExporter = new Export();
        $csvExporter->build($solicitudes,[
            'consecutivo',
            'bduaserial',
            'cod_eps',
            'tipo_iden',
            'identificacion',
            'aprobado',
            'cautraslado',
            'fecha_factible'
        ], ['header' => false]);

        $this->exporter = $csvExporter;
        $this->filename = 'S4EPS025'.today()->format('dmY').'.txt';
    }

    public function download($nombre)
    {
        return $this->exporter->download($nombre);
    }

    public function getFileName()
    {
        return $this->filename;
    }
}