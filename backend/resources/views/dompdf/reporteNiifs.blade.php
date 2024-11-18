<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PLAN DE CUENTAS</title>
    <link rel="stylesheet" href="./css/reporteNiifs.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <div class="header">
        <div class="actual_fecha">
            <p class="negrilla">
                Fecha Actual: {{Date::today()->format('l, j F Y')}}
                <br>
                Hora: {{ \Carbon\Carbon::now()->format('H:m:s') }}
            </p>
            <script type="text/php" class="pages">
              if (isset($pdf)) {
                $font = $fontMetrics->getFont("Helvetica", "lighter");
                $pdf->page_text(520, 28, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7.5, array(0, 0, 0));
              }
            </script>
            {{--<span class="pages">Página 1 de 1</span>--}}
        </div>
        <div class="logo">
            <img src="./images/capresoca.jpg" >
        </div>
        <div style="display: block; font-weight: bolder !important;
                            font-size: 1.2em">NIT. 891.856.000-7</div>
        <br>
        <br>
        <div style="padding-top: 0 !important;">
            <h3 style="padding: 0 !important; margin-top: 0 !important;">PLAN DE CUENTAS</h3>
        </div>
        <br>

        {{--<div style="display: block; font-weight: bolder !important;--}}
        {{--font-size: 1.2em">{{ $prCdp->presupuesto_inicial_gasto->entidadResolucion->tercero->nombre_completo }}</div>--}}
        {{--<div style="display: block; font-weight: bolder !important;--}}
        {{--font-size: 1.2em">NIT. 891.856.000-7</div>--}}
    </div>
    <div class="tabla-1">
        <table class="table-collapse">
            <thead>
            <tr>
                <th width="18%">CUENTA CONTABLE</th>
                <th width="50%">NOMBRE</th>
                <th>NIVEL</th>
                <th>CUENTA MAYOR</th>
                <th class="text_center">
                    MANEJA
                    <table class="table-collapse">
                        <thead>
                        <tr>
                            <th>T</th>
                            <th>C</th>
                            <th>R</th>
                        </tr>
                        </thead>
                    </table>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($niifs as $niif)
            <tr>
                <td>{{$niif->codigo}}</td>
                <td>{{$niif->nombre}}</td>
                <td>{{$niif->nivel->nombre}}</td>
                <td>{{$niif->padre ? $niif->padre->codigo : ''}}</td>
                <td class="text_center">
                    <table class="table-collapse">
                        <tbody>
                        <tr>
                            <td>{{ $niif->maneja_tercero === 1 ? 'S' : 'N' }}</td>
                            <td>{{ $niif->maneja_ccosto === 1 ? 'S' : 'N' }}</td>
                            <td>{{ $niif->tipo === 'Retencion' ? 'S' : 'N' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <footer class="footer_2">
        <table class="table-collapse">
            <tbody>
            <tr><td class="negrilla">T= Terceros</td></tr>
            <tr><td class="negrilla">C= Centros de Costo</td></tr>
            <tr><td class="negrilla">R= Retencion</td></tr>
            <tr><td class="basica-text">Licenciado a: [EPS CAPRESOCA] Nit:[824,000,462]</td></tr>
            </tbody>
        </table>
    </footer>
</body>
</html>