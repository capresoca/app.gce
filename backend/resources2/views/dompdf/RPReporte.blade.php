<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>RP</title>
    <link rel="stylesheet" href="./css/reportesPresupuestalesPdf.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<main>
    <div class="header">
        <div style="padding-top: 0 !important;">
            <h3 style="padding: 0 !important; margin-top: 0 !important;">{{ 'REGISTRO PRESUPUESTAL No.' . $prRp->consecutivo }}</h3>
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
        {{--font-size: 1.2em">{{ $prRp->presupuesto_inicial_gasto->entidadResolucion->tercero->nombre_completo }}</div>--}}
        {{--<div style="display: block; font-weight: bolder !important;--}}
        {{--font-size: 1.2em">NIT. 891.856.000-7</div>--}}
    </div>
    <div style="margin-top: 15mm !important;">
        {{--<table class="table-collapse">--}}
            {{--<thead>--}}
            {{--<tr>--}}
                {{--<th  class="basica-text" width="15%">Fecha: </th>--}}
                {{--<th class="basica-text">{{ \Carbon\Carbon::parse($prRp->fecha)->format('F d \d\e\l Y') }}</th>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<th class="basica-text">Dependencia: </th>--}}
                {{--<th class="basica-text">{{ $prRp->dependencia->codigo . ' - ' . $prRp->dependencia->nombre}}</th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
        {{--</table>--}}
        {{--<br>--}}
        <div class="basica-text" style="font-size: 1.2em">El suscrito Jefe de la División de Presupuesto.</div>
    </div>
    <div class="sub-title" style="margin-bottom: 30px">
        <h3 style="padding: 0">
            CERTIFICA</h3>
    </div>
    <div class="body_2" style="margin-bottom: 20px">
         <span class="text_1">
             Que en el Presupuesto General de Rentas y Gastos de {{ $prRp->entidadResolucion->tercero->nombre_completo }}
             del presente Período Fiscal, se efectúa una reserva presupuestal a nombre de: {{$prRp->tercero->nombre_completo . ', ' . $prRp->tercero->identificacion_completa . ' - '. $prRp->tercero->digito_verificacion}}
        </span>
    </div>
    <div class="body_2">
        <table class="table-collapse">
            <tr>
                <td class="basica-text text_left" style="vertical-align: top" width="8%">Objeto:</td>
                <td class="basica-text text_left text_justify" colspan="3">{{ $prRp->observaciones ? $prRp->observaciones : 'Información no registrada.' }}</td>
            </tr>
            <tr>
                <td class="basica-text text_left" style="padding-top: 15px" width="8%">Dependencia:</td>
                <td class="basica-text text_left text_justify" style="padding-top: 15px; padding-left: 2mm" colspan="3">{{ $prRp->tipo === 'Contrato' ? $prRp->minuta->cdp->dependencia->codigo_nombre : ($prRp->tipo === 'No Aplica' ? $prRp->cdp->dependencia->codigo_nombre : 'Información no registrada.') }}</td>
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
            @foreach($prRp->detalles as $detalle)
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
                <td colspan="2" class="text_right basica-text" style="padding-right: 2mm">Total Registro </td>
                <td class="celda basica-text text_right">
                    {{
                    ($prRp->valor_compromiso !== null || $prRp->valor_compromiso !== 0)
                      ? '$'. number_format($prRp->valor_compromiso, 2, ',', '.')
                      : "$ 0,00"}}
                </td>
            </tr>
            </tbody>
        </table>
        <table class="table-collapse">
            <tr>
                <td class="text_left negrilla" style="vertical-align: top" width="20%">No. CDP:</td>
                <td class="negrilla text_left text_justify" colspan="2">{{ $prRp->tipo === 'Contrato' ? $prRp->minuta->cdp->consecutivo : ($prRp->tipo === 'No Aplica' ? $prRp->cdp->consecutivo : 'N/A') }}</td>
            </tr>
            <tr>
                <td class="basica-text text_left" style="vertical-align: top">Tipo de Contrato:</td>
                <td class="basica-text text_left text_justify" colspan="2">{{  $prRp->tipo === 'Contrato' ? $prRp->minuta->tipo : 'No Aplica' }}</td>
            </tr>
            <tr>
                <td class="basica-text text_left" style="vertical-align: top">Contrato No.:</td>
                <td class="basica-text text_left text_justify" colspan="2">{{  $prRp->tipo === 'Contrato' ? $prRp->minuta->numero_contrato : 'No Aplica' }}</td>
            </tr>
        </table>
        <br>
    </div>
    <div class="body_2" style="margin-top: 52mm">
         <span class="text_1">
             Expedida en CASANARE, YOPAL, {{ \Carbon\Carbon::parse($prRp->fecha)->format('d \d\e F \d\e\l \añ\o Y') }}
        </span>
    </div>
    <div style="width: 100%; text-align: center; margin-top: 50px;">
        <h3 style="width: 50%; margin: 0 25% 0 25%; border-top: solid gray 1px;">TECNICO ADTVO DE PRESUPUESTO (e)</h3>
        {{--<p>Recepción Cuentas Medicas</p>--}}
    </div>
</main>
</body>
</html>