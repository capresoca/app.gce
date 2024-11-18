<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ESTUDIO PREVIO SERVICIOS DE SALUD</title>
    <link rel="stylesheet" href="./css/estudioPrevioPdf.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<header>
    <table class="table-collapse" style="width: 100%">
        <tbody>
        <tr>
            <td width="25%">
                <div class="logo">
                    <img src="./images/capresoca.jpg">
                </div>
                <div style="display: block; font-weight: bolder !important;
                            font-size: 1.2em">NIT. 891.856.000-7
                </div>
            </td>
            <td>
                <div class="texto-cabecera">
                    <h2 style="padding: 0; margin-bottom: 0; margin-top: 0">
                        ESTUDIO PREVIO @if($ce_proconestprevio->servicios_salud == '1')SERVICIOS DE SALUD @else
                            ADMINISTRATIVO @endif
                    </h2>
                    <span style="display: block; font-weight: lighter; font-size: 1.2em">
                            @if($ce_proconestprevio->servicios_salud == '1')
                            FO-CBS-02
                        @else
                            FO-CBS-01
                        @endif
                        </span>
                    <span style="display: block; font-weight: lighter; font-size: 1.2em">{{Date::today()->format('Y-m-d')}}</span>
                    <span style="display: block; font-weight: lighter; font-size: 1.2em">V.01</span>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2"
                style="font-weight: lighter !important; font-size: 3.0mm; padding: 25px 0 0;margin-bottom: 0">100.10
            </td>
        </tr>
        </tbody>
    </table>
</header>
<footer class="footer_2">
    <table class="table-collapse">
        <tbody>
        <tr>
            <td class="footer_image">
                <img src="./images/logo_super.png" alt="logo_super">
            </td>
            <td style="text-align: right !important;">
                <div style="padding-right: 25px !important;">
                    <span style="display: block">Calle 7 No. 19 - 34. Teléfonos: (8) 635 8162 - 635 8163</span>
                    <span style="display: block">635 8232 Ext. 135 Email. <u>capresocaeps@capresoca.com.co</u></span>
                    <span>Yopal Casanare</span>
                </div>
            </td>
            <td class="text_center" width="12%">
                {{ '      ' }}
                <script type="text/php" class="pages">
                if (isset($pdf)) {
                $font = $fontMetrics->getFont("Helvetica", "lighter");
                $pdf->page_text(530, 755, "{PAGE_NUM} de {PAGE_COUNT}", $font, 8, array(0, 0, 0));
                }

                </script>
            </td>
        </tr>
        </tbody>
    </table>
</footer>
{{--<main>--}}
{{--    <span style="display: block; font-weight: lighter; font-size: 1.2em">100.10</span>--}}
{{--    <table class="table-collapse">--}}
{{--        <tbody>--}}
{{--        <tr>--}}
{{--            <td class="celda text_center negrilla" style="padding: 5px 5px; font-size: 3.0mm" width="15%">DEPENDENCIA</td>--}}
{{--            <td class="celda text_center negrilla" style="padding: 5px 5px; font-size: 3.0mm" width="25%">DIVISIÓN SERVICIOS DE SALUD</td>--}}
{{--            <td class="celda text_center negrilla" style="padding: 5px 5px; font-size: 3.0mm" width="25%">No. DOCUMENTO</td>--}}
{{--            <td class="celda text_center negrilla" style="padding: 5px 5px; font-size: 3.0mm" width="25%">{{str_pad($ce_proconestprevio->estudiosPrevios->consecutivo,6,"0",STR_PAD_LEFT)}}</td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td colspan="4" class="celda text_center color_grey negrilla" style="padding: 4px 4px; font-size: 3.0mm">--}}
{{--                1. INFORMACIÓN GENERAL--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td class="plusFont celda text_left negrilla" style="padding: 0 0 0 5px; font-size: 3.0mm">1.1 FECHA DE PRESENTACIÓN</td>--}}
{{--            <td class="plusFont celda text_justify" colspan="3" style="padding: 0 0 0 5px; font-size: 3.0mm">{{\Illuminate\Support\Carbon::parse($ce_proconestprevio->estudiosPrevios->fecha)->format('d \\d\\e M \\d\\e\\l Y')}}</td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td class="plusFont celda text_left negrilla" style="padding: 0 0 0 5px; font-size: 3.0mm;">1.2 OBJETO A CONTRATAR</td>--}}
{{--            <td colspan="3" class="plusFont celda text_justify" style="padding: 0 0 0 5px; font-size: 3.0mm;">{{$ce_proconestprevio->objeto ? $ce_proconestprevio->objeto : 'NO REGISTRADO' }}</td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td class="celda text_justify negrilla" colspan="4" style="padding-left: 5px; font-size: 3.0mm">1.3 IMPUTACIÓN PRESUPUESTAL</td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td colspan="4" style="padding: 0">--}}
{{--                <table class="table-collapse" width="100%">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th colspan="2" class="celda text_left negrilla" style="padding: 4px 5px; font-size: 3.0mm; width: 15%">1.3.1 CÓDIGO</th>--}}
{{--                        <th colspan="2" class="celda text_left negrilla" style="padding: 4px 5px; font-size: 3.0mm; width: 75%">1.3.2 NOMBRE</th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($ce_proconestprevio->estudiosPrevios->imputacionPresupuestal as $item)--}}
{{--                        <tr>--}}
{{--                            <td class="celda text_justify" colspan="2" style="padding: 0 0 0 5px; font-size: 3.0mm">--}}
{{--                                {{ $ce_proconestprevio->estudiosPrevios->imputacionPresupuestal ? $item->strgasto->rubro->codigo : 'NO REGISTRADO' }}--}}
{{--                            </td>--}}
{{--                            <td class="celda text_justify" colspan="2" style="padding-left: 5px; font-size: 3.0mm">--}}
{{--                                {{ $ce_proconestprevio->estudiosPrevios->imputacionPresupuestal ? $item->strgasto->rubro->nombre : 'NO REGISTRADO' }}--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        </tbody>--}}
{{--        <tbody>--}}
{{--        <tr>--}}
{{--            <td class="celda negrilla" style="padding: 0 0 0 5px !important; font-size: 3.0mm; width: 15%">--}}
{{--                1.4 PRODUCTO(S) A ENTREGAR--}}
{{--            </td>--}}
{{--            <td colspan="3" class="celda text_justify etiquetaP" style="padding: 0 0 0 5px; font-size: 3.0mm; width: 75%">{!!$ce_proconestprevio->estudiosPrevios->productos_entregar!!}</td>--}}
{{--        </tr>--}}
{{--        </tbody>--}}
{{--        <tbody>--}}
{{--        <tr>--}}
{{--            <td colspan="4" style="padding: 0; margin: 0">--}}
{{--                <div class="table-collapse" style="background: #e0a800 !important; margin: 0 !important; padding: 0 !important;">--}}
{{--                    <div>--}}
{{--                        <div>--}}
{{--                            <div class="celda text_center negrilla color_grey" style="padding: 4px 5px; font-size: 3.0mm">2. DESCRIPCIÓN DE LA NECESIDAD</div>--}}
{{--                        </div>--}}
{{--                        <div class="p-mas">--}}
{{--                            <div class="etiquetaP celda text_justify" style="font-size: 3.0mm">--}}
{{--                                {!!$ce_proconestprevio->estudiosPrevios->desc_necesidad!!}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td class="celda text_center negrilla color_grey" colspan="4" style="padding: 5px 5px; font-size: 3.0mm">3. DEFINICIÓN TÉCNICA</td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td class="celda text_justify none_border_bottom" colspan="4" style="padding: 0 0 0 5px; font-size: 3.0mm">3.1 DESCRIPCIÓN:</td>--}}
{{--        </tr>--}}
{{--        <tr>--}}
{{--            <td class="celda text_justify none_border_top" colspan="4" style="padding: 0 0 0 5px; font-size: 3.0mm">--}}
{{--                {!!$ce_proconestprevio->estudiosPrevios->desc_tecnica!!}--}}
{{--            </td>--}}
{{--        </tr>--}}
{{--        </tbody>--}}
{{--    </table>--}}
{{--</main>--}}
@if($ce_proconestprevio->estudiosPrevios->estado === 'Registrado')
    <div class="watermark">BORRADOR</div>
@endif
<main>
    {{--    <span style="display: block; font-weight: lighter; font-size: 1.2em">100.10</span>--}}
    <div class="tabla-1">
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="celda text_center negrilla" style="padding: 5px 5px; font-size: 3.0mm" width="15%">
                    DEPENDENCIA
                </td>
                <td class="celda text_center negrilla" style="padding: 5px 5px; font-size: 3.0mm" width="25%">DIVISIÓN
                    SERVICIOS DE SALUD
                </td>
                <td class="celda text_center negrilla" style="padding: 5px 5px; font-size: 3.0mm" width="25%">No.
                    DOCUMENTO
                </td>
                <td class="celda text_center negrilla" style="padding: 5px 5px; font-size: 3.0mm"
                    width="25%">{{str_pad($ce_proconestprevio->estudiosPrevios->consecutivo,6,"0",STR_PAD_LEFT)}}</td>
            </tr>
            <tr>
                <td colspan="4" class="celda text_center color_grey negrilla"
                    style="padding: 4px 4px; font-size: 3.0mm">
                    1. INFORMACIÓN GENERAL
                </td>
            </tr>
            <tr>
                <td class="plusFont celda text_left negrilla" style="padding: 0 0 0 5px; font-size: 3.0mm">1.1 FECHA DE
                    PRESENTACIÓN
                </td>
                {{--                <td class="plusFont celda text_justify" colspan="3" style="padding: 0 0 0 5px; font-size: 3.0mm">{{\Illuminate\Support\Carbon::parse($ce_proconestprevio->estudiosPrevios->fecha)->format('d \\d\\e M \\d\\e\\l Y')}}</td>--}}
                <td class="plusFont celda text_justify" colspan="3"
                    style="padding: 0 0 0 5px; font-size: 3.0mm">{{strtoupper( \Jenssegers\Date\Date::parse($ce_proconestprevio->estudiosPrevios->fecha)->format('d \d\e F \d\e\l Y'))}}</td>
            </tr>
            <tr>
                <td class="plusFont celda text_left negrilla" style="padding: 0 0 0 5px; font-size: 3.0mm;">1.2 OBJETO A
                    CONTRATAR
                </td>
                <td colspan="3" class="plusFont celda text_justify"
                    style="padding: 0 0 0 5px; font-size: 3.0mm;">{{$ce_proconestprevio->objeto ? $ce_proconestprevio->objeto : 'NO REGISTRADO' }}</td>
            </tr>
            <tr>
                <td class="celda text_justify negrilla" colspan="4" style="padding-left: 5px; font-size: 3.0mm">1.3
                    IMPUTACIÓN PRESUPUESTAL
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding: 0">
                    <table class="table-collapse" width="100%">
                        <thead>
                        <tr>
                            <th colspan="2" class="celda text_left negrilla"
                                style="padding: 4px 5px; font-size: 3.0mm; width: 15%">1.3.1 CÓDIGO
                            </th>
                            <th colspan="2" class="celda text_left negrilla"
                                style="padding: 4px 5px; font-size: 3.0mm; width: 75%">1.3.2 NOMBRE
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ce_proconestprevio->estudiosPrevios->imputacionPresupuestal as $item)
                            <tr>
                                <td class="celda text_justify" colspan="2" style="padding: 0 0 0 5px; font-size: 3.0mm">
                                    {{ $ce_proconestprevio->estudiosPrevios->imputacionPresupuestal ? $item->strgasto->rubro->codigo : 'NO REGISTRADO' }}
                                </td>
                                <td class="celda text_justify" colspan="2" style="padding-left: 5px; font-size: 3.0mm">
                                    {{ $ce_proconestprevio->estudiosPrevios->imputacionPresupuestal ? $item->strgasto->rubro->nombre .' (Régimen '.$item->strgasto->rubro->regimen.')' : 'NO REGISTRADO' }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="celda negrilla" style="padding: 0 0 0 5px !important; font-size: 3.0mm; width: 15%">
                    1.4 PRODUCTO(S) A ENTREGAR
                </td>
                <td colspan="3" class="celda text_justify etiquetaP"
                    style="padding: 0 0 0 5px; font-size: 3.0mm; width: 75%">{!!$ce_proconestprevio->estudiosPrevios->productos_entregar!!}</td>
            </tr>
            </tbody>
        </table>
        <div>
            <div class="celda text_center negrilla color_grey none_border_top none_border_bottom"
                 style="padding: 4px 5px; font-size: 3.0mm">2. DESCRIPCIÓN DE LA NECESIDAD
            </div>
        </div>
        <div class="etiquetaP celda text_justify equix" style="font-size: 3.0mm">
            {!!$ce_proconestprevio->estudiosPrevios->desc_necesidad!!}
        </div>
        <div>
            <div class="celda text_center negrilla color_grey none_border_top none_border_bottom"
                 style="padding: 4px 5px; font-size: 3.0mm">3. DEFINICIÓN TÉCNICA
            </div>
        </div>
        <div class="etiquetaP celda text_justify equix"
             style="padding: 0 0 -5px 5px; font-size: 3.0mm; margin-top: 0 !important;">
            <span class="negrilla" style="font-weight: lighter; font-size: 3.0mm">3.1 DESCRIPCIÓN:</span>
            {!!$ce_proconestprevio->estudiosPrevios->desc_tecnica!!}
        </div>
        <div class="celda text_justify p_actividades" style="padding: 0 2px 0 5px; font-size: 3.0mm">
            <span class="negrilla" style="font-weight: lighter; font-size: 3.0mm">3.2 ACTIVIDADES A DESARROLLAR PARA EL LOGRO DEL OBJETO O PRODUCTO A CONTRATAR:</span>
            @foreach($ce_proconestprevio->estudiosPrevios->actividades as $key => $item)
                {!!$ce_proconestprevio->estudiosPrevios->actividades ? "<p>(" . ($key+=1) . ") </p>" . $item->actividad . "<p>, </p>" : 'NO REGISTRA'!!}
            @endforeach
        </div>
        <div>
            <div class="celda text_center negrilla color_grey none_border_top none_border_bottom"
                 style="padding: 4px 5px; font-size: 3.0mm">4. SOPORTE ECONÓMICO
            </div>
        </div>
        <div class="etiquetaP celda text_justify sop_economico" style="padding: 0 0 0 5px; font-size: 3.0mm">
            {!!$ce_proconestprevio->estudiosPrevios->sop_economico!!}
        </div>
        <div class="etiquetaP celda text_justify" style="padding: 0 0 0 5px; font-size: 3.0mm">
            <span class="negrilla" style="display: block !important;font-weight: lighter; font-size: 3.0mm">
                4.1 ESPECIFICACIONES TÉCNICAS DEL OBJETO O PRODUCTO A CONTRATAR:</span>
            {!!$ce_proconestprevio->estudiosPrevios->esp_tecnicas!!}
        </div>
        <div class="etiquetaP celda text_justify" style="padding: 0 0 0 5px; font-size: 3.0mm">
             <span class="negrilla" style="display: block !important;font-weight: lighter; font-size: 3.0mm">
                4.2 IDENTIFICACION DE ALTERNATIVAS DE EJECUCIÓN:</span>
            {!!$ce_proconestprevio->estudiosPrevios->alt_ejecucion!!}
        </div>
        <div>
            <div class="celda text_center negrilla color_grey"
                 style="padding: 4px 5px; font-size: 3.0mm">5. RIESGOS DE LA CONTRATACIÓN
            </div>
        </div>
        <div class="etiquetaP celda text_justify riesgo_est" style="padding: 0 0 0 5px; font-size: 3.0mm">
             <span class="negrilla" style="display: block !important;font-weight: lighter; font-size: 3.0mm">
                5.1 DESCRIPCIÓN DE LOS POSIBLES RIESGOS:</span>
            {!!$ce_proconestprevio->estudiosPrevios->pos_riesgos!!}
        </div>
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="celda text_justify negrilla" style="padding-left: 5px; font-size: 3.0mm; width: 15%">5.2
                    PÓLIZAS
                </td>
                <td class="celda text_justify" colspan="3" style="padding: 0 0 0 5px; font-size: 3.0mm; width: 75%">
                    <table class="table-collapse">
                        <tbody>
                        <tr>
                            <td class="text_justify none_border_bottom none_border_top" style="font-size: 3.0mm"
                                colspan="3">
                                @if($ce_proconestprevio->estudiosPrevios->garantias)
                                    <ol style="margin: 0; padding: 0 0 0 17px">
                                        @foreach($ce_proconestprevio->estudiosPrevios->garantias as $key => $item)
                                            <li>
                                                {{ $ce_proconestprevio->estudiosPrevios->garantias ? $item->garantia->nombre . ': ' . $item->descripcion : 'NO REGISTRADO' }}
                                            </li>
                                        @endforeach
                                    </ol>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            @if($ce_proconestprevio->estudiosPrevios['descripgarantias'] != '')
                                <td class="text_justify none_border_bottom none_border_top" style="font-size: 3.0mm"
                                    colspan="3">
                                    {!! $ce_proconestprevio->estudiosPrevios['descripgarantias'] !!}
                                </td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </td>
                {{--                <td class="text_justify" style="padding-top: 0.5mm !important;" colspan="4">--}}
                {{--                    {!! $ce_proconestprevio->estudioPrevios['descripgarantias'] !!}--}}
                {{--                </td>--}}
                {{--                @if($ce_proconestprevio->estudioPrevios['descripgarantias'] != '')--}}
                {{--                    <td class="text_justify" style="padding-top: 0.5mm !important;" colspan="4">--}}
                {{--                        {!! $ce_proconestprevio->estudioPrevios['descripgarantias'] !!}--}}
                {{--                    </td>--}}
                {{--                @endif--}}
            </tr>
            </tbody>
        </table>
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="celda text_center negrilla color_grey" colspan="4"
                    style="padding: 5px 5px; font-size: 3.0mm">6. CONDICIONES DEL OBJETO CONTRACTUAL
                </td>
            </tr>
            <tr>
                <td class="celda text_justify none_border_bottom negrilla" colspan="4"
                    style="padding: 0 0 0 5px; font-size: 3.0mm">6.1 TIPO DE CONTRATACIÓN:
                </td>
            </tr>
            <tr>
                <td class="celda text_justify none_border_top" colspan="4"
                    style="padding: 0 0 0 5px; font-size: 3.0mm">{!!$ce_proconestprevio->estudiosPrevios->tipoContratacion->nombre!!}</td>
            </tr>
            </tbody>
        </table>
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="plusFont celda text_left negrilla" style="padding: 0 0 0 5px; font-size: 3.0mm; width: 15%">
                    6.2 OBJETO A CONTRATAR
                </td>
                <td class="plusFont celda text_justify"
                    style="padding: 0 0 0 5px; font-size: 3.0mm; width: 75%">{{$ce_proconestprevio->objeto ? $ce_proconestprevio->objeto : 'NO REGISTRADO' }}</td>
            </tr>
            </tbody>
        </table>
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="plusFont celda text_justify negrilla"
                    style="padding: 0 0 0 5px; font-size: 3.0mm; width: 15%">6.3 PLAZO
                </td>
                <td colspan="3" class="celda text_justify"
                    style="padding: 0 0 0 5px; font-size: 3.0mm; width: 75%; vertical-align: top !important;">{!! $ce_proconestprevio->estudiosPrevios['desc_plazo'] === '<p>&nbsp;</p>' ? '' : strtoupper($ce_proconestprevio->estudiosPrevios['desc_plazo'])!!}</td>
            </tr>
            </tbody>
        </table>
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="plusFont celda text_justify negrilla"
                    style="padding: 0 0 0 5px; font-size: 3.0mm; width: 15%">6.4 VALOR
                </td>
                <td class="celda text_justify" style="padding: 0 0 0 5px; font-size: 3.0mm; width: 75%">
                    {{$ce_proconestprevio->estudiosPrevios->valor !== null ? '$ '. number_format($ce_proconestprevio->estudiosPrevios->valor, 2, ',', '.') : "$ 0,00"}}</td>
            </tr>
            </tbody>
        </table>
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="celda text_justify negrilla" colspan="4" style="padding-left: 5px; font-size: 3.0mm">6.6
                    FORMA DE PAGO
                </td>
            </tr>
            <tr>
                @if($ce_proconestprevio->estudiosPrevios->forpagos)
                    <td colspan="4" style="padding: 0">
                        <table class="table-collapse" width="100%">
                            <tbody>
                            <tr>
                                <td class="celda text_left negrilla"
                                    style="padding: 4px 5px; font-size: 3.0mm; width: 15%">6.6.1 TIPO
                                </td>
                                <td class="celda text_left negrilla" style="padding: 4px 5px; font-size: 3.0mm">6.6.2
                                    DESCRIPCIÓN
                                </td>
                                <td class="celda text_left negrilla" style="padding: 4px 5px; font-size: 3.0mm">6.6.3
                                    VALOR
                                </td>
                            </tr>
                            @foreach($ce_proconestprevio->estudiosPrevios->forpagos as $item)
                                <tr>
                                    <td class="celda text_justify"
                                        style="padding: 0 0 0 5px; font-size: 3.0mm; width: 15%">
                                        {{ $ce_proconestprevio->estudiosPrevios->forpagos ? $item->tipo : 'NO REGISTRADO' }}
                                    </td>
                                    <td class="celda text_justify" style="padding: 0 0 0 5px; font-size: 3.0mm">
                                        {{ $ce_proconestprevio->estudiosPrevios->forpagos ? $item->descripcion : 'NO REGISTRADO' }}
                                    </td>
                                    <td class="celda" style="font-size: 3.0mm; text-align: right !important;">
                                        {{ $ce_proconestprevio->estudiosPrevios->forpagos ? ($item->valor !== null || $item->valor !== 0 ? (($item->tipo === 'Porcentaje') ? '% ':'$ ') . number_format($item->valor , 2, ',', '.') : "$ 0,00") : 'NO REGISTRADO' }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </td>
                @endif
            </tr>
            </tbody>
        </table>
        <div class="container">
            @if($ce_proconestprevio->servicios_salud == '1')
                <div class="celda text_justify negrilla" style="padding-left: 5px; font-size: 3.0mm">6.7 TARIFAS</div>
                @if($ce_proconestprevio['estudiosPrevios']['proconminutageos'] != [] && ($geocons != [] || $geosubs != []))
                    <div class="celda text_justify none_border_top none_border_bottom"
                         style="padding-left: 5px; font-size: 2.5mm">
                        <div style="width: 90%; margin-left: 35px !important; padding-top: 1.2mm !important;">
                            @if($ce_proconestprevio['estudiosPrevios']['modalidad']['id'] === 1)
                                <table class="table-collapse">
                                    <tbody>
                                    <tr>
                                        <td class="celda negrilla text_center">MUNICIPIO</td>
                                        <td class="celda negrilla text_center">SERVICIO CONTRATADO</td>
                                        <td class="celda negrilla text_center">%POBLACIÓN RÉG SUB</td>
                                        <td class="celda negrilla text_center">%POBLACIÓN PORTABILIDAD</td>
                                        <td class="celda negrilla text_center">{{'VALOR UPC MENSUAL ' . \Jenssegers\Date\Date::now()->format('Y') . ' SUB'}}</td>
                                        <td class="celda negrilla text_center">% UPC</td>
                                        <td class="celda negrilla text_center">{{'VALOR PERCAPITA SEGÚN UPC AÑO ' . \Jenssegers\Date\Date::now()->format('Y') . ' SUBSIDIADO'}}</td>
                                        <td class="celda negrilla text_center">{{'VALOR SUB SEGÚN TIEMPO PACTADO INICIAL Y UPC ' . \Jenssegers\Date\Date::now()->format('Y')}}</td>
                                    </tr>
                                    </tbody>
                                    <tbody>
                                    @foreach($geosubs as $geo)
                                        <tr>
                                            <td class="celda text_center">{{$geo['municipio']}}</td>
                                            <td class="celda text_center">{{$geo['servicio']}}</td>
                                            <td class="celda text_center">{{$geo['porcentaje_subsidiado']}}</td>
                                            <td class="celda text_center">{{$geo['portabilidad'] . '%'}}</td>
                                            <td class="celda text_center">
                                                {{($geo['valor_upc_subsidiado'] !== null || $geo['valor_upc_subsidiado'] !== 0)
                                                      ? '$'. number_format($geo['valor_upc_subsidiado'], 2, ',', '.')
                                                      : "$ 0,00"}}</td>
                                            <td class="celda text_center">{{$geo['porcent_pob_subsidiado'] . '%'}}</td>
                                            <td class="celda text_center">
                                                {{($geo['percapita'] !== null || $geo['percapita'] !== 0)
                                                      ? '$'. number_format($geo['percapita'], 2, ',', '.')
                                                      : "$ 0,00"}}</td>
                                            <td class="celda text_center">
                                                {{($geo['valor_x_tiempo'] !== null || $geo['valor_x_tiempo'] !== 0)
                                                      ? '$'. number_format($geo['valor_x_tiempo'], 2, ',', '.')
                                                      : "$ 0,00"}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                            <table class="table-collapse" style="width: 90%;">
                                <tbody>
                                <tr>
                                    <td class="celda negrilla text_center">MUNICIPIO</td>
                                    <td class="celda negrilla text_center">SERVICIO CONTRATADO</td>
                                    <td class="celda negrilla text_center"
                                        @if($ce_proconestprevio['estudiosPrevios']['modalidad']['id'] === 1)colspan="2"@endif>
                                        %POBLACIÓN REG CONTRIBUTIVO
                                    </td>
                                    @if(($ce_proconestprevio['estudiosPrevios']['modalidad']['id'] === 9))
                                        <td class="celda negrilla text_center">VALOR UPS REG CONTRIBUTIVO</td>
                                        <td class="celda negrilla text_center">%POBLACIÓN REG SUBSIDIADO</td>
                                        <td class="celda negrilla text_center">VALOR UPS REG SUBSIDIADO</td>
                                        <td class="celda negrilla text_center">VALOR</td>
                                    @else
                                        <td class="celda negrilla text_center">{{'VALOR UPC MENSUAL ' . \Jenssegers\Date\Date::now()->format('Y') . ' CONTRIBUTIVO'}}</td>
                                        <td class="celda negrilla text_center">% UPC</td>
                                        <td class="celda negrilla text_center">{{'VALOR PERCAPITA SEGÚN UPC AÑO' . \Jenssegers\Date\Date::now()->format('Y') .' CONTRIBUTIVO'}}</td>
                                        <td class="celda negrilla text_center">{{'VALOR SUB SEGÚN TIEMPO PACTADO INICIAL Y UPC ' . \Jenssegers\Date\Date::now()->format('Y')}}</td>
                                    @endif
                                </tr>
                                </tbody>
                                <tbody>
                                @foreach($geocons as $geo)
                                    <tr>
                                        <td class="celda text_center">{{$geo['municipio']}}</td>
                                        <td class="celda text_center">{{$geo['servicio']}}</td>
                                        <td class="celda text_center">{{$geo['porcentaje_contributivo'] . '%'}}</td>
                                        <td class="celda text_center">
                                            {{($geo['valor_upc_contributivo'] !== null || $geo['valor_upc_contributivo'] !== 0)
                                                  ? '$'. number_format($geo['valor_upc_contributivo'], 2, ',', '.')
                                                  : "$ 0,00"}}</td>
                                        @if(($ce_proconestprevio['estudiosPrevios']['modalidad']['id'] === 9))
                                            <td class="celda text_center">{{$geo['porcentaje_subsidiado'] . '%'}}</td>
                                            <td class="celda text_center">
                                                {{($geo['valor_upc_subsidiado'] !== null || $geo['valor_upc_subsidiado'] !== 0)
                                                      ? '$'. number_format($geo['valor_upc_subsidiado'], 2, ',', '.')
                                                      : "$ 0,00"}}</td>
                                            <td class="celda text_center">
                                                {{($geo['valor'] !== null || $geo['valor'] !== 0)
                                                      ? '$'. number_format($geo['valor'], 2, ',', '.')
                                                      : "$ 0,00"}}</td>
                                        @else
                                            <td class="celda text_center">{{$geo['porcent_pob_contributivo'] . '%'}}</td>
                                            <td class="celda text_center">
                                                {{($geo['percapita'] !== null || $geo['percapita'] !== 0)
                                                      ? '$'. number_format($geo['percapita'], 2, ',', '.')
                                                      : "$ 0,00"}}</td>
                                            <td class="celda text_center">
                                                {{($geo['valor_x_tiempo'] !== null || $geo['valor_x_tiempo'] !== 0)
                                                      ? '$'. number_format($geo['valor_x_tiempo'], 2, ',', '.')
                                                      : "$ 0,00"}}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
                <div class="celda tab_tarifas none_border_top" style="padding-top: 0.5mm">
                    {!! $ce_proconestprevio->estudiosPrevios->tarifas !!}
                </div>
            @endif
        </div>
        {{--            <div style="display: inline-block !important; width: 100% !important;">--}}
        {{--                <div class="celda text_justify negrilla"--}}
        {{--                     style="padding: 0 0 0 5px;--}}
        {{--                     font-size: 3.0mm;--}}
        {{--                     width: 15%;--}}
        {{--                     display: inline-block;--}}
        {{--                     margin-right: 0 !important;--}}
        {{--                     height: 100%;--}}
        {{--                     float: top !important;">6.7 TARIFAS--}}
        {{--                </div>--}}
        {{--                <div class="celda text_justify"--}}
        {{--                     style="padding: 0 0 0 5px; font-size: 3.0mm;--}}
        {{--                     width: 82.5%; display: inline-block; margin-left: 0 !important;">--}}
        {{--                    {!!$ce_proconestprevio->estudiosPrevios->tarifas!!}--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        <div class="tab" style="position: relative !important;">--}}
        {{--            <table class="table-collapse">--}}
        {{--                <tbody>--}}
        {{--                <tr>--}}
        {{--                    <td class="celda text_justify negrilla" style="padding: 0 0 0 5px; font-size: 3.0mm; width: 15%">6.7 TARIFAS</td>--}}
        {{--                    <td class="celda text_justify tab_tarifas" style="padding: 0 0 0 5px; font-size: 3.0mm; width: 75%">{!!$ce_proconestprevio->estudiosPrevios->tarifas!!}</td>--}}
        {{--                </tr>--}}
        {{--                </tbody>--}}
        {{--            </table>--}}
        {{--        </div>--}}
        {{--        @endif--}}
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="celda text_justify negrilla" style="padding: 0 0 0 5px; font-size: 3mm; width: 15%">7.
                    MODALIDAD
                </td>
                <td colspan="3" class="celda text_justify"
                    style="padding: 0 0 0 5px; font-size: 3mm;  width: 75%">{{$ce_proconestprevio->estudiosPrevios['modalidad']['nombre']}}</td>
            </tr>
            </tbody>
        </table>
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="plusFont celda text_left negrilla" style="padding: 0 0 0 5px; font-size: 3mm; width: 15%">8.
                    LUGAR DE EJECUCIÓN
                </td>
                <td colspan="3" class="celda text_justify"
                    style="padding: 0 0 0 5px; font-size: 3mm; width: 75%">{!!$ce_proconestprevio->estudiosPrevios->lugar_ejecucion!!}</td>
            </tr>
            </tbody>
        </table>
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="celda text_justify negrilla" style="padding: 0 0 0 5px; font-size: 3.0mm; width: 15%">9.
                    SUPERVISIÓN
                </td>
                <td class="celda text_justify"
                    style="padding: 0 5px 0 5px; font-size: 3.0mm; width: 75% ">{{$ce_proconestprevio->estudiosPrevios->supervicion}}</td>
            </tr>
            </tbody>
        </table>
        <table class="table-collapse">
            <thead>
            <tr>
                <th class="celda text_left negrilla color_grey" colspan="2"
                    style="padding: 4px 5px; font-size: 3.0mm">PROYECTO ESTUDIO PREVIO:
                </th>
                <th class="celda text_left negrilla color_grey" colspan="2"
                    style="padding: 4px 5px; font-size: 3.0mm">REVISIÓN JURÍDICA ESTUDIO PREVIO:
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="celda" colspan="2" style="padding: 0 !important;">
                    <table class="table-collapse">
                        <tbody>
                        <tr>
                            <td class="celda text_left none_borde_derecho none_borde_izquierdo"
                                style="width: 15%; font-size: 3.0mm; padding-top: 1mm; padding-bottom: 1mm">
                                FIRMA:
                                <br />
                                <br />
                                <br />
                                <br />
                            </td>
                            <td class="celda text_left"></td>
                        </tr>
                        <tr>
                            <td class="celda text_left none_borde_derecho none_borde_izquierdo"
                                style="width: 15%; font-size: 3.0mm; padding-top: 1mm; padding-bottom: 1mm">
                                NOMBRE CARGO:
                            </td>
                            <td class="celda text_left" style="padding-left: 1.5mm">
                                <span class="negrilla" style="display: block; font-weight: lighter; font-size: 3.0mm">{{ $usuario_proyecta ? $usuario_proyecta->name : 'NO ASIGNADO' }}</span>
                                <span style="display: block; font-weight: lighter; font-size: 3.0mm">PROFESIONAL DE APOYO DAF</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td class="celda" colspan="2" style="padding: 0 !important;">
                    <table class="table-collapse">
                        <tbody>
                        <tr>
                            <td class="celda text_left none_borde_derecho none_borde_izquierdo"
                                style="width: 15%; font-size: 3.0mm; padding-top: 1mm; padding-bottom: 1mm">
                                FIRMA:
                                <br />
                                <br />
                                <br />
                                <br />
                            </td>
                            <td class="celda text_left"></td>
                        </tr>
                        <tr>
                            <td class="celda text_left none_borde_derecho none_borde_izquierdo"
                                style="width: 15%; font-size: 3.0mm; padding-top: 1mm; padding-bottom: 1mm">
                                NOMBRE CARGO:
                            </td>
                            <td class="celda text_left" style="padding-left: 1.5mm">
                                <span class="negrilla" style="display: block; font-weight: lighter; font-size: 3.0mm">{{ (!is_null($usuario_revisa)) ? $usuario_revisa->name : 'NO ASIGNADO' }}</span>
                                <span style="display: block; font-weight: lighter; font-size: 3.0mm">PROFESIONAL ESPECIALIZADO DSS-DAF</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        <table class="table-collapse">
            <tbody>
            <tr>
                <td class="celda text_center negrilla color_grey" colspan="2"
                    style="padding: 4px 5px; font-size: 3.0mm">APROBO ESTUDIO PREVIO
                </td>
            </tr>
            <tr>
                <td class="celda text_left"
                    style=" width: 5%; font-size: 3.0mm; padding-top: 1mm; padding-bottom: 1mm">
                    FIRMA:
                    <br />
                    <br />
                    <br />
                    <br />
                </td>
                <td class="celda text_left" style="width: 85%;"></td>
            </tr>
            <tr>
                <td class="celda text_left "
                    style="width: 5%; font-size: 3.0mm; padding-top: 1mm; padding-bottom: 1mm">
                    NOMBRE CARGO:
                </td>
                <td class="celda text_center" style="padding-left: 1.5mm; width: 85%">
                    <span class="negrilla" style="display: block; font-weight: lighter; font-size: 3.0mm">{{ (!is_null($usuario_aprueba)) ? $usuario_aprueba->name : 'NO ASIGNADO' }}</span>
                    <span style="display: block; font-weight: lighter; font-size: 3.0mm">SUBGERENTE DIVISIÓN DE SERVICIOS DE SALUD</span>
                </td>
            </tr>
            </tbody>
        </table>
        {{--        <table class="table-collapse">--}}
        {{--            <tbody>--}}
        {{--            <tr>--}}
        {{--                <td colspan="2" class="celda" style="text-align: left !important; vertical-align: middle; padding: 20px 5px 50px; width: 50%">--}}
        {{--                    <div class="" style="display: block !important; font-size: 3.0mm"><span class="negrilla">ELABORÓ:</span></div>--}}
        {{--                    <div class="" style="display: block !important; font-size: 3.0mm">C.C.</div>--}}
        {{--                    <div class="" style="display: block !important; font-size: 3.0mm">CARGO:</div>--}}
        {{--                    <div class="" style="display: block !important; font-size: 3.0mm">FIRMA</div>--}}
        {{--                </td>--}}
        {{--                <td colspan="2" class="celda" style="text-align: left !important; vertical-align: middle; padding: 20px 5px 50px">--}}
        {{--                    <div class="" style="display: block !important; font-size: 3.0mm"><span class="negrilla">REVISÓ:</span></div>--}}
        {{--                    <div class="" style="display: block !important; font-size: 3.0mm">C.C.</div>--}}
        {{--                    <div class="" style="display: block !important; font-size: 3.0mm">CARGO:</div>--}}
        {{--                    <div class="" style="display: block !important; font-size: 3.0mm">FIRMA</div>--}}
        {{--                </td>--}}
        {{--            </tr>--}}
        {{--            <tr>--}}
        {{--                <td colspan="4" style="padding-top: 20px; font-size: 3.0mm; ">--}}
        {{--                    <span>"<"fimas/">"</span>--}}
        {{--                </td>--}}
        {{--            </tr>--}}
        {{--            </tbody>--}}
        {{--        </table>--}}
    </div>
    {{--    <div class="saltopagina"></div>--}}
</main>
</body>
</html>