<template>
  <div>
    <v-dialog v-model="dialog" persistent width="500px">
      <v-form data-vv-scope="formRpDialog" @submit.prevent="agregarDetalle(detalle)">
        <v-card ref="dialogRp">
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar rubro</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12>
                <v-autocomplete
                  label="Rubro"
                  v-model="detalle.rubro"
                  @change="val => {
                    detalle.pr_rubro_id = val ? val.id : null
                    obtenerCdpRubro()
                  }"
                  :items="rubros"
                  :filter="filterRubros"
                  :hint="detalle.rubro ? detalle.rubro.codigo : ''"
                  persistent-hint
                  item-text="nombre"
                  item-value="id"
                  name="rubro"
                  no-data-text="No existen datos"
                  prepend-icon="subtitles"
                  required
                  v-validate="'required|rubroValido'" required
                  return-object
                  readonly
                  :error-messages="errors.collect('rubro')">

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
                <v-text-field :value="detalle.tipo_gasto ? detalle.tipo_gasto.nombre : null" readonly
                              label="Tipo de gasto" key="tipo de gasto" prepend-icon="compare_arrows"
                              name="tipo de gasto" v-validate="'required'" required></v-text-field>
              </v-flex>
              <v-flex xs12>
                <input-detail-flex
                  label="Valor disponible"
                  prependIcon="money"
                  :text="cdpRubro ? currency(cdpRubro.valor_disponible): 0"
                />
              </v-flex>
              <v-flex xs12>
                <input-number
                  v-model.number="detalle.valor"
                  label="Saldo"
                  name="saldo"
                  :precision="0"
                  prepend-icon="attach_money"
                  v-validate="'required|max_value:' + (cdpRubro ? cdpRubro.valor_disponible: null)"
                  :error-messages="errors.collect('saldo')"
                ></input-number>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="resetDialog">Cancelar</v-btn>
            <v-btn type="submit" color="primary">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-dialog v-model="dialogAnular" persistent max-width="400">
      <v-card>
        <v-card-title class="grey lighten-3">
          <v-card-text class="title">¿Por qué anula el RP?</v-card-text>
        </v-card-title>
        <v-container fluid>
          <v-layout row wrap>
            <v-flex xs12>
              <v-textarea
                outline
                v-model="rp.concepto_anulacionint"
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
          <v-btn flat @click.native="dialogAnular = false">Cancelar</v-btn>
          <v-btn color="primary" flat @click="anular">Enviar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card ref="formPresupuesto">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> Registro RP </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-chip label color="white" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="rp.consecutivo == null ? '00' : str_pad(rp.consecutivo,8,'0','STR_PAD_LEFT')"></span>
        </v-chip>
        <v-chip v-if="this.rp.id" :color="colorEstado" text-color="white">
          <v-avatar >
            <v-icon>{{ iconoEstado }}</v-icon>
          </v-avatar>
          {{ rp.estado }}
        </v-chip>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12>
            <v-form data-vv-scope="formRp">
              <v-container fluid grid-list-md>
                <v-layout v-if="rp.estado === 'Anulado'">
                  <v-flex xs12>
                    <v-alert
                      :value="true"
                      type="error"
                    >
                      <strong>Concepto anulación:</strong> {{ rp.concepto_anulacionint }}
                    </v-alert>

                  </v-flex>
                </v-layout>
                <v-layout row wrap>
                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> Presupuesto</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>
                          <v-flex xs12 sm4 v-if="parametros.entidad !== null">
                            <v-text-field v-model="rp.periodo"
                                          prepend-icon="calendar_today"
                                          label="Periodo" key="periodo"
                                          readonly>
                            </v-text-field>
                          </v-flex>
                          <v-flex xs12 sm4 v-else>
                            <v-select
                              :items="presupuestos"
                              v-model="rp.periodo"
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
                            <v-text-field :value="rp.entidad_resolucion ? rp.entidad_resolucion.codigo : null"
                                          prepend-icon="account_balance"
                                          label="Unidad ejecutora" key="unidad ejecturoa"
                                          readonly>

                            </v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field :value="rp.entidad_resolucion ? rp.entidad_resolucion.nombre : null"
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
                          <v-flex xs12 sm12>
                            <v-subheader v-if="readOnly" class="pa-0 ma-0" v-text="'Documento: ' + rp.tipo"></v-subheader>
                            <v-radio-group v-else label="Documento" v-model="rp.tipo" row :readonly="readOnly">
                              <v-radio disabled label="Orden de compra" value="Orden de Compra"></v-radio>
                              <v-radio label="Contrato" value="Contrato"></v-radio>
                              <v-radio label="No Aplica" value="No Aplica"></v-radio>
                            </v-radio-group>
                          </v-flex>
                          <v-flex xs12 sm4 v-if="rp.estado !== 'Confirmado' && rp.estado !== 'Anulado'">
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
                                name="fecha activo"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha activo')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="rp.fecha"
                                @input="menuDate = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha activo')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                  this.saveLocalStorage()
                                }"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>
                          <v-flex xs12 sm4 v-else>
                            <v-text-field v-model="computedDateFormatted" prepend-icon="event"
                                          label="Fecha" key="fecha"
                                          name="fecha"
                                          readonly
                                          :error-messages="errors.collect('fecha')" ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm8>
                            <postulador-v2
                              no-data="Busqueda por nombre o número de documento."
                              hint="identificacion_completa"
                              item-text="nombre_completo"
                              data-title="identificacion_completa"
                              data-subtitle="nombre_completo"
                              label="Tercero"
                              :detail="detalleTercero"
                              entidad="terceros"
                              @changeid="val => rp.gn_tercero_id = val"
                              v-model="rp.tercero"
                              name="Tercero"
                              preicon="person"
                              :rules="'required'"
                              v-validate="'required'"
                              :error-messages="errors.collect('Tercero')"
                              :readonly="readOnly"
                              :clearable="!readOnly"
                              :no-btn-create="readOnly"
                              :no-btn-edit="readOnly"
                            ></postulador-v2>
                          </v-flex>
                        </v-layout>
                        <template v-if="rp.tipo === 'Contrato'">
                          <v-expand-transition>
                            <v-layout row wrap v-if="rp.tipo === 'Contrato'">
                              <v-flex xs12 sm12>
                                <v-select
                                  v-if="rp.estado === null || rp.estado === 'Registrado'"
                                  :items="contratos"
                                  v-model="rp.minuta"
                                  item-text="objeto"
                                  item-value="pr_cdp_id"
                                  @change="val => {
                                    rp.ce_proconminuta_id = val ? val.id : null,
                                    obtenerRubrosContrato(val)
                                  }"
                                  name="contrato"
                                  label="Contrato"
                                  no-data-text="No hay contratos disponibles"
                                  :error-messages="errors.collect('contrato')"
                                  v-validate="'required'"
                                  prepend-icon="receipt"
                                  return-object
                                  :readonly="readOnly"
                                  :clearable="!readOnly"
                                >
                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item.numero_contrato"/>
                                        <v-list-tile-sub-title v-html="data.item.objeto"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-select>
                                <v-textarea
                                  v-else
                                  auto-grow rows="1"
                                  :hint="`${rp.minuta ? 'Número Contrato: ' + rp.minuta.numero_contrato : ''}`"
                                  persistent-hint
                                  prepend-icon="receipt"
                                  label="Contrato"
                                  :value="rp.minuta ? rp.minuta.objeto : null"
                                  readonly
                                ></v-textarea>
                              </v-flex>
                            </v-layout>
                          </v-expand-transition>
                        </template>
                        <template v-if="noAplica && (rp.estado === null || rp.estado === 'Registrado')">
                          <v-expand-transition>
                            <v-layout row wrap v-if="noAplica && (rp.estado === null || rp.estado === 'Registrado')">
                              <v-flex xs12 sm12>
                                <v-autocomplete
                                  label="CDP"
                                  v-model="rp.cdp"
                                  @change="val => {
                                    rp.pr_cdp_id = val ? val.id : null,
                                    obtenerRubrosCdp(val)
                                  }"
                                  :items="cdps"
                                  :filter="filterRubros"
                                  :hint="rp.cdp ? ('Consecutivo: ' + rp.cdp.consecutivo) : null"
                                  persistent-hint
                                  item-text="objecto"
                                  item-value="id"
                                  :readonly="readOnly"
                                  name="cdp"
                                  no-data-text="No existen datos"
                                  prepend-icon="subtitles"
                                  v-validate="'required'"
                                  return-object
                                  :error-messages="errors.collect('cdp')" clearable>
                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="'Consecutivo: ' + data.item.consecutivo"/>
                                        <v-list-tile-sub-title v-html="data.item.objecto"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                              </v-flex>
                            </v-layout>
                          </v-expand-transition>
                        </template>
                        <template>
                          <v-slide-y-transition>
                            <v-layout row wrap v-if="openTable">
                              <v-flex xs12>
                                <v-subheader class="pl-1">CDP</v-subheader>
                                <v-data-table
                                  :headers="headersCDP"
                                  :items="tableCDP"
                                  class="elevation-1"
                                  no-data-text="No ha escogido ningún CDP"
                                  hide-actions>
                                  <template slot="items" slot-scope="props">
                                    <td>{{ props.item.consecutivo }}</td>
                                    <td>{{ props.item.objecto }}</td>
                                    <td class="text-xs-center">{{ props.item.fecha_vence }}</td>
                                    <td class="text-xs-right">{{ currency(props.item.valor_cdp)}}</td>
                                    <td class="text-xs-right">{{ currency(props.item.suma_valores_ejecutados)}}</td>
                                    <td class="text-xs-center">{{ props.item.estado }}</td>
                                  </template>
                                </v-data-table>
                              </v-flex>
                            </v-layout>
                          </v-slide-y-transition>
                        </template>
                        <v-layout>
                          <v-flex xs12>
                            <v-textarea v-model="rp.observaciones" prepend-icon="reorder" auto-grow rows="1"
                                        label="Observaciones" key="observaciones"
                                        name="observaciones" v-validate="'required'" required
                                        :readonly="readOnly"
                                        :error-messages="errors.collect('observaciones')" ></v-textarea>
                          </v-flex>
                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>
                </v-layout>
                <template>
                  <v-slide-y-transition>
                    <v-layout v-if="rp.detalles.length">
                      <v-flex xs12 class="pb-4">
                        <template>
                          <loading v-model="localLoading" />
                          <v-card>
                            <v-toolbar dense class="grey lighten-4 elevation-0">
                              <v-toolbar-title class="subheading">Detalles</v-toolbar-title>
                              <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-data-table :headers="headers"
                                          :items="rp.detalles"
                                          :loading="tableLoading"
                                          hide-actions rows-per-page-text="Registros por página"
                                          :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                              <template slot="items" slot-scope="props">
                                <td>{{ props.item.rubro.codigo }}</td>
                                <td>{{ props.item.rubro.nombre }}</td>
                                <td>{{ props.item.tipo_gasto.nombre }}</td>
                                <td>{{ props.item.valor | numberFormat(0,'$') }}</td>
                                <td>
                                  <v-tooltip top
                                             v-if="!readOnly"
                                             v-model="props.item.show">
                                    <v-btn
                                      flat
                                      fab
                                      dark
                                      small
                                      color="white"
                                      slot="activator"
                                      @click="editarDetalle(props.index,props.item)"
                                    >
                                      <v-icon color="accent">mode_edit</v-icon>
                                    </v-btn>
                                    <span>Modificar</span>
                                  </v-tooltip>
<!--                                  <v-speed-dial v-if="!readOnly"-->
<!--                                                v-model="props.item.show"-->
<!--                                                direction="left"-->
<!--                                                open-on-hover-->
<!--                                                transition="slide-x-transition"-->
<!--                                  >-->
<!--                                    <v-btn-->
<!--                                      slot="activator"-->
<!--                                      v-model="props.item.show"-->
<!--                                      color="blue darken-2"-->
<!--                                      dark-->
<!--                                      fab-->
<!--                                      small-->
<!--                                    >-->
<!--                                      <v-icon>chevron_left</v-icon>-->
<!--                                      <v-icon>close</v-icon>-->
<!--                                    </v-btn>-->
<!--                                    <v-tooltip top>-->
<!--                                      <v-btn-->
<!--                                        fab-->
<!--                                        dark-->
<!--                                        small-->
<!--                                        color="white"-->
<!--                                        slot="activator"-->
<!--                                        @click="editarDetalle(props.index,props.item)"-->
<!--                                      >-->
<!--                                        <v-icon color="accent">mode_edit</v-icon>-->
<!--                                      </v-btn>-->
<!--                                      <span>Modificar</span>-->
<!--                                    </v-tooltip>-->
<!--&lt;!&ndash;                                    <v-tooltip top>&ndash;&gt;-->
<!--&lt;!&ndash;                                      <v-btn&ndash;&gt;-->
<!--&lt;!&ndash;                                        fab&ndash;&gt;-->
<!--&lt;!&ndash;                                        dark&ndash;&gt;-->
<!--&lt;!&ndash;                                        small&ndash;&gt;-->
<!--&lt;!&ndash;                                        color="white"&ndash;&gt;-->
<!--&lt;!&ndash;                                        slot="activator"&ndash;&gt;-->
<!--&lt;!&ndash;                                        @click="eliminarDetalle(props.index, props.item.id)"&ndash;&gt;-->
<!--&lt;!&ndash;                                      >&ndash;&gt;-->
<!--&lt;!&ndash;                                        <v-icon color="accent">delete</v-icon>&ndash;&gt;-->
<!--&lt;!&ndash;                                      </v-btn>&ndash;&gt;-->
<!--&lt;!&ndash;                                      <span>Eliminar</span>&ndash;&gt;-->
<!--&lt;!&ndash;                                    </v-tooltip>&ndash;&gt;-->

<!--                                  </v-speed-dial>-->
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
                              <template v-slot:footer>
                                <td :colspan="headers.length">
                                  <v-spacer></v-spacer>
                                  <strong>Valor compromiso: {{ rp.valor_compromiso | numberFormat(0,'$') }}</strong>
                                </td>
                              </template>
                            </v-data-table>
                          </v-card>
                        </template>
                      </v-flex>
                    </v-layout>
                  </v-slide-y-transition>
                </template>

              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions v-if="rp.estado !== 'Confirmado' && rp.estado !== 'Anulado'" class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn v-if="rp.id" @click="dialogAnular = true" dark color="danger" :loading="loadingSubmit">Anular</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn v-if="rp.estado !== 'Confirmado' && rp.estado !== 'Anulado'" color="primary" @click.prevent="submit" :loading="loadingSubmit" >Guardar</v-btn>

            <v-btn color="success" @click="confirmar" slot="activator"><span v-if="!rp.id && rp.estado === null">Guardar y Confirmar</span> <span v-else>Confirmar</span></v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {RP_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import store from '@/store/complementos/index'
  import { Validator } from 'vee-validate'
  import InputNumber from '@/components/general/InputNumber'
  export default {
    name: 'RegistroRp',
    props: ['parametros'],
    components: {
      Loading,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      InputNumber
    },
    data () {
      return {
        detalleTercero: () => import('@/components/administrativo/niif/terceros/DetalleTercero'),
        rubrosCreados: '',
        readOnly: false,
        openTable: false,
        noAplica: false,
        menuDate: false,
        dialog: false,
        dialogAnular: false,
        loadingSubmit: false,
        tableLoading: false,
        localLoading: false,
        rp: null,
        detalle: null,
        cdpRubro: null,
        presupuestos: [],
        contratos: [],
        cdps: [],
        rubros: [],
        tableCDP: [],
        headersCDP: [
          {
            text: 'Consecutivo',
            align: 'left',
            sortable: false,
            value: 'consecutivo'
          },
          {
            text: 'Objeto',
            align: 'left',
            sortable: false,
            value: 'objeto'
          },
          {
            text: 'Fecha vencimiento',
            align: 'left',
            sortable: false,
            value: 'fechaVence'
          },
          {
            text: 'Valor',
            align: 'left',
            sortable: false,
            value: 'valor'
          },
          {
            text: 'Total Ejecutado',
            align: 'left',
            sortable: false,
            value: 'suma_valores_ejecutados'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
          }
        ],
        headers: [
          {
            text: 'Rubro',
            align: 'left',
            sortable: false,
            value: 'rubro'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Tipo gasto',
            align: 'left',
            sortable: false,
            value: 'tipoGasto'
          },
          {
            text: 'Valor',
            align: 'left',
            sortable: false,
            value: 'valor'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    beforeMount () {
      this.formReset()
      this.resetDialog()
      this.validadorPostulador()
      this.loadLocalStorage()
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      if (this.rp.id === null) this.obtenerPeriodos()
      if (this.rp.estado === null || this.rp.estado === 'Registrado') {
        this.obtenerContratos()
        this.obtenerCdps()
      }
      const dict = {
        custom: {
          rubro: {
            not_in: 'Este rubro ya fue registrado.'
          }
        }
      }

      this.$validator.localize('es', dict)
    },
    computed: {
      complementos () {
        return store.getters.complementosPresupuesto
      },
      iconoEstado () {
        if (this.rp.estado === 'Registrado') return 'stars'
        if (this.rp.estado === 'Confirmado') return 'check_circle'
        if (this.rp.estado === 'Anulado') return 'remove_circle'
      },
      colorEstado () {
        if (this.rp.estado === 'Registrado') return 'primary'
        if (this.rp.estado === 'Confirmado') return 'teal'
        if (this.rp.estado === 'Anulado') return 'red'
      },
      computedDateFormatted () {
        return this.formDate(this.rp.fecha)
      }
    },
    watch: {
      'rp.tipo' (val) {
        if (val === 'No Aplica') {
          this.noAplica = true
          this.rp.minuta = null
        }
        if (val === 'Contrato') {
          this.noAplica = false
          this.rp.cdp = null
        }
        this.$validator.reset()
      },
      'rp.minuta' (val) {
        if (val) {
          if (val.cdp) {
            this.rp.cdp = val.cdp
            this.rp.pr_cdp_id = val.cdp.id
          }
        } else {
          this.rp.cdp = null
          this.rp.pr_cdp_id = null
        }
      },
      'rp.cdp' (val) {
        if (val) {
          this.tableCDP.push(val)
          this.openTable = true
          this.rp.fecha_vence = val.fecha_vence
          this.rp.observaciones = val.objecto
        } else {
          this.rubros = []
          this.rp.detalles = []
          this.rp.observaciones = null
          this.tableCDP = []
          this.openTable = false
          this.rp.fecha_vence = null
          this.rp.valor_compromiso = null
        }
      }
    },
    methods: {
      getRegistro (id) {
        let loader = this.$loading.show({container: this.$refs.cargar})
        this.axios.get('pr_rps/' + id)
          .then((response) => {
            // console.log('response', response)
            if (response.data !== '') {
              let boolena = false
              this.readOnly = (response.data.data.estado === 'Registrado') ? (boolena) : !boolena
              this.rp = response.data.data
            }
            loader.hide()
          }).catch(() => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ', color: 'danger'})
          })
      },
      formReset () {
        this.rp = {
          id: null,
          ce_proconminuta_id: null,
          estado: null,
          fecha: null,
          consecutivo: null,
          fecha_vence: null,
          gn_tercero_id: null,
          observaciones: null,
          periodo: null,
          plazo: null,
          pr_cdp_id: null,
          pr_entidad_resolucion_id: null,
          pr_strgasto_id: null,
          tipo: null,
          valor_compromiso: null,
          detalles: [],
          tercero: null,
          cdp: null,
          minuta: null,
          entidad_resolucion: null
        }
      },
      resetDialog () {
        this.dialog = false
        this.detalle = {
          id: null,
          pr_detcdp_id: null,
          pr_rubro_id: null,
          pr_tipo_gasto_id: null,
          tipo_gasto: null,
          valor: 0,
          rubro: null
        }
        this.rubrosCreados = this.getRubrosUsados()
      },
      saveLocalStorage () {
        if (this.rp.fecha) localStorage.setItem('fechaRp', JSON.stringify(this.rp.fecha))
      },
      loadLocalStorage () {
        if (localStorage.getItem('fechaRp')) { this.rp.fecha = JSON.parse(localStorage.getItem('fechaRp')) }
      },
      openDialog () {
        this.dialog = true
        this.rubrosCreados = this.getRubrosUsados()
      },
      formDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      calcularCompromiso () {
        let valorCalculado = 0
        this.rp.detalles.forEach(x => { valorCalculado += x.valor })
        this.rp.valor_compromiso = valorCalculado
      },
      getRubrosUsados () {
        return this.rp.detalles.map(cod => { if (this.detalle.rubro) if (!(this.detalle.rubro.id != null && this.detalle.rubro.id === cod.rubro.id)) return cod.rubro.id })
      },
      async agregarDetalle (detalle) {
        if (await this.validador('formRpDialog')) {
          if (typeof detalle.index !== 'number') {
            this.rp.detalles.push(detalle)
          } else {
            this.rp.detalles
              .splice(this.rp.detalles
                .findIndex(x => this.rp.detalles.indexOf(x) === detalle.index), 1, detalle)
          }
          this.calcularCompromiso()
          this.dialog = false
          this.resetDialog()
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
        }
      },
      editarDetalle (index, detalle) {
        this.detalle = detalle
        this.detalle.index = index
        this.obtenerCdpRubro()
        this.dialog = true
        this.rubrosCreados = this.getRubrosUsados()
      },
      eliminarDetalle (index) {
        this.rp.detalles.splice(index, 1)
        this.calcularCompromiso()
        this.dialog = false
      },
      filterRubros (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.codigo + item.codigo.split('.').join(''))
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      validadorPostulador () {
        Validator.extend('rubroValido', {
          validate: (value, prop) => new Promise((resolve) => {
            let response = {}
            if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
              this.rubrosCreados.includes(value.id) ? response = {valido: false, mensaje: 'Rubro ya usado'} : response = {valido: true, mensaje: null}
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
      llenarTablaDetalles (detalles) {
        this.rp.detalles = []
        detalles.forEach((item) => {
          this.rp.detalles.push({
            id: null,
            pr_detcdp_id: item.id,
            pr_tipo_gasto_id: item.pr_tipo_gasto_id,
            pr_rubro_id: item.rubro.id,
            tipo_gasto: item.tipo_gasto,
            valor: 0,
            rubro: item.rubro
          })
        })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async confirmar () {
        if (await this.validador('formRp')) {
          this.rp.estado = 'Confirmado'
          this.submit()
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
        }
      },
      async anular () {
        if (await this.validador('formRp')) {
          this.dialogAnular = false
          this.rp.estado = 'Anulado'
          this.submit()
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
        }
      },
      async submit () {
        let valores = this.rp.detalles.filter(type => type.valor === 0)
        if (await this.validador('formRp') && (valores.length === 0)) {
          this.localLoading = true
          let estado = this.rp.estado
          this.rp.estado = (!this.rp.id && estado === null) ? 'Registrado' : estado
          const typeRequest = !this.rp.id ? 'crear' : 'editar'
          let send = !this.rp.id ? this.axios.post('pr_rps', this.rp) : this.axios.put('pr_rps/' + this.rp.id, this.rp)
          send.then(response => {
            this.$store.commit(SNACK_SHOW, {msg: `Item ${!this.rp.id ? 'creado' : 'actualizado'} satisfactoriamente`, color: 'success'})
            this.$store.commit(RP_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            this.$validator.reset()
            this.formReset()
            this.resetDialog()
          }).catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro. ', error: e})
          })
        } else {
          if (valores.length >= 1) {
            this.$store.commit(SNACK_SHOW, {msg: 'Existen detalles con valores igual a 0.', color: 'danger'})
          } else {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
          }
        }
      },
      // Peticiones
      obtenerPresupuesto (periodo) {
        let loader = this.$loading.show({container: this.$refs.formPresupuesto.$el})
        this.axios.get('rps/' + periodo)
          .then((response) => {
            if (response.data !== '') {
              this.rp.entidad_resolucion = response.data.data.entidad_resolucion
              this.rp.pr_entidad_resolucion_id = response.data.data.entidad_resolucion ? response.data.data.entidad_resolucion.id : null
              this.rp.periodo = this.rp.entidad_resolucion.periodo
            }
            loader.hide()
          }).catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      obtenerPeriodos () {
        this.axios.get('rps_periodos_cdps ').then((res) => {
          this.presupuestos = res.data
          this.obtenerPresupuesto(this.presupuestos[0])
        }).catch((e) => {
          this.$store.commit(SNACK_ERROR_LIST, {expression: ' al obtener presupuesto. ', error: e})
        })
      },
      obtenerContratos () {
        this.axios.get(`contratos_cdps`)
          .then((response) => {
            if (response.data !== '') {
              this.contratos = response.data.data
            }
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el los contratos. ' + e, color: 'danger'})
          })
      },
      obtenerCdps () {
        this.axios.get(`cdps`)
          .then((response) => {
            if (response.data !== '') {
              this.cdps = response.data.data
            }
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el los contratos. ' + e, color: 'danger'})
          })
      },
      obtenerRubrosContrato (contrato) {
        if (contrato) {
          this.tableCDP = []
          let loader = this.$loading.show({container: this.$refs.formPresupuesto.$el})
          this.axios.get(`contrato_with_cdp_rubros/${contrato.pr_cdp_id}`)
            .then((response) => {
              this.rubros = response.data.rubros
              this.llenarTablaDetalles(response.data.detalles)
              loader.hide()
            }).catch(e => {
              loader.hide()
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el los contratos. ' + e, color: 'danger'})
            })
        }
      },
      obtenerRubrosCdp (cdp) {
        if (cdp) {
          this.tableCDP = []
          let loader = this.$loading.show({container: this.$refs.formPresupuesto.$el})
          this.axios.get(`contrato_with_cdp_rubros/${cdp.id}`)
            .then((response) => {
              this.rubros = response.data.rubros
              this.llenarTablaDetalles(response.data.detalles)
              loader.hide()
            }).catch(e => {
              loader.hide()
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el los contratos. ' + e, color: 'danger'})
            })
        }
      },
      obtenerCdpRubro () {
        let loader = this.$loading.show({container: this.$refs.dialogRp.$el})
        this.axios.get(`cdp_rubro?cdpId=${this.rp.pr_cdp_id}&rubroId=${this.detalle.pr_rubro_id}`)
          .then((response) => {
            if (response.data !== '') {
              this.cdpRubro = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
