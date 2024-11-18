<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>COMPROBANTE DE EGRESO</title>
    <link rel="stylesheet" href="./css/comEgresoPdf.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <div class="main">
        <div class="header">
            <div class="logo">
                <img src="./images/capresoca.jpg" >
            </div>
            <div class="fecha">
                <p>Fecha Impresión: {{Date::today()->format('l, j F Y')}}</p>
            </div>
            <div class="celdas_head">
                <div>
                    <table>
                        <tbody>
                        <tr>
                            <td class="label">FECHA:</td>
                            <td class="field field_head">{{ \Carbon\Carbon::parse($comprobante->fecha)->format('Y/m/d') }}</td>
                        </tr>
                        <tr>
                            <td class="label">ESTADO: </td>
                            <td class="field field_head">{{ $comprobante->estado }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="title">
                    <div>COMP. DE EGRESO N° </div>
                    <div>
                        <span>{{ str_pad($comprobante->consecutivo,8,"0",STR_PAD_LEFT) }}</span>
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
            <table class="table-collapse">
                <thead>
                <tr>
                    <th>

                    </th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</body>
</html>