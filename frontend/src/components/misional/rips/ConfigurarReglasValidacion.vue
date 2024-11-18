<template>
  <div class="container">
    <v-container class="pl-0 pr-0">
      <v-layout row wrap>
        <v-flex xs12>
          <v-toolbar class="elevation-1 toolbar--dense">
            <v-icon>build</v-icon>
            <v-toolbar-title class="text-uppercase">Configuración de Reglas de Validación </v-toolbar-title>
          </v-toolbar>
          <!--<h3 class="display-1"></h3>-->
        </v-flex>
      </v-layout>
    </v-container >
    <v-expansion-panel>
      <v-expansion-panel-content>
        <div slot="header">Archivo de control "CT"</div>
        <v-card flat icon>
          <v-card-text>
            <v-container>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ct.codigo"
                    label="Codigo del prestador de servicios de salud"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ct.codigo)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ct.fecha"
                    label="Fecha de radicacion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ct.fecha)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ct.nombreArchivo"
                    label="Nombre de los archivos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ct.nombreArchivo)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ct.esEntero"
                    label="Numero de registros entero"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ct.esEntero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
      <v-expansion-panel-content>
        <div slot="header">Archivo de transacciones "AF"</div>
        <v-card flat icon>
          <v-card-text>
            <v-container>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.codigoPrestadorCt"
                    label="Codigo del prestador de servicios de salud igual al codigo en archivo de control."
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.codigoPrestadorCt)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.tipoIdentificacionPrestador"
                    label="Tipo de identificacion del prestador de servicios de salud."
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.tipoIdentificacionPrestador)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.existePrestadorServicioSalud"
                    label="Codigo del prestador de servicio de salud existe en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.existePrestadorServicioSalud)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.identificacionPrestador"
                    label="Identificacion del prestador de servicios de salud"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.identificacionPrestador)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.mismaFacturaCaptiacion"
                    label="Capitacion: Misma factura en todos los registros"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.mismaFacturaCaptiacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.fechaExpedicion"
                    label="Fecha de expedicion de la factura menor que la fecha actual"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.fechaExpedicion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.fechaInicio"
                    label="Fecha de inicio del periodo facturado menor que la fecha actual"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.fechaInicio)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.FechaInicioMenorQueFechafinal"
                    label="Fecha inicial del periodo facturado menor que fecha final del periodo facturado"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.FechaInicioMenorQueFechafinal)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.fechaFinal"
                    label="Fecha final del periodo facturado menor que la fecha actual"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.fechaFinal)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.fechaFinalMayorQueFechaInicial"
                    label="Fecha final del periodo facturado mayor que la fecha inicial del periodo facturado"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.fechaFinalMayorQueFechaInicial)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.codigoEntidadadministradora"
                    label="Codigo de la entidad administradora"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.codigoEntidadadministradora)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.numeroPositivo"
                    label="Valor neto a pagar por la entidad contratante siempre positivo"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.numeroPositivo)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.cruceFacturas"
                    label="Validar cruce de facturas del archivo de transacciones con los demas archivos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.cruceFacturas)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.af.sumaValoresFacturas"
                    label="Suma de valores de los archivos AC+AM+AP+AT igual a la suma del archivo AF"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.af.sumaValoresFacturas)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
      <v-expansion-panel-content>
        <div slot="header">Archivo de usuarios "US"</div>
        <v-card flat icon>
          <v-card-text>
            <v-container>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.us.rangosTipoEdad"
                    label="Unidad de medida de la edad entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.us.rangosTipoEdad)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.us.edad"
                    label="Edad entre el rango de la unidad de medida de la edad"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.us.edad)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.us.tipoIdentificacionUsuario"
                    label="Tipo de identificacion del usuario existe entre rango de valores permitidos, Tipo de identificacion del usuario es valido con la edad"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.us.tipoIdentificacionUsuario)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.us.longitudIdentificacion"
                    label="Longitud de identificacion del usuario es valido con el tipo de identificacion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.us.longitudIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.us.tipoUsuario"
                    label="Tipo de usuario existe entre rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.us.tipoUsuario)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.us.rangoGenero"
                    label="Genero existe entre rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.us.rangoGenero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.us.codigoDepartamento"
                    label="Codigo de departamento existe en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.us.codigoDepartamento)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.us.codigoMunicipio"
                    label="Codigo del municipio existe en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.us.codigoMunicipio)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.us.zona"
                    label="Zona existe entre rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.us.zona)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
      <v-expansion-panel-content>
        <div slot="header">Archivo de consulta "AC"</div>
        <v-card flat icon>
          <v-card-text>
            <v-container>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.numeroFacturaEnAf"
                    label="Numero de factura existe en el archivo de transacciones"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.numeroFacturaEnAf)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.codigoPrestadorCt"
                    label="Codigo del prestador de servicios de salud igual al codigo en archivo de control"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.codigoPrestadorCt)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.rangosTipoIdentificacion"
                    label="Tipo de identificacion dentro del rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.rangosTipoIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.longitudIdentificacion"
                    label="Longitud de identificacion del usuario es valido con el tipo de identificacion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.longitudIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.fechaConsulta"
                    label="Fecha de consulta menor que la fecha actual"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.fechaConsulta)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.numeroAutorizacion"
                    label="Numero de autorizacion existe en el sistema"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.numeroAutorizacion)" flat small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.existeCup"
                    label="Codigo del procedimiento CUP existe en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.existeCup)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.cupValidoConsulta"
                    label="Codigo del procedimiento CUP es valido para consultas"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.cupValidoConsulta)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.rangoFinalidad"
                    label="Finalidad existe entre rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.rangoFinalidad)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.finalidadUltimos"
                    label="Finalidad NO es '09' (Detección de enfermedad profesional) o '10' (No aplica) "
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.finalidadUltimos)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.referenciaCruzadaFinalidad"
                    label="Referencia cruzada entre finalidad de la consulta (01-08), diagnostico de consulta, edad del afiliado"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.referenciaCruzadaFinalidad)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.causaExterna"
                    label="Causa externa existe entre rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.causaExterna)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.diagnosticoPrincipal"
                    label="Diagnostico principal CIE10 existe en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.diagnosticoPrincipal)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.referenciaCruzadaCie10"
                    label="(Si el diagnostico principal existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.referenciaCruzadaCie10)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.referenciaCruzadaCie10Cups"
                    label="(Si el diagnostico principal existe). Referencia cruzada CIE10 y Codigo de procedimiento CUP"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.referenciaCruzadaCie10Cups)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.diagnosticoSecundario1"
                    label="Diagnostico relacionado N°1 CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.diagnosticoSecundario1)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.referenciaCruzadaCie101"
                    label="(Si el diagnostico relacionado N°1 CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.referenciaCruzadaCie101)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.referenciaCruzadaCie10Cups1"
                    label="(Si el diagnostico relacionado N°1 CIE10 existe). Referencia cruzada CIE10 y Codigo de procedimiento CUP"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.referenciaCruzadaCie10Cups1)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.diagnosticoSecundario2"
                    label="Diagnostico relacionado N°2 CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.diagnosticoSecundario2)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.referenciaCruzadaCie102"
                    label="(Si el diagnostico relacionado N°2 CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.referenciaCruzadaCie102)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.referenciaCruzadaCie10Cups2"
                    label="(Si el diagnostico relacionado N°2 CIE10 existe). Referencia cruzada CIE10 y Codigo de procedimiento CUP"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.referenciaCruzadaCie10Cups2)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.diagnosticoSecundario3"
                    label="Diagnostico relacionado N°3 CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.diagnosticoSecundario3)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.referenciaCruzadaCie103"
                    label="(Si el diagnostico relacionado N°3 CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.referenciaCruzadaCie103)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.referenciaCruzadaCie10Cups3"
                    label="(Si el diagnostico relacionado N°3 CIE10 existe). Referencia cruzada CIE10 y Codigo de procedimiento CUP"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.referenciaCruzadaCie10Cups3)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.tipoDiagnosticoPrincipal"
                    label="Tipo de diagnostico principal entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.tipoDiagnosticoPrincipal)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.valorConsultaEntero"
                    label="Valor de consulta siempre entero"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.valorConsultaEntero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.valorConsultaCero"
                    label="(Se aplica tanto SI es o NO es Capitacion) El valor de la consulta NO puede ser cero"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.valorConsultaCero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <!--
                        <v-divider></v-divider>
                      <v-layout row wrap>
                            <v-flex xs11 sm11 md11 lg11>
                                <v-switch
                                v-model="reglas.ac.valorConsultaSiempreCeroCapitacion"
                                label="(Si es capitacion). El valor de la consulta siempre debe ser CERO '0'"
                                color="success"
                                :value="true"
                                hide-details
                              ></v-switch>
                            </v-flex>
                            <v-flex xs1 sm1 md1 lg1>
                              <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.valorConsultaSiempreCeroCapitacion)" flat icon small>
                                  <v-icon>help</v-icon>
                              </v-btn>
                            </v-flex>
                        </v-layout>-->
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.valorCuotaModeradoraEntero"
                    label="Valor de la cuota moderadora siempre entero"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.valorCuotaModeradoraEntero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.valorNetoEntero"
                    label="Valor neto a pagar siempre entero"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.valorNetoEntero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ac.valorNetoCero"
                    label="(Se aplica tanto SI es o NO es Capitacion) El valor neto a pagar NO puede ser cero"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ac.valorNetoCero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
      <v-expansion-panel-content>
        <div slot="header">Archivo de procedimientos "AP"</div>
        <v-card flat icon>
          <v-card-text>
            <v-container>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.numeroFacturaEnAf"
                    label="Numero de factura existe en el archivo de transacciones"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.numeroFacturaEnAf)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.codigoPrestadorCt"
                    label="Codigo del prestador de servicios de salud igual al codigo en archivo de control"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.codigoPrestadorCt)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.rangosTipoIdentificacion"
                    label="Tipo de identificacion dentro del rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.rangosTipoIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.longitudIdentificacion"
                    label="Longitud de identificacion del usuario es valido con el tipo de identificacion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.longitudIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.fechaProcedimiento"
                    label="Fecha de procedimiento menor a la fecha actual"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.fechaProcedimiento)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.numeroAutorizacion"
                    label="Numero de autorizacion existe en el sistema"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.numeroAutorizacion)" flat small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.existeCup"
                    label="Codigo del procedimiento CUP existe en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.existeCup)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.realizacionProcedimiento"
                    label="Realizacion de procedimiento existe entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.realizacionProcedimiento)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.finalidadProcedimiento"
                    label="Finalidad del procedimiento existe entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.finalidadProcedimiento)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.personalQueAtiende"
                    label="Personal que atiende existe entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.personalQueAtiende)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.diagnosticoQuirurgico"
                    label="Diagnostico principal es obligatorio cuando el procedimiento es quirurgico"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.diagnosticoQuirurgico)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.diagnosticoPrincipal"
                    label="Diagnostico principal CIE10 existe en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.diagnosticoPrincipal)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.referenciaCruzadaCie10"
                    label="(Si el diagnostico principal existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.referenciaCruzadaCie10)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.referenciaCruzadaCie10Cups"
                    label="(Si el diagnostico principal existe). Referencia cruzada CIE10 y Codigo de procedimiento CUP"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.referenciaCruzadaCie10Cups)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.diagnosticoSecundario1"
                    label="Diagnostico relacionado N°1 CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.diagnosticoSecundario1)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.referenciaCruzadaCie101"
                    label="(Si el diagnostico relacionado N°1 CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.referenciaCruzadaCie101)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.referenciaCruzadaCie10Cups1"
                    label="(Si el diagnostico relacionado N°1 CIE10 existe). Referencia cruzada CIE10 y Codigo de procedimiento CUP"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.referenciaCruzadaCie10Cups1)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.diagnosticoSecundario2"
                    label="Diagnostico relacionado N°2 CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.diagnosticoSecundario2)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.referenciaCruzadaCie102"
                    label="(Si el diagnostico relacionado N°2 CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.referenciaCruzadaCie102)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.referenciaCruzadaCie10Cups2"
                    label="(Si el diagnostico relacionado N°2 CIE10 existe). Referencia cruzada CIE10 y Codigo de procedimiento CUP"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.referenciaCruzadaCie10Cups2)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.formaRealizacion"
                    label="Forma de realizacion existe entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.formaRealizacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.valorProcedimientoEntero"
                    label="Valor del procedimiento siempre entero"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.valorProcedimientoEntero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ap.valorConsultaCero"
                    label="(Se aplica tanto SI es o NO es Capitacion). El valor del procedimiento NO puede ser cero"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.valorConsultaCero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <!--<v-layout row wrap>
                            <v-flex xs11 sm11 md11 lg11>
                                <v-switch
                                    v-model="reglas.ap.valorConsultaSiempreCero"
                                    label="(Si es capitacion). El valor del procedimiento siempre debe ser CERO '0'"
                                    color="success"
                                    :value="true"
                                    hide-details
                                ></v-switch>
                            </v-flex>
                              <v-flex xs1 sm1 md1 lg1>
                                <v-btn color="primary" @click="mostrarAyuda(ayudas.ap.valorConsultaSiempreCero)" flat icon small>
                                    <v-icon>help</v-icon>
                                </v-btn>
                              </v-flex>
                        </v-layout>-->
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
      <v-expansion-panel-content>
        <div slot="header">Archivo de urgencia con observacion "AU"</div>
        <v-card flat icon>
          <v-card-text>
            <v-container>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.numeroFacturaEnAf"
                    label="Numero de factura existe en el archivo de transacciones"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.numeroFacturaEnAf)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.codigoPrestadorCt"
                    label="Codigo del prestador de servicios de salud igual al codigo en archivo de control"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.codigoPrestadorCt)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.rangosTipoIdentificacion"
                    label="Tipo de identificacion dentro del rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.rangosTipoIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.longitudIdentificacion"
                    label="Longitud de identificacion del usuario es valido con el tipo de identificacion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.longitudIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.fechaingreso"
                    label="Fecha de ingreso menor a la fecha actual"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.fechaingreso)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.fechaIngresoMenorFechaEgreso"
                    label="Fecha y hora de ingreso menor a la fecha y hora de salida del usuario en observacion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.fechaIngresoMenorFechaEgreso)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.numeroAutorizacion"
                    label="Numero de autorizacion existe en el sistema"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.numeroAutorizacion)" flat small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.causaExterna"
                    label="Causa externa existe entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.causaExterna)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.diagnosticoPrincipal"
                    label="Diagnostico principal CIE10 a la salida existe en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.diagnosticoPrincipal)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.referenciaCruzadaCie10"
                    label="(Si el diagnostico principal a la salida existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.referenciaCruzadaCie10)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.diagnosticoSecundario1"
                    label="Diagnostico relacionado N°1 CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.diagnosticoSecundario1)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.referenciaCruzadaCie101"
                    label="(Si el diagnostico relacionado N°1 CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.referenciaCruzadaCie101)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.diagnosticoSecundario2"
                    label="Diagnostico relacionado N°2 CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.diagnosticoSecundario2)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.referenciaCruzadaCie102"
                    label="(Si el diagnostico relacionado N°2 CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.referenciaCruzadaCie102)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.diagnosticoSecundario3"
                    label="Diagnostico relacionado N°3 CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.diagnosticoSecundario3)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.referenciaCruzadaCie103"
                    label="(Si el diagnostico relacionado N°3 CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.referenciaCruzadaCie103)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.destinoUsuario"
                    label="Destino del usuario a la salida exista entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.destinoUsuario)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.estadoSalida"
                    label="Estado de salida existe entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.estadoSalida)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.au.causaBasicaMuerte"
                    label="Causa basica de la muerte existe el codigo CIE10 en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.au.causaBasicaMuerte)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
      <v-expansion-panel-content>
        <div slot="header">Archivo de hospitalizacion "AH"</div>
        <v-card flat icon>
          <v-card-text>
            <v-container>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.numeroFacturaEnAf"
                    label="Numero de factura existe en el archivo de transacciones"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.numeroFacturaEnAf)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.codigoPrestadorCt"
                    label="Codigo del prestador de servicios de salud igual al codigo en archivo de control"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.codigoPrestadorCt)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.rangosTipoIdentificacion"
                    label="Tipo de identificacion dentro del rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.rangosTipoIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.longitudIdentificacion"
                    label="Longitud de identificacion del usuario es valido con el tipo de identificacion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.longitudIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.viaIngreso"
                    label="via de ingreso existe entre rango de valores permitidos, busqueda en archivo de consulta 'AC' o archivo de observacion de urgencia 'AU'"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.viaIngreso)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.fechaingreso"
                    label="Fecha de ingreso es menor a la fecha actual"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.fechaingreso)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.fechaIngresoMenorFechaEgreso"
                    label="Fecha y hora de inegreso es menor a la fecha y hora de salida del usuario de la institucion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.fechaIngresoMenorFechaEgreso)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.numeroAutorizacion"
                    label="Numero de autorizacion existe en el sistema"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.numeroAutorizacion)" flat small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.causaExterna"
                    label="Causa externa existe entre el rango de valores perm
										itidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.per)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.diangosticoIngreso"
                    label="Diagnostico principal de ingreso nunca puede ser un codigo 'Z'"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.diangosticoIngreso)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.diagnosticoPrincipalIngreso"
                    label="(Si el diagnostico principal CIE10 de ingreso existe). Debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.diagnosticoPrincipalIngreso)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.referenciaCruzadaCie10Ingreso"
                    label="(Si el diagnostico principal de ingreso existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.referenciaCruzadaCie10Ingreso)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.diagnosticoPrincipal"
                    label="Diagnostico principal CIE10 de egreso existe en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.diagnosticoPrincipal)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.referenciaCruzadaCie10"
                    label="(Si el diagnostico principal de egreso existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.referenciaCruzadaCie10)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.diagnosticoSecundario1"
                    label="Diagnostico relacionado de egreso N°1 CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.diagnosticoSecundario1)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.referenciaCruzadaCie101"
                    label="(Si el diagnostico relacionado de egreso N°1 CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.referenciaCruzadaCie101)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.diagnosticoSecundario2"
                    label="Diagnostico relacionado de egreso N°2 CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.diagnosticoSecundario2)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.referenciaCruzadaCie102"
                    label="(Si el diagnostico relacionado de egreso N°2 CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.referenciaCruzadaCie102)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.diagnosticoSecundario3"
                    label="Diagnostico relacionado de egreso N°3 CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.diagnosticoSecundario3)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.referenciaCruzadaCie103"
                    label="(Si el diagnostico relacionado de egreso N°3 CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.referenciaCruzadaCie103)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.diagnosticoSecundario4"
                    label="Diagnostico de la complicacion CIE10. Si esta digitado, debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.diagnosticoSecundario4)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.referenciaCruzadaCie104"
                    label="(Si el diagnostico de la complicacion CIE10 existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.referenciaCruzadaCie104)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.estadoSalida"
                    label="Estado de salida existe entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.estadoSalida)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.causaBasicaMuerte"
                    label="Causa basica de la muerte existe"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.causaBasicaMuerte)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.diagnosticoSecundario5"
                    label="(Si existe la causa basica de la muerte). Codigo CIE10 debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.diagnosticoSecundario5)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ah.referenciaCruzadaCie105"
                    label="(Si el diagnostico de la causa basica de la muerte existe). Referencia cruzada CIE10"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ah.referenciaCruzadaCie105)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
      <v-expansion-panel-content>
        <div slot="header">Archivo de recien nacidos "AN"</div>
        <v-card flat icon>
          <v-card-text>
            <v-container>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.an.numeroFacturaEnAf"
                    label="Numero de factura existe en el archivo de transacciones"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.an.numeroFacturaEnAf)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.an.codigoPrestadorCt"
                    label="Codigo del prestador de servicios de salud igual al codigo en archivo de control"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.an.codigoPrestadorCt)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.an.rangosTipoIdentificacion"
                    label="Tipo de identificacion dentro del rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.an.rangosTipoIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.an.longitudIdentificacion"
                    label="Longitud de identificacion del usuario es valido con el tipo de identificacion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.an.longitudIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.an.fechaingreso"
                    label="Fecha de ingreso debe ser menor a la fecha actual"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.an.fechaingreso)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.an.fechaNacimientoMenorFechaSalida"
                    label="Fecha y hora de nacimiento debe ser menor a la fecha y hora de salida de la institucion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.an.fechaNacimientoMenorFechaSalida)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.an.controlPrenatal"
                    label="Control prenatal debe existir entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.an.controlPrenatal)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.an.generoAn"
                    label="Genero debe existir entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.an.generoAn)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.an.pesoEntero"
                    label="El peso del recien nacido debe ser numerico"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.an.pesoEntero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.an.diagnosticoSecundario1"
                    label="Diagnostico del recien nacido. Si esta digitado debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.an.diagnosticoSecundario1)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.an.diagnosticoSecundario2"
                    label="Diagnostico causa basica de la muerte. Si esta digitado debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.an.diagnosticoSecundario2)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
      <v-expansion-panel-content>
        <div slot="header">Archivo de medicamentos "AM"</div>
        <v-card flat icon>
          <v-card-text>
            <v-container>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.am.numeroFacturaEnAf"
                    label="Numero de factura existe en el archivo de transacciones"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.am.numeroFacturaEnAf)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.am.codigoPrestadorCt"
                    label="Codigo del prestador de servicios de salud igual al codigo en archivo de control"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.am.codigoPrestadorCt)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.am.rangosTipoIdentificacion"
                    label="Tipo de identificacion dentro del rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.am.rangosTipoIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.am.longitudIdentificacion"
                    label="Longitud de identificacion del usuario es valido con el tipo de identificacion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.am.longitudIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.am.numeroAutorizacion"
                    label="Numero de autorizacion existe en el sistema"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.am.numeroAutorizacion)" flat small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.am.codigoMedicamento"
                    label="Codigo del medicamento debe existir en la base de datos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.am.codigoMedicamento)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.am.tipoMedicamento"
                    label="Tipo de medicamento existe entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.am.tipoMedicamento)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.am.valorUnitarioProducto"
                    label="Valor unitario debe estar digitado si existen mas de 1 unidad de medicamentos digitados"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.am.valorUnitarioProducto)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.am.valorTotalMedicamento"
                    label="Valor total del medicamento. Multiplicacion del valor unitario por numero de unidades debe ser igual al valor total del medicamento"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.am.valorTotalMedicamento)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.am.valorMedicamentoCero"
                    label="Si es capitacion. El valor del medicamento NO puede ser cero"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.am.valorMedicamentoCero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
      <v-expansion-panel-content>
        <div slot="header">Archivo de otros servicios "AT"</div>
        <v-card flat icon>
          <v-card-text>
            <v-container>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.at.numeroFacturaEnAf"
                    label="Numero de factura existe en el archivo de transacciones"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.at.numeroFacturaEnAf)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.at.codigoPrestadorCt"
                    label="Codigo del prestador de servicios de salud igual al codigo en archivo de control"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.at.codigoPrestadorCt)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.at.rangosTipoIdentificacion"
                    label="Tipo de identificacion dentro del rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.at.rangosTipoIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.at.longitudIdentificacion"
                    label="Longitud de identificacion del usuario es valido con el tipo de identificacion"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.at.longitudIdentificacion)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.at.numeroAutorizacion"
                    label="Numero de autorizacion existe en el sistema"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.at.numeroAutorizacion)" flat small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.at.tipoServicio"
                    label="Tipo de servicio existe entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.at.tipoServicio)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.at.valorTotalMaterialInsumo"
                    label="Valor total del material e insumo. Multiplicacion del valor unitario por cantidad"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.at.valorTotalMaterialInsumo)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.at.valorTotalInsumoCero"
                    label="(Se aplica tanto SI es o NO es Capitacion). El valor del material e insumo NO puede ser cero"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.at.valorTotalInsumoCero)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
      <v-expansion-panel-content>
        <div slot="header">Archivo de descripción agrupada de los servicios de salud prestados "AD"</div>
        <v-card flat icon>
          <v-card-text>
            <v-container>
              <v-layout row wrap>
                <v-flex xs11 sm12 md11 lg11>
                  <v-switch
                    v-model="reglas.ad.codigoPrestadorCt"
                    label="Codigo del prestador de servicios de salud igual al codigo en archivo de control"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ad.codigoPrestadorCt)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
              <v-divider></v-divider>
              <v-layout row wrap>
                <v-flex xs11 sm11 md11 lg11>
                  <v-switch
                    v-model="reglas.ad.codigoConcepto"
                    label="Codigo del concepto exista entre el rango de valores permitidos"
                    color="success"
                    :value="true"
                    hide-details
                  ></v-switch>
                </v-flex>
                <v-flex xs1 sm1 md1 lg1>
                  <v-btn color="primary" @click="mostrarAyuda(ayudas.ad.codigoConcepto)" flat icon small>
                    <v-icon>help</v-icon>
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-expansion-panel-content>
    </v-expansion-panel>
    <v-container>
      <v-speed-dial
        v-model="fab"
        bottom
        right
        direction="bottom"
        open-on-hover
        fixed
        class="mb-4"
      >
        <v-tooltip left slot="activator">
          <v-btn
            slot="activator"
            v-model="fab"
            color="blue darken-2"
            dark
            fab
            @click="submit"
          >
            <v-icon>save</v-icon>
            <!--<v-icon>close</v-icon>-->
          </v-btn>
          <span>Configurar reglas de validación</span>
        </v-tooltip>
      </v-speed-dial>
      <!--<v-layout row wrap>-->
        <!--<v-flex xs12>-->
          <!--<v-btn color="primary" :left="true" @click="submit">Configurar reglas de validacion</v-btn>-->
        <!--</v-flex>-->
      <!--</v-layout>-->
    </v-container >
    <v-dialog
      v-model="dialogHelp"
    >

      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title>
          Ayuda y descripcion de la validacion
        </v-card-title>
        <div v-show="bottomNav==0" class="headline pa-5">
          <div class="text-xs-center">{{ help.descripcion }}</div>
        </div>
        <div v-show="bottomNav==1" class="headline pa-5">
          <div class="text-xs-center">{{ help.valido }}</div>
        </div>
        <div v-show="bottomNav==2" class="headline pa-5">
          <div class="text-xs-center">{{ help.invalido }}</div>
        </div>
        <v-bottom-nav
          :active.sync="bottomNav"
          :color="color"
          :value="true"
          absolute
          shift>
          <v-btn dark>
            <span>Descripcion</span>
            <v-icon>assignment</v-icon>
          </v-btn>
          <v-btn dark>
            <span>Ejemplo valido</span>
            <v-icon>done_all</v-icon>
          </v-btn>
          <v-btn dark>
            <span>Ejemplo No Valido</span>
            <v-icon>warning</v-icon>
          </v-btn>
        </v-bottom-nav>
        <!--</v-card-text>-->
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="white"
            flat icon
            @click="dialogHelp = false"
          >
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
  export default {
    name: 'ConfigurarReglasValidacion',
    data () {
      return {
        fab: false,
        dialogHelp: false,
        help: {
          descripcion: '',
          valido: '',
          invalido: ''
        },
        bottomNav: 0,
        reglas: {
          ct: {
            codigo: true,
            fecha: true,
            nombreArchivo: true,
            esEntero: true
          },
          af: {
            codigoPrestadorCt: true,
            tipoIdentificacionPrestador: true,
            existePrestadorServicioSalud: true,
            identificacionPrestador: true,
            mismaFacturaCaptiacion: true,
            fechaExpedicion: true,
            fechaInicio: true,
            FechaInicioMenorQueFechafinal: true,
            fechaFinal: true,
            fechaFinalMayorQueFechaInicial: true,
            codigoEntidadadministradora: true,
            numeroPositivo: true,
            cruceFacturas: true,
            sumaValoresFacturas: true
          },
          us: {
            rangosTipoEdad: true,
            edad: true,
            tipoIdentificacionUsuario: true,
            longitudIdentificacion: true,
            tipoUsuario: true,
            rangoGenero: true,
            codigoDepartamento: true,
            codigoMunicipio: true,
            zona: true
          },
          ac: {
            numeroFacturaEnAf: true,
            codigoPrestadorCt: true,
            rangosTipoIdentificacion: true,
            longitudIdentificacion: true,
            fechaConsulta: true,
            numeroAutorizacion: true,
            existeCup: true,
            cupValidoConsulta: true,
            rangoFinalidad: true,
            finalidadUltimos: true,
            referenciaCruzadaFinalidad: true,
            causaExterna: true,
            diagnosticoPrincipal: true,
            referenciaCruzadaCie10: true,
            referenciaCruzadaCie10Cups: true,
            diagnosticoSecundario1: true,
            referenciaCruzadaCie101: true,
            referenciaCruzadaCie10Cups1: true,
            diagnosticoSecundario2: true,
            referenciaCruzadaCie102: true,
            referenciaCruzadaCie10Cups2: true,
            diagnosticoSecundario3: true,
            referenciaCruzadaCie103: true,
            referenciaCruzadaCie10Cups3: true,
            tipoDiagnosticoPrincipal: true,
            valorConsultaEntero: true,
            valorConsultaCero: true,
            valorConsultaSiempreCeroCapitacion: true,
            valorCuotaModeradoraEntero: true,
            valorNetoEntero: true,
            valorNetoCero: true
          },
          ap: {
            numeroFacturaEnAf: true,
            codigoPrestadorCt: true,
            rangosTipoIdentificacion: true,
            longitudIdentificacion: true,
            fechaProcedimiento: true,
            numeroAutorizacion: true,
            existeCup: true,
            realizacionProcedimiento: true,
            finalidadProcedimiento: true,
            personalQueAtiende: true,
            diagnosticoQuirurgico: true,
            diagnosticoPrincipal: true,
            referenciaCruzadaCie10: true,
            referenciaCruzadaCie10Cups: true,
            diagnosticoSecundario1: true,
            referenciaCruzadaCie101: true,
            referenciaCruzadaCie10Cups1: true,
            diagnosticoSecundario2: true,
            referenciaCruzadaCie102: true,
            referenciaCruzadaCie10Cups2: true,
            formaRealizacion: true,
            valorProcedimientoEntero: true,
            valorConsultaCero: true,
            valorConsultaSiempreCero: true
          },
          au: {
            numeroFacturaEnAf: true,
            codigoPrestadorCt: true,
            rangosTipoIdentificacion: true,
            longitudIdentificacion: true,
            fechaingreso: true,
            fechaIngresoMenorFechaEgreso: true,
            numeroAutorizacion: true,
            causaExterna: true,
            diagnosticoPrincipal: true,
            referenciaCruzadaCie10: true,
            diagnosticoSecundario1: true,
            referenciaCruzadaCie101: true,
            diagnosticoSecundario2: true,
            referenciaCruzadaCie102: true,
            diagnosticoSecundario3: true,
            referenciaCruzadaCie103: true,
            destinoUsuario: true,
            estadoSalida: true,
            causaBasicaMuerte: true
          },
          ah: {
            numeroFacturaEnAf: true,
            codigoPrestadorCt: true,
            rangosTipoIdentificacion: true,
            longitudIdentificacion: true,
            viaIngreso: true,
            fechaingreso: true,
            fechaIngresoMenorFechaEgreso: true,
            numeroAutorizacion: true,
            causaExterna: true,
            diangosticoIngreso: true,
            diagnosticoPrincipalIngreso: true,
            referenciaCruzadaCie10Ingreso: true,
            diagnosticoPrincipal: true,
            referenciaCruzadaCie10: true,
            diagnosticoSecundario1: true,
            referenciaCruzadaCie101: true,
            diagnosticoSecundario2: true,
            referenciaCruzadaCie102: true,
            diagnosticoSecundario3: true,
            referenciaCruzadaCie103: true,
            diagnosticoSecundario4: true,
            referenciaCruzadaCie104: true,
            estadoSalida: true,
            causaBasicaMuerte: true,
            diagnosticoSecundario5: true,
            referenciaCruzadaCie105: true
          },
          an: {
            numeroFacturaEnAf: true,
            codigoPrestadorCt: true,
            rangosTipoIdentificacion: true,
            longitudIdentificacion: true,
            fechaingreso: true,
            fechaNacimientoMenorFechaSalida: true,
            controlPrenatal: true,
            generoAn: true,
            pesoEntero: true,
            diagnosticoSecundario1: true,
            diagnosticoSecundario2: true
          },
          am: {
            numeroFacturaEnAf: true,
            codigoPrestadorCt: true,
            rangosTipoIdentificacion: true,
            longitudIdentificacion: true,
            numeroAutorizacion: true,
            codigoMedicamento: true,
            tipoMedicamento: true,
            valorUnitarioProducto: true,
            valorTotalMedicamento: true,
            valorMedicamentoCero: true
          },
          at: {
            numeroFacturaEnAf: true,
            codigoPrestadorCt: true,
            rangosTipoIdentificacion: true,
            longitudIdentificacion: true,
            numeroAutorizacion: true,
            tipoServicio: true,
            valorTotalMaterialInsumo: true,
            valorTotalInsumoCero: true
          },
          ad: {
            codigoPrestadorCt: true,
            codigoConcepto: true
          }
        },
        ayudas: {
          ct: {
            codigo: {},
            fecha: {},
            nombreArchivo: {},
            esEntero: {}
          },
          af: {
            codigoPrestadorCt: {},
            tipoIdentificacionPrestador: {},
            existePrestadorServicioSalud: {},
            identificacionPrestador: {},
            mismaFacturaCaptiacion: {},
            fechaExpedicion: {},
            fechaInicio: {},
            FechaInicioMenorQueFechafinal: {},
            fechaFinal: {},
            fechaFinalMayorQueFechaInicial: {},
            codigoEntidadadministradora: {},
            numeroPositivo: {},
            cruceFacturas: {},
            sumaValoresFacturas: {}
          },
          us: {
            rangosTipoEdad: {},
            edad: {},
            tipoIdentificacionUsuario: {},
            longitudIdentificacion: {},
            tipoUsuario: {},
            rangoGenero: {},
            codigoDepartamento: {},
            codigoMunicipio: {},
            zona: {}
          },
          ac: {
            numeroFacturaEnAf: {},
            codigoPrestadorCt: {},
            rangosTipoIdentificacion: {},
            longitudIdentificacion: {},
            fechaConsulta: {},
            numeroAutorizacion: {},
            existeCup: {},
            cupValidoConsulta: {},
            rangoFinalidad: {},
            finalidadUltimos: {},
            referenciaCruzadaFinalidad: {},
            causaExterna: {},
            diagnosticoPrincipal: {},
            referenciaCruzadaCie10: {},
            referenciaCruzadaCie10Cups: {},
            diagnosticoSecundario1: {},
            referenciaCruzadaCie101: {},
            referenciaCruzadaCie10Cups1: {},
            diagnosticoSecundario2: {},
            referenciaCruzadaCie102: {},
            referenciaCruzadaCie10Cups2: {},
            diagnosticoSecundario3: {},
            referenciaCruzadaCie103: {},
            referenciaCruzadaCie10Cups3: {},
            tipoDiagnosticoPrincipal: {},
            valorConsultaEntero: {},
            valorConsultaCero: {},
            valorConsultaSiempreCeroCapitacion: {},
            valorCuotaModeradoraEntero: {},
            valorNetoEntero: {},
            valorNetoCero: {}
          },
          ap: {
            numeroFacturaEnAf: {},
            codigoPrestadorCt: {},
            rangosTipoIdentificacion: {},
            longitudIdentificacion: {},
            fechaProcedimiento: {},
            numeroAutorizacion: {},
            existeCup: {},
            realizacionProcedimiento: {},
            finalidadProcedimiento: {},
            personalQueAtiende: {},
            diagnosticoQuirurgico: {},
            diagnosticoPrincipal: {},
            referenciaCruzadaCie10: {},
            referenciaCruzadaCie10Cups: {},
            diagnosticoSecundario1: {},
            referenciaCruzadaCie101: {},
            referenciaCruzadaCie10Cups1: {},
            diagnosticoSecundario2: {},
            referenciaCruzadaCie102: {},
            referenciaCruzadaCie10Cups2: {},
            formaRealizacion: {},
            valorProcedimientoEntero: {},
            valorConsultaCero: {},
            valorConsultaSiempreCero: {}
          },
          au: {
            numeroFacturaEnAf: {},
            codigoPrestadorCt: {},
            rangosTipoIdentificacion: {},
            longitudIdentificacion: {},
            fechaingreso: {},
            fechaIngresoMenorFechaEgreso: {},
            numeroAutorizacion: {},
            causaExterna: {},
            diagnosticoPrincipal: {},
            referenciaCruzadaCie10: {},
            diagnosticoSecundario1: {},
            referenciaCruzadaCie101: {},
            diagnosticoSecundario2: {},
            referenciaCruzadaCie102: {},
            diagnosticoSecundario3: {},
            referenciaCruzadaCie103: {},
            destinoUsuario: {},
            estadoSalida: {},
            causaBasicaMuerte: {}
          },
          ah: {
            numeroFacturaEnAf: {},
            codigoPrestadorCt: {},
            rangosTipoIdentificacion: {},
            longitudIdentificacion: {},
            viaIngreso: {},
            fechaingreso: {},
            fechaIngresoMenorFechaEgreso: {},
            numeroAutorizacion: {},
            causaExterna: {},
            diangosticoIngreso: {},
            diagnosticoPrincipalIngreso: {},
            referenciaCruzadaCie10Ingreso: {},
            diagnosticoPrincipal: {},
            referenciaCruzadaCie10: {},
            diagnosticoSecundario1: {},
            referenciaCruzadaCie101: {},
            diagnosticoSecundario2: {},
            referenciaCruzadaCie102: {},
            diagnosticoSecundario3: {},
            referenciaCruzadaCie103: {},
            diagnosticoSecundario4: {},
            referenciaCruzadaCie104: {},
            estadoSalida: {},
            causaBasicaMuerte: {},
            diagnosticoSecundario5: {},
            referenciaCruzadaCie105: {}
          },
          an: {
            numeroFacturaEnAf: {},
            codigoPrestadorCt: {},
            rangosTipoIdentificacion: {},
            longitudIdentificacion: {},
            fechaingreso: {},
            fechaNacimientoMenorFechaSalida: {},
            controlPrenatal: {},
            generoAn: {},
            pesoEntero: {},
            diagnosticoSecundario1: {},
            diagnosticoSecundario2: {}
          },
          am: {
            numeroFacturaEnAf: {},
            codigoPrestadorCt: {},
            rangosTipoIdentificacion: {},
            longitudIdentificacion: {},
            numeroAutorizacion: {},
            codigoMedicamento: {},
            tipoMedicamento: {},
            valorUnitarioProducto: {},
            valorTotalMedicamento: {},
            valorMedicamentoCero: {}
          },
          at: {
            numeroFacturaEnAf: {},
            codigoPrestadorCt: {},
            rangosTipoIdentificacion: {},
            longitudIdentificacion: {},
            numeroAutorizacion: {},
            tipoServicio: {},
            valorTotalMaterialInsumo: {},
            valorTotalInsumoCero: {}
          },
          ad: {
            codigoPrestadorCt: {},
            codigoConcepto: {}
          }
        }
      }
    },
    methods: {
      getReglasValidacion () {
        this.axios.get('rs_reglas_validaciones')
          .then(response => {
            console.log(response.data)
            this.reglas = response.data.reglas
            this.ayudas = response.data.ayudas
          })
          .catch(e => {
            console.log(e.response)
            this.$store.commit('SNACK_SHOW', {msg: e.response.message, color: 'error'})
          })
      },
      submit () {
        this.axios.post('rs_reglas_validaciones', this.reglas)
          .then(response => {
            if (response.data.state === 'save') {
              this.$store.commit('SNACK_SHOW', {msg: 'Reglas de validación configuradas correctamente', color: 'success'})
              this.reglas = response.data.reglas
            } else {
              this.$store.commit('SNACK_SHOW', {msg: 'No se pueden configurar las reglas de validación.', color: 'error'})
            }
          })
          .catch(e => {
            console.log(e.response)
            this.$store.commit('SNACK_SHOW', {msg: e.response.message, color: 'error'})
          })
      },
      mostrarAyuda (data) {
        this.dialogHelp = true
        this.help = data
      }
    },
    mounted () {
      this.getReglasValidacion()
    },
    computed: {
      color () {
        switch (this.bottomNav) {
          case 0: return 'blue-grey'
          case 1: return 'teal'
          case 2: return 'red'
        }
      }
    }
  }
</script>

<style scoped>

</style>
