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
            <th>NRO. FACTURA</th>
            <th>CÓDIGO PRESTADOR</th>
            <th>TIPO IDENTIFICACIÓN</th>
            <th>NÚMERO IDENTIFICACIÓN</th>
            <th>NÚMERO AUTORIZACIÓN</th>
            <th>CÓDIGO MEDICAMENTO</th>
            <th>TIPO MEDICAMENTO</th>
            <th>NOMBRE GENÉRICO</th>
            <th>FORMA FARMACEUTICA</th>
            <th>CONCENTRACIÓN MEDICAMENTO</th>
            <th>UNIDAD MEDIDA MEDICAMENTO</th>
            <th>NÚMERO UNIDADES</th>
            <th>VALOR UNITARIO</th>
            <th>VALOR TOTAL MEDICAMENTO</th>
            <th>NÚMERO CUENTA</th>
            <th>NRO. CONTRATO</th>
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
                <td>{{ $rip->numero_autorizacion }}</td>
                <td>{{ $rip->codigo_medicamento }}</td>
                <td>{{ $rip->tipo_medicamento }}</td>
                <td>{{ $rip->nombre_generico }}</td>
                <td>{{ $rip->forma_farmaceutica }}</td>
                <td>{{ $rip->concentracion_medicamento }}</td>
                <td>{{ $rip->unidad_medida_medicamento }}</td>
                <td>{{ $rip->numero_unidades }}</td>
                <td>{{ $rip->valor_unitario }}</td>
                <td>{{ $rip->valor_total_medicamento }}</td>
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
