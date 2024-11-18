<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CXP</title>
    <link rel="stylesheet" href="./css/cxpPdf.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <div class="main">
        <div class="header">
            <div class="logo">
                <img src="./images/capresoca.jpg" >
            </div>
            <div class="fecha">
                <p>Fecha Actual: {{Date::today()->format('l, j F Y')}}</p>
            </div>
            <div class="celdas_head">
                <div>
                    <table>
                        <tbody>
                        <tr>
                            <td class="label">FECHA CAUSACIÓN:</td>
                            <td class="field field_head">{{ $pg_cxp->fecha_causacion }}</td>
                        </tr>
                        <tr>
                            <td class="label">REVISIÓN No: </td>
                            <td class="field field_head">{{ '0' }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="title">
                    <div>CUENTA POR PAGAR N° </div>
                    <div>
                        <span>{{ str_pad($pg_cxp->consecutivo,6,"0",STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>
            <div class="subheading">
                <span><i>Evolucionamos pensando en Usted</i></span>
                <script type="text/php" class="pages">
                  if (isset($pdf)) {
                    $font = $fontMetrics->getFont("Helvetica", "lighter");
                    $pdf->page_text(528, 115, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
                  }
                </script>
            </div>
        </div>
        <div class="tablas">
            <table class="detail">
                <tbody>
                <tr>
                    <td class="label">Consecutivo: </td>
                    <td class="field" style="text-align: center; width: 20%">{{ $pg_cxp->consecutivo }}</td>
                    <td class="label">Factura: </td>
                    <td class="field" style="text-align: center">{{ $pg_cxp->no_factura }}</td>
                    <td class="label" >Estado: </td>
                    <td class="field" style="text-align: center">{{ $pg_cxp->estado }}</td>
                </tr>
                <tr>
                    <td class="label">Proveedor: </td>
                    <td class="field" colspan="5">{{ $pg_cxp->proveedor ? ($pg_cxp->proveedor->tercero->identificacion_completa . ' - ' . $pg_cxp->proveedor->tercero->nombre_completo) : 'No Registra' }}</td>
                </tr>
                <tr>
                    <td class="label">Tercero: </td>
                    <td class="field" colspan="5">{{ $pg_cxp->tercero ? ($pg_cxp->tercero->identificacion . ' - ' . $pg_cxp->tercero->nombre_completo) : 'No Registra' }}</td>
                </tr>
                <tr>
                    <td class="label">Fecha Factura: </td>
                    <td class="field" style="text-align: center">{{ $pg_cxp->fecha_factura }}</td>
                    <td class="label" >Plazo: </td>
                    <td class="field" style="width: 15%; text-align: center">{{ $pg_cxp->cxp_plazo . ($pg_cxp->cxp_plazo === 1 ? ' Día' : ' Días')  }}</td>
                    <td class="label" style="width: 13.8% !important;">Fecha Vencimiento: </td>
                    <td class="field" style="text-align: center">{{ $pg_cxp->fecha_vencimiento }}</td>
                </tr>
                <tr>
                    <td class="label">Cuenta: </td>
                    <td class="field" colspan="3">{{ $pg_cxp->niif ? ($pg_cxp->niif->codigo . ' - ' . $pg_cxp->niif->nombre) : 'No Registra' }}</td>
                    <td class="label" style="padding: 1px">Valor: </td>
                    <td class="field" style="text-align: right !important; position: fixed; width: 15%; padding-right: 0">{{ $pg_cxp->valor_moneda }}</td>
                </tr>
                <tr>
                    <td class="label">Valor: </td>
                    <td class="field" colspan="5">{{ $pg_cxp->valor_mon }}</td>
                </tr>
                <tr>
                    <td class="label">Observaciones: </td>
                    <td class="field" colspan="5">{{ $pg_cxp->observaciones }}</td>
                </tr>
                </tbody>
            </table>
            <p style="text-align: center; padding-top: 5px">CONCEPTOS</p>
            {{--Tabla 2--}}
            <table class="list">
                <thead>
                <tr class="head">
                    <th class="center">Concepto</th>
                    <th class="center">Cuenta</th>
                    <th class="center">Centro</th>
                    <th class="center">Naturaleza</th>
                    <th class="center">Valor</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pg_cxp->detalles as $detalle)
                    <tr class="list_row">
                        <td colspan="5">

                        </td>
                    </tr>
                    <tr class="list_row">
                        <td class="concepto">
                            <span>{{ $detalle->conpago ? ($detalle->conpago->codigo . ' - ' . $detalle->conpago->nombre) : 'No Registra' }}</span>
                        </td>
                        <td class="descripcion">
                            <span>{{ $detalle->niif ? ($detalle->niif->codigo) : 'No Registra' }}</span>
                        </td>
                        <td>
                            <span >{{ $detalle->cencosto ?  ($detalle->cencosto->codigo) : 'No Registra'  }}</span>
                        </td>
                        <td>
{{--                            <span>{{ $detalle->conpago ? ($detalle->conpago->niif->clascuenta->naturaleza) : 'No Registra' }}</span>--}}
                            <span>{{ $detalle->naturaleza === 1 ? 'Débito' : 'Crédito' }}</span>
                        </td>
                        <td class="derecha">
                        <span>
                            {{$detalle->valor !== null ? '$ '. number_format($detalle->valor, 2, ',', '.') : "$ 0,00"}}
                        </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--<p style="text-align: center; padding-top: 5px">CUOTAS</p>--}}
        </div>
        <div class="firmas">
            <div>
                <p>ALBA LUCY CRUZ PARDO</p>
                <span>Subgerente Administrativo y Financiero</span>
            </div>
            <div>
                <p>NURIA BOHORQUEZ PEÑA</p>
                <span>Gerente</span>
            </div>
        </div>
        <div id="footer">
            <div>
                <span>
                    Nombre reporte : PGRPCXP
                </span>
                <span>{{ 'Usuario : '. 47435472}}</span>
            </div>
        </div>
    </div>
</body>
</html>