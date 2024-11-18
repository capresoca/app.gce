<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>OBLIGACIÓN PRESUPUESTAL</title>
    <link rel="stylesheet" href="./css/reportesPresupuestalesPdf.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<main>
    <div class="header">
        <div style="padding-top: 0 !important;">
            <h3 style="padding: 0 !important; margin-top: 0 !important;">{{ 'OBLIGACIÓN PRESUPUESTAL No.' . $prObligacion->consecutivo }}</h3>
        </div>
        <br>
        <div class="logo">
            <img src="./images/capresoca.jpg" >
        </div>
        <div style="display: block; font-weight: bolder !important;
                        font-size: 1.2em">NIT. 891.856.000-7</div>
        <br>
        <br>
        {{--<div style="display: block; font-weight: bolder !important;--}}
        {{--font-size: 1.2em">{{ $prObligacion->presupuesto_inicial_gasto->entidadResolucion->tercero->nombre_completo }}</div>--}}
        {{--<div style="display: block; font-weight: bolder !important;--}}
        {{--font-size: 1.2em">NIT. 891.856.000-7</div>--}}
    </div>
    <div style="margin-top: 15mm !important;"></div>
    <div class="body_2">
        <table class="table-collapse">
            <tr>
                <td class="basica-text text_left" style="vertical-align: top" width="8%">Dependencia:</td>
                <td class="basica-text text_left text_justify" style="padding-left: 2mm" colspan="3">{{ $prObligacion->detalles[0]->cdp->dependencia->codigo_nombre }}</td>
            </tr>
            <tr>
                <td class="basica-text text_left" style="vertical-align: top" width="8%">Tercero:</td>
                <td class="basica-text text_left text_justify" colspan="3">{{ $prObligacion->tercero ?
                $prObligacion->tercero->nombre_completo . '   -   ' . $prObligacion->tercero->identificacion_completa . ' - '. $prObligacion->tercero->digito_verificacion : 'Información no registrada.' }}</td>
            </tr>
            <tr>
                <td class="basica-text text_left" style="vertical-align: top" width="8%">Concepto:</td>
                <td class="basica-text text_left text_justify" colspan="3">{{ $prObligacion->observaciones ? $prObligacion->observaciones : 'Información no registrada.' }}</td>
            </tr>
            <tr>
                <td class="basica-text text_left" width="8%">Ordenador:</td>
                <td class="basica-text text_left text_justify" colspan="3">{{ $prObligacion->entidadResolucion->representante_legal }}</td>
            </tr>
        </table>
    </div>
    <div class="sub-title" style="margin-bottom: 20px">
        <h3 style="padding: 0">
            IMPUTACIÓN PRESUPUESTAL</h3>
    </div>
    <div class="tabla-1">
        <table class="table-collapse">
            <thead>
            <tr>
                <th class="celda text_center color_grey" width="18%">CÓDIGO</th>
                <th class="celda text_center color_grey">DESCRIPCIÓN</th>
                <th class="celda text_center color_grey" width="20%">VALOR</th>
            </tr>
            </thead>
            <tbody>
            @foreach($prObligacion->detalles as $detalle)
                <tr>
                    <td class="celda basica-text">{{ $detalle->rubro->codigo }}</td>
                    <td class="celda basica-text">{{ $detalle->rubro->nombre }}</td>
                    <td class="celda basica-text text_right">
                        {{ ($detalle->valor !== null || $detalle->valor !== 0)
                          ? '$'. number_format($detalle->valor, 2, ',', '.')
                          : "$ 0,00"}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text_right basica-text" style="padding-right: 2mm">Total Obligación </td>
                <td class="celda basica-text text_right">
                    {{
                    ($prObligacion->valor_obligacion !== null || $prObligacion->valor_obligacion !== 0)
                      ? '$'. number_format($prObligacion->valor_obligacion, 2, ',', '.')
                      : "$ 0,00"}}
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <table class="table-collapse">
            <tr>
                <td class="text_left negrilla" style="vertical-align: top" width="20%">No. Registro:</td>
                <td class="negrilla text_left text_justify" colspan="2">{{ $prObligacion->detalles[0]->rp->consecutivo }}</td>
            </tr>
            {{--<tr>--}}
                {{--<td class="basica-text text_left" style="vertical-align: top">Tipo de Contrato:</td>--}}
                {{--<td class="basica-text text_left text_justify" colspan="2">{{  $prObligacion->tipo === 'Contrato' ? $prObligacion->minuta->tipo : 'No Aplica' }}</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td class="basica-text text_left" style="vertical-align: top">Contrato No.:</td>--}}
                {{--<td class="basica-text text_left text_justify" colspan="2">{{  $prObligacion->tipo === 'Contrato' ? $prObligacion->minuta->numero_contrato : 'No Aplica' }}</td>--}}
            {{--</tr>--}}
        </table>
        <br>
    </div>
    <div class="body_2" style="margin-top: 99mm">
         <span class="text_1">
             Expedida en CASANARE, YOPAL, {{ \Carbon\Carbon::parse($prObligacion->fecha)->format('d \d\e F \d\e\l \añ\o Y') }}
        </span>
    </div>
    {{--<div style="width: 100%; text-align: center; margin-top: 50px;">--}}
        {{--<h3 style="width: 50%; margin: 0 25% 0 25%; border-top: solid gray 1px;">TECNICO ADTVO DE PRESUPUESTO (e)</h3>--}}
        {{--<p>Recepción Cuentas Medicas</p>--}}
    {{--</div>--}}
</main>
</body>
</html>