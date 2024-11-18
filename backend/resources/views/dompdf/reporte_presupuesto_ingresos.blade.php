<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PRESUPUESTO DE INGRESOS</title>
    <link rel="stylesheet" href="./css/reporteStringresosPdf.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<header>
    <div class="logo">
        <img src="./images/capresoca.jpg" >
    </div>
    <div style="display: block; font-weight: bolder !important;
                        font-size: 1.2em">NIT. 891.856.000-7</div>
</header>
<footer class="footer_2">
    <table class="table-collapse">
        <tbody>
        <tr>
            <td class="footer_image">
                <img src="./images/logo_super.png" alt="logo_super">
            </td>
            <td style="text-align: left !important;">
                <div style="padding-right: 0 !important;">
                    <span style="display: block">Calle 7 No. 19 - 34. Teléfonos: (8) 635 8162 - 635 8163</span>
                    <span style="display: block">635 8232 Ext. 135 Email. capresocaeps@capresoca.com.co</span>
                    <span>Yopal Casanare</span>
                </div>
            </td>
            {{--<td class="text_center" width="12%">--}}
            {{--{{ '      ' }}--}}
            {{--<script type="text/php" class="pages">--}}
            {{--if (isset($pdf)) {--}}
            {{--$font = $fontMetrics->getFont("Helvetica", "lighter");--}}
            {{--$pdf->page_text(528, 685, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));--}}
            {{--}--}}
            {{--</script>--}}
            {{--</td>--}}
        </tr>
        </tbody>
    </table>
</footer>
<main class="main">
    <div class="body_1">
        <span class="text_1">
            Que en consonancia con lo anterior, el artículo decimo del{{$stringreso->entidadResolucion
            ? ' ' . $stringreso->entidadResolucion->resolucion . ' ': ' No se encuentra registrado la entidad/resolución '}}
             dispuso que: <i style="font-weight: lighter !important;">"El Gerente de {{ $stringreso->entidadResolucion
            ? ' ' . $stringreso->entidadResolucion->nombre . ' ' : 'No se encuentra registrado la entidad/resolución '}} mediante Resolución, liquidará, clasificará, distribuirá y
            definirá los gastos de conformidad con las necesidades particulares de la entidad y las normas legales
                que reglamente el uso de los recursos"</i>.
            <br>
            <br>
            Por lo expuesto anteriormente,
        </span>
    </div>
    <div class="sub-title">
        <h3 style="padding: 0 !important;">RESUELVE</h3>
    </div>
    <div class="sub-title" style="margin-bottom: 30px">
        <h3 style="padding: 0">
            PRIMERA PARTE
            <br>
            DEL PRESUPUESTO DE INGRESOS</h3>
    </div>
    <div class="body_2" >
         <span class="text_1">
             <b><u>ARTÍCULO PRIMERO: </u></b> Fíjese el Presupuesto de Ingresos de{{$stringreso->entidadResolucion
             ? ' ' . $stringreso->entidadResolucion->nombre . '., ': ' No se encuentra registrado nombre de entidad/resolución, '}}para
             la vigencia final fiscal de{{' '. $stringreso->periodo .', '}}en la suma de
             {{ $stringreso->valor_letra_presupuesto .
             ' (' . (($stringreso->valor_presupuesto !== null || $stringreso->valor_presupuesto !== 0)
                       ? '$'. number_format($stringreso->valor_presupuesto, 2, ',', '.')
                       : "$ 0,00") . '), Mcte,'}} según el siguiente detalle:
        </span>
    </div>
    <div class="tabla-1">
        <table class="table-collapse">
            <thead>
            <tr>
                <th class="text_center celda" colspan="3">
                    {{ 'PRESUPUESTO DE INGRESOS AÑO ' . $stringreso->periodo }}
                </th>
            </tr>
            <tr>
                <th class="celda text_center">CÓDIGO</th>
                <th class="celda text_center">CONCEPTO</th>
                <th class="celda text_center">VALOR</th>
            </tr>
            </thead>
            <tbody>
            @foreach($detalles as $detalle)
            <tr>
                    <td class="celda text_right" width="20%">{!!$detalle->auxiliar === 1 ? $detalle->codigo : '<b>' . $detalle->codigo . '</b>'!!}</td>
                    <td class="celda text_left" style="padding-right: 2mm !important;" width="55%"> {!!$detalle->auxiliar === 1 ? $detalle->nombre : '<b>' . $detalle->nombre . '</b>'!!}</td>
                    {{--<td class="celda text_left"></td>--}}
                    <td class="celda text_right">{!!$detalle->auxiliar === 1 ? (($detalle->total !== null || $detalle->total !== 0)
                       ? '$'. number_format($detalle->total, 2, ',', '.')
                       : "$ 0,00") : '<b>' . (($detalle->total !== null || $detalle->total !== 0)
                       ? '$'. number_format($detalle->total, 2, ',', '.')
                       : "$ 0,00") . '</b>'!!}</td>
            </tr>
            @endforeach
        </table>
        {{--<div class="saltopagina"></div>--}}
    </div>
</main>
</body>
</html>