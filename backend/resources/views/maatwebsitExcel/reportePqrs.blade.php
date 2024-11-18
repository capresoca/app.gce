<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte PQR</title>
</head>
<style>
    body{
        font-family: 'Helvetica', sans-serif;
    }
    thead{
        background: rgb(146,208,80) ;
    }
    th{
        height: 50px;
        font-weight: bold;
    }
</style>
<body>
<div>
    <table>
        <thead style="background: #92D050 ;">
        <tr>
            <th>CODIGO</th>
            <th>TIPO DE QUEJA
                (Riesgo de vida - EPS)</th>
            <th>AÑO</th>
            <th>MES</th>
            <th>FECHA CREACION</th>
            <th width="20mm">CANAL
                (Chat - Correo - Telefonico - Escrito - Personalizado - Web)</th>
            <th width="20mm">CARACTERIZACION
                (Peticion - Queja - Reclamo - Consulta - Solicitud)</th>
            <th width="15mm">TIPO DOCUMENTO AFECTADO</th>
            <th width="20mm">NUMERO DOCUMENTO</th>
            <th width="20mm">MUNICIPIO</th>
            <th width="200mm">AFECTADO</th>
            <th width="20mm">REGIMEN AFECTADO
                (Subsidiado . Contributivo)</th>
            <th>MACROMOTIVO / CLASIFICACION</th>
            <th>MOTIVOS GENERALES</th>
            <th>MOTIVOS ESPECIFICOS</th>
            <th>ENTIDAD</th>
            <th>OBSERVACION
                (Se describe lo que el afiliado dice)</th>
            <th>FECHA DE RESPUESTA INTERNA</th>
            <th>Tiempo de respuesta</th>
            <th>Estado</th>
            <th>Fuente</th>
            <th>Respuesta al afiliado (Describe lo que el funcionario responde)</th>
            <th>Contacto Usuario</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pqrsds as $pqr)
            <tr>
                <td>{{$pqr->id}}</td>
                <td>{{$pqr->tipo->riesgo_vida ? 'SI' : 'NO'}}</td>
                <td>{{$pqr->fecha_recepcion_array['year']}}</td>
                <td>{{$pqr->fecha_recepcion_array['month']}}</td>
                <td>{{substr($pqr->fecha_recepcion,0,10)}}</td>
                <td>{{$pqr->medio_recepcion}}</td>
                <td>{{$pqr->tipo ? $pqr->tipo->tipo : null}}</td>
                <td>{{$pqr->afiliado ? $pqr->afiliado->tipo_identificacion : null }}</td>
                <td>{{$pqr->afiliado->identificacion }}</td>
                <td>{{$pqr->municipio ? $pqr->municipio->nombre : null}}</td>
                <td width="200mm"> {{ $pqr->afiliado ? $pqr->afiliado->nombre_completo : null }}</td>
                <td> {{ $pqr->afiliado ? $pqr->afiliado->regimen->nombre : 'No Registra' }}</td>
                <td>{{!$pqr->motivo ? null : $pqr->motivo->motivo_general->macromotivo->descripcion}}</td>
                <td>{{!$pqr->motivo ? null :$pqr->motivo->motivo_general->descripcion}}</td>
                <td>{{!$pqr->motivo ? null :$pqr->motivo->descripcion}}</td>
                <td>{{!$pqr->entidad ? null : $pqr->entidad->nombre}}</td>
                <td>{{$pqr->relato}}</td>
                <td>{{!$pqr->fecha_respuesta?'NR':$pqr->fecha_respuesta}}</td>
                <td>{{$pqr->dias_respuesta >= 0 ? $pqr->dias_respuesta : 0}}</td>
                <td>{{$pqr->estado != 'Respondido' ? 'Abierta' : 'Cerradas'}}</td>
                <td>{{$pqr->fuente}}</td>
                <td>{{($pqr->respuesta_funcionario !== null) ? $pqr->respuesta_funcionario : 'Aún no se ha respondido'}}</td>
                <td>{{$pqr->afiliado ? ('Télefono: '. ($pqr->afiliado->telefono_fijo ?? '###').' - Celular: '. ($pqr->afiliado->celular ?? '###').' - Correo Electrónico: ' . ($pqr->afiliado->correo_electronico ?? '###'))  : ''}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>