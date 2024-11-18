<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SOLICITUD CERTIFICADO DE DISPONIBILIDAD PRESUPUESTAL</title>
    <link rel="stylesheet" href="./css/solicitudCdPdf.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <div class="main">
        <div id="header">
            <div class="logo">
                <img src="./images/capresoca.jpg" >
                <div style="display: block; font-weight: bolder !important;
                            font-size: 1.2em">NIT. 891.856.000-7</div>
            </div>
            <div class="sub-header">
                <h3>SOLICITUD CERTIFICADO DE DISPONIBILIDAD PRESUPUESTAL</h3>
                <div style="display: block !important;">
                    <span style="display: block">FO-CBS-03</span>
                    <span style="display: block">{{ \Carbon\Carbon::now()->format('Y-m-d') }}</span>
                    <span style="display: block">V.01</span>
                    <span>Consecutivo:  <b>{{ ' ' . $solicitud->consecutivo }}</b></span>
                </div>
            </div>
        </div>
        <div class="body_1">
            <table class="table-collapse">
                <tbody>
                <tr>
                    <td class="celda color_grey negrilla padding_td" width="20%">FECHA DE SOLICITUD</td>
                    <td class="celda negrilla-2 padding_td">{{ \Carbon\Carbon::parse($solicitud->fecha)->format('d \\DE F \\DE Y') }}</td>
                </tr>
                </tbody>
            </table>
            <table class="table-collapse">
                <tbody>
                <tr>
                    <td class="celda color_grey negrilla" width="26%">DESCRIPCIÓN DE LA NECESIDAD:</td>
                    <td class="celda color_grey none_borde_izquierdo desc_necesidad"
                        style="text-align: justify !important; padding: 0 5px 0 2px!important;">
                            {!!$solicitud->desc_necesidad ? $solicitud->desc_necesidad : '<b></b>'!!}
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="table-collapse">
                <tbody>
                <tr>
                    <td class="celda" width="30%" style="padding: 0">
                        <table class="table-collapse">
                            <tbody>
                            <tr>
                                <td class="celda color_grey padding_td negrilla">DESCRIPCIÓN SOLICITANTE:</td>
                            </tr>
                            <tr>
                                <td class="celda text_center none_border_bottom none_border_top none_borde_derecho none_borde_izquierdo negrilla">DIVISION ADMINISTRATIVA Y FINANCIERA</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="celda" style="padding: 0">
                        <table class="table-collapse">
                            <tbody>
                            <tr>
                                <td class="celda color_grey text_center negrilla padding_td" colspan="2">CLASE DE GASTO</td>
                            </tr>
                            <tr>
                                <td class="celda text_center color_grey negrilla padding_td">GASTOS DE FUNCIONAMIENTO</td>
                                <td class="celda text_center color_grey negrilla padding_td">GASTOS DE OPERACIÓN</td>
                            </tr>
                            <tr>
                                <td class="celda border_bottom borde_derecho padding_td">
                                    {{
                                        $solicitud->clas_funcionamiento ? 'X' : ''
                                    }}
                                    {{--{{--}}
                                     {{--$solicitud->detstrgasto--}}
                                     {{--? ($solicitud->detstrgasto->tipoGasto->clasificacion === 'Funcionamiento'--}}
                                     {{--? 'X' : '') : ''--}}
                                    {{--}}--}}
                                </td>
                                <td class="celda text_center padding_td">
                                    {{
                                        $solicitud->clas_gastos ? 'X' : ''
                                    }}
                                    {{--{{--}}
                                     {{--$solicitud->detstrgasto--}}
                                     {{--?  ($solicitud->detstrgasto->tipoGasto->clasificacion === 'Gastos de operacion comercial y de'--}}
                                     {{--? 'X' : '') : ''--}}
                                    {{--}}--}}
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
                    <td class="celda color_grey negrilla padding_td" width="20%">FUNCIONARIO</td>
                    <td class="celda text_center negrilla text_uppercase padding_td" colspan="2">
                        {{ (count($solicitud->rubros) > 0) ? $solicitud->rubros[0]->detstrgasto
                            ? ($solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->jefe_financiero_encargado !== null
                            ? $solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->jefe_financiero_encargado->usuario->name
                            : $solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->jefe_financiero) : 'NO REGISTRA': 'NO REGISTRA'
                        }}
                        {{--{{ $solicitud->detstrgasto--}}
                        {{--? $solicitud->detstrgasto->strGasto->entidadResolucion->jefe_financiero : 'NO REGISTRA'--}}
                        {{--}}--}}
                    </td>
                </tr>
                <tr>
                    <td class="celda color_grey negrilla padding_td" width="20%">CARGO</td>
                    <td class="celda negrilla text_center padding_td">
                        {{
                            'SUBGERENTE ADMINISTRATIVA Y FINANCIERA ' . (count($solicitud->rubros) > 0 && $solicitud->rubros[0]->detstrgasto
                            ? ($solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->jefe_financiero_encargado !== null
                                ? '(E)'
                                : '')
                            : '')
                        }}
                    </td>
                    <td class="celda negrilla padding_td">FIRMA</td>
                </tr>
                {{--<tr>--}}
                    {{--<td class="celda color_grey negrilla" width="20%">IMPUTACIÓN PRESUPUESTAL</td>--}}
                    {{--<td class="celda negrilla" colspan="2">--}}
                        {{--{{ $solicitud->detstrgasto--}}
                        {{--? $solicitud->detstrgasto->rubro->codigo : 'NO REGISTRA'--}}
                        {{--}}</td>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                </tbody>
            </table>
            <table class="table-collapse">
                <tbody>
                <tr>
                    <td class="celda color_grey negrilla text_center padding_td" colspan="3">IMPUTACIÓN PRESUPUESTAL</td>
                </tr>
                <tr>
                    {{--<td class="celda negrilla color_grey text_center">N°</td>--}}
                    <td class="celda negrilla color_grey text_center padding_td" width="20%">CÓDIGO</td>
                    <td class="celda negrilla color_grey text_center padding_td">NOMBRE</td>
                    <td class="celda negrilla color_grey text_center padding_td" width="30%">VALOR RUBRO</td>
                </tr>
                @foreach($solicitud->rubros as $key => $rubro)
                    <tr>
                        {{--<td class="celda negrilla text_center">{{$key}}</td>--}}
                        <td class="celda negrilla text_center padding_td">
                            {{ $rubro->detstrgasto ? $rubro->detstrgasto->rubro->codigo : 'NO REGISTRA'}}
                        </td>
                        <td class="celda negrilla text_justify padding_td">
                            {{ $rubro->detstrgasto ? $rubro->detstrgasto->rubro->nombre : 'NO REGISTRA'}}
                        </td>
                        <td class="celda negrilla text_right padding_td">
                            <span style="float: right !important; padding-right: 0 !important;">
                                {{
                               ($rubro->valor !== null || $rubro->valor !== 0)
                               ? '$ '. number_format($rubro->valor, 2, ',', '.') : "$ 0,00"
                                }}
                            </span>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <table class="table-collapse">
                <tbody>
                {{--<tr>--}}
                    {{--<td class="celda color_grey negrilla padding_td" colspan="2">NOMBRE RUBRO:</td>--}}
                {{--</tr>--}}
                {{--<tr>--}}
                    {{--<td class="celda negrilla" colspan="2"> {{ $solicitud->detstrgasto--}}
                        {{--? $solicitud->detstrgasto->rubro->nombre : 'NO REGISTRA'--}}
                        {{--}}</td>--}}
                {{--</tr>--}}
                <tr>
                    <td class="celda negrilla color_grey padding_td">OBJETO A CONTRATAR:</td>
                    <td class="celda negrilla color_grey padding_td text_center">VALOR</td>
                </tr>
                <tr>
                    <td class="celda" style="text-align: justify !important; font-size: 3.0mm; padding: 2px 2px !important;">
                        {!!$solicitud->objeto_acontratar!!}
                        {{--{{ $solicitud->incapacidad--}}
                        {{--? 'Pago a la Empresa ' . $solicitud->incapacidad->relacion_laboral->pagador->razon_social .--}}
                        {{--' de ' . $solicitud->incapacidad->tipo_incapacidad->prestacion->tipo . ' de la trabajadora ' : ''}}--}}
                        {{--<b>{{ $solicitud->incapacidad ? ' '. $solicitud->incapacidad->afiliado->nombre_completo : ''}}</b>--}}
                        {{--{{ $solicitud->incapacidad ? ' identifiacada con ' . $solicitud->incapacidad->afiliado->identificacion_completa .--}}
                        {{--' comprendida del ' . \Carbon\Carbon::parse($solicitud->incapacidad->fecha_inicio)->format('d/m/Y')--}}
                        {{--. ' AL ' . \Carbon\Carbon::parse($solicitud->incapacidad->fecha_fin)->format('d/m/Y') . '.'--}}
                        {{--: ''}}--}}
                    </td>
                    <td class="celda negrilla text_center" width="20%">
                        {{
                        ($solicitud->valor_total !== null || $solicitud->valor_total !== 0)
                        ? '$ '. number_format($solicitud->valor_total, 2, ',', '.') : "$ 0,00"
                        }}
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="table-collapse">
                <tbody>
                <tr>
                    <td class="celda negrilla color_grey color_grey padding_td" width="20%">TOTAL EN LETRAS</td>
                    <td class="celda negrilla text_center padding_td">
                        {{ NumeroALetras::convertir($solicitud->valor_total, 'PESOS', 'CENTAVOS') }}
                    </td>
                </tr>
                <tr>
                    <td class="celda negrilla color_grey padding_td">EXPIDE</td>
                    <td class="celda negrilla text_center text_uppercase padding_td">
                        {{--$solicitud->detstrgasto ? : 'NO REGISTRA'--}}
                        {{
                            count($solicitud->rubros) > 0 && $solicitud->rubros[0]->detstrgasto ?
                            $solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->jefe_presupuestal_encargado !== null
                            ? $solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->jefe_presupuestal_encargado->usuario->name
                            : $solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->jefe_presupuesto
                            : 'NO REGISTRA'
                        }}
                    </td>
                </tr>
                <tr>
                    <td class="celda negrilla color_grey padding_td">CARGO</td>
                    <td class="celda negrilla text_center padding_td">
                        {{
                            'TÉCNICO ADMINISTRATIVO DE PRESUPUESTO ' . (count($solicitud->rubros) > 0 && $solicitud->rubros[0]->detstrgasto
                            ? ($solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->jefe_presupuestal_encargado !== null
                                ? '(E)'
                                : '')
                            : '')
                        }}
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="footer_1 celda">
                <div class="border_top text_center negrilla text_uppercase">
                    {{ count($solicitud->rubros) > 0 && $solicitud->rubros[0]->detstrgasto
                           ? ($solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->representante_encargado !== null
                           ? $solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->representante_encargado->usuario->name
                           : $solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->representante_legal) : 'NO REGISTRA'
                    }}
                    {{--{{ $solicitud->detstrgasto--}}
                    {{--? $solicitud->detstrgasto->strGasto->entidadResolucion->representante_legal : 'NO REGISTRA'--}}
                    {{--}}--}}
                    <span style="font-weight: lighter !important; display: block !important;">
                         {{
                            'Gerente ' . (count($solicitud->rubros) > 0 && $solicitud->rubros[0]->detstrgasto
                            ? ($solicitud->rubros[0]->detstrgasto->strGasto->entidadResolucion->representante_encargado !== null
                                ? '(E)'
                                : '')
                            : '')
                        }}
                    </span>
                </div>
            </div>
            <div class="footer_2 border_top">
                <table class="table-collapse">
                    <tbody>
                    <tr>
                        <td class="footer_image">
                            <img src="./images/logo_super.png" alt="logo_super">
                        </td>
                        <td style="text-align: right !important;">
                            <div style="padding-right: 0 !important;">
                                <span style="display: block">Calle 7 No. 19 - 34. Teléfonos: (8) 635 8162 - 635 8163</span>
                                <span style="display: block">635 8232 Ext. 135 Email. capresocaeps@capresoca.com.co</span>
                                <span>Yopal Casanare</span>
                            </div>
                        </td>
                        <td class="text_center" width="12%">
                            {{ '      ' }}
                            <script type="text/php" class="pages">
                              if (isset($pdf)) {
                                $font = $fontMetrics->getFont("Helvetica", "lighter");
                                $pdf->page_text(528, 685, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
                              }
                            </script>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
