<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE RIPS</title>
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
            <th>NÚMERO RADICADO</th>
            <th>NOMBRE ARCHIVO</th>
            <th>NÚMERO FILA</th>
            <th>CONSECUTIVO CARGUE</th>
            <th>TIPO IDENTIFICACIÓN</th>
            <th>NÚMERO IDENTIFICACIÓN</th>
            <th>CÓDIGO PRESTADOR</th>
            <th>TIPO USUARIO</th>
            <th>PRIMER APELLIDO</th>
            <th>SEGUNDO APELLIDO</th>
            <th>PRIMER NOMBRE</th>
            <th>SEGUNDO NOMBRE</th>
            <th>EDAD</th>
            <th>UNIDAD DE MEDIDA EDAD</th>
            <th>SEXO</th>
            <th>CÓDIGO DEPARTAMENTO</th>
            <th>CÓDIGO MUNICIPIO</th>
            <th>ZONA</th>
            <th>NÚMERO CUENTA</th>
            <th>NÚMERO CONTRATO</th>
            <th>PERIODO FACTURADO</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rips as $rip)
            <tr>
                <td>{{ $rip->numero_radicado }}</td>
                <td>{{ $rip->nombre_archivo }}</td>
                <td>{{ $rip->numero_fila }}</td>
                <td>{{ $rip->consecutivo_cargue }}</td>
                <td>{{ $rip->tipo_identificacion }}</td>
                <td>{{ $rip->numero_identificacion }}</td>
                <td>{{ $rip->codigo_prestador }}</td>
                <td>{{ $rip->tipo_usuario }}</td>
                <td>{{ $rip->primer_apellido }}</td>
                <td>{{ $rip->segundo_apellido }}</td>
                <td>{{ $rip->primer_nombre }}</td>
                <td>{{ $rip->segundo_nombre }}</td>
                <td>{{ $rip->edad }}</td>
                <td>{{ $rip->unidad_medida_edad }}</td>
                <td>{{ $rip->sexo }}</td>
                <td>{{ $rip->codigo_departamento }}</td>
                <td>{{ $rip->codigo_municipio }}</td>
                <td>{{ $rip->zona }}</td>
                <td>{{ $rip->numero_cuenta }}</td>
                <td>{{ $rip->numero_contrato }}</td>
                <td>{{ $rip->periodo_facturado }}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
