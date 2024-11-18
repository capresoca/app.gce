<div class="carnetizacion">

    <div style="width: 100%; border-bottom: solid 2px darkslategray">
        <div style="width: 20%; text-align: left; display: inline-block">
            <img style="width: 110px; margin: 30px 0 0 3px" src="./images/capresoca.jpg">
            <p style="padding: 0; margin-left: 8px"> NIT. 891.856.000-7</p>
        </div>
        <div style="display: inline-block; width: 58%; text-align: center; font-weight: bold">
            <p>SISTEMA GENERAL DE SEGURIDAD SOCIAL EN SALUD</p>
            <p>REGIMEN {{strtoupper($afitramite->afiliado->regimen->nombre)}}</p>
            <p>SEGUIMIENTO DE CARNETIZACIÓN</p>
        </div>
        <div style="display: inline-block; width: 20%; font-size: 10px; text-align: right; margin-top: -30px">
            <p>CODIGO  RE-ASS-08</p>
            <p>CTRD  200.01.11</p>
            <p>VERSION  1.0</p>
            <p>VIGENCIA 2010</p>
        </div>

    </div>
    <br>
    <br>
    <br>
    <div class="info-afiliado">
        <p style="width: 80px">Fecha Entrega:</p><p style="width: 170px">{{ isset($afitramite->afitramite->tramite->fecha_radicacion) ? (strftime('%d/%m/%Y', strtotime($afitramite->afitramite->tramite->fecha_radicacion))) : ''}}</p>
        <p style="width: 100px">Barrio:</p><p style="width: 150px">{{$afitramite->afiliado->barrio != null ? $afitramite->afiliado->barrio->nombre : 'NR'}}</p>
        <p style="width: 80px">Contrato:</p><p style="width: 70px">NR</p>
    </div>
    <div class="info-afiliado">
        <p style="width: 80px">Direccion:</p><p style="width: 170px">{{$afitramite->afiliado->direccion}}</p>
        <p style="width: 100px">Ciudad: </p><p style="width: 150px">{{ucwords(strtolower($afitramite->afiliado->municipio->nombre)).' - '.ucfirst(strtolower($afitramite->afiliado->municipio->departamento->nombre))}}</p>
        <p style="width: 80px">Telefono:</p><p style="width: 70px">{{$afitramite->afiliado->celular}}</p>
    </div>
    <div class="info-afiliado">
        <p style="width: 80px">Ficha Sisben:</p><p style="width: 170px">{{$afitramite->afiliado->ficha_sisben}}</p>
        <p style="width: 100px">Puntaje Sisben: </p><p style="width: 150px">{{$afitramite->afiliado->puntaje_sisben}}</p>
        <p style="width: 80px">{{$afitramite->afiliado->as_regimene_id == 1 ? 'Rango:' : 'Sisben' }}</p><p style="width: 70px">{{$afitramite->afiliado->as_regimene_id == 1 ? $afitramite->afiliado->rango() : 'Nivel '.$afitramite->afiliado->nivel_sisben}}</p>
    </div>
    <br>
    <br>
    <div style="width: 100%; text-align: center">
        <h3>INFORMACION FAMILIAR INTEGRANTES DEL NUCLEO</h3>
    </div>
    <br>
    <table>
        <tr>
            <th>No Carnet</th>
            <th>Documento</th>
            <th>Primer Apellido</th>
            <th>Segundo Apellido</th>
            <th>Primer Nombre</th>
            <th>Segundo Nombre</th>
            <th>Nacimiento</th>
            <th>Parentesco</th>
        </tr>
        <tr>
            <td></td>
            <td>{{$afitramite->afiliado->tipo_identificacion.' '.$afitramite->afiliado->identificacion}}</td>
            <td>{{$afitramite->afiliado->apellido1}}</td>
            <td>{{$afitramite->afiliado->apellido2}}</td>
            <td>{{$afitramite->afiliado->nombre1}}</td>
            <td>{{$afitramite->afiliado->nombre2}}</td>
            <td>{{strftime('%d/%m/%Y', strtotime($afitramite->afiliado->fecha_nacimiento))}}</td>
            <td>
                @if($afitramite->afiliado->as_regimene_id == 1)
                    Cotizante
                @else
                    Cabeza de Familia
                @endif
            </td>
        </tr>
        @foreach($afitramite->beneficiarios as $beneficiario)
            <tr>
                <td></td>
                <td>{{$beneficiario->tipo_identificacion.' '.$beneficiario->identificacion}}</td>
                <td>{{$beneficiario->apellido1}}</td>
                <td>{{$beneficiario->apellido2}}</td>
                <td>{{$beneficiario->nombre1}}</td>
                <td>{{$beneficiario->nombre2}}</td>
                <td>{{strftime('%d/%m/%Y', strtotime($beneficiario->fecha_nacimiento))}}</td>
                <td>{{$beneficiario->parentesco ? $beneficiario->parentesco->nombre : 'NR'}}(a)</td>
            </tr>
        @endforeach
    </table>
    <br>
    <fieldset style="width: 100%;padding-top: 100px">
        <legend><h3 style="padding: 0; margin: 0">Quien Recibe: </h3></legend>
        <div style="display: inline-block; width: 25%; text-align: center; padding: 0 2% 0 2%">
            <p style="width: 100%; border-top: solid 1px darkslategrey">Nombres y Apellidos</p>
        </div>
        <div style="display: inline-block; width: 25%; text-align: center; padding: 0 2% 0 2%">
            <p style="width: 100%; border-top: solid 1px darkslategrey">Firma</p>
        </div>
        <div style="display: inline-block; width: 25%; text-align: center ; padding: 0 2% 0 2%">
            <p style="width: 100%; border-top: solid 1px darkslategrey">Identificación</p>
        </div>
        <div style="display: inline-block; width: 10%; text-align: center; padding: 0 2% 0 1%">
            <div style="margin-top: -86px;" class="huella">
                <p style="margin-top: 68px; font-weight: lighter">Huella</p>
            </div>
        </div>
    </fieldset>
    <br>
    <br>
    <fieldset style="width: 100%;padding-top: 100px">
        <legend><h3 style="padding: 0; margin: 0">Quien Entrega: </h3></legend>
        <div style="display: inline-block; width: 25%; text-align: center; padding: 0 2% 0 2%">
            <p style="width: 100%; border-top: solid 1px darkslategrey">Nombres y Apellidos</p>
        </div>
        <div style="display: inline-block; width: 25%; text-align: center; padding: 0 2% 0 2%">
            <p style="width: 100%; border-top: solid 1px darkslategrey">Firma</p>
        </div>
        <div style="display: inline-block; width: 25%; text-align: center ; padding: 0 2% 0 2%">
            <p style="width: 100%; border-top: solid 1px darkslategrey">Identificación</p>
        </div>
        <div style="display: inline-block; width: 10%; text-align: center; padding: 0 2% 0 1%">
            <div style="margin-top: -86px;" class="huella">
                <p style="margin-top: 68px; font-weight: lighter ">Huella</p>
            </div>
        </div>
    </fieldset>
    <br>
    <br>

    <div style="width: 100%">
        <fieldset style="width: 100%; height: 150px;">
            <legend><h3 style="padding: 0; margin: 0">Observaciones Generales</h3></legend>
        </fieldset>
    </div>


    </div>
