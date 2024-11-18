<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PRESUPUESTO DE GASTOS</title>
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
    {{--<div class="body_1">--}}
        {{--<span class="text_1">--}}
            {{--Que en consonancia con lo anterior, el artículo decimo del{{$stringreso->entidadResolucion--}}
            {{--? ' ' . $stringreso->entidadResolucion->resolucion . ' ': ' No se encuentra registrado la entidad/resolución '}}--}}
             {{--dispuso que: <i style="font-weight: lighter !important;">"El Gerente de {{ $stringreso->entidadResolucion--}}
            {{--? ' ' . $stringreso->entidadResolucion->nombre . ' ' : 'No se encuentra registrado la entidad/resolución '}} mediante Resolución, liquidará, clasificará, distribuirá y--}}
            {{--definirá los gastos de conformidad con las necesidades particulares de la entidad y las normas legales--}}
                {{--que reglamente el uso de los recursos"</i>.--}}
            {{--<br>--}}
            {{--<br>--}}
            {{--Por lo expuesto anteriormente,--}}
        {{--</span>--}}
    {{--</div>--}}
    {{--<div class="sub-title">--}}
        {{--<h3 style="padding: 0 !important;">RESUELVE</h3>--}}
    {{--</div>--}}
    <div class="sub-title" style="margin-bottom: 30px">
        <h3 style="padding: 0">
            SEGUNDA PARTE
            <br>
            DEL PRESUPUESTO DE GASTOS</h3>
    </div>
    <div class="body_2" >
         <span class="text_1">
             <b><u>ARTÍCULO SEGUNDO: </u></b> Fíjese el Presupuesto de Gastos de {{$strgasto->entidadResolucion
             ? ' ' . $strgasto->entidadResolucion->nombre . ' ': ' No se encuentra registrado nombre de entidad/resolución '}}, para
             la vigencia final fiscal del{{' '. $strgasto->periodo .', '}}en la suma de
             {{ $strgasto->valor_letra_presupuesto .
             ' (' . (($strgasto->valor_presupuesto !== null || $strgasto->valor_presupuesto !== 0)
                       ? '$'. number_format($strgasto->valor_presupuesto, 2, ',', '.')
                       : "$ 0,00") . '), Mcte,'}} los cuales se distributen de la siguiente forma:
        </span>
    </div>
    <div class="tabla-1">
        <table class="table-collapse">
            <thead>
            <tr>
                <th class="text_center" colspan="3" style="padding: 10px"></th>
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
    <div class="sub-title" style="margin-bottom: 30px">
        <br>
        <h3 style="padding: 0">
            TERCERA PARTE
            <br>
            DISPOSICIONES GENERALES</h3>
    </div>
    <div class="body_2" >
         <span class="text_1">
             <b><u>ARTÍCULO TERCERO:</u> COMPLEMENTARIDAD Y APLICACIÓN.</b>
        </span>
        <br>
        <span  style="text-align: justify !important;" class="text_1">
             <b>COMPLEMENTARIDAD:</b> La ejecución del presupuesto de {{$strgasto->entidadResolucion
             ? ' ' . $strgasto->entidadResolucion->nombre . ' ': ' No se encuentra registrado nombre de entidad/resolución '}}
            se sujetará a las normas contenidas en la Constitución Política de 1991, los Decretos Ley 568, 630 y 111 de 1996, lo dispuesto
            en la ordenanza No. 015 de 2015 y demás disposiciones que sobre la materia de expidan.
        </span>
        <br>
        <span style="text-align: justify !important;" class="text_1">
             <b>APLICACIÓN:</b> Las presentes disposiciones rigen para el funcionamiento y ejecucuión del presupuesto de
            {{$strgasto->entidadResolucion ? ' ' . $strgasto->entidadResolucion->nombre . ' '
            : ' No se encuentra registrado nombre de entidad/resolución'}}.
        </span>
        <br>
    </div>
    <div class="body_2" >
         <span class="text_1" style="text-align: justify !important;">
             <b><u>ARTÍCULO CUARTO:</u> DEL PRESUPUESTO DE INGRESOS.</b> El presupuesto de ingresos contiene la estimación de
             los ingresos no tributarios y los recursos de capital. Los ingresos de{{$strgasto->entidadResolucion ? ' ' . $strgasto->entidadResolucion->nombre . ' '
            : ' No se encuentra registrado nombre de entidad/resolución'}} se clasifican en:
        </span>
        <br>
        <span class="text_1" style="text-align: justify !important;">
            A. INGRESOS CORRIENTES NO TRIBUTARIOS: Son los recursos  que percibe {{$strgasto->entidadResolucion ? ' ' . $strgasto->entidadResolucion->nombre
            : ' No se encuentra registrado nombre de entidad/resolución'}}., en forma permanente en razón a sus funciones y atribuciones por concepto de la Administracion de recursos
            del Régimen Subsidiado en Salud. Régimen Contributivo en Salud y por otros ingresos adicionales tales como Copagos, Recobros FOSYGA vigencias anteriores,
            Recobros entes territoriales y otros ingresos.
        </span>
        <br>
        <span class="text_1" style="text-align: justify !important;">
            B. RECURSOS DE CAPITAL: Son los ingresos de carácter ocasional que percibe la entidad tales como rendimientos financieros, excedentes financieros,
            cancelación de reservas, recuperación de cartera y Donaciones, entre otros.
        </span>
    </div>
    <div class="body_2" >
         <span class="text_1" style="text-align: justify !important;">
             <b><u>ARTÍCULO QUINTO:</u> DEFINICIÓN DE LOS GASTOS.</b> Las apropiaciones  incluidas en el presupuesto para la vigencia año {{ strtolower(NumeroALetras::convertir($strgasto->periodo))  . ' (' . $strgasto->periodo . '), de '
             . ($strgasto->entidadResolucion ? ' ' . $strgasto->entidadResolucion->nombre . '.' : ' No se encuentra registrado nombre de entidad/resolución')}} se clasifican en:
        </span>
    </div>
</main>
</body>
</html>