<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Plan de Cuentas</title>
</head>
<body>
{{--<div>--}}
    {{--<h2>PLAN DE CUENTAS</h2>--}}
{{--</div>--}}
<div>
    <table>
        <thead>
        <tr>
            <th>CUENTA CONTABLE</th>
            <th>NOMBRE</th>
            <th>NIVEL</th>
            <th>CUENTA MAYOR</th>
            <th>TERCERO</th>
            <th>CENTRO DE COSTO</th>
            <th>RETENCIÓN</th>
        </tr>
        </thead>
        <tbody>
        @foreach($niifs as $niif)
            <tr>
                <td>{{$niif->codigo}}</td>
                <td>{{$niif->nombre}}</td>
                <td>{{$niif->nivel->nombre}}</td>
                <td>{{$niif->padre ? $niif->padre->codigo : ''}}</td>
                <td>{{$niif->maneja_tercero === 1 ? 'S' : 'N'}}</td>
                <td>{{$niif->maneja_ccosto === 1 ? 'S' : 'N' }}</td>
                <td>{{$niif->tipo === 'Retencion' ? 'S' : 'N' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>