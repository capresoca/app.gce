<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informe Glosas </title>
</head>
<style>
    body{font-family: 'Helvetica', sans-serif;}
    thead{background: rgb(71,164,245);}
    th{
        height: 50px;
        font-weight: bold;
    }
</style>
<body>
<div>
    <table>
        <thead style="background: #47a4f5 ;">
        <tr>
            <th>CONSECUTIVO</th>
            <th>No. RADICADO RIP</th>
            <th>No. RADICADO CUENTA</th>
            <th>No. FACTURA</th>
            <th>IDENTIFICACIÓN PACIENTE</th>
            <th>NOMBRE COMPLETO PACIENTE</th>
            <th>CÓDIGO DE SERVICIO</th>
            <th width="20mm">NOMBRE DE SERVICIO</th>
            <th>CANT.</th>
            <th>VALOR UNITARIO</th>
            <th>VALOR TOTAL</th>
            <th>VALOR GLOSA</th>
            <th width="20mm">COD. GLOSA</th>
            <th width="200mm">OBSERVACIÓN</th>
{{--            <th width="200mm">USUARIO GLOSA</th>--}}
        </tr>
        </thead>
        <tbody>
        @foreach($cuentas as $key => $cuenta)
            <tr>
                <td>{{++$key}}</td>
                <td>{{$cuenta['radicado_rip']}}</td>
                <td>{{$cuenta['numero_radicado']}}</td>
                <td>{{$cuenta['numero_factura']}}</td>
                <td>{{$cuenta['identificacion']}}</td>
                <td>{{$cuenta['afiliado']}}</td>
                <td>{{$cuenta['codigo_servico']}}</td>
                <td>{{$cuenta['nombre_servicio']}}</td>
                <td>{{$cuenta['cantidad']}}</td>
                <td>{{'$ '.($cuenta['valor_unitario'] !== null ? number_format($cuenta['valor_unitario'], 2, ',', '.') : '0.0')}}</td>
                <td>{{'$ '.($cuenta['valor_total'] !== null ? number_format($cuenta['valor_total'], 2, ',', '.') : '0.0')}}</td>
                <td>{{'$ '.($cuenta['valor_glosa'] !== null ? number_format($cuenta['valor_glosa'], 2, ',', '.') : '0.0')}}</td>
                <td width="20mm">{{$cuenta['detalle_glosa']}}</td>
                <td width="500mm"> {{ $cuenta['observacion'] }}</td>
{{--                <td width="500mm"> {{ $cuenta['usuario'] }}</td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>