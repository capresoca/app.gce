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
            <th>NÚMERO FACTURA</th>
            <th>CÓDIGO PRESTADOR</th>
            <th>TIPO IDENTIFICACIÓN</th>
            <th>NÚMERO IDENTIFICACIÓN</th>
            <th>VIA INGRESO</th>
            <th>FECHA INGRESO</th>
            <th>HORA INGRESO</th>
            <th>NÚMERO AUTORIZACIÓN</th>
            <th>CAUSA EXTERNA</th>
            <th>DIAGNÓSTICO PRINCIPAL INGRESO</th>
            <th>DIAGNÓSTICO PRINCIPAL EGRESO</th>
            <th>DIAGNÓSTICO RELACIONADO 1</th>
            <th>DIAGNÓSTICO RELACIONADO 2</th>
            <th>DIAGNÓSTICO RELACIONADO 3</th>
            <th>DIAGNÓSTICO COMPLICACIÓN</th>
            <th>ESTADO SALIDA</th>
            <th>DIAGNÓSTICO CAUSA MUERTE</th>
            <th>FECHA EGRESO</th>
            <th>HORA EGRESO</th>
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
                <td>{{ $rip->numero_factura }}</td>
                <td>{{ $rip->codigo_prestador }}</td>
                <td>{{ $rip->tipo_identificacion }}</td>
                <td>{{ $rip->numero_identificacion }}</td>
                <td>{{ $rip->via_ingreso }}</td>
                <td>{{ $rip->fecha_ingreso }}</td>
                <td>{{ $rip->hora_ingreso }}</td>
                <td>{{ $rip->numero_autorizacion }}</td>
                <td>{{ $rip->causa_externa }}</td>
                <td>{{ $rip->diagnostico_principal_ingreso }}</td>
                <td>{{ $rip->diagnostico_principal_egreso }}</td>
                <td>{{ $rip->diagnostico_relacionado_1 }}</td>
                <td>{{ $rip->diagnostico_relacionado_2 }}</td>
                <td>{{ $rip->diagnostico_relacionado_3 }}</td>
                <td>{{ $rip->diagnostico_complicacion }}</td>
                <td>{{ $rip->estado_salida }}</td>
                <td>{{ $rip->diagnostico_causa_muerte }}</td>
                <td>{{ $rip->fecha_egreso }}</td>
                <td>{{ $rip->hora_egreso }}</td>
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
