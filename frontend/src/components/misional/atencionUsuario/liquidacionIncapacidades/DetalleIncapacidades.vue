<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formValor">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Nuevo valor</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12>
                <v-text-field v-model="nuevoIbc"
                              label="Salario base de cotización" key="salario base de cotizacion"
                              name="salario base de cotizacion"
                              :error-messages="errors.collect('salario base de cotizacion')"></v-text-field>


              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="dialog = false">Cancelar</v-btn>
            <v-btn color="primary" @click="cerrarModalYCalcularLiquidacion" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-dialog v-model="dialogDiasPagar" width="500px">
      <v-form data-vv-scope="formValor">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Nuevo valor</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12 sm12 md12>
                <v-text-field v-model="nuevoDias"
                              label="Días a pagar" key="dias a pagar"
                              name="dias a pagar"
                              :error-messages="errors.collect('dias a pagar')"></v-text-field>


              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="dialogDiasPagar = false">Cancelar</v-btn>
            <v-btn color="primary" @click="cerrarModalDiasYCalcularLiquidacion" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-dialog v-model="dialogMaternidad" width="500px" v-if="estadoRelacionLaboral && estadoRelacionLaboral.maternidad">
      <v-card>
        <v-card-text class="pa-0">
          <v-data-table
            :headers="headers"
            :items=" estadoRelacionLaboral ? estadoRelacionLaboral.maternidad.aportes : []"
            class="elevation-0"
            hide-actions
            no-data-text="No existen datos"
          >
            <template slot="items" slot-scope="props">
              <td class="text-xs-center">{{ props.item.id }}</td>
              <td class="text-xs-center">{{ props.item.periodo }}</td>
              <td class="text-xs-center">{{ props.item.ibc | numberFormat(0,'$') }}</td>
              <td class="text-xs-center">{{ props.item.dias }}</td>
            </template>
          </v-data-table>
        </v-card-text>
        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" flat @click="cerrarDialogMaternidad">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card ref="formLiquidacion">
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12>
            <v-card flat class="content-list-p0 pt-0">
              <v-list two-line>
                <template>
                  <v-list-tile>
                    <v-list-tile-avatar color="info">
                      <v-icon dark>assignment</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        Detalle liquidación incapacidad
                      </v-list-tile-title>
                      <v-list-tile-sub-title v-if="liquidacion">No. {{ liquidacion.consecutivo }}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-spacer></v-spacer>
                  <v-divider/>
                </template>
              </v-list>
              <v-card-text>
                <v-container fluid grid-list-xl class="pa-0" ref="detalleLiquidacion">
                  <loading v-model="loading" />
                  <v-slide-y-transition group>

                    <v-layout row wrap v-if="liquidacion" key="seccion1">
                      <v-flex xs12 v-if="estadoRelacionLaboral.periodo_actual_mora">
                        <v-alert :value="true" type="error">
                          El periodo actual esta en mora
                        </v-alert>
                      </v-flex>

                      <v-flex xs12 v-if="estadoRelacionLaboral.periodo_consultado_mora">
                        <v-alert :value="true" type="error">
                          El periodo en el que se generó la incapacidad está en mora
                        </v-alert>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Afiliado</v-list-tile-sub-title>
                          <span style="text-decoration: underline; cursor: pointer" @click.prevent="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: {id: liquidacion.afiliado.id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})">
                            {{ liquidacion.afiliado.nombre_completo }}
                          </span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm6>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Tipo Incapacidad</v-list-tile-sub-title>
                          <span>{{ liquidacion.tipo_incapacidad.prestacion.tipo }} > {{ liquidacion.tipo_incapacidad.tipo }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Identificación</v-list-tile-sub-title>
                          <span>{{ liquidacion.afiliado.identificacion_completa }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Fecha nacimiento</v-list-tile-sub-title>
                          <span>{{ liquidacion.afiliado.fecha_nacimiento }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Estado</v-list-tile-sub-title>
                          <span>{{ liquidacion.estado }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Prorroga</v-list-tile-sub-title>
                          <span>{{ liquidacion.tipo_incapacidad.tipo_incapacidad == 1 ? 'Si' : 'No' }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Días incapacidad</v-list-tile-sub-title>
                          <span>{{ liquidacion.dias_incapacidad }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Días incapacidad total</v-list-tile-sub-title>
                          <span>{{ liquidacion.dias_incapacidad_total }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3 v-if="liquidacion.tipo_incapacidad.prestacion.tipo === 'LICENCIA DE MATERNIDAD'">
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Días gestación</v-list-tile-sub-title>
                          <span>{{ liquidacion.dias_gestacion }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3 v-if="liquidacion.dias_prematuro">
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Días prematuro</v-list-tile-sub-title>
                          <span>{{ liquidacion.dias_prematuro }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Relación laboral</v-list-tile-sub-title>
                          <span>{{ liquidacion.relacion_laboral.pagador.razon_social }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Inicio incapacidad</v-list-tile-sub-title>
                          <span>{{ liquidacion.fecha_inicio }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Fin incapacidad</v-list-tile-sub-title>
                          <span>{{ liquidacion.fecha_fin }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Pagar a</v-list-tile-sub-title>
                          <span>{{ liquidacion.pagar_a }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">DX</v-list-tile-sub-title>
                          <span>{{ liquidacion.cie10.codigo }} - {{ liquidacion.cie10.descripcion }}</span>
                        </v-list-tile-content>
                      </v-flex>

                    </v-layout>

                    <v-layout row wrap key="aportesAfiliado">
                      <v-flex xs12>
                        <v-btn v-if="estadoRelacionLaboral && estadoRelacionLaboral.maternidad" @click="dialogMaternidad = true" color="info" >Aportes afiliado</v-btn>
                      </v-flex>
                    </v-layout>

                    <v-layout row wrap v-if="liquidacion && liquidacion.incapacidad_anterior" key="seccionIncapacidadAnterior">
                      <v-flex xs12 class="pa-0">
                        <v-subheader>Incapacidad anterior</v-subheader>
                      </v-flex>
                      <v-flex xs2>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Fecha inicio</v-list-tile-sub-title>
                          <span>{{ liquidacion.incapacidad_anterior.fecha_inicio }}</span>
                        </v-list-tile-content>
                      </v-flex>
                      <v-flex xs10>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Fecha fin</v-list-tile-sub-title>
                          <span>{{ liquidacion.incapacidad_anterior.fecha_fin }}</span>
                        </v-list-tile-content>
                      </v-flex>
                    </v-layout>

                    <v-layout row wrap key="seccion1.1">
                      <v-flex xs12 sm4 v-if="liquidacion && liquidacion.histclinica">
                        <v-layout align-center fill-height>
                          <v-flex d-flex xs10 sm8>
                            <v-text-field v-model="liquidacion.histclinica.nombre"
                                          label="Historia clínica" key="historia clinica"
                                          name="historia clinica" disabled prepend-icon="attach_file"
                                          :error-messages="errors.collect('historia clinica')" ></v-text-field>

                          </v-flex>
                          <v-flex d-flex xs2 sm4>
                            <v-tooltip top>
                              <v-btn
                                fab
                                color="success"
                                small
                                :href="liquidacion.histclinica.url_signed"
                                target="_blank"
                                slot="activator"
                              >
                                <v-icon>remove_red_eye</v-icon>
                              </v-btn>
                              <span>Ver archivo</span>
                            </v-tooltip>
                          </v-flex>
                        </v-layout>
                      </v-flex>

                      <v-flex xs12 sm4 v-if="liquidacion && liquidacion.certbanco">
                        <v-layout align-center fill-height>
                          <v-flex d-flex xs10 sm8>
                            <v-text-field v-model="liquidacion.certbanco.nombre"
                                          label="Certificación bancaria" key="certificacion bancaria"
                                          name="certificacion bancaria" disabled prepend-icon="attach_file"
                                          :error-messages="errors.collect('certificacion bancaria')" ></v-text-field>

                          </v-flex>
                          <v-flex d-flex xs2 sm4>
                            <v-tooltip top>
                              <v-btn
                                fab
                                color="success"
                                small
                                :href="liquidacion.certbanco.url_signed"
                                target="_blank"
                                slot="activator"
                              >
                                <v-icon>remove_red_eye</v-icon>
                              </v-btn>
                              <span>Ver archivo</span>
                            </v-tooltip>
                          </v-flex>
                        </v-layout>
                      </v-flex>

                      <v-flex xs12 sm4 v-if="liquidacion && liquidacion.incapacidad">
                        <v-layout align-center fill-height>
                          <v-flex d-flex xs10 sm8>
                            <v-text-field v-model="liquidacion.incapacidad.nombre"
                                          label="Incapacidad" key="incapacidad"
                                          name="incapacidad" disabled prepend-icon="attach_file"
                                          :error-messages="errors.collect('incapacidad')" ></v-text-field>

                          </v-flex>
                          <v-flex d-flex xs2 sm4>
                            <v-tooltip top>
                              <v-btn
                                fab
                                color="success"
                                small
                                :href="liquidacion.incapacidad.url_signed"
                                target="_blank"
                                slot="activator"
                              >
                                <v-icon>remove_red_eye</v-icon>
                              </v-btn>
                              <span>Ver archivo</span>
                            </v-tooltip>
                          </v-flex>
                        </v-layout>
                      </v-flex>

                      <v-flex xs12 sm4 v-if="liquidacion && liquidacion.archivo_identificacion">
                        <v-layout align-center fill-height>
                          <v-flex d-flex xs10 sm8>
                            <v-text-field v-model="liquidacion.archivo_identificacion.nombre"
                                          label="Identificación" key="identificacion"
                                          name="identificacion" disabled prepend-icon="attach_file"
                                          :error-messages="errors.collect('identificacion')" ></v-text-field>

                          </v-flex>
                          <v-flex d-flex xs2 sm4>
                            <v-tooltip top>
                              <v-btn
                                fab
                                color="success"
                                small
                                :href="liquidacion.archivo_identificacion.url_signed"
                                target="_blank"
                                slot="activator"
                              >
                                <v-icon>remove_red_eye</v-icon>
                              </v-btn>
                              <span>Ver archivo</span>
                            </v-tooltip>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                    </v-layout>

                  </v-slide-y-transition>
                </v-container>
              </v-card-text>
            </v-card>

            <v-card flat class="content-list-p0 pt-0" v-if="liquidacion">
              <v-list two-line>
                <template>
                  <v-divider/>
                  <v-list-tile>
                    <v-list-tile-avatar color="grey">
                      <v-icon dark>attach_money</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        Liquidación
                      </v-list-tile-title>
                      <v-list-tile-sub-title v-if="liquidacion">Cálculo</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-spacer></v-spacer>
                  <v-divider/>
                </template>
              </v-list>
              <v-card-text>
                <v-container fluid grid-list-xl class="pa-0" ref="detalleLiquidacion">
                  <loading v-model="loading" />
                  <v-slide-y-transition group>

                    <v-layout row wrap v-if="liquidacion" key="seccion1">

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Salario base de cotización</v-list-tile-sub-title>
                          <span>
                            {{ ibc | numberFormat(0,'$') }}
                            <v-tooltip top v-if="this.liquidacion.estado !== 'Aprobada' && this.liquidacion.estado !== 'Negada'"><v-icon small slot="activator" class="ml-2" @click="abrirModal">edit</v-icon><span>Editar</span></v-tooltip>
                          </span>
                          <span v-if="activarTachadoIbc" :class="{tachado: activarTachadoIbc}">
                            {{ ibcCalculado | numberFormat(0,'$') }}
                          </span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3>
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Salario diario </v-list-tile-sub-title>
                          <span>{{ valorSalarioPorLosDias | numberFormat(0,'$') }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3 v-if="liquidacion.tipo_incapacidad.prestacion.id !== 1">
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Valor a reconocer diario <span class="font-weight-bold">({{ porcentajeAPagar }}%)</span></v-list-tile-sub-title>
                          <span>{{ valorAReconocer | numberFormat(0,'$') }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm3 v-if="liquidacion.tipo_incapacidad.prestacion.id !== 1">
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Valor mínimo a reconocer</v-list-tile-sub-title>
                          <span>{{ valorMinimioAReconocer | numberFormat(0,'$') }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm2 v-if="liquidacion.tipo_incapacidad.prestacion.id === 1">
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Días cotizados</v-list-tile-sub-title>
                          <span>{{ estadoRelacionLaboral.maternidad ? estadoRelacionLaboral.maternidad.dias_cotizados : '' }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm2 v-if="liquidacion.tipo_incapacidad.prestacion.id === 1">
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Días neto</v-list-tile-sub-title>
                          <span>{{ estadoRelacionLaboral.maternidad.dias_neto }}</span>
                        </v-list-tile-content>
                      </v-flex>

                      <v-flex xs12 sm2 v-if="liquidacion.tipo_incapacidad.prestacion.id === 1">
                        <v-list-tile-content slot="activator">
                          <v-list-tile-sub-title class="caption grey--text">Días a pagar</v-list-tile-sub-title>
                          <span>
                            {{ dias }}
                            <v-tooltip top v-if="this.liquidacion.estado !== 'Aprobada' && this.liquidacion.estado !== 'Negada'"><v-icon small slot="activator" class="ml-2" @click="abrirModalDias">edit</v-icon><span>Editar</span></v-tooltip>
                          </span>
                          <span v-if="activarTachadoDias" :class="{tachado: activarTachadoDias}">
                            {{ diasCalculado }}
                          </span>
                        </v-list-tile-content>
                      </v-flex>

                    </v-layout>

                    <v-layout row wrap v-if="liquidacion" key="seccionLiquidacion">

                      <v-flex xs6>
                        <v-card color="blue-grey darken-2" class="white--text">
                          <v-card-title primary-title>
                            <div>
                              <div>
                                <span>{{ valorAMultiplicar || valorSalarioPorLosDias | numberFormat(0,'$') }} x {{ totalDias }} días</span>
                                <br>
                                <span class="font-weight-bold title">TOTAL A PAGAR: </span>
                                <span class="subheading font-weight-regular">{{ valorAPagar | numberFormat(0,'$') }}</span>
                              </div>

                            </div>
                          </v-card-title>
                        </v-card>
                      </v-flex>

                    </v-layout>


                  </v-slide-y-transition>
                </v-container>
              </v-card-text>
            </v-card>

            <v-card flat class="content-list-p0 pt-0" v-if="liquidacion">
              <v-list two-line>
                <template>
                  <v-divider/>
                  <v-list-tile>
                    <v-list-tile-avatar color="success">
                      <v-icon dark>input</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        Respuesta
                      </v-list-tile-title>
                      <v-list-tile-sub-title>Estado: {{ liquidacion.estado || 'Sin respuesta'}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-spacer></v-spacer>
                  <v-divider/>
                </template>
              </v-list>
              <div>
                <v-card-text>
                  <v-container fluid grid-list-xl class="pa-0" ref="detalleLiquidacion">
                    <loading v-model="loading" />
                    <v-slide-y-transition group>

                      <div row wrap key="respuesta">
                        <v-form data-vv-scope="formEstadoIncapacidad">
                          <v-flex xs12 sm4>
                            <v-menu
                              ref="menuDate"
                              :close-on-content-click="false"
                              v-model="menuDate"
                              :nudge-right="40"
                              lazy
                              transition="scale-transition"
                              offset-y
                              full-width
                              max-width="290px"
                              min-width="290px"
                            >
                              <v-text-field
                                slot="activator"
                                v-model="computedDateFormatted"
                                label="Fecha trámite"
                                prepend-icon="event"
                                readonly
                                name="fecha trámite"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha trámite')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="liquidacion.fecha_tramite"
                                @input="menuDate = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha trámite')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                }"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12>
                            <v-subheader class="estado">Estado</v-subheader>
                            <v-radio-group v-model="estadoLiquidacion" row>
                              <v-radio label="Aprobada" value="Aprobada"></v-radio>
                              <v-radio label="Negada" value="Negada"></v-radio>
                              <v-radio label="Liquidada" value="Liquidada"></v-radio>
                            </v-radio-group>
                          </v-flex>

                          <v-flex xs12 sm4 v-if="esAprobada">
                            <v-autocomplete
                              :items="rubros"
                              v-model="liquidacion.pr_rubro_id"
                              :filter="filterRubros"
                              item-text="nombre"
                              item-value="id"
                              name="rubro"
                              label="Rubro"
                              no-data-text="No hay rubros disponibles"
                              :error-messages="errors.collect('rubro')"
                              v-validate="'required'" required
                              prepend-icon="subtitles"
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

                          <v-flex xs12>
                            <v-textarea v-model="liquidacion.observaciones"
                                        label="Observaciones" key="observaciones"
                                        name="observaciones"
                            ></v-textarea>
                          </v-flex>

                          <v-flex xs12 v-if="liquidacion.estado === 'Aprobada'">
                            <v-btn :loading="loadingPdf" color="blue-grey" class="white--text" @click="generarReporte(liquidacion.pr_solicitud_cp_id)">
                              Descargar solicitud CDP
                              <v-icon right dark>cloud_download</v-icon>
                            </v-btn>
                          </v-flex>
                        </v-form>
                      </div>
                    </v-slide-y-transition>
                  </v-container>
                </v-card-text>
              </div>

            </v-card>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="close" :loading="loadingSubmit">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn @click="submit" :loading="loadingSubmit" color="primary" :disabled="errors.any()">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import ToolbarList from '@/components/general/ToolbarList'
  import {LIQUIDACION_RELOAD_ITEM} from '../../../../store/modules/general/tables'

  export default {
    name: 'DetalleIncapacidades',
    components: {ToolbarList, Loading, InputDetailFlex: () => import('@/components/general/InputDetailFlex')},
    props: ['parametros'],
    data () {
      return {
        liquidacion: null,
        loading: false,
        loadingSubmit: false,
        estadoRelacionLaboral: null,
        ibc: null,
        ibcCalculado: null,
        nuevoIbc: null,
        activarTachadoIbc: false,
        dias: null,
        diasCalculado: null,
        nuevoDias: null,
        activarTachadoDias: false,
        valorSalarioPorLosDias: null,
        valorReconocerPorIncapacidad: null,
        valorAReconocer: 0,
        valorMinimioAReconocer: null,
        valorAMultiplicar: null,
        prorroga: null,
        totalDias: null,
        valorAPagar: null,
        pagarHasta90dias: 66.66666,
        pagarDesde91dias: 50,
        porcentajeAPagar: null,
        dialog: false,
        dialogDiasPagar: false,
        dialogMaternidad: false,
        menuDate: false,
        estadoLiquidacion: 'Liquidada',
        rubros: [],
        esAprobada: false,
        loadingPdf: false,

        headers: [
          {
            text: 'Id',
            align: 'center',
            sortable: false,
            value: 'id'
          },
          {
            text: 'Periodo',
            align: 'center',
            sortable: false,
            value: 'periodo'
          },
          {
            text: 'IBC',
            align: 'center',
            sortable: false,
            value: 'ibc'
          },
          {
            text: 'Días pagados',
            align: 'center',
            sortable: false,
            value: 'diasPagados'
          }
        ]
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) {
        this.getRegisto(this.parametros.entidad.id)
        this.getRubros()
      }
    },
    watch: {
      'estadoLiquidacion' (value) {
        value === 'Aprobada' ? this.esAprobada = true : this.esAprobada = false
      }
    },
    computed: {
      computedDateFormatted () {
        return this.formDate(this.liquidacion.fecha_tramite)
      }
    },
    methods: {
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.detalleLiquidacion.$el
        })
        this.axios.get('incapacidades/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.liquidacion = response.data.incapacidad
              this.estadoLiquidacion = this.liquidacion.estado
              this.estadoRelacionLaboral = response.data.estado_relacion_laboral
              this.porcentajeAPagar = this.liquidacion.dias_incapacidad_total <= 90 ? this.pagarHasta90dias : this.pagarDesde91dias
              this.ibc = this.estadoRelacionLaboral.promedio_ibc || this.liquidacion.relacion_laboral.ibc
              this.ibcCalculado = this.estadoRelacionLaboral.promedio_ibc || this.liquidacion.relacion_laboral.ibc
              this.liquidacion.tipo_incapacidad.prestacion.id === 1 ? this.diasMaternidad() : this.diasNormal()

              if (this.liquidacion.ibc_calculado !== this.liquidacion.ibc_pagado) {
                this.ibc = this.liquidacion.ibc_pagado
                this.ibcCalculado = this.liquidacion.ibc_calculado
                this.activarTachadoIbc = true
              }

              if (this.liquidacion.dias_calculado !== this.liquidacion.dias_pagado) {
                this.dias = this.liquidacion.dias_pagado
                this.diasCalculado = this.liquidacion.dias_calculado
                this.totalDias = this.dias
                this.activarTachadoDias = true
              }

              this.calcularLiquidacion()
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la incapacidad. ' + e, color: 'danger'})
          })
      },
      getRubros () {
        this.axios.get('incapacidades_rubros')
          .then(res => {
            this.rubros = res.data.data
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los rubros. ' + e, color: 'danger'})
          })
      },
      calcularLiquidacion () {
        this.liquidacion.tipo_incapacidad.prestacion.id === 1 ? this.calcularLiquidacionMaternidad() : this.calcularLiquidacionNormal()
      },
      calcularLiquidacionNormal () {
        let valor

        this.valorSalarioPorLosDias = this.ibc / 30
        this.valorReconocerPorIncapacidad = (this.valorSalarioPorLosDias * this.porcentajeAPagar) / 100
        this.valorMinimioAReconocer = this.estadoRelacionLaboral.smlv.valor / 30
        this.valorReconocerPorIncapacidad > this.valorMinimioAReconocer ? valor = this.valorReconocerPorIncapacidad : valor = this.valorMinimioAReconocer
        this.valorAReconocer = valor
        this.valorAReconocer > this.valorMinimioAReconocer ? this.valorAMultiplicar = this.valorAReconocer : this.valorAMultiplicar = this.valorMinimioAReconocer
        this.valorAPagar = this.valorAMultiplicar * this.totalDias
      },
      calcularLiquidacionMaternidad () {
        this.valorSalarioPorLosDias = this.ibc / 30
        this.valorAPagar = this.valorSalarioPorLosDias * this.totalDias
      },
      agregarValoresCalculados () {
        this.liquidacion.ibc_calculado = this.ibcCalculado
        this.liquidacion.ibc_pagado = this.ibc
        this.liquidacion.dias_calculado = this.diasCalculado
        this.liquidacion.dias_pagado = this.dias
        this.liquidacion.valor_a_reconocer_diario = this.valorAReconocer
        this.liquidacion.total_a_pagar = this.valorAPagar
        this.liquidacion.dias_pago = this.totalDias
        this.liquidacion.estado = this.estadoLiquidacion
      },
      diasMaternidad () {
        this.dias = this.estadoRelacionLaboral.maternidad.dias_pagar
        this.diasCalculado = this.estadoRelacionLaboral.maternidad.dias_pagar
        this.totalDias = this.estadoRelacionLaboral.maternidad.dias_pagar
      },
      diasNormal () {
        this.prorroga = this.liquidacion.tipo_incapacidad.prorroga
        this.totalDias = this.prorroga ? this.liquidacion.dias_incapacidad : this.liquidacion.dias_incapacidad - 2
        this.dias = this.totalDias
        this.diasCalculado = this.totalDias
      },
      aproximarEntero (valor) {
        return Math.round(valor)
      },
      abrirModal () {
        this.nuevoIbc = this.ibc
        this.dialog = true
      },
      abrirModalDias () {
        this.nuevoDias = this.dias
        this.dialogDiasPagar = true
      },
      cerrarModalYCalcularLiquidacion () {
        this.ibc = parseInt(this.nuevoIbc)
        this.activarTachadoIbc = true
        this.calcularLiquidacion()
        this.dialog = false
      },
      cerrarModalDiasYCalcularLiquidacion () {
        this.dias = parseInt(this.nuevoDias)
        this.totalDias = this.dias
        this.activarTachadoDias = true
        this.calcularLiquidacion()
        this.dialogDiasPagar = false
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      close () {
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      cerrarDialogMaternidad () {
        this.dialogMaternidad = false
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      filterRubros (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.codigo)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      generarReporte (id) {
        this.loadingPdf = true
        this.axios.get('firmar-ruta?nombre_ruta=certificado-presupuestal&id=' + id)
          .then((res) => {
            let url = res.data
            let win = window.open(url, '_blank')
            win.focus()
            this.loadingPdf = false
          }).catch(e => {
            this.loadingPdf = false
            this.$store.commit(SNACK_SHOW, {msg: 'Error al Imprimir. ' + e.response, color: 'danger'})
          })
      },
      async submit (estado) {
        if (await this.validador('formEstadoIncapacidad')) {
          let loader = this.$loading.show({
            container: this.$refs.cargar
          })
          this.loadingSubmit = true
          this.agregarValoresCalculados()

          this.axios.put('incapacidades/' + this.liquidacion.id, this.liquidacion)
            .then(response => {
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.$store.commit(LIQUIDACION_RELOAD_ITEM, {item: response.data.incapacidad_o, estado: 'editar', key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
              if (response.data.incapacidad.estado === 'Aprobada') this.generarReporte(response.data.incapacidad.pr_solicitud_cp_id)
              loader.hide()
            })
            .catch(e => {
              loader.hide()
              this.loadingSubmit = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e, color: 'danger'})
            })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>
  .estado {
    height: 10px;
    padding: 0;
  }

  .tachado{
    text-decoration:line-through;
  }
</style>
