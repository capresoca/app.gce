<template>
  <div>
    <v-dialog persistent
              v-model="dialog2"
              max-width="300">
      <v-card>
        <v-card-title class="headline grey lighten-3">¿En qué tipo de estructura presupuestal realizará traslado?</v-card-title>
        <v-container>
          <v-radio-group v-model="prTraslado.tipo" row>
            <v-radio label="Ingresos" value="Ingreso"></v-radio>
            <v-radio label="Gastos" value="Gasto"></v-radio>
          </v-radio-group>
        </v-container>
        <v-card-actions class="grey lighten-3">
          <v-spacer></v-spacer>
          <v-btn color="black lighten-3" medium flat v-text="'Cancelar'" @click="cerrar"></v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogThree" persistent max-width="400">
      <v-card>
        <!--<v-card-title class="headline">Use Google's location service?</v-card-title>-->
        <v-card-title class="grey lighten-3">
          <v-card-text class="title">¿Por qué anula el traslado?</v-card-text>
        </v-card-title>
        <v-container fluid>
          <v-layout row wrap>
            <v-flex xs12>
              <v-textarea
                outline
                v-model="detalle_anulacion"
                color="primary"
                name="Detalle de anulación"
              >
                <div slot="label">
                  Detalle de anulación
                </div>
              </v-textarea>
              <!--<textarea box name="Detalle de anulación" v-model="detalle_anulacion" placeholder="Detalle de anulación"></textarea>-->
            </v-flex>
          </v-layout>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn flat @click.native="anular(2)">Cancelar</v-btn>
          <v-btn color="primary" flat @click="actualizarEstado(detalle_anulacion)">Enviar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card ref="formTraladoPresupuesto">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title v-text="'Traslado a Presupuesto Inicial de ' + (prTraslado.tipo === null ? 'N/A' : (prTraslado.tipo !== 'Ingreso' ? 'Gastos' : 'Ingresos'))"> </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-chip label color="white" v-if="prTraslado.id" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="prTraslado.consecutivo_presupuestal == null ? '00' : prTraslado.consecutivo_presupuestal"></span>
        </v-chip>
        <v-chip v-if="prTraslado.id" :color="colorEstado" text-color="white">
          <v-avatar>
            <v-icon>{{ iconoEstado }}</v-icon>
          </v-avatar>
          {{ prTraslado.estado }}
        </v-chip>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12>
            <v-form data-vv-scope="formGastos">
              <v-container fluid grid-list-md>
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> Presupuesto</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>
                          <v-flex xs12 sm4 v-if="prTraslado.id">
                            <v-text-field
                              v-model="prTraslado.periodo"
                              :loading="loadingUno"
                              prepend-icon="calendar_today"
                              label="Periodo"
                              key="periodo"
                              readonly>
                            </v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 v-else>
                            <v-select
                              :items="presupuestos"
                              v-model="prTraslado.periodo"
                              :loading="loadingUno"
                              @change="val => obtenerPresupuesto(val)"
                              name="periodo"
                              label="Periodo"
                              no-data-text="No hay resoluciones disponibles"
                              :error-messages="errors.collect('periodo')"
                              v-validate="'required'" required
                              prepend-icon="calendar_today"
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field :value="presupuesto ? (presupuesto.entidad_resolucion ? presupuesto.entidad_resolucion.codigo : null) : null"
                                          prepend-icon="account_balance" :loading="loadingUno"
                                          label="Unidad ejecutora" key="unidad ejecturoa"
                                          readonly>

                            </v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field :value="presupuesto ? presupuesto.entidad_resolucion.nombre : null"
                                          :loading="loadingUno"
                                          prepend-icon="account_balance"
                                          label="Nombre" key="nombre"
                                          readonly>

                            </v-text-field>
                          </v-flex>

                        </v-layout>

                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text fluid grid-list-xl>
                        <v-layout row wrap>
                          <v-flex xs12 sm4 md4 v-if="!prTraslado.id">
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
                                label="Fecha"
                                prepend-icon="event"
                                readonly
                                name="fecha"
                                key="fecha"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="prTraslado.fecha"
                                @input="menuDate = false"
                                locale='es'
                                no-title
                                :readonly="readOnly"
                                @change="() => {
                                    let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                                    $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                 }"
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>
                          <v-flex xs12 sm4 md4 v-else>
                            <v-text-field v-model="computedDateFormatted" prepend-icon="event"
                                          label="Fecha" key="fecha"
                                          name="fecha"
                                          readonly
                                          :error-messages="errors.collect('fecha')" ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm8 md8>
                            <v-text-field v-model="prTraslado.documento" prepend-icon="assignment"
                                          label="Documento" key="documento"
                                          name="documento" v-validate="'required'" required
                                          :readonly="readOnly"
                                          :error-messages="errors.collect('documento')" ></v-text-field>
                          </v-flex>
                          <v-layout>
                            <v-flex xs12>
                              <v-textarea v-model="prTraslado.observaciones"
                                          auto-grow rows="1"
                                          prepend-icon="reorder"
                                          label="Observaciones"
                                          key="observaciones"
                                          name="observaciones"
                                          v-validate="'required'"
                                          :readonly="readOnly"
                                          :error-messages="errors.collect('observaciones')" ></v-textarea>
                            </v-flex>
                          </v-layout>
                        </v-layout>

                      </v-card-text>
                    </v-card>
                  </v-flex>
                  <v-expand-transition>
                    <v-layout v-if="presupuesto ? presupuesto.id : null">
                      <v-flex xs12 class="pb-4" v-if="presupuesto ? presupuesto.id : null">
                        <template>
                          <loading v-model="localLoading"/>
                          <v-card>
                            <v-toolbar dense class="grey lighten-3 elevation-0">
                              <v-toolbar-title class="subheading" v-text="'Traslados'"></v-toolbar-title>
                              <v-spacer></v-spacer>
                              <v-btn v-if="!readOnly" small color="primary" @click.prevent="abrirModal"><v-icon>add</v-icon> Agregar</v-btn>
                            </v-toolbar>
                            <v-expand-transition mode="in-out">
                              <v-card v-show="dialog" ref="dialogTraslados">
                                <v-card-title class="grey lighten-3 elevation-0">
                                  <span class="subheading pl-2"style="font-weight: 500" v-text="'Nuevo Traslado'"></span>
                                </v-card-title>
                                <v-form data-vv-scope="formDialogGastos">
                                  <v-container fluid grid-list-xl>
                                    <v-layout row wrap>
<!--                                      <v-flex xs12 class="pb-0 pt-0">-->
<!--                                        <v-radio-group row :key="'naturaleza'"-->
<!--                                                       label="Naturaleza"-->
<!--                                                       class="mt-1"-->
<!--                                                       v-model="detalle.naturaleza"-->
<!--                                                       v-validate="'required'"-->
<!--                                                       data-vv-as="naturaleza"-->
<!--                                                       :error-messages="errors.collect('naturaleza')"-->
<!--                                                       :name="'naturaleza'">-->
<!--                                          &lt;!&ndash;                                    <v-radio-group label="Naturaleza" class="mt-1" v-model="detalle.naturaleza" row>&ndash;&gt;-->
<!--                                          <v-radio label="Crédito" value="Crédito"></v-radio>-->
<!--                                          <v-radio label="Débito" value="Débito"></v-radio>-->
<!--                                        </v-radio-group>-->
<!--                                      </v-flex>-->
                                      <v-flex xs12 sm12 md4>
                                        <v-autocomplete
                                          :items="rubros"
                                          v-model="detalle.rubro_trasladado"
                                          @change="val => detalle.pr_rubro_trasladado_id = (val) ? val.id : null"
                                          :filter="filterRubros"
                                          :loading="loadingRubroUno"
                                          item-text="nombre"
                                          item-value="id"
                                          name="rubro a debitar"
                                          label="Rubro a Debitar"
                                          :hint="detalle.rubro_trasladado ? 'Código: ' + detalle.rubro_trasladado.codigo : ''"
                                          persistent-hint
                                          no-data-text="No hay rubros disponibles"
                                          :error-messages="errors.collect('rubro a debitar')"
                                          v-validate="'required'"
                                          prepend-icon="subtitles"
                                          return-object
                                          clearable
                                          flat
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
                                        <template>
                                          <v-expand-transition>
                                            <v-list v-if="presupuestoRubroTrasladado" class="grey lighten-4">
                                              <v-list-tile>
                                                <v-list-tile-content>
                                                  <v-list-tile-title
                                                    class="body-1"
                                                    v-html="'<b>Valor</b>' + ((!detalle.id || !detalle.indexId) ? '<b> disponible:</b> ' + currency(presupuestoRubroTrasladado.valor_disponible) : '<b> anterior:</b> ' + currency(detalle.saldo_rubro))"
                                                  >
                                                  </v-list-tile-title>
                                                  <v-list-tile-title
                                                    class="body-1"
                                                    v-html="presupuestoRubroTrasladado.tipo_gasto
                                                    ? '<b>Tipo de gasto:</b> ' + presupuestoRubroTrasladado.tipo_gasto.nombre
                                                    : (presupuestoRubroTrasladado.tipo_ingreso
                                                    ?  '<b>Tipo de ingreso:</b> ' + presupuestoRubroTrasladado.tipo_ingreso.nombre : '')"></v-list-tile-title>
                                                </v-list-tile-content>
                                              </v-list-tile>
                                            </v-list>
                                          </v-expand-transition>
                                        </template>
                                      </v-flex>
                                      <v-flex xs12 sm12 md4>
                                        <v-autocomplete
                                          :items="rubros"
                                          v-model="detalle.rubro_traslado"
                                          @change="val => detalle.pr_rubro_id = val ? val.id : null"
                                          :filter="filterRubros"
                                          item-text="nombre"
                                          item-value="id"
                                          :loading="loadingRubroDos"
                                          name="rubro"
                                          :disabled="detalle.rubro_trasladado === null ? true : (false)"
                                          label="Rubro Acreditar"
                                          :hint="detalle.rubro_traslado ? 'Código: ' + detalle.rubro_traslado.codigo : ''"
                                          persistent-hint
                                          key="rubro"
                                          no-data-text="No hay rubros disponibles"
                                          v-validate="'required|not_in:'+ rubrosCreados +'|rubroTraslado'"
                                          :error-messages="errors.collect('rubro')"
                                          required
                                          prepend-icon="subtitles"
                                          return-object
                                          clearable
                                          flat
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
                                        <template>
                                          <v-expand-transition>
                                            <v-list v-if="rubroTraslado" class="grey lighten-4">
                                              <v-list-tile>
                                                <v-list-tile-content>
                                                  <v-list-tile-title
                                                    class="body-1"
                                                    v-html="'<b>Valor</b>' + ((!detalle.id || !detalle.indexId)  ? '<b> actual:</b> ' + currency(rubroTraslado.valor_disponible) : '<b> anterior:</b> ' + currency(rubroTraslado.valor_disponible - detalle.valor_movimiento))"
                                                  >
                                                  </v-list-tile-title>
                                                  <v-list-tile-title
                                                    class="body-1"
                                                    v-html="rubroTraslado.tipo_gasto
                                                    ? '<b>Tipo de gasto:</b> ' + rubroTraslado.tipo_gasto.nombre
                                                    : (rubroTraslado.tipo_ingreso
                                                    ?  '<b>Tipo de ingreso:</b> ' + rubroTraslado.tipo_ingreso.nombre : '')"></v-list-tile-title>
                                                </v-list-tile-content>
                                              </v-list-tile>
                                            </v-list>
                                          </v-expand-transition>
                                        </template>
                                      </v-flex>
                                      <v-flex xs12 sm12 md4>
                                        <input-number
                                          label="Valor A Trasladar"
                                          key="valor a trasladar"
                                          id="valorMovimiento"
                                          name="valor a trasladar"
                                          prepend-icon="attach_money"
                                          :disabled="detalle.rubro_trasladado === null || detalle.rubro_traslado === null || ((detalle.rubro_trasladado ? detalle.rubro_trasladado.id : detalle.rubro_trasladado = null) === (detalle.rubro_traslado ? detalle.rubro_traslado.id : detalle.rubro_traslado = null)) ? true : (false)"
                                          :precision="0"
                                          v-model.number="valorTrasladado"
                                          v-validate="'required|min_value:1|max_value:' + (presupuestoRubroTrasladado ? presupuestoRubroTrasladado.valor_disponible : null)"
                                          :error-messages="errors.collect('valor a trasladar')"
                                        />
<!--                                        @input="val => modificandoSaldoRubro(val)"-->
                                        <template>
                                          <v-expand-transition>
                                            <v-list v-if="valorTrasladado !== 0 && valorTrasladado > 0" class="grey lighten-4">
                                              <v-list-tile>
                                                <v-list-tile-content>
                                                  <v-list-tile-title
                                                    class="body-1"
                                                    v-html="'<b>Saldo rubro debitado: </b>' + currency(valorATrasladar)"
                                                  >
                                                  </v-list-tile-title>
                                                  <v-list-tile-title
                                                    class="body-1"
                                                    v-html="'<b>Nuevo valor acreditado: </b>' + currency(nuevoValorTraslado)"
                                                  >
                                                  </v-list-tile-title>
                                                </v-list-tile-content>
                                              </v-list-tile>
                                            </v-list>
                                          </v-expand-transition>
                                        </template>
                                      </v-flex>
                                    </v-layout>
                                  </v-container>
                                </v-form>
                                <v-card-actions class="grey lighten-4">
                                  <v-spacer></v-spacer>
                                  <v-btn @click="cancelarDealle" flat small color="red" class="white--text" dark>Cancelar</v-btn>
                                  <v-btn @click="agregarDetalle(detalle)"  flat small color="primary" dark v-text=" detalle.id ? 'Actualizar' : 'Agregar'"></v-btn>
                                </v-card-actions>
                              </v-card>
                            </v-expand-transition>

                            <v-expand-transition>
                              <v-data-table
                                v-if="prTraslado.detalles.length"
                                :headers="headers"
                                :items="prTraslado.detalles"
                                :loading="tableLoading"
                                hide-actions rows-per-page-text="Registros por página"
                                :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                                  <template slot="items" slot-scope="props">
                                    <td class="text-xs-left">{{ props.item.rubro_trasladado ? props.item.rubro_trasladado.codigo : '' }}</td>
                                    <td class="text-xs-right">{{ props.item.saldo_rubro | numberFormat(0,'$')  }}</td>
                                    <td class="text-xs-center">{{ props.item.rubro_traslado ? props.item.rubro_traslado.codigo : '' }}</td>
                                    <td class="text-xs-right">{{ currency(props.item.valor_rubro_traslado) }}</td>
                                    <td class="text-xs-right">{{ props.item.valor_movimiento | numberFormat(0,'$')  }}</td>
<!--                                    <td class="text-xs-center">{{ props.item.naturaleza }}</td>-->
                                    <td class="text-xs-center">
                                      <v-speed-dial v-if="prTraslado.estado === 'Registrado'"
                                                    v-model="props.item.show"
                                                    direction="left"
                                                    open-on-hover
                                                    transition="slide-x-transition"
                                      >
                                        <v-btn
                                          slot="activator"
                                          v-model="props.item.show"
                                          color="blue darken-2"
                                          dark
                                          fab
                                          small
                                        >
                                          <v-icon>chevron_left</v-icon>
                                          <v-icon>close</v-icon>
                                        </v-btn>
                                        <v-tooltip top>
                                          <v-btn
                                            fab
                                            dark
                                            small
                                            color="white"
                                            slot="activator"
                                            @click="editarDetalle(props.index, props.item)"
                                          >
                                            <v-icon color="accent">mode_edit</v-icon>
                                          </v-btn>
                                          <span>Editar</span>
                                        </v-tooltip>
                                        <v-tooltip top>
                                          <v-btn
                                            fab
                                            dark
                                            small
                                            color="white"
                                            slot="activator"
                                            @click="eliminarDetalle(props.index, props.item.id)"
                                          >
                                            <v-icon color="accent">delete</v-icon>
                                          </v-btn>
                                          <span>Eliminar</span>
                                        </v-tooltip>

                                      </v-speed-dial>
                                    </td>
                                  </template>
                                  <template slot="no-data">
                                    <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
                                      Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
                                    </v-alert>
                                    <v-alert v-else :value="true" color="info" icon="info">
                                      Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
                                    </v-alert>
                                  </template>
                                  <template slot="pageText" slot-scope="props">
                                    {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                                  </template>
                                </v-data-table>
                            </v-expand-transition>
                          </v-card>
                        </template>
                      </v-flex>
                    </v-layout>
                  </v-expand-transition>
                </v-layout>
              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions v-if="prTraslado.estado === 'Registrado'" class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 sm6 class="text-xs-center text-sm-left">
            <v-btn v-if="prTraslado.id" @click="anular(1)" dark color="danger" :loading="loadingSubmit" v-text="'Anular'"></v-btn>
          </v-flex>
          <v-flex xs12 sm6 class="text-xs-center text-xs-right pt-1">
            <v-btn v-if="prTraslado.estado !== 'Confirmado'" color="primary" @click.prevent="submit" :loading="loadingSubmit">Guardar</v-btn>
            <v-btn color="success" @click="confirmar" slot="activator">
              <span v-if="!prTraslado.id">Guardar y Confirmar</span>
              <span v-else>Confirmar</span>
            </v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {TRASLADOS_PRESUPUESTALES_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import { Validator } from 'vee-validate'
  export default {
    name: 'RegistroTraslado',
    props: ['parametros'],
    components: {
      Loading,
      InputNumber: () => import('@/components/general/InputNumber')
    },
    data () {
      return {
        prTraslado: null,
        dialog2: false,
        dialogThree: false,
        presupuesto: null,
        presupuestos: [],
        presupuestoRubroTrasladado: null,
        detalle_anulacion: null,
        rubroTraslado: null,
        rubros: [],
        rubrosFiltrados: [],
        rubrosCreados: '',
        detalle: {},
        localLoading: false,
        cambiarValor: false,
        seEstaCreando: false,
        tableLoading: false,
        loadingSubmit: false,
        dialog: false,
        menuDate: false,
        readOnly: false,
        loadingUno: false,
        loadingRubroUno: false,
        loadingRubroDos: false,
        valorATrasladar: 0,
        valorTrasladado: 0,
        nuevoValorTraslado: 0,
        // Fun
        valorDisponible: 0,
        valorRubroTraslado: 0,
        headers: [
          {
            text: 'Rubro Debitado',
            align: 'left',
            sortable: false,
            value: 'rubro_trasladado'
          },
          {
            text: 'Valor Debitado',
            align: 'center',
            sortable: false,
            value: 'valorInicial'
          },
          {
            text: 'Rubro Acreditado',
            align: 'center',
            sortable: false,
            value: 'rubro_traslado'
          },
          {
            text: 'Valor Rubro Acreditado',
            align: 'center',
            sortable: false,
            value: 'rubroTraslado'
          },
          {
            text: 'Valor Trasladado',
            align: 'center',
            sortable: false,
            value: 'valor_movimiento'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    created () {
      this.formReset()
      this.resetDialog()
      this.validadorPostulador()
      Validator.extend('rubroTraslado', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = ((this.detalle.rubro_trasladado ? this.detalle.rubro_trasladado.id : 0) === value.id)
              ? {valido: false, mensaje: 'El rubro seleccionado debe ser diferente al rubro a trasladar.'}
              : {valido: true, mensaje: null}
            return resolve({
              valid: response.valido,
              data: {
                message: response.mensaje
              }
            })
          }
        }),
        getMessage: (field, params, data) => data.message
      })
    },
    mounted () {
      if (this.parametros.entidad !== null) {
        this.getRegister()
      } else {
        this.dialog2 = true
        this.seEstaCreando = true
      }
      const dict = {
        custom: {
          rubro: {
            not_in: 'El rubro ya fue registrado para traslado.'
          }
        }
      }

      this.$validator.localize('es', dict)
    },
    computed: {
      iconoEstado () {
        if (this.prTraslado.estado === 'Registrado') return 'stars'
        if (this.prTraslado.estado === 'Confirmado') return 'check_circle'
        if (this.prTraslado.estado === 'Anulado') return 'remove_circle'
      },
      colorEstado () {
        if (this.prTraslado.estado === 'Registrado') return 'primary'
        if (this.prTraslado.estado === 'Confirmado') return 'teal'
        if (this.prTraslado.estado === 'Anulado') return 'red'
      },
      computedDateFormatted () {
        return this.formDate(this.prTraslado.fecha)
      }
    },
    watch: {
      'prTraslado.tipo' (value) {
        if (value) {
          this.obtenerPresupuestos()
          this.dialog2 = false
        }
      },
      'detalle.pr_rubro_trasladado_id' (value) {
        if (value) {
          this.obtenerPresupuestoRubroATrasladar()
        } else {
          this.presupuestoRubroTrasladado = null
          this.rubroTraslado = null
          this.detalle.rubro_traslado = null
          this.detalle.pr_rubro_id = null
          this.detalle.saldo_rubro = null
        }
      },
      'detalle.pr_rubro_id' (value) {
        if (value) {
          this.obtenerRubroATraslado()
        } else {
          this.rubroTraslado = null
        }
      },
      'detalle.saldo_rubro' (value) {
        this.valorATrasladar = value === null ? 0 : value
      },
      'valorTrasladado' (val) {
        this.modificandoSaldoRubro(val)
        this.detalle.valor_movimiento = val
      }
    },
    methods: {
      getRegister () {
        let loader = this.$loading.show({
          container: this.$refs.formTraladoPresupuesto.$el
        })
        let id = this.parametros.entidad.id
        this.axios.get(`pr_traslados/${id}`)
          .then(response => {
            if (response.data !== '') {
              this.prTraslado = response.data.data
              if (response.data.data.estado === 'Registrado') {
                this.obtenerPresupuesto(this.prTraslado.periodo)
              }
              if (this.prTraslado.estado !== 'Registrado') {
                this.readOnly = true
                this.prTraslado.periodo = response.data.data.periodo
                this.presupuesto = this.prTraslado.tipo === 'Gasto' ? response.data.data.presupuesto_inicial_gasto : response.data.data.presupuesto_inicial_ingreso
                this.prTraslado.pr_strgasto_id = (this.prTraslado.tipo === 'Gasto') ? this.presupuesto.id : null
                this.prTraslado.pr_stringreso_id = (this.prTraslado.tipo === 'Ingreso') ? this.presupuesto.id : null
                this.prTraslado.detalles = response.data.data.detalles
              }
            }
            loader.hide()
          }).catch((e) => {
            loader.hide()
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el traslado presupuestal. ', error: e})
          })
      },
      modificandoSaldoRubro (value) {
        console.log('guest', value)
        if (value > this.valorDisponible || value === 0 || value === null || value === '') {
          this.valorATrasladar = this.valorDisponible
          this.nuevoValorTraslado = 0
        } else {
          let valor = parseFloat(value)
          let valorAnterior = valor
          let valorResultado = 0
          let sumaResultado = 0
          this.valorATrasladar = this.valorDisponible
          this.nuevoValorTraslado = this.valorRubroTraslado
          if (value !== valorAnterior) {
            valorResultado = Math.round(this.valorATrasladar - parseFloat(value))
            sumaResultado = Math.round(this.nuevoValorTraslado + parseFloat(value))
          } else {
            valorResultado = Math.round(this.valorATrasladar - parseFloat(valor))
            sumaResultado = Math.round(this.nuevoValorTraslado + parseFloat(valor))
          }
          console.log('valor', valor)
          this.valorATrasladar = valorResultado
          this.nuevoValorTraslado = sumaResultado
        }
        // console.log('guest2as', this.presupuestoRubroTrasladado)
        // let valorDisponible = this.presupuestoRubroTrasladado ? this.presupuestoRubroTrasladado.valor_disponible : 0
        // console.log('valorasdssd', valorDisponible)
        // let valorRubroTraslado = this.rubroTraslado ? this.rubroTraslado.valor_disponible : 0
        // console.log('valora-a', valorRubroTraslado)
        // if (value) {
        // } else {
        //   this.valorATrasladar = this.valorDisponible
        //   this.nuevoValorTraslado = 0
        //   this.cambiarValor = false
        // }
      },
      obtenerPresupuestos () {
        if (!this.prTraslado.id) {
          let getPeriodos = this.prTraslado.tipo === 'Ingreso' ? 'peridos_presupuesto_ingresos' : 'peridos_presupuesto_gastos'
          this.axios.get(getPeriodos)
            .then(res => {
              if (res.data.exists === true) {
                this.presupuestos = res.data.data
                let anioActual = this.moment().format('YYYY')
                let periodoExiste = this.presupuestos.find(type => type === anioActual)
                if (periodoExiste) {
                  this.prTraslado.periodo = anioActual
                  this.obtenerPresupuesto(anioActual)
                } else {
                  this.$store.commit(SNACK_SHOW, {msg: 'No se encuentra confirmado el periodo actual.', color: 'danger'})
                }
              } else {
                this.$store.commit(SNACK_SHOW, {msg: res.data.data, color: 'danger'})
              }
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al obtener presupuesto. ', error: e})
            })
        }
      },
      obtenerPresupuesto (periodo) {
        this.loadingUno = true
        let tipo = this.prTraslado.tipo
        this.axios.get(`periodo_presupuestos?periodo=${periodo}&tipo=${tipo}`)
          .then((response) => {
            if (response.data !== '') {
              this.presupuesto = response.data.presupuesto
              this.rubros = response.data.rubros
              this.prTraslado.pr_strgasto_id = (tipo === 'Gasto') ? this.presupuesto.id : null
              this.prTraslado.pr_stringreso_id = (tipo === 'Ingreso') ? this.presupuesto.id : null
              this.prTraslado.periodo = this.presupuesto.periodo
            }
            this.loadingUno = false
          })
          .catch(e => {
            this.loadingUno = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      obtenerPresupuestoRubroATrasladar () {
        this.loadingRubroUno = true
        let presupuestoId = this.presupuesto.id
        this.axios.get(`presupuesto_rubro_traslado?presupuestoId=${presupuestoId}&rubroId=${this.detalle.rubro_trasladado.id}&tipo=${this.prTraslado.tipo}`)
          .then((response) => {
            this.presupuestoRubroTrasladado = response.data.data
            this.detalle.saldo_rubro = this.presupuestoRubroTrasladado.valor_disponible
            this.valorDisponible = response.data.data.valor_disponible
            this.loadingRubroUno = false
          }).catch(e => {
            this.loadingRubroUno = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      obtenerRubroATraslado () {
        this.loadingRubroDos = true
        let presupuestoId = this.presupuesto.id
        this.axios.get(`presupuesto_rubro_traslado?presupuestoId=${presupuestoId}&rubroId=${this.detalle.rubro_traslado.id}&tipo=${this.prTraslado.tipo}`)
          .then((response) => {
            this.rubroTraslado = response.data.data
            this.detalle.tipo_gasto = this.rubroTraslado.tipo_gasto
            this.detalle.tipo_ingreso = this.rubroTraslado.tipo_ingreso
            this.detalle.valor_rubro_traslado = this.rubroTraslado.valor_disponible
            this.valorRubroTraslado = response.data.data.valor_disponible
            this.loadingRubroDos = false
          }).catch(e => {
            this.loadingRubroDos = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      getRubrosUsados () {
        return this.prTraslado.detalles.map(cod => { if (!(this.detalle.rubro_traslado.id != null && this.detalle.rubro_traslado.id === cod.rubro_traslado.id)) return cod.rubro_traslado.id })
      },
      abrirModal () {
        this.dialog = true
        // this.rubrosCreados = this.getRubrosUsados()
      },
      async agregarDetalle (detalle) {
        if (await this.validador('formDialogGastos')) {
          this.agregarValoresAlDetalle()
          if (typeof detalle.indexId !== 'number') {
            this.prTraslado.detalles.push(detalle)
          } else {
            this.prTraslado.detalles
              .splice(this.prTraslado.detalles
                .findIndex(x => this.prTraslado.detalles.indexOf(x) === detalle.indexId), 1, detalle)
          }
          this.$validator.reset()
          this.resetDialog()
          this.dialog = false
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
        }
      },
      editarDetalle (index, detalle) {
        this.valorTrasladado = detalle.valor_movimiento
        this.detalle = JSON.parse(JSON.stringify(detalle))
        this.detalle.indexId = index
        this.valorDisponible = this.detalle.saldo_rubro
        this.valorRubroTraslado = this.detalle.valor_rubro_traslado
        this.rubrosCreados = this.getRubrosUsados()
        this.dialog = true
      },
      eliminarDetalle (index) {
        this.prTraslado.detalles.splice(index, 1)
        this.dialog = false
      },
      cancelarDealle () {
        this.resetDialog()
        this.$validator.reset()
        this.valorTrasladado = 0
        this.dialog = false
      },
      formReset () {
        this.prTraslado = {
          id: null,
          consecutivo_presupuestal: null,
          periodo: null,
          fecha: null,
          documento: null,
          detalle_modificacion: null,
          tipo: null,
          estado: 'Registrado',
          pr_strgasto_id: null,
          pr_stringreso_id: null,
          concepto_anulacion: null,
          fecha_anulacion: null,
          detalles: []
        }
        this.rubrosCreados = this.getRubrosUsados()
        this.presupuesto = null
      },
      resetDialog () {
        this.detalle = {
          // Ids (Foraneas)
          id: null,
          indexId: null,
          pr_detstrgasto_id: null,
          pr_detstringreso_id: null,
          pr_rubro_id: null,
          pr_rubro_trasladado_id: null,
          pr_tipo_gasto_id: null,
          pr_tipo_ingreso_id: null,
          pr_detstrgastodebitado_id: null,
          pr_detstringresodebitado_id: null,
          // Objetos
          tipo_ingreso: null,
          tipo_gasto: null,
          saldo_rubro: null,
          valor_rubro_traslado: null,
          naturaleza: null,
          valor_movimiento: null,
          rubro_trasladado: null,
          detalleGasto: null,
          detalleIngreso: null,
          rubro_traslado: null
        }
        this.valorATrasladar = 0
        this.valorTrasladado = 0
        this.nuevoValorTraslado = 0
        this.valorDisponible = 0
        this.valorRubroTraslado = 0
        this.presupuestoRubroTrasladado = null
        this.rubroTraslado = null
        // this.rubrosCreados = this.getRubrosUsados()
      },
      agregarValoresAlDetalle () {
        this.detalle.saldo_rubro = this.presupuestoRubroTrasladado.valor_disponible
        if (this.prTraslado.tipo === 'Gasto') {
          this.detalle.pr_detstrgastodebitado_id = this.presupuestoRubroTrasladado.id
          this.detalle.pr_detstrgasto_id = this.rubroTraslado.id
          this.detalle.pr_tipo_gasto_id = this.rubroTraslado.tipo_gasto.id
        } else {
          this.detalle.pr_detstringresodebitado_id = this.presupuestoRubroTrasladado.id
          this.detalle.pr_detstringreso_id = this.rubroTraslado.id
          this.detalle.pr_tipo_ingreso_id = this.rubroTraslado.tipo_ingreso.id
        }
      },
      formDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      filterRubros (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.codigo)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      validadorPostulador () {
        Validator.extend('rubroValido', {
          validate: (value, prop) => new Promise((resolve) => {
            let response = {}
            if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
              this.rubrosCreados.includes(value.id) ? response = {valido: false, mensaje: 'Rubro ya usado'} : response = {valido: true, mensaje: null}
              return resolve({valid: response.valido, data: {message: response.mensaje}})
            }
          }),
          getMessage: (field, params, data) => data.message
        })
      },
      confirmar () {
        this.prTraslado.estado = 'Confirmado'
        this.submit()
      },
      anular (item) {
        let val = false
        this.prTraslado.estado = (item === 1) ? 'Anulado' : 'Registrado'
        this.detalle_anulacion = null
        this.dialogThree = (item === 1) ? true : val
      },
      actualizarEstado (detalle) {
        this.prTraslado.concepto_anulacion = detalle
        this.submit()
      },
      cerrar () {
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      async submit () {
        if (await this.validador('formGastos')) {
          this.localLoading = true
          const typeRequest = !this.prTraslado.id ? 'crear' : 'editar'
          let send = !this.prTraslado.id ? this.axios.post('pr_traslados', this.prTraslado) : this.axios.put('pr_traslados/' + this.prTraslado.id, this.prTraslado)
          send.then(response => {
            this.$store.commit(SNACK_SHOW, {msg: `Item ${!this.prTraslado.id ? 'creado' : 'actualizado'} satisfactoriamente`, color: 'success'})
            this.$store.commit(TRASLADOS_PRESUPUESTALES_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          }).catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro. ', error: e})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>
