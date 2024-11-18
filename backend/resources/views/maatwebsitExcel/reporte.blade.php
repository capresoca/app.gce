<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Tutela</title>
</head>
<body>
<div>
    <h2>RELACIÓN DE ACCIONES DE TUTELA E INCIDENTES DE DESACATO</h2>
</div>
<div>
    <table>
        <thead>
        <tr>
            <th>RADICADO</th>
            <th>FECHA DE RADICACIÓN</th>
            <th>FECHA DE NOTIFICACIÓN</th>
            <th>JUZGADO</th>
            <th>DEPARTAMENTO</th>
            <th>PRETENSIÓN</th>
            <th>CLASIFICAIÓN</th>
            <th>TUTELA CON FALLO</th>
            <th>FALLO INTEGRAL</th>
            <th>INCIDENTE DE DESACATO NOTIFICADO</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reportes as $repo)
            <tr>
                <td>{{$repo->no_tutela}}</td>
                <td>{{$repo->fecha}}</td>
                <td>{{$repo->fecha_notificacion}}</td>
                <td>{{$repo->juzgado ? $repo->juzgado->nombre : null}}</td>
                <td>{{$repo->departamento ? $repo->departamento->nombre : null}}</td>
                <td>{{$repo->pretencion ? $repo->pretencion->pretencion : null }}</td>
                <td>{{$repo->tipo_tutela }}</td>
                <td> {{ $repo->last_fallo ? $repo->last_fallo->tipo_fallo : 'No Registra' }}</td>
                <td> {{ $repo->last_fallo ? $repo->last_fallo->fallo_integral : 'No Registra' }}</td>
                <td>{{$repo->incidente_desacato}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
