<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ "INFORME AUDITORÍA CUENTAS MÉDICAS" }}</title>
    <link rel="stylesheet" href="./css/general.css">
</head>
<body>
    <header>
        <div class="logos">
            <img src="./images/capresoca.jpg" >
        </div>
        <div class="titulo">
            <h3>INFORME DE CUENTAS MÉDICAS SOSALUD</h3>
        </div>
        <div class="legal">
            <p>CODIGO  RE-ASS-08</p>
            <p>CTRD  200.01.11</p>
            <p>VIGENCIA </p>
            <p>
                <script type="text/php">
                        if (isset($pdf)) {
                        $font = $fontMetrics->getFont("Helvetica", "lighter", "Arial");
                        $pdf->page_text(534, 755, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 8, array(0, 0, 0));
                        }
                </script>
            </p>
        </div>
    </header>
    <footer>
        <section style="width: 100%; margin-top: 1.42cm !important; padding: 0 !important">
            <h4>{{'Impreso: ' . \Jenssegers\Date\Date::today()->format('l, j F Y') . ' por ' . $user}}</h4>
        </section>
    </footer>
    <main>
        <section class="tabla-1">
            <table class="table-collapse">
                <thead>
                <tr>
                    <th class="celda"
                        colspan="4"
                        style="background-color: #EFF1F1 !important;
                        height: 15px !important; vertical-align: middle;
                        text-align: center !important;">
                        INFORMACIÓN RADICADO
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="celda negrilla" style="vertical-align: top !important;">Entidad:</td>
                    <td class="celda" colspan="3" style="padding-left: 10px !important;">{!! $cuentas['entidad'] !!}</td>
                </tr>
                <tr>
                    <td class="celda negrilla" colspan="4" style="background-color: #EFF1F1 !important; vertical-align: top !important;">Radicados:</td>
                </tr>
                <tr>
                    <td class="celda negrilla" style="vertical-align: top !important;">No. Rip:</td>
                    <td class="celda" style="text-align: right; float: right">{!! $cuentas['radicado_rip'] !!}</td>
                    <td class="celda negrilla" style="width: 20% !important;">No. Cuenta:</td>
                    <td class="celda" style="text-align: right; float: right">
                        {{str_pad($cuentas['consecutivo'],6,"0",STR_PAD_LEFT)}}
                    </td>
                </tr>
                <tr>
                    <td class="celda negrilla" style="width: 20% !important;">Fecha Rip:</td>
                    <td class="celda" style="text-align: right; float: right">{{\Jenssegers\Date\Date::parse($cuentas['fecha_rip'])->format('Y-m-d')}}
                    </td> <td class="celda negrilla" style="width: 20% !important;">Fecha Cuenta:</td>
                    <td class="celda" style="text-align: right; float: right">{{$cuentas['fecha_radicado']}}</td>
                </tr>
                <tr>
                    <td class="celda negrilla" style="width: 20% !important;">Periodo Inicio:</td>
                    <td class="celda" style="text-align: right; float: right">{{$cuentas['periodo_inicio']}}</td>
                    <td class="celda negrilla" style="width: 20% !important;">Periodo Fin:</td>
                    <td class="celda" style="text-align: right; float: right">{{$cuentas['periodo_fin']}}</td>
                </tr>
                <tr>
                    <td class="celda negrilla" style="width: 20% !important;">No. Facturas:</td>
                    <td class="celda" style="text-align: right; float: right">{{$cuentas['numero_facturas']}}</td>
                    <td class="celda negrilla" style="width: 20% !important;">No. Contrato:</td>
                    <td class="celda" style="text-align: right; float: right">{{$cuentas['numero_contrato']}}</td>
                </tr>
                <tr>
                    <td class="celda negrilla" style="width: 20% !important;">Tipo Facturación:</td>
                    <td class="celda" style="text-align: right; float: right">{{$cuentas['tipo_facturacion']}}</td>
                    <td class="celda negrilla" style="width: 20% !important;">Plan Beneficio:</td>
                    <td class="celda" style="text-align: right; float: right">{{$cuentas['plan_beneficio']}}</td>
                </tr>
                </tbody>
            </table>
        </section>
        <section class="tabla-2">
            <table class="table-collapse">
                <thead>
                <tr>
                    <th class="celda"
                        colspan="4"
                        style="background-color: #EFF1F1 !important;
                        height: 15px !important; vertical-align: middle;
                        text-align: center !important;">
                        VALOR
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="celda negrilla" style="width: 20%">Cuenta:</td>
                    <td class="celda" colspan="3" style="text-align: right; float: right">
                        {{'$'.number_format($cuentas['valor_cuenta'],2,',','.')}}
                    </td>
                </tr>
                <tr>
                    <td class="celda negrilla">Descuentos:</td>
                    <td class="celda" style="text-align: right; float: right">
                        {{'$'.number_format($cuentas['descuentos'],2,',','.')}}</td>
                    <td class="celda negrilla" width="20%">Copagos:</td>
                    <td class="celda" style="text-align: right; float: right;">
                        {{'$'.number_format($cuentas['copagos'],2,',','.')}}</td>
                </tr>
                <tr>
                    <td class="celda negrilla">Glosas:</td>
                    <td class="celda" style="text-align: right; float: right">
                        {{'$'.($cuentas['glosas'] !== null ? (number_format($cuentas['glosas'],2,',','.')) : '0.0')}}</td>
                    <td class="celda negrilla">Avalado:</td>
                    <td class="celda" style="text-align: right; float: right">
                        {{'$'.($cuentas['valor_avalado'] !== null ? (number_format($cuentas['valor_avalado'],2,',','.')) : '0.0')}}</td>
                </tr>
                </tbody>
            </table>
        </section>
        <section class="tabla-3">
            <table>
                <thead>
                <tr>
                    <th class="celda"
                        colspan="8"
                        style="background-color: #EFF1F1 !important;
                        height: 15px !important; vertical-align: middle;
                        text-align: center !important;">
                         {{ 'FACTURAS DE LA CUENTA N° ' . str_pad($cuentas['consecutivo'],6,"0",STR_PAD_LEFT) }}
                    </th>
                </tr>
                <tr>
                    <th class="celda text_center">ID</th>
                    <th class="celda text_center"># FACTURA</th>
                    <th class="celda text_center">IDENTIFICACIÓN</th>
                    <th class="celda text_center">NOMBRES</th>
                    <th class="celda text_center">V. FACTURA</th>
                    <th class="celda text_center">V. COPAGO</th>
                    <th class="celda text_center">V. GLOSA</th>
                    <th class="celda text_center">V. PAGAR</th>
                </tr>
                </thead>
                <tbody>
                @foreach($facturas as $key => $factura)
                    <tr>
                        <td class="celda">{{$factura['id_factura']}}</td>
                        <td class="celda">{{$factura['numero_factura']}}</td>
                        <td class="celda">{{$factura['identificacion']}}</td>
                        <td class="celda">{{($factura['apellidos'] . ' ' . $factura['nombres'])}}</td>
                        <td class="celda" style="text-align: right; float: right;">{{'$'.number_format($factura['valor_factura'],2,',','.')}}</td>
                        <td class="celda" style="text-align: right; float: right;">{{'$'.number_format($factura['valor_copago'],2,',','.')}}</td>
                        <td class="celda" style="text-align: right; float: right;">
                            {{'$'.($factura['valor_glosa'] !== null ? (number_format($factura['valor_glosa'],2,',','.')) : '0.0')}}</td>
                        <td class="celda" style="text-align: right; float: right;">
                            {{'$'.($factura['valor_pagar'] !== null ? (number_format($factura['valor_pagar'],2,',','.')) : '0.0')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>
        <br><br><br>
        <section style="width: 100%; text-align: center; margin-top: 100px;">
            <h4 style="width: 50%; margin: 0 25% 0 25%; border-top: solid gray 1px;">SOSALUD S.A.S</h4>
            <p>AUDITOR RESPONSABLE</p>
        </section>
    </main>
</body>
</html>