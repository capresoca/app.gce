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
            <th>CÓDIGO PRESTADOR</th>
            <th>RAZÓN SOCIAL</th>
            <th>TIPO IDENTIFICACIÓN</th>
            <th>NÚMERO IDENTIFICACIÓN</th>
            <th width="20mm">NRO. FACTURA</th>
            <th>FECHA EXPEDICIÓN FACTURA</th>
            <th>FECHA INICIO</th>
            <th>FECHA FINAL</th>
            <th>CÓDIGO ENTIDAD</th>
            <th>NOMBRE ENTIDAD</th>
            <th width="20mm">NRO. CONTRATO</th>
            <th>PLAN BENEFICIOS</th>
            <th>NÚMERO PÓLIZA</th>
            <th>VALOR COPAGO</th>
            <th>VALOR COMISIÓN</th>
            <th>VALOR DESCUENTOS</th>
            <th>VALOR NETO A PAGAR</th>
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
                <td>{{ $rip->codigo_prestador }}</td>
                <td>{{ $rip->razon_social }}</td>
                <td>{{ $rip->tipo_identificacion }}</td>
                <td>{{ $rip->numero_identificacion }}</td>
                <td>{{ $rip->numero_factura }}</td>
                <td>{{ $rip->fecha_expedicion_factura }}</td>
                <td>{{ $rip->fecha_inicio }}</td>
                <td>{{ $rip->fecha_final }}</td>
                <td>{{ $rip->codigo_entidad }}</td>
                <td>{{ $rip->nombre_entidad }}</td>
                <td>{{ $rip->numero_contrato }}</td>
                <td>{{ $rip->plan_beneficios }}</td>
                <td>{{ $rip->numero_poliza }}</td>
                <td>{{ $rip->valor_copago }}</td>
                <td>{{ $rip->valor_comision }}</td>
                <td>{{ $rip->valor_descuentos }}</td>
                <td>{{ $rip->valor_neto_pagar }}</td>
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
