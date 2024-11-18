<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Tutela</title>
    <link rel="stylesheet" href="./css/reporte_tutelaPdf.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<div class="main">
    <div class="header">
        <div class="pagess">
            <script type="text/php" class="pages">
              if (isset($pdf)) {
                $font = $fontMetrics->getFont("Helvetica", "lighter");
                $pdf->page_text(708, 578, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
              }
            </script>
            {{--755, 550--}}
        </div>
        <div class="imagenes_encabezado">
            <div class="imagen_1">
                <img src="./images/minysuper_salud.png" alt="logo_minysuper_salud">
            </div>
            <div class="imagen_2">
                <img src="./images/logo_col.png" alt="logo_col">
            </div>
        </div>
        <div class="titulo">
            <h2>RELACIÓN DE ACCIONES DE TUTELA E INCIDENTES DE DESACATO</h2>
        </div>
    </div>
    <div class="vigencia">
        <span>{{ 'VIGENCIA: ' . \Carbon\Carbon::now()->format('Y') }}</span>
    </div>
    <table class="list">
        <thead></thead>
        <tbody>
        <tr class="head">
            <td class="center">RADICADO</td>
            <td class="center">FECHA DE RADICACIÓN</td>
            <td class="center">FECHA DE NOTIFICACIÓN</td>
            <td class="center">JUZGADO</td>
            <td class="center">DEPARTAMENTO</td>
            <td class="center">PRETENSIÓN</td>
            <td class="center">CLASIFICAIÓN</td>
            <td class="center">TUTELA CON FALLO</td>
            <td class="center">FALLO INTEGRAL</td>
            <td class="center">INCIDENTE DE DESACATO NOTIFICADO</td>
        </tr>
        @foreach($reportes as $repo)
            <tr class="body">
                <td class="center">{{$repo->no_tutela ? $repo->no_tutela :  '##'}}</td>
                <td class="center">{{$repo->fecha}}</td>
                <td class="center">{{$repo->fecha_notificacion}}</td>
                <td class="">{{$repo->juzgado ? $repo->juzgado->nombre : null}}</td>
                <td class="center">{{$repo->departamento ? $repo->departamento->nombre : null}}</td>
                <td class="">{{$repo->pretencion ? $repo->pretencion->pretencion : null }}</td>
                <td class="">{{$repo->tipo_tutela }}</td>
                <td class="center"> {{ $repo->last_fallo ? $repo->last_fallo->tipo_fallo : 'No Registra' }}</td>
                <td class="center"> {{ $repo->last_fallo ? $repo->last_fallo->fallo_integral : 'No Registra' }}</td>
                <td class="center">{{$repo->incidente_desacato}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="footer">
    </div>
</div>
</body>
</html>
