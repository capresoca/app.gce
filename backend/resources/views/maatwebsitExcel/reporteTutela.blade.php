<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INDICADOR TUTELAS</title>
</head>
<style>
    body{
        font-family: 'Helvetica', sans-serif;
    }
    thead{
        background: rgb(146,208,80) ;
    }
    th{
        height: 50px;
        font-weight: bold;
    }
</style>
<body>
<div>
    <table>
        <thead style="background: #92D050 ;">
        <tr>
            <th>REGIMEN</th>
            <th>TIPO DE DOCUMENTO</th>
            <th>NUMERO DE DOCUMENTO</th>
            <th>FECHA DE NACIMIENTO</th>
            <th>PRIMER APELLIDO</th>
            <th>SEGUNDO APELLIDO</th>
            <th>PRIMER NOMBRE</th>
            <th>SEGUNDO NOMBRE</th>
            <th width="20mm">REPRESENTANTE O AGENTE OFICIOSO</th>
            <th>FECHA AFILIACIÓN</th>
            <th>TIPO DE AFILIADO</th>
            <th>NIVEL DEL SISBEN</th>
            <th>CÓDIGO MUNICIPIO DE RESIDENCIA DEL AFILIADO</th>
            <th>ENTIDADES ACCIONADAS</th>
            <th width="20mm">PRETENSIÓN</th>
            <th>DESCRIPCIÓN PRETENSIÓN</th>
            <th>MEDIDA PROVISIONAL</th>
            <th>JUZGADO DE CONOCIMIENTO</th>
            <th>DEPARTAMENTO</th>
            <th>NUMERO RADICADO DE TUTELA</th>
            <th>FECHA NOTIFICACIÓN DE LA TUTELA</th>
            <th>FECHA DE CONTESTACIÓN ACCIÓN DE TUTELA</th>
            <th>OPORTUNIDAD EN LA CONTESTACIÓN</th>
            <th>TIPO TUTELA / CLASIFICACIÓN TUTELA</th>
            <th width="20mm">CONTESTACIÓN</th>
            <th width="20mm">SENTIDO DEL FALLO DE PRIMERA INSTANCIA</th>
            <th>TUTELA CON FALLO</th>
            <th>FALLO INTEGRAL</th>
            <th>INSTANCIA</th><!--  agregado -->
            <th>FECHA DE NOTIFICACION DEL FALLO DE TUTELA PRIMERA INSTANCIA</th>
            <th>RECURSO DE IMPUGNACIÓN</th>
            <th>CODIGO DE DIAGNOSTICO</th>
            <th>CODIGO DEL SERVICIO AUTORIZADO</th>
            <th>CODIGO DE LA PRESTACIÓN</th>
            <th>DESACATO</th>
            <th>FECHA DE DESACATO DE TUTELA</th>
            <th>FECHA DE PRESTACION DEL SERVICIO POSTERIOR AL DESACATO DE TUTELA</th>
            <th width="20mm">OBSERVACIONES GENERALES</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tutelas as $tutela)
            <tr>
                @if($tutela->afiliados_tutelas->count())
                    <td>{{ $tutela->afiliados_tutelas[0]->afiliado ? ($tutela->afiliados_tutelas[0]->afiliado->as_regimene_id === 2 ? 'SUBSIDIADO' : 'CONTRIBUTIVO') : 'N/A'  }}</td>
                    {{--<td>{{ $tutela->afiliado ? ($tutela->afiliado->as_regimene_id === 2 ? 'SUBSIDIADO' : 'CONTRIBUTIVO') : 'N/A'  }}</td>--}}
                    <td>{{ $tutela->afiliados_tutelas[0]->tipo_id ? $tutela->afiliados_tutelas[0]->tipo_id->abreviatura : 'N/A' }}</td>
                    <td>{{ $tutela->afiliados_tutelas[0]->identificacion ? $tutela->afiliados_tutelas[0]->identificacion : 'N/A'  }}</td>
                    <td>{{ $tutela->afiliados_tutelas[0]->afiliado ? ($tutela->afiliados_tutelas[0]->afiliado->fecha_nacimiento ? $tutela->afiliados_tutelas[0]->afiliado->fecha_nacimiento_slash : 'N/A') : 'N/A' }}</td>
                    <td>{{ $tutela->afiliados_tutelas[0]->afiliado
                    ? ($tutela->afiliados_tutelas[0]->afiliado->apellido1 ? $tutela->afiliados_tutelas[0]->afiliado->apellido1 : 'N/A')
                    : ($tutela->afiliados_tutelas[0]->apellido1 ? $tutela->afiliados_tutelas[0]->apellido1 : 'N/A') }}</td>
                    <td>{{ $tutela->afiliados_tutelas[0]->afiliado
                    ? ($tutela->afiliados_tutelas[0]->afiliado->apellido2 ? $tutela->afiliados_tutelas[0]->afiliado->apellido2 : 'N/A')
                    : ($tutela->afiliados_tutelas[0]->apellido2 ? $tutela->afiliados_tutelas[0]->apellido2 : 'N/A') }}</td>
                    <td>{{ $tutela->afiliados_tutelas[0]->afiliado
                    ? ($tutela->afiliados_tutelas[0]->afiliado->nombre1 ? $tutela->afiliados_tutelas[0]->afiliado->nombre1 : 'N/A')
                    : ($tutela->afiliados_tutelas[0]->nombre1 ? $tutela->afiliados_tutelas[0]->nombre1 : 'N/A') }}</td>
                    <td>{{ $tutela->afiliados_tutelas[0]->afiliado
                    ? ($tutela->afiliados_tutelas[0]->afiliado->nombre2 ? $tutela->afiliados_tutelas[0]->afiliado->nombre2 : 'N/A')
                    : ($tutela->afiliados_tutelas[0]->nombre2 ? $tutela->afiliados_tutelas[0]->nombre2 : 'N/A') }}</td>
                    {{--<td>{{ $tutela->afiliado ? ($tutela->afiliado->apellido2 ? $tutela->afiliado->apellido2 : 'N/A') : 'N/A' }}</td>--}}
{{--                    <td>{{ $tutela->afiliado ? ($tutela->afiliado->apellido2 ? $tutela->afiliado->nombre1 : 'N/A') : 'N/A' }}</td>--}}
{{--                    <td>{{ $tutela->afiliado ? ($tutela->afiliado->apellido2 ? $tutela->afiliado->nombre2 : 'N/A') : 'N/A' }}</td>--}}
                    <td>{{ $tutela->nombre ? $tutela->nombre : 'N/A' }}</td>
                    <td>{{ $tutela->afiliados_tutelas[0]->afiliado ? ($tutela->afiliados_tutelas[0]->afiliado->fecha_afiliacion ? $tutela->afiliados_tutelas[0]->afiliado->fecha_afiliacion_slash : 'N/A') : 'N/A' }}</td>
                    <td>{{ $tutela->afiliados_tutelas[0]->afiliado ? ($tutela->afiliados_tutelas[0]->afiliado->tipo_afiliado ? $tutela->afiliados_tutelas[0]->afiliado->tipo_afiliado->nombre : 'N/A') : 'N/A' }}</td>
                    <td>{{ $tutela->afiliados_tutelas[0]->afiliado ? ($tutela->afiliados_tutelas[0]->afiliado->nivel_sisben ? $tutela->afiliados_tutelas[0]->afiliado->nivel_sisben : 'N/A') : 'N/A' }}</td>
                    <td>{{ $tutela->afiliados_tutelas[0]->afiliado ? ($tutela->afiliados_tutelas[0]->afiliado->municipio ? $tutela->afiliados_tutelas[0]->afiliado->municipio->codigo : 'N/A') : 'N/A' }}</td>
                @endif
                <td>{{ $tutela->ent_accionadas ? $tutela->ent_accionadas : 'N/A' }}</td>
                <td>{{ $tutela->pretencion ? $tutela->pretencion->pretencion : 'N/A' }}</td>
                <td>{{ $tutela->desc_pretencion ? $tutela->desc_pretencion : 'N/A' }}</td>
                <td>{{ $tutela->med_provisional ? $tutela->med_provisional : 'N/A' }}</td>
                <td>{{ $tutela->juzgado ? $tutela->juzgado->nombre : 'N/A' }}</td>
                <td>{{ $tutela->departamento ? $tutela->departamento->nombre : 'N/A' }}</td>
                <td>{{ $tutela->no_tutela ? $tutela->no_tutela : 'N/A' }}</td>
                <td>{{ $tutela->fecha_notificacion ? \Carbon\Carbon::parse($tutela->fecha_notificacion)->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $tutela->last_contestacion ? \Carbon\Carbon::parse($tutela->last_contestacion->fecha)->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $tutela->dias_opotunidad ? $tutela->dias_opotunidad : '0' }}</td>
                <td>{{ $tutela->tipo_tutela ? $tutela->tipo_tutela : 'N/A' }}</td>
                <td>{{ $tutela->last_contestacion ? $tutela->last_contestacion->contestacion : 'N/A' }}</td>
                <td>{{ $tutela->last_fallo ? $tutela->last_fallo->desc_fallo : 'N/A' }}</td>
                <td>{{ $tutela->last_fallo ? $tutela->last_fallo->tipo_fallo : 'N/A' }}</td>
                <td>{{ $tutela->last_fallo ? $tutela->last_fallo->fallo_integral : 'N/A' }}</td>
                <td>{{ $tutela->last_fallo ? $tutela->last_fallo->instancia : 'N/A' }}</td><!--  agregado -->
                <td>{{ $tutela->last_fallo ? \Carbon\Carbon::parse($tutela->last_fallo->fecha)->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $tutela->last_impugnacion ? 'Si' : 'No'}}</td>
                @if($tutela->codDiagnostico->count())
                    <td>{{ $tutela->codDiagnostico[0]->cie10_principal_id }}</td>
                    @foreach($detalles as $detalle)
                    @if($detalle-> au_autorizacion_id == $tutela->codDiagnostico[0]-> id)
                    <td>{{ $detalle-> rs_cups_id ? $detalle-> rs_cups_id : 'N-A' }}</td>
                    <td>{{ $detalle-> rs_cum_id ? $detalle-> rs_cum_id : 'N-A' }} / {{ $detalle-> rs_cups_id ? $detalle-> rs_cups_id : 'N-A' }} / {{ $detalle-> rs_otros_id ? $detalle-> rs_otros_id : 'N-A' }}</td>
                    @endif
                    @endforeach
                @else 
                    <td>{{'N/A'}}</td>
                    <td>{{'N/A'}}</td>
                    <td>{{'N/A'}}</td>
                @endif
                
                <td>{{ $tutela->last_desacato ? 'Si' : 'No' }}</td>
                <td>{{ $tutela->last_desacato ? \Carbon\Carbon::parse($tutela->last_desacato->fecha)->format('d/m/Y') : 'No' }}</td>
                <td>{{ 'NA' }}</td>
                <td>{{ 'NA' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
