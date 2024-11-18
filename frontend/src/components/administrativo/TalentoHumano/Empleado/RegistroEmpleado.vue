<template>
  <div>
    <v-card ref="loaderRef">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ ordenEdit ? 'Edicion de empleado' : 'Registro de empleado' }} </v-toolbar-title>
      </v-toolbar>
      <v-card-text class="no-padding">
        <v-stepper  v-model="stepActual">
          <v-stepper-header>
            <v-stepper-step :complete="stepActual > 1" step="1" editable >Informacion general</v-stepper-step>
            <v-divider/>
            <v-stepper-step :complete="stepActual > 2" step="2" editable >Personal y laboral</v-stepper-step>
            <v-divider/>
            <v-stepper-step :complete="stepActual > 3" step="3"  editable>Informacion fiscal</v-stepper-step>
            <v-divider/>
            <v-stepper-step step="4"  editable>Afiliaciones</v-stepper-step>
          </v-stepper-header>
          <v-stepper-items>
            <v-stepper-content step="1">
              <v-container fluid grid-list-xl >
                <v-layout row wrap>
                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field label="Código"
                                          v-model="empleado.codigo"
                                          key="codigo"
                                          v-validate="'required|max:16'"
                                          name="Codigo"
                                          :error-messages="errors.collect('Codigo')"
                                          prepend-icon="subtitles"
                                          required>
                            </v-text-field>
                          </v-flex>

                          <v-flex xs12 sm12 md4 lg4>
                            <v-autocomplete
                              label="Grupo de empleados"
                              v-model="empleado.th_grupo_empleados_id"
                              :items="gruposEmpleados"
                              item-value="id"
                              item-text="nombre"
                              name="grupo empleados"
                              persistent-hint
                              prepend-icon="face"
                              no-data-text="No existen datos"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('grupo empleados')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="'Código: ' + data.item.codigo"></v-list-tile-sub-title>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm12 md4 lg4>
                            <v-autocomplete
                              label="Subgrupo de empleados"
                              v-model="empleado.th_subgrupo_empleado_id"
                              :items="subGruposEmpleados"
                              item-value="id"
                              item-text="nombre"
                              name="subgrupo empleados"
                              persistent-hint
                              prepend-icon="face"
                              no-data-text="No existen datos"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('subgrupo empleados')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="'Código: ' + data.item.codigo"></v-list-tile-sub-title>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Tercero</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm12 md7 lg7>
                            <postulador
                              nodata="Busqueda por nombre o número de documento."
                              itemtext="identificacion"
                              datatitle="identificacion_completa"
                              datasubtitle="nombre_completo"
                              filter="identificacion_completa,nombre_completo"
                              label="Tercero"
                              scope="formRegistroEmpleado"
                              ref="postulaTercero"
                              entidad="terceros"
                              preicon="person"
                              @change="val => empleado.gn_tercero_id = val"
                              @objectReturn="val => seleccionTercero(val)"
                              :value="empleado.tercero"
                              clearable
                            />
                          </v-flex>

                          <v-flex xs12 sm12 md1 lg1>
                            <v-text-field label="Documento" :value="empleado.tercero.tipo_id.abreviatura" disabled></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field label="Lugar de expedicion" :value="empleado.tercero.municipio_expedicion.nombre + ' - ' + empleado.tercero.municipio_expedicion.nombre_departamento" disabled></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm12 md6 lg6>
                            <v-text-field label="Apellidos" :value="empleado.tercero.apellido1 + ' ' + empleado.tercero.apellido2" disabled></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm12 md6 lg6>
                            <v-text-field label="Nombres" :value="empleado.tercero.nombre1 + ' ' + empleado.tercero.nombre2" disabled></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field label="Direccion" :value="empleado.tercero.direccion" disabled></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field label="Ciudad" :value="empleado.tercero.municipio.nombre +' - ' +empleado.tercero.municipio.nombre_departamento" disabled></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field label="Telefono" :value="empleado.tercero.telefono_fijo"  disabled></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm12 md3 lg3>
                            <v-text-field label="Fac. Prest" :disabled="empleado.salario != 'Integral'" v-model="empleado.factura"></v-text-field>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Contrato</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm3>
                              <v-subheader class="tipo">Contrato</v-subheader>
                              <v-radio-group v-model="empleado.contrato" row>
                                <v-radio label="Indefinido" value="Indefinido"></v-radio>
                                <v-radio label="Fijo" value="Fijo"></v-radio>
                              </v-radio-group>
                          </v-flex>

                          <v-flex xs12 sm3>
                            <v-text-field label="Meses" :disabled="empleado.contrato != 'Fijo' " v-model="empleado.meses"></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm3>
                            <v-subheader class="tipo">Salario</v-subheader>
                            <v-radio-group v-model="empleado.salario" row>
                              <v-radio label="Normal" value="Normal"></v-radio>
                              <v-radio label="Integral" value="Integral"></v-radio>
                            </v-radio-group>
                          </v-flex>

                          <v-flex xs12 sm3>
                            <v-subheader class="tipo">Alto riesgo</v-subheader>
                            <v-radio-group v-model="empleado.alto_riesgo" row>
                              <v-radio label="Si" value="Si"></v-radio>
                              <v-radio label="No" value="No"></v-radio>
                            </v-radio-group>
                          </v-flex>

                          <v-flex xs12 sm12 md6 lg6>
                            <v-text-field label="Area de servicio" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm12 md6 lg6>
                            <v-subheader class="tipo">Área</v-subheader>
                            <v-radio-group v-model="empleado.area" row>
                              <v-radio label="Misional" value="Misional"></v-radio>
                              <v-radio label="Apoyo" value="Apoyo"></v-radio>
                            </v-radio-group>
                          </v-flex>

                          <v-flex xs12 sm12 md3 lg3>
                            <v-subheader class="tipo">Estado</v-subheader>
                            <v-radio-group v-model="empleado.estado" row>
                              <v-radio label="Activo" value="Activo"></v-radio>
                              <v-radio label="Retirado" value="Retirado"></v-radio>
                            </v-radio-group>
                          </v-flex>

                          <v-flex xs12 sm12 md3 lg3 v-if="empleado.estado === 'Retirado'">
                            <v-dialog
                              ref="pickerFechaRetiro"
                              v-model="pickerFechaRetiro"
                              :return-value.sync="empleado.fecha_retiro"
                              persistent
                              :disabled="empleado.estado != 'Retirado'"
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_retiro"
                                label="Fecha"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_retiro" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaRetiro = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaRetiro.save(empleado.fecha_retiro)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                          <v-flex xs12 sm12 md3 lg3 v-if="empleado.estado === 'Retirado'">
                            <v-autocomplete
                              label="Grupo de empleados"
                              v-model="empleado.th_retiro_id"
                              :items="motivosRetiro"
                              item-value="id"
                              item-text="nombre"
                              name="motivo retiro"
                              persistent-hint
                              prepend-icon="face"
                              no-data-text="No existen datos"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('motivo retiro')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="'Código: ' + data.item.codigo"></v-list-tile-sub-title>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm12 md3 lg3 v-if="empleado.estado === 'Retirado'">
                            <v-dialog
                              ref="pickerFechaRetiroPensiones"
                              v-model="pickerFechaRetiroPensiones"
                              :return-value.sync="empleado.fecha_retiro_pensiones"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_retiro_pensiones"
                                label="Fecha retiro pensiones"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_retiro_pensiones"locale="es-co"
                                               scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaRetiroPensiones = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaRetiroPensiones.save(empleado.fecha_retiro_pensiones)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>


                </v-layout>
              </v-container>
            </v-stepper-content>
          </v-stepper-items>
          <v-stepper-items>
            <v-stepper-content step="2">
              <v-container fluid grid-list-xl >
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4>
                            <v-dialog
                              ref="pickerFechaNacimiento"
                              v-model="pickerFechaNacimiento"
                              :return-value.sync="empleado.fecha_nacimiento"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_nacimiento"
                                label="Fecha de nacimiento"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_nacimiento" :max="fechaActual" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaNacimiento = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaNacimiento.save(empleado.fecha_nacimiento)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm4>
                            <v-text-field label="Edad" :value="empleado.edad" readonly></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm4>
                            <v-autocomplete
                              label="Lugar de nacimiento"
                              v-model="empleado.gn_municipio_nacimiento_id"
                              :items="complementos.municipios"
                              item-value="id"
                              item-text="nombre"
                              :filter="filterMunicipios"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.nombre_departamento"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm4>
                            <v-autocomplete
                              label="País"
                              v-model="empleado.gn_pais_id"
                              :items="complementosFormAfiliados.paises"
                              item-value="id"
                              item-text="nombre"
                              name="Pais"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('Pais')"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-action>
                                    <flag :iso="data.item.iso_dos"/>
                                  </v-list-tile-action>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                              <template slot="selection" slot-scope="data">
                                <flag :iso="data.item.iso_dos"/> &nbsp; {{data.item.nombre}}
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm4>
                            <v-subheader class="tipo">Sexo</v-subheader>
                            <v-radio-group v-model="empleado.genero" row>
                              <v-radio label="Femenino" value="Femenino"></v-radio>
                              <v-radio label="Masculino" value="MAsculino"></v-radio>
                            </v-radio-group>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="complementosEmpleado.estadosCivil"
                              label="Estado civil"
                              v-model="empleado.estado_civil"
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field label="Hijos" v-model="empleado.hijos"></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field label="Personas a cargo" v-model="empleado.personas_acargo"></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="complementosEmpleado.tiposSangre"
                              label="Tipo de sangre"
                              v-model="empleado.tipo_sangre"
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field label="% Prima Técnica" v-model="empleado.prima_tecnica"></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-dialog
                              ref="pickerFechaIngreso"
                              v-model="pickerFechaIngreso"
                              :return-value.sync="empleado.fecha_ingreso"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_ingreso"
                                label="Fecha de ingreso"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_ingreso"
                                             locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaIngreso = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaIngreso.save(empleado.fecha_ingreso)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-dialog
                              ref="pickerFechaIngresoEnt"
                              v-model="pickerFechaIngresoEnt"
                              :return-value.sync="empleado.fecha_ingresoent"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_ingresoent"
                                label="Fecha de ingreso Ent"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_ingresoent" locale="es-co"
                                             :first-day-of-week="1"  scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaIngresoEnt = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaIngresoEnt.save(empleado.fecha_ingresoent)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field label="Resolucion" v-model="empleado.resolucion"></v-text-field>
                          </v-flex>


                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>


                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Policia Nacional</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm12 md12 lg12>
                            <v-text-field label="N° Registro código Policia Nacional" v-model="empleado.registro_das"></v-text-field>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Sueldo</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm12 md6 lg6>
                            <v-text-field label="Inicial" v-model="empleado.sueldo_inicial"></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-subheader class="tipo">Variable</v-subheader>
                            <v-radio-group v-model="empleado.variable" row>
                              <v-radio label="Si" value="Si"></v-radio>
                              <v-radio label="No" value="No"></v-radio>
                            </v-radio-group>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-text-field label="Sueldo anterior" v-model="empleado.sueldo_anterior"></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-dialog
                              ref="pickerFechaSueldoAnterior"
                              v-model="pickerFechaSueldoAnterior"
                              :return-value.sync="empleado.fecha_sueldo_anterior"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_sueldo_anterior"
                                label="Fecha anterior"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_sueldo_anterior" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaSueldoAnterior = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaSueldoAnterior.save(empleado.fecha_sueldo_anterior)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-text-field label="Sueldo Nuevo" v-model="empleado.sueldo_nuevo"></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-dialog
                              ref="pickerFechaSueldoNuevo"
                              v-model="pickerFechaSueldoNuevo"
                              :return-value.sync="empleado.fecha_sueldo_nuevo"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_sueldo_nuevo"
                                label="Fecha nuevo"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_sueldo_nuevo" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaSueldoNuevo = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaSueldoNuevo.save(empleado.fecha_sueldo_nuevo)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-dialog
                              ref="pickerFechaInicioAntiguedad"
                              v-model="pickerFechaInicioAntiguedad"
                              :return-value.sync="empleado.fecha_incantiguedad"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_incantiguedad"
                                label="Inc Antiguedad"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_incantiguedad" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaInicioAntiguedad = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaInicioAntiguedad.save(empleado.fecha_incantiguedad)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-dialog
                              ref="pickerFechaUltBonificacion"
                              v-model="pickerFechaUltBonificacion"
                              :return-value.sync="empleado.fecha_ultbonificacion"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_ultbonificacion"
                                label="Ult. Bonificación"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_ultbonificacion" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaUltBonificacion = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaUltBonificacion.save(empleado.fecha_ultbonificacion)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-dialog
                              ref="pickerFechaUltPNavidad"
                              v-model="pickerFechaUltPNavidad"
                              :return-value.sync="empleado.fecha_ultnavidad"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_ultnavidad"
                                label="Ult. P. Navidad"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_ultnavidad" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaUltPNavidad = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaUltPNavidad.save(empleado.fecha_ultnavidad)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-dialog
                              ref="pickerFechaUltPServicios"
                              v-model="pickerFechaUltPServicios"
                              :return-value.sync="empleado.fecha_ultservicios"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_ultservicios"
                                label="Ult. P. Servicios"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_ultservicios" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaUltPServicios = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaUltPServicios.save(empleado.fecha_ultservicios)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Vacaciones</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4>
                            <v-dialog
                              ref="pickerFechaInicioVacaciones"
                              v-model="pickerFechaInicioVacaciones"
                              :return-value.sync="empleado.fecha_iniciovacaciones"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_iniciovacaciones"
                                label="Fecha Inicio vacaciones"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_iniciovacaciones" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaInicioVacaciones = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaInicioVacaciones.save(empleado.fecha_iniciovacaciones)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm4>
                            <v-dialog
                              ref="pickerFechaFinalVacaciones"
                              v-model="pickerFechaFinalVacaciones"
                              :return-value.sync="empleado.fecha_finalvacaciones"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_finalvacaciones"
                                label="Fecha final vacaciones"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_finalvacaciones" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaFinalVacaciones = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaFinalVacaciones.save(empleado.fecha_finalvacaciones)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm4>
                            <v-dialog
                              ref="pickerFechaIngresoVacaciones"
                              v-model="pickerFechaIngresoVacaciones"
                              :return-value.sync="empleado.fecha_ingresovacaciones"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_ingresovacaciones"
                                label="Fecha ingreso vacaciones"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_ingresovacaciones" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaIngresoVacaciones = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaIngresoVacaciones.save(empleado.fecha_ingresovacaciones)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm4>
                            <v-dialog
                              ref="pickerFechaPeriodoLiquidadoVacaciones"
                              v-model="pickerFechaPeriodoLiquidadoVacaciones"
                              :return-value.sync="empleado.fecha_periodoliquidadovacaciones"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_periodoliquidadovacaciones"
                                label="Periodo liquidado"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_periodoliquidadovacaciones" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaPeriodoLiquidadoVacaciones = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaPeriodoLiquidadoVacaciones.save(empleado.fecha_periodoliquidadovacaciones)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm4>
                            <v-text-field label="Últimas pagadas" v-model="empleado.ultimas_vacaciones_pagadas"></v-text-field>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Incrementos</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm3>
                            <v-subheader class="tipo">Incrementos por antiguedad</v-subheader>
                            <v-radio-group v-model="empleado.inc_antiguedad" row>
                              <v-radio label="Si" value="Si"></v-radio>
                              <v-radio label="No" value="No"></v-radio>
                            </v-radio-group>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-text-field label="Inicial" v-model="empleado.inc_inicial"></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-text-field label="Anterior" v-model="empleado.inc_anterior"></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-text-field label="Nuevo" v-model="empleado.inc_nuevo"></v-text-field>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                </v-layout>
              </v-container>
            </v-stepper-content>
          </v-stepper-items>
          <v-stepper-items>
            <v-stepper-content step="3">
              <v-container fluid grid-list-xl >
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Retención</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm3>
                            <v-text-field label="Ded. Vivienda" v-model="empleado.ded_vivienda"></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-dialog
                              ref="pickerFechaVencVievienda"
                              v-model="pickerFechaVencVievienda"
                              :return-value.sync="empleado.fecha_vencvivienda"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_vencvivienda"
                                label="Periodo liquidado"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_vencvivienda" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaVencVievienda = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaVencVievienda.save(empleado.fecha_vencvivienda)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-text-field label="Ded. Ult. 12 Mes" v-model="empleado.ded_ult12"></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-text-field label="Salud año" v-model="empleado.ded_salud_year"></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-text-field label="Ded. Est./Med. Prep" v-model="empleado.ded_est_med_prep"></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-dialog
                              ref="pickerFechaVencEst"
                              v-model="pickerFechaVencEst"
                              :return-value.sync="empleado.fecha_vencest"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_vencest"
                                label="Periodo liquidado"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_vencest" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaVencEst = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaVencEst.save(empleado.fecha_vencest)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-subheader class="tipo">Proc. RTF</v-subheader>
                            <v-radio-group v-model="empleado.proc_rtf" row>
                              <v-radio label="Si" value="Si"></v-radio>
                              <v-radio label="No" value="No"></v-radio>
                            </v-radio-group>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-subheader class="tipo">RTF Por</v-subheader>
                            <v-radio-group v-model="empleado.rtf_por" row>
                              <v-radio label="Valor" value="Valor"></v-radio>
                              <v-radio label="%" value="%"></v-radio>
                            </v-radio-group>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm12 md5 lg5>
                            <v-layout row wrap>
                              <v-flex xs12 sm12 md12 lg12>
                                Tipo de vinculacion
                                <v-radio-group v-model="empleado.tipo_vinculacion">
                                  <v-radio
                                    v-for="(tipos,key,index) in complementosEmpleado.tipoVinculacion"
                                    :key="'vinculacionId'+key"
                                    :label="tipos"
                                    :value="tipos"
                                  ></v-radio>
                                </v-radio-group>
                              </v-flex>
                            </v-layout>
                          </v-flex>
                          <v-flex xs12 sm12 md7 lg7>
                            <v-layout row wrap>
                              <v-flex xs12 sm12 md12 lg12>
                                <v-subheader class="tipo">Jornada laboral</v-subheader>
                                <v-radio-group v-model="empleado.jornada_laboral" row>
                                  <v-radio label="Completa" value="Completa"></v-radio>
                                  <v-radio label="Medio tiempo" value="Medio tiempo"></v-radio>
                                </v-radio-group>
                              </v-flex>
                              <v-flex xs12 sm12 md6 lg6>
                                <v-select
                                  :items="complementosEmpleado.jornadaAsignada"
                                  label="Jornada asignada"
                                  v-model="empleado.jornada_asignada"
                                ></v-select>
                              </v-flex>
                              <v-flex xs12 sm12 md6 lg6>
                                <v-subheader class="tipo">Ley 50</v-subheader>
                                <v-radio-group v-model="empleado.ley_50" row>
                                  <v-radio label="Si" value="Si"></v-radio>
                                  <v-radio label="No" value="No"></v-radio>
                                </v-radio-group>
                              </v-flex>
                              <v-flex xs12 sm12 md6 lg6>
                                <v-text-field label="Meses completos el ultimo año" v-model="empleado.meses_completos"></v-text-field>
                              </v-flex>
                              <v-flex xs12 sm12 md6 lg6>
                                <v-subheader class="tipo">Sindicato</v-subheader>
                                <v-radio-group v-model="empleado.sindicato" row>
                                  <v-radio label="Si" value="Si"></v-radio>
                                  <v-radio label="No" value="No"></v-radio>
                                </v-radio-group>
                              </v-flex>
                              <v-flex xs12 sm12 md6 lg6>
                                <v-text-field label="IBC" v-model="empleado.ibc"></v-text-field>
                              </v-flex>
                              <v-flex xs12 sm12 md3 lg3>
                                <v-text-field label="Dias" v-model="empleado.dias"></v-text-field>
                              </v-flex>
                              <v-flex xs12 sm12 md3 lg3>
                                <v-text-field label="Horas" v-model="empleado.horas"></v-text-field>
                              </v-flex>
                              <v-flex xs12 sm12 md12 lg12>
                                <v-autocomplete
                                  label="Cargo inicial"
                                  v-model="empleado.th_cargo_inicial_id"
                                  :items="cargos"
                                  item-value="id"
                                  item-text="nombre"
                                  name="cargo inicial"
                                  persistent-hint
                                  prepend-icon="face"
                                  no-data-text="No existen datos"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('cargo inicial')"
                                  clearable>
                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.nombre"/>
                                        <v-list-tile-sub-title v-html="'Código: ' + data.item.codigo"></v-list-tile-sub-title>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                              </v-flex>
                              <v-flex xs12 sm12 md12 lg12>
                                <v-autocomplete
                                  label="Cargo actual"
                                  v-model="empleado.th_cargo_actual_id"
                                  :items="cargos"
                                  item-value="id"
                                  item-text="nombre"
                                  name="cargo actual"
                                  persistent-hint
                                  prepend-icon="face"
                                  no-data-text="No existen datos"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('cargo actual')"
                                  clearable>
                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.nombre"/>
                                        <v-list-tile-sub-title v-html="'Código: ' + data.item.codigo"></v-list-tile-sub-title>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                              </v-flex>
                              <v-flex xs12 sm12 md12 lg12>
                                <v-autocomplete
                                  label="Grado de profesionalismo"
                                  v-model="empleado.th_profesionalismo_id"
                                  :items="gradosProfesional"
                                  item-value="id"
                                  item-text="nombre"
                                  name="grado profesional"
                                  persistent-hint
                                  prepend-icon="face"
                                  no-data-text="No existen datos"
                                  required
                                  v-validate="'required'"
                                  :error-messages="errors.collect('grado profesional')"
                                  clearable>
                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.nombre"/>
                                        <v-list-tile-sub-title v-html="'Código: ' + data.item.codigo"></v-list-tile-sub-title>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                              </v-flex>
                            </v-layout>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                </v-layout>
              </v-container>
            </v-stepper-content>
          </v-stepper-items>
          <v-stepper-items>
            <v-stepper-content step="4">
              
              <v-container fluid grid-list-xl >
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm12>
                            <v-subheader class="tipo">Forma de pago</v-subheader>
                            <v-radio-group v-model="empleado.forma_pago" row>
                              <v-radio label="Por consignacion" value="Por consignacion"></v-radio>
                              <v-radio label="En cheque" value="En cheque"></v-radio>
                            </v-radio-group>
                          </v-flex>

                          <v-flex xs12 sm6>
                            <v-autocomplete
                              label="Banco"
                              v-model="empleado.ts_banco_id"
                              :items="complementosBancos.bancos"
                              item-value="id"
                              item-text="nombre"
                              :filter="filterBancos"
                              prepend-icon="account_balance"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.codigo"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm6>
                            <v-radio-group v-model="empleado.tipo_cuenta"
                                           :column="false">
                              <v-radio
                                key="cuentaAhorro"
                                label="Cuenta ahorros"
                                value="cuenta ahorros"
                              ></v-radio>
                              <v-radio
                                key="cuentaCorriente"
                                label="Cuenta corriente"
                                value="Cuenta corriente"
                              ></v-radio>
                            </v-radio-group>
                          </v-flex>

                          <v-flex xs12 sm6>
                            <v-text-field label="Número de cuenta" v-model="empleado.numero_cuenta" prepend-icon="subtitles"></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm3 >
                            <v-checkbox label="Afiliado al FNA" v-model="empleado.afiliado_fna"></v-checkbox>
                          </v-flex>

                          <v-flex xs12 sm3>
                            <v-checkbox label="Regimen de retroactividad" v-model="empleado.retroactividad"></v-checkbox>
                          </v-flex>


                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> Sucursal</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm6>
                            <v-text-field label="Sucursal" readonly></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm3>
                            <v-text-field label="Tarifa" v-model="empleado.tarifa" ></v-text-field>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> Salud</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm12 md6 lg6>
                            <v-autocomplete
                              label="EPS"
                              v-model="empleado.as_eps_id"
                              :items="complementosEps.epss"
                              item-value="id"
                              item-text="nombre"
                              :filter="filterEps"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.cod_habilitacion"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm12 md2 lg2>
                            <v-dialog
                              ref="pickerFechaEps"
                              v-model="pickerFechaEps"
                              :return-value.sync="empleado.fecha_eps"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_eps"
                                label="Fecha"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_eps" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaEps = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaEps.save(empleado.fecha_eps)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-autocomplete
                              label="Fon. Salud Vol"
                              v-model="empleado.fon_salud"
                              :items="fondosDeSalud"
                              item-value="id"
                              item-text="nombre"
                              name="fon. salud"
                              persistent-hint
                              no-data-text="No existen datos"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('fon. salud')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="'Código: ' + data.item.codigo"></v-list-tile-sub-title>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm12 md2 lg2>
                            <v-dialog
                              ref="pickerFechaSalud"
                              v-model="pickerFechaSalud"
                              :return-value.sync="empleado.fecha_salud"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_salud"
                                label="Fecha"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_salud" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaSalud = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaSalud.save(empleado.fecha_salud)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>


                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> Fondo pensiones</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm12 md6>
                            <v-autocomplete
                              label="Fondo de pension"
                              v-model="empleado.as_afps_id"
                              :items="complementosFormAfiliados.afps"
                              item-value="id"
                              item-text="nombre"
                              :filter="filterFondosPension"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.codigo"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm12 md2 lg2>
                            <v-dialog
                              ref="pickerFechaPension"
                              v-model="pickerFechaPension"
                              :return-value.sync="empleado.fecha_pension"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_pension"
                                label="Fecha"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_pension" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaPension = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaPension.save(empleado.fecha_pension)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                          <v-flex xs12 sm12 md6 lg6>
                            <v-autocomplete
                              label="Fondo de Pension Voluntaria 1"
                              v-model="empleado.as_afps_vol1_id"
                              :items="complementosFormAfiliados.afps"
                              item-value="id"
                              item-text="nombre"
                              :filter="filterFondosPension"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.codigo"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm12 md2 lg2>
                            <v-dialog
                              ref="pickerFechaPensionVol1"
                              v-model="pickerFechaPensionVol1"
                              :return-value.sync="empleado.fecha_pensionvol1"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_pensionvol1"
                                label="Fecha"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_pensionvol1" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaPensionVol1 = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaPensionVol1.save(empleado.fecha_pensionvol1)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                          <v-flex xs12 sm12 md6 lg6>
                            <v-autocomplete
                              label="Fondo de Pension Voluntaria 2"
                              v-model="empleado.as_afps_vol2_id"
                              :items="complementosFormAfiliados.afps"
                              item-value="id"
                              item-text="nombre"
                              :filter="filterFondosPension"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.codigo"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm12 md2 lg2>
                            <v-dialog
                              ref="pickerFechaPensionVol2"
                              v-model="pickerFechaPensionVol2"
                              :return-value.sync="empleado.fecha_pensionvol2"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_pensionvol2"
                                label="Fecha"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_pensionvol2" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaPensionVol2 = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaPensionVol2.save(empleado.fecha_pensionvol2)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                          <v-flex xs12 sm12 md6 lg6>
                            <v-autocomplete
                              label="Fondo de Pension Voluntaria 3"
                              v-model="empleado.as_afps_vol3_id"
                              :items="complementosFormAfiliados.afps"
                              item-value="id"
                              item-text="nombre"
                              :filter="filterFondosPension"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.codigo"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm12 md2 lg2>
                            <v-dialog
                              ref="pickerFechaPensionVol3"
                              v-model="pickerFechaPensionVol3"
                              :return-value.sync="empleado.fecha_pensionvol3"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_pensionvol3"
                                label="Fecha"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_pensionvol3" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaPensionVol3 = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaPensionVol3.save(empleado.fecha_pensionvol3)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>


                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> Cesantías</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm6>
                            <v-autocomplete
                              label="Fon. Cesantias"
                              v-model="empleado.fon_cesantias"
                              :items="fondosDeCesantia"
                              item-value="id"
                              item-text="nombre"
                              name="fon. cesantia"
                              persistent-hint
                              no-data-text="No existen datos"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('fon. cesantia')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="'Código: ' + data.item.codigo"></v-list-tile-sub-title>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm2>
                            <v-dialog
                              ref="pickerFechaCesantias"
                              v-model="pickerFechaCesantias"
                              :return-value.sync="empleado.fecha_cesantias"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_cesantias"
                                label="Fecha"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_cesantias" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaCesantias = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaCesantias.save(empleado.fecha_cesantias)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                          <v-flex xs12 sm6>
                            <v-autocomplete
                              label="Cajas de compensacion"
                              v-model="empleado.as_ccfs_id"
                              :items="complementosFormAfiliados.ccfs"
                              item-value="id"
                              item-text="nombre"
                              :filter="filterCajaCompensacion"
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                    <v-list-tile-sub-title v-html="data.item.cod_habilitacion"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm2>
                            <v-dialog
                              ref="pickerFechaCajaCompensacion"
                              v-model="pickerFechaCajaCompensacion"
                              :return-value.sync="empleado.fecha_caja"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_caja"
                                label="Fecha"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_caja" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaCajaCompensacion = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaCajaCompensacion.save(empleado.fecha_caja)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                          <v-flex xs12 sm6>
                              <v-flex xs12 sm12 md12 lg12>
                                <v-checkbox label="Afiliado a plan de atencion complementaria" v-model="empleado.afiliado_pac"></v-checkbox>
                              </v-flex>
                          </v-flex>

                          <v-flex xs12 sm2>
                            <v-dialog
                              ref="pickerFechaAfiliacionPAC"
                              v-model="pickerFechaAfiliacionPAC"
                              :return-value.sync="empleado.fecha_afiliacionpac"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_afiliacionpac"
                                label="Fecha de afiliacion PAC"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_afiliacionpac" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaAfiliacionPAC = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaAfiliacionPAC.save(empleado.fecha_afiliacionpac)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                          <v-flex xs12 sm6>
                              <v-flex xs12 sm12 md12 lg12>
                                <v-checkbox label="Subsidiado 2/3 del plan de atencion complementaria" v-model="empleado.subsidiado_pac"></v-checkbox>
                              </v-flex>
                          </v-flex>

                          <v-flex xs12 sm2>
                            <v-dialog
                              ref="pickerFechaSuspencionPac"
                              v-model="pickerFechaSuspencionPac"
                              :return-value.sync="empleado.fecha_suspencionpac"
                              persistent
                              lazy
                              full-width
                              width="290px">
                              <v-text-field
                                slot="activator"
                                v-model="empleado.fecha_suspencionpac"
                                label="Fecha de suspencion PAC"
                                prepend-icon="event"
                                readonly>
                              </v-text-field>
                              <v-date-picker v-model="empleado.fecha_suspencionpac" locale="es-co"
                                             :first-day-of-week="1" scrollable>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="pickerFechaSuspencionPac = false">Cancelar</v-btn>
                                <v-btn flat color="primary" @click="$refs.pickerFechaSuspencionPac.save(empleado.fecha_suspencionpac)">OK</v-btn>
                              </v-date-picker>
                            </v-dialog>
                          </v-flex>

                          <v-flex xs12 sm6>
                            <v-flex xs12 sm12 md12 lg12>
                              <v-checkbox label="Suspender aporte PAC" v-model="empleado.suspender_pac"></v-checkbox>
                            </v-flex>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>




                </v-layout>
              </v-container>
            </v-stepper-content>
            <v-layout row wrap>
              <v-flex xs12 sm4 class="text-xs-center text-sm-left">
                <v-tooltip v-if="stepActual > 1" top>
                  <v-btn slot="activator" fab small color="primary" @click.native="stepActual--">
                    <v-icon>arrow_back_ios</v-icon>
                  </v-btn>
                  <span>Anterior</span>
                </v-tooltip>
                <v-tooltip v-if="stepActual < 4" top>
                  <v-btn slot="activator" fab small color="primary" @click.native="stepActual++">
                    <v-icon>arrow_forward_ios</v-icon>
                  </v-btn>
                  <span>Siguiente</span>
                </v-tooltip>
              </v-flex>
            </v-layout>
          </v-stepper-items>
        </v-stepper>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit" >Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
    <v-dialog v-model="dialogCodigo" persistent max-width="290">      
      <v-card>
        <v-card-title class="headline">Codigo del empleado</v-card-title>
        <v-card-text>El codigo del empleado ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click="editarByCodigo">Cargar datos</v-btn>
          <v-btn color="green darken-1" flat @click="cerrarByCodigo">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import store from '@/store/complementos/index'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {EMPLEADO_TH_RELOAD_ITEM} from '@/store/modules/general/tables'
  import moment from 'moment'
  export default {
    name: 'RegistroEmpleado',
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    props: ['parametros'],
    data () {
      return {
        filterMunicipios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterBancos (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.codigo)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterEps (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.cod_habilitacion + ' ' + item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterFondosPension (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codigo + ' ' + item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterCajaCompensacion (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.cod_habilitacion + ' ' + item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        complementosEmpleado: {
          estadosCivil: [],
          tiposSangre: [],
          jornadaAsignada: [],
          tipoVinculacion: []
        },
        dialogCodigo: false,
        buscandoCodigo: false,
        stepActual: 1,
        fechaActual: '2018-01-01',
        tabActive: null,
        pickerFechaIngreso: false,
        pickerFechaIngresoEnt: false,
        pickerFechaRetiro: false,
        pickerFechaRetiroPensiones: false,
        pickerFechaSueldoAnterior: false,
        pickerFechaSueldoNuevo: false,
        pickerFechaInicioAntiguedad: false,
        pickerFechaUltBonificacion: false,
        pickerFechaUltPNavidad: false,
        pickerFechaUltPServicios: false,
        pickerFechaInicioVacaciones: false,
        pickerFechaFinalVacaciones: false,
        pickerFechaIngresoVacaciones: false,
        pickerFechaPeriodoLiquidadoVacaciones: false,
        pickerFechaVencVievienda: false,
        pickerFechaVencEst: false,
        pickerFechaAfiliacionPAC: false,
        pickerFechaSuspencionPac: false,
        pickerFechaEps: false,
        pickerFechaSalud: false,
        pickerFechaPension: false,
        pickerFechaPensionVol1: false,
        pickerFechaPensionVol2: false,
        pickerFechaPensionVol3: false,
        pickerFechaCesantias: false,
        pickerFechaCajaCompensacion: false,
        pickerFechaNacimiento: false,
        ordenEdit: false,
        loadingSubmit: false,
        empleado: {
          tercero: {
            identificacion: '',
            apellido1: '',
            apellido2: '',
            nombre1: '',
            nombre2: '',
            direccion: '',
            telefono_fijo: '',
            municipio: {
              nombre: '',
              nombre_departamento: ''
            },
            municipio_expedicion: {
              nombre: '',
              nombre_departamento: ''
            },
            tipo_id: {}
          },
          gn_pais_id: 45
        },
        gruposEmpleados: [],
        subGruposEmpleados: [],
        motivosRetiro: [],
        cargos: [],
        gradosProfesional: [],
        fondosDeSalud: [],
        fondosDeCesantia: []
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)

      this.getGruposEmpleados()
      this.getSubGruposEmpleados()
      this.getMotivosRetiro()
      this.getCargos()
      this.getGradosProfesionalismo()
      this.getFondos()

      this.fechaActual = moment().format('YYYY-MM-DD')
      this.axios.get('empleado/complementos')
        .then((response) => {
          this.complementosEmpleado = response.data.data
        }).catch(e => {
          this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
        })
    },
    watch: {
      'empleado.fecha_nacimiento' () {
        var nacimiento = moment(this.empleado.fecha_nacimiento)
        var hoy = moment()
        this.empleado.edad = hoy.diff(nacimiento, 'years')
      },
      'empleado.salario' (val) {
        if (val === 'Normal') {
          this.empleado.factura = ''
        }
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosTercerosFormComplementarios
      },
      complementosBancos () {
        return store.getters.complementosFormBancos
      },
      complementosEps () {
        return store.getters.complementosFormTraslado
      },
      complementosFormAfiliados () {
        return store.getters.complementosFormAfiliados
      }
    },
    methods: {
      findByCodigo () {
        if (this.empleado.codigo) {
          this.buscandoCodigo = true
          this.axios.get('empleado/codigo/' + this.empleado.codigo)
            .then(response => {
              if (response.data.exists) {
                this.$store.commit(SNACK_SHOW, {msg: 'El codigo del empleado existe', color: 'success'})
                this.formReset()
                console.log(response.data.data)
                this.procesarEmpleado(response.data.data)
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              console.log(e)
              this.buscandoCodigo = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al consultar el codigo. ' + e.response, color: 'danger'})
            })
        }
      },
      seleccionTercero (val) {
        if (val !== null) {
          if (val.id !== null && val.id !== '' && val.id !== undefined) {
            if (val.id !== this.empleado.tercero.id) {
              this.axios.get('empleado/tercero/' + val.id)
                .then((response) => {
                  if (response.data.exists) {
                    console.log(response.data.tercero)
                    this.procesarTercero(response.data.tercero)
                  } else {
                    this.$store.commit(SNACK_SHOW, {msg: 'No podemos recuperar los datos del tercero. Intente de nuevo', color: 'danger'})
                  }
                }).catch(e => {
                  this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
                })
            }
          }
        }
      },
      getRegistro (id) {
        let loader = this.$loading.show({
          container: this.$refs.loaderRef.$el
        })
        this.axios.get('empleado/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.empleado = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al hacer la busqueda de registros. ', error: e})
          })
      },
      getGruposEmpleados () {
        this.axios.get('th_grupos_empleados')
          .then((response) => {
            if (response.data !== '') {
              this.gruposEmpleados = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
          })
      },
      getSubGruposEmpleados () {
        this.axios.get('th_subgrupos_empleados')
          .then((response) => {
            if (response.data !== '') {
              this.subGruposEmpleados = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
          })
      },
      getMotivosRetiro () {
        this.axios.get('th_retiros')
          .then((response) => {
            if (response.data !== '') {
              this.motivosRetiro = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
          })
      },
      getCargos () {
        this.axios.get('th_cargos_empleados')
          .then((response) => {
            if (response.data !== '') {
              this.cargos = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
          })
      },
      getGradosProfesionalismo () {
        this.axios.get('th_profesionalismos')
          .then((response) => {
            if (response.data !== '') {
              this.gradosProfesional = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
          })
      },
      getFondos () {
        this.axios.get('th_fondos')
          .then((response) => {
            if (response.data !== '') {
              for (const fondo of response.data.data) {
                if (fondo.tipo_fondo === 'Salud') this.fondosDeSalud.push(fondo)
                if (fondo.tipo_fondo === 'Cesantías') this.fondosDeCesantia.push(fondo)
              }
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los registros. ' + e.response, color: 'danger'})
          })
      },
      procesarEmpleado (val) {
        if (val.tercero.nombre1 === null) val.tercero.nombre1 = ''
        if (val.tercero.nombre2 === null) val.tercero.nombre2 = ''
        if (val.tercero.apellido1 === null) val.tercero.apellido1 = ''
        if (val.tercero.apellido2 === null) val.tercero.apellido2 = ''
        if (val.tercero.municipio === null) val.tercero.municipio = {nombre: '', nombre_departamento: ''}
        if (val.tercero.municipio_expedicion === null) val.tercero.municipio_expedicion = {nombre: '', nombre_departamento: ''}
        this.empleado = val
      },
      editarByCodigo () {
        this.dialogCodigo = false
        this.ordenEdit = true
      },
      cerrarByCodigo () {
        this.formReset()
        this.ordenEdit = false
        this.dialogCodigo = false
      },
      procesarTercero (val) {
        if (val.nombre1 === null) val.nombre1 = ''
        if (val.nombre2 === null) val.nombre2 = ''
        if (val.apellido1 === null) val.apellido1 = ''
        if (val.apellido2 === null) val.apellido2 = ''
        if (val.municipio === null) val.municipio = {nombre: '', nombre_departamento: ''}
        if (val.municipio_expedicion === null) val.municipio_expedicion = {nombre: '', nombre_departamento: ''}
        this.empleado.tercero = val
        this.empleado.gn_tercero_id = val.id
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formRegistroEmpleado')) {
          this.loadingSubmit = true
          if (this.empleado.id) {
            this.axios.put('empleado/' + this.empleado.id, this.empleado)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El empleado se actualizo correctamente', color: 'success'})
                this.formReset()
                console.log(response.data.data)
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(EMPLEADO_TH_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('empleado', this.empleado)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El empleado se creó correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(EMPLEADO_TH_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      formReset () {
        this.stepActual = 1
        this.fechaActual = '2018-01-01'
        this.tabActive = null
        this.pickerFechaIngreso = false
        this.pickerFechaIngresoEnt = false
        this.pickerFechaRetiro = false
        this.pickerFechaRetiroPensiones = false
        this.pickerFechaSueldoAnterior = false
        this.pickerFechaSueldoNuevo = false
        this.pickerFechaInicioAntiguedad = false
        this.pickerFechaUltBonificacion = false
        this.pickerFechaUltPNavidad = false
        this.pickerFechaUltPServicios = false
        this.pickerFechaInicioVacaciones = false
        this.pickerFechaFinalVacaciones = false
        this.pickerFechaIngresoVacaciones = false
        this.pickerFechaPeriodoLiquidadoVacaciones = false
        this.pickerFechaVencVievienda = false
        this.pickerFechaVencEst = false
        this.pickerFechaAfiliacionPAC = false
        this.pickerFechaSuspencionPac = false
        this.pickerFechaEps = false
        this.pickerFechaSalud = false
        this.pickerFechaPension = false
        this.pickerFechaPensionVol1 = false
        this.pickerFechaPensionVol2 = false
        this.pickerFechaPensionVol3 = false
        this.pickerFechaCesantias = false
        this.pickerFechaCajaCompensacion = false
        this.pickerFechaNacimiento = false
        this.ordenEdit = false
        this.empleado = {
          tercero: {
            identificacion: '',
            apellido1: '',
            apellido2: '',
            nombre1: '',
            nombre2: '',
            direccion: '',
            telefono_fijo: '',
            municipio: {
              nombre: '',
              nombre_departamento: ''
            },
            municipio_expedicion: {
              nombre: '',
              nombre_departamento: ''
            },
            tipo_id: {
              abreviatura: '-'
            }
          }
        }
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      }
    }
  }
</script>

<style scoped>
  .tipo {
    height: 10px;
    padding: 0;
  }
  .no-padding {
    padding: 0;
  }
</style>
