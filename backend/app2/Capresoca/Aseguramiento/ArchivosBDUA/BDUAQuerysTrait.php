<?php
/**
 * Created by PhpStorm.
 * User: Wilfredo
 * Date: 3/02/2020
 * Time: 3:10 PM
 */

namespace App\Capresoca\Aseguramiento\ArchivosBDUA;


trait BDUAQuerysTrait
{

    private function getNSQuery(): string
    {
        return "SELECT
                  @row_number := @row_number + 1 as consecutivo,     
                 if(a.as_regimene_id='1','EPSC25','EPS025') AS CODIGOEPS,
                 c.abreviatura AS Tipo_documento,
                 b1.identificacion,
                 b1.apellido1,
                 b1.apellido2,
                 b1.nombre1,
                 b1.nombre2,
                 DATE_FORMAT(a.fecha_nacimiento, '%d/%m/%Y') as fechaNacimiento,
                 e.abreviatura as genero,
                 SUBSTRING(d.codigo,1,2) AS cD,
                 SUBSTRING(d.codigo,3,3) AS cM,
                 b2.codigo AS codigoNovedad,
                 DATE_FORMAT(b1.fecha_ini_novedad, '%d/%m/%Y') as fechaInicio,
                 b1.v1 AS nuevoDato1,
                 b1.v2 AS nuevoDato2,
                 b1.v3 AS nuevoDato3,
                 b1.v4 AS nuevoDato4,
                 b1.v5 AS nuevoDato5,
                 b1.v6 AS nuevoDato6
            FROM as_afiliados AS a
            LEFT JOIN as_estadoafiliados AS b ON b.id = a.estado
            LEFT JOIN as_novtramites AS b1 ON b1.as_afiliado_id = a.id
            LEFT JOIN as_tipnovedades AS b2 ON b2.id = b1.as_tipnovedade_id
            LEFT JOIN as_tramites AS b3 ON b3.id = b1.as_tramite_id
            LEFT JOIN gn_tipdocidentidades AS c ON c.id = a.gn_tipdocidentidad_id
            LEFT JOIN gn_municipios AS d ON d.id = a.gn_municipio_id
            LEFT JOIN gn_sexos AS e ON e.id = a.gn_sexo_id
            LEFT JOIN as_pobespeciales AS f ON f.id = a.as_pobespeciale_id
            LEFT JOIN gn_zonas AS g ON g.codigo
            LEFT JOIN rs_entidades AS h ON h.id = a.rs_entidade_id
            WHERE a.as_regimene_id = 2 AND b1.id IS NOT NULL AND b3.estado IN ('Registrado','Radicado') AND b1.v1!='';";
    }

    private function getMSQuery(): string
    {
        return "SELECT      
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
            WHERE a1.estado IN ('Registrado','Radicado') AND a.as_regimene_id = 2 AND a.gn_tipdocidentidad_id IN (3,4)
            GROUP BY a.id;";
    }

    private function getMCQuery(): string{
        return "SELECT     
           if(a.as_regimene_id='1','EPSC25','EPS025') AS CODIGOEPS,
           c.abreviatura AS Tipo_documento_cotizante,
           a2.identificacion as identCotizante,
           c1.abreviatura as tipIndenAfiliado,
           a.identificacion as identAfiliado,
           a2.nombre1,
           a2.nombre2,
           a2.apellido1,
           a2.apellido2,
           DATE_FORMAT(a2.fecha_nacimiento, '%d/%m/%Y') as fecha_nacimiento,
           e.abreviatura AS genero,
           j.codigo AS tipo_Afiloiado,
           k.codigo AS tipo_afiliado,
           if(k.codigo='C','',m.codigo) AS parentezco,
           ifnull(n.abreviatura,'') AS cond_discapacidad,
           SUBSTRING(d.codigo,1,2) AS cD,
           SUBSTRING(d.codigo,3,3) AS cM,
           g.codigo AS zona,
           DATE_FORMAT(a1.fecha_radicacion, '%d/%m/%Y') as Fecha_Afiliacion,
           substring(p.abreviatura,1,2) AS tipoIdenAportante,
           o.identificacion as identAportante,
           q.codigo AS actividad,
           h.cod_habilitacion
            FROM as_formtrasmovs                        AS a1 
            LEFT JOIN as_afiliados                      AS a ON a.cabfamilia_id = a1.as_afiliado_id
            LEFT JOIN as_afiliados                      AS a2 ON a2.id = a.cabfamilia_id
            LEFT JOIN as_estadoafiliados                AS b ON b.id = a.estado
            LEFT JOIN as_afitramites                    AS b1 ON b1.as_afiliado_id = a2.id
            LEFT JOIN gn_tipdocidentidades          AS c ON c.id = a2.gn_tipdocidentidad_id
            LEFT JOIN gn_tipdocidentidades          AS c1 ON c1.id = a2.gn_tipdocidentidad_id
            LEFT JOIN gn_municipios                     AS d ON d.id = a2.gn_municipio_id
            LEFT JOIN gn_sexos                          AS e ON e.id = a2.gn_sexo_id
            LEFT JOIN as_pobespeciales              AS f ON f.id = a.as_pobespeciale_id
            LEFT JOIN gn_zonas                          AS g ON g.id = a.gn_zona_id
            LEFT JOIN rs_entidades                      AS h ON h.id = a2.rs_entidade_id
            LEFT JOIN as_pagadores                      AS i ON i.id = a1.as_pagadore_id
            LEFT JOIN as_clasecotizantes                AS j ON j.id = a1.as_clasecotizante_id
            LEFT JOIN as_tipafiliados                   AS k ON k.id = a.as_tipafiliado_id
            LEFT JOIN as_parentescos                    AS m ON m.id = a.as_parentesco_id
            LEFT JOIN as_condicion_discapacidades   AS n ON n.id = a.as_condicion_discapacidades_id
            LEFT JOIN gn_terceros                       AS o ON o.identificacion = i.identificacion
            LEFT JOIN gn_tipdocidentidades          AS p ON p.id = o.gn_tipdocidentidad_id
            LEFT JOIN nf_ciius                          AS q ON q.id = a1.nf_ciiu_id
            WHERE a1.estado IN ('Registrado','Radicado') AND a1.tipo='Movilidad'  AND  a.as_regimene_id = 1 AND a.cabfamilia_id IS NOT NULL
            GROUP BY a.id;";
    }

    private function getNCQuery(): string
    {
        return "SELECT      
        @row_number := @row_number + 1 as consecutivo,
        if(a.as_regimene_id='1','EPSC25','EPS025') AS CODIGOEPS,
        c.abreviatura AS Tipo_documento,
        b1.identificacion,
        b1.apellido1,
        b1.apellido2,
        b1.nombre1,
        b1.nombre2,
        DATE_FORMAT(a.fecha_nacimiento, '%d/%m/%Y') as fechaNacimiento,
        SUBSTRING(d.codigo,1,2) AS cD,
        SUBSTRING(d.codigo,3,3) AS cM,
        b2.codigo AS codigoNovedad,
        DATE_FORMAT(b1.fecha_ini_novedad, '%d/%m/%Y') as fechaInicio,
        b1.v1 AS nuevoDato1,
        b1.v2 AS nuevoDato2,
        b1.v3 AS nuevoDato3,
        b1.v4 AS nuevoDato4,
        b1.v5 AS nuevoDato5,
        b1.v6 AS nuevoDato6
        FROM as_afiliados AS a
        LEFT JOIN as_estadoafiliados AS b ON b.id = a.estado
        LEFT JOIN as_novtramites AS b1 ON b1.as_afiliado_id = a.id
        LEFT JOIN as_tipnovedades AS b2 ON b2.id = b1.as_tipnovedade_id
        LEFT JOIN as_tramites AS b3 ON b3.id = b1.as_tramite_id
        LEFT JOIN gn_tipdocidentidades AS c ON c.id = a.gn_tipdocidentidad_id
        LEFT JOIN gn_municipios AS d ON d.id = a.gn_municipio_id
        LEFT JOIN gn_sexos AS e ON e.id = a.gn_sexo_id
        LEFT JOIN as_pobespeciales AS f ON f.id = a.as_pobespeciale_id
        LEFT JOIN gn_zonas AS g ON g.codigo
        LEFT JOIN rs_entidades AS h ON h.id = a.rs_entidade_id
        WHERE a.as_regimene_id = 1 AND b1.id IS NOT NULL AND b3.estado IN ('Registrado','Radicado') AND b1.v1!='';";
    }

    private function getS1Query(): string
    {
        return "SELECT      
                 'EPS025' AS CODIGOEPS,
                 c.abreviatura AS Tipo_documento,
                 a.identificacion,
                 a.apellido1 AS PrimerApellido,
                 ifnull(a.apellido2,'') AS SegundoApellido,
                 a.nombre1 AS PrimerNombre,
                 ifnull(a.nombre2,'') AS SegundoNombre,
                 a.fecha_nacimiento,
                 e.abreviatura AS genero,
                 c1.abreviatura AS tipoIdent,
                 a1.identificacion AS identificacion1,
                 a1.apellido1,
                 ifnull(a1.apellido2,'') AS apellido2,
                 a1.nombre1,
                 ifnull(a1.nombre2,'') AS nombre2,
                 a1.fecha_nacimiento AS fechaNacimiento,
                 if(a1.gn_sexo_id='1','F','M') AS genero1,
                 SUBSTRING(d.codigo,1,2) AS cD,
                 SUBSTRING(d.codigo,3,3) AS cM,
                 g.codigo AS zona,
                 DATE_FORMAT(a1.fecha_traslado, '%d/%m/%Y') as Fecha_Afiliacion,
                 f.codigo AS tipoPoblacion,
                 a.nivel_sisben,
                 a1.tipo_traslado,
                 a.as_tipafiliado_id,
                 IF(a.as_tipafiliado_id IN (1,2),'',a2.tipo_identificacion) AS tipIdCabFam,
                 IF(a.as_tipafiliado_id IN (1,2),'',a2.identificacion) AS idenCabFam,
                 IF(a.as_tipafiliado_id IN (1,2),'',m.codigo) AS parantezco
                FROM as_formtrasmovs                        AS a1 
                LEFT JOIN as_afiliados                      AS a ON a.id = a1.as_afiliado_id
                LEFT JOIN as_afiliados                      AS a2 ON a2.id = a.cabfamilia_id
                LEFT JOIN as_estadoafiliados                AS b ON b.id = a.estado
                LEFT JOIN as_afitramites                    AS b1 ON b1.as_afiliado_id = a.id
                LEFT JOIN gn_tipdocidentidades          AS c ON c.id = a.gn_tipdocidentidad_id
                LEFT JOIN gn_tipdocidentidades          AS c1 ON c1.id = a1.gn_tipdocidentidad_id
                LEFT JOIN gn_municipios                     AS d ON d.id = a.gn_municipio_id
                LEFT JOIN gn_sexos                          AS e ON e.id = a.gn_sexo_id
                LEFT JOIN as_pobespeciales              AS f ON f.id = a.as_pobespeciale_id
                LEFT JOIN gn_zonas                          AS g ON g.id = a.gn_zona_id
                LEFT JOIN rs_entidades                      AS h ON h.id = a.rs_entidade_id
                LEFT JOIN as_pagadores                      AS i ON i.id = a1.as_pagadore_id
                LEFT JOIN as_clasecotizantes                AS j ON j.id = a1.as_clasecotizante_id
                LEFT JOIN as_tipafiliados                   AS k ON k.id = a.as_tipafiliado_id
                LEFT JOIN as_parentescos                    AS m ON m.id = a.as_parentesco_id
                LEFT JOIN as_condicion_discapacidades   AS n ON n.id = a.as_condicion_discapacidades_id
                LEFT JOIN as_epss                               AS o ON o.id = a1.as_eps_id                 
                WHERE a1.estado IN ('Registrado','Radicado') AND a1.tipo='Movilidad'  AND  a.as_regimene_id = 1 
                GROUP BY a.tipo_identificacion, a.identificacion";
    }

    private function getR1Query(): string
    {
        return "SELECT 	    
                 'EPSC25' AS CODIGOEPS1,
                 c.abreviatura AS Tipo_documento,
                 a.identificacion,
                 a.apellido1 AS PrimerApellido,
                 a.apellido2 AS SegundoApellido,
                 a.nombre1 AS PrimerNombre,
                 a.nombre2 AS SegundoNombre,
                 DATE_FORMAT(a1.fecha_nacimiento, '%d/%m/%Y') as fecha_nacimiento,
                 e.abreviatura AS genero,
                 o1.cod_habilitacion  AS CODIGOEPS2,
                 a1.id AS consecutivo,
                 a1.tipo_traslado,
                 if(a.as_tipafiliado_id=1,'',a2.tipo_identificacion) AS tipIdeCabFam,
                 if(a.as_tipafiliado_id=1,'',a2.identificacion) AS IdenCabFam,
                 c1.abreviatura AS tipoIdent1,
                 a1.identificacion AS identificacion1,		 
                 a1.apellido1 AS PrimerApellido1,
                 a1.apellido2 AS SegundoApellido1,
                 a1.nombre1 AS PrimerNombre1,
                 a1.nombre2 AS SegundoNombre1,
                 a1.fecha_nacimiento AS fechaNacimiento1,
                 if(a1.gn_sexo_id='1','F','M') AS genero1,
                 ifNULL(j.codigo,'') AS tipo_Cotizante,
                 k.codigo_planos AS tipo_afiliado,
                 IF(a.as_tipafiliado_id=1,'',m.codigo) AS parentezco,
                 ifnull(n.abreviatura,'') AS cond_discapacidad,
                 SUBSTRING(d.codigo,1,2) AS cD,
                 SUBSTRING(d.codigo,3,3) AS cM,
                 ifnull(g.codigo,'') AS zona,
                 DATE_FORMAT(a1.fecha_traslado, '%d/%m/%Y') as Fecha_Afiliacion,
                 IF(a.as_tipafiliado_id=1,IF(a1.as_clasecotizante_id=3,a2.tipo_identificacion,substring(p.abreviatura,1,2)),'') AS tipoIdenAportante,
                 IF(a.as_tipafiliado_id=1,IF(a1.as_clasecotizante_id=3,a2.identificacion,o.identificacion),'') AS idenAportante,
               if(NULL,if(a.as_tipafiliado_id=1,q.codigo,''),'') AS actividad
        FROM as_formtrasmovs 						AS a1
        LEFT JOIN as_afiliados 						AS a ON a.id = a1.as_afiliado_id
        LEFT JOIN as_afiliados                 AS a2 ON a2.id = a.cabfamilia_id
        LEFT JOIN as_estadoafiliados 				AS b ON b.id = a.estado
        LEFT JOIN as_afitramites 					AS b1 ON b1.as_afiliado_id = a.id
        LEFT JOIN gn_tipdocidentidades 			AS c ON c.id = a.gn_tipdocidentidad_id
        LEFT JOIN gn_tipdocidentidades 			AS c1 ON c1.id = a1.gn_tipdocidentidad_id
        LEFT JOIN gn_municipios 					AS d ON d.id = a.gn_municipio_id
        LEFT JOIN gn_sexos 							AS e ON e.id = a.gn_sexo_id
        LEFT JOIN as_pobespeciales 				AS f ON f.id = a.as_pobespeciale_id
        LEFT JOIN gn_zonas 							AS g ON g.id = a.gn_zona_id
        LEFT JOIN rs_entidades 						AS h ON h.id = a.rs_entidade_id
        LEFT JOIN as_pagadores 						AS i ON i.id = a1.as_pagadore_id
        LEFT JOIN as_clasecotizantes				AS j ON j.id = a1.as_clasecotizante_id
        LEFT JOIN as_tipafiliados 					AS k ON k.id = a.as_tipafiliado_id
        LEFT JOIN as_parentescos 					AS m ON m.id = a.as_parentesco_id
        LEFT JOIN as_condicion_discapacidades 	AS n ON n.id = a.as_condicion_discapacidades_id
        LEFT JOIN as_epss								AS o1 ON o1.id = a1.as_eps_id					
        LEFT JOIN gn_terceros                  AS o ON o.identificacion = i.identificacion
        LEFT JOIN gn_tipdocidentidades         AS p ON p.id = o.gn_tipdocidentidad_id
        LEFT JOIN nf_ciius                     AS q ON q.id = a1.nf_ciiu_id
        WHERE a1.estado IN ('Registrado','Radicado') AND a1.tipo='Movilidad' AND a.as_regimene_id = 2
        GROUP BY a.tipo_identificacion, a.identificacion";
    }

    private function getS4Query(){
        return "select 
                bduaserial,
                cod_eps,
                d.tip_docu_cabfamiliaS2 tipo_iden,
                d.identificacion_cabfamiliaS2 identificacion, 
                if(d.respuesta = 'Aprobado',1,0) as aprobado,
                e.codigo as cautraslado,
                d.fecha_factible
                from 
                (select max(a.id) max_id from as_s1vids a join as_soltraslados b on b.as_svid_id = a.id) as c 
                left join as_soltraslados d on c.max_id = d.as_svid_id 
                left join as_cautraslados e on e.id = d.as_cautraslado_id
                where d.as_regimen_id = 2";
    }

    private function getR4Query(){
        return "select * from (select max(a.id) max_id from as_s1vids a join as_soltraslados b on b.as_svid_id = a.id) as c 
                left join as_soltraslados d on c.max_id = d.as_svid_id where d.as_regimen_id = 1";
    }

}