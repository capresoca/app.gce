<template>
  <div>
    <v-dialog v-model="dialog" persistent width="500px">
      <v-form data-vv-scope="formDialogGastos">
        <v-card ref="dialogModificacion">
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Nueva modificación</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12>
                <v-flex xs12>
                  <v-autocomplete
                    :items="rubros"
                    v-model="detalle.rubro"
                    @change="() => obtenerPresupuestoRubro()"
                    :filter="filterRubros"
                    item-text="nombre"
                    item-value="id"
                    name="rubro"
                    label="Rubro"
                    :hint="detalle.rubro ? detalle.rubro.codigo : ''"
                    persistent-hint
                    no-data-text="No hay rubros disponibles"
                    :error-messages="errors.collect('rubro')"
                    v-validate="'required|rubroValido'" required
                    prepend-icon="subtitles"
                    return-object
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

              </v-flex>

              <v-flex xs12>
                <v-text-field v-model="modificacion.tipo === 'Gasto' ? detalle.tipo_gasto.nombre : detalle.tipo_ingreso.nombre" readonly
                              :label="`Tipo de ${modificacion.tipo === 'Gasto' ? modificacion.tipo : modificacion.tipo}`" :key="`tipo de ${modificacion.tipo}`" prepend-icon="compare_arrows"
                              :name="`tipo de ${modificacion.tipo}`" v-validate="'required'" required></v-text-field>
              </v-flex>

              <v-flex xs12>
                <v-text-field v-model="detalle.valor_inicial" readonly
                              label="Valor inicial" key="valor inicial" prepend-icon="money"
                              name="valor inicial"></v-text-field>
              </v-flex>

              <v-flex xs12>
                <v-radio-group v-model="detalle.naturaleza" row>
                  <v-radio label="Crédito" value="Crédito"></v-radio>
                  <v-radio label="Débito" value="Débito"></v-radio>
                </v-radio-group>
              </v-flex>

              <v-flex xs12>
                <v-text-field v-model.number="detalle.valor_movimiento" @keyup.enter="agregarDetalle(detalle)" prepend-icon="attach_money"
                              label="Valor movimiento" key="valor movimiento"
                              name="valor movimiento" v-validate="'required|numeric'" required
                              :error-messages="errors.collect('valor movimiento')">

                </v-text-field>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="resetDialog">Cancelar</v-btn>
            <v-btn @click="agregarDetalle(detalle)" color="primary">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-dialog persistent
      v-model="dialog2"
      max-width="300">
      <v-card>
        <v-card-title class="headline grey lighten-3">¿Qué tipo de estructura presupuestal modificará?</v-card-title>
        <v-container>
          <v-radio-group v-model="modificacion.tipo" row>
            <v-radio label="Ingresos" value="Ingreso"></v-radio>
            <v-radio label="Gastos" value="Gasto"></v-radio>
          </v-radio-group>
        </v-container>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogThree" persistent max-width="400">
      <v-card>
        <!--<v-card-title class="headline">Use Google's location service?</v-card-title>-->
        <v-card-title class="grey lighten-3">
          <v-card-text class="title">¿Por qué anula la modificación?</v-card-text>
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
    <v-card ref="formPresupuesto">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title v-text="'Modificación Presupuesto Inicial de ' + (modificacion.tipo === null ? 'N/A' : (modificacion.tipo !== 'Ingreso' ? 'Gastos' : 'Ingresos'))"> </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-chip label color="white" v-if="modificacion.id" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="modificacion.consecutivo_presupuestal == null ? '00' : modificacion.consecutivo_presupuestal"></span>
        </v-chip>
        <v-chip v-if="modificacion.id" :color="colorEstado" text-color="white">
          <v-avatar>
            <v-icon>{{ iconoEstado }}</v-icon>
          </v-avatar>
          {{ modificacion.estado }}
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

                          <v-flex xs12 sm4 v-if="parametros.entidad !== null">
                            <v-text-field v-model="presupuesto.entidad_resolucion.periodo"
                                          prepend-icon="calendar_today"
                                          label="Periodo" key="periodo"
                                          readonly>

                            </v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 v-else>
                            <v-select
                              :items="presupuestos"
                              v-model="presupuesto.entidad_resolucion.periodo"
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
                            <v-text-field v-model="presupuesto.entidad_resolucion.codigo"
                                          prepend-icon="account_balance"
                                          label="Unidad ejecutora" key="unidad ejecturoa"
                                          readonly>

                            </v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="presupuesto.entidad_resolucion.nombre"
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
                      <v-card-text>
                        <v-layout row wrap>
                          <v-flex xs12 sm4 md4 v-if="!modificacion.id">
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
                                v-model="modificacion.fecha"
                                @input="menuDate = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha activo')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                }"
                                locale='es'
                                no-title
                                :readonly="readOnly"
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
                            <v-text-field v-model="modificacion.documento" prepend-icon="assignment"
                                          label="Documento" key="documento"
                                          name="documento" v-validate="'required'" required
                                          :readonly="readOnly || modificacion.estado === 'Anulada'"
                                          :error-messages="errors.collect('documento')" ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout>
                          <v-flex xs12>
                            <v-textarea v-model="modificacion.detalle_modificacion" auto-grow rows="1" prepend-icon="reorder"
                                        label="Observaciones" key="observaciones"
                                        name="observaciones" v-validate="'required'" required
                                        :readonly="readOnly || modificacion.estado === 'Anulada'"
                                        :error-messages="errors.collect('observaciones')" ></v-textarea>
                          </v-flex>
                        </v-layout>

                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4" v-if="presupuesto.id">
                    <template>
<!--                      <loading v-model="localLoading" />-->
                      <v-card>
                        <v-toolbar dense class="grey lighten-4 elevation-0">
                          <v-toolbar-title class="subheading">Modificaciones</v-toolbar-title>
                          <v-spacer></v-spacer>
                          <v-btn v-if="!readOnly && modificacion.estado !== 'Anulada'" small color="primary" @click.prevent="abrirModal"><v-icon>add</v-icon> Agregar modificación</v-btn>
                        </v-toolbar>
                        <v-data-table :headers="headers"
                                      :items="modificacion.detalles"
                                      :loading="tableLoading"
                                      hide-actions rows-per-page-text="Registros por página"
                                      :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                          <template slot="items" slot-scope="props">
                            <td class="text-xs-left">{{ props.item.rubro.codigo }}</td>
                            <td class="text-xs-left">{{ props.item.rubro.nombre }}</td>
                            <td class="text-xs-right">{{ props.item.valor_inicial | numberFormat(0,'$')  }}</td>
                            <td class="text-xs-center">{{ props.item.naturaleza }}</td>
                            <td class="text-xs-right">{{ props.item.valor_movimiento | numberFormat(0,'$')  }}</td>
                            <td class="text-xs-center">
                              <v-speed-dial v-if="!readOnly && modificacion.estado !== 'Anulada'"
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
                                    @click="editarDetalle(props.index,props.item)"
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
                      </v-card>
                    </template>
                  </v-flex>

                  <v-layout v-if="modificacion.estado === 'Anulada' && modificacion.concepto_anulacion !== null">
                    <v-flex xs12>
                      <v-textarea v-model="modificacion.concepto_anulacion" class="text-sm-justify"
                                  auto-grow rows="1" prepend-icon="reorder"
                                  label="Concepto de Anulación"
                                  :readonly="modificacion.estado === 'Anulada'"
                                  :error-messages="errors.collect('observaciones')" ></v-textarea>
                    </v-flex>
                  </v-layout>

                </v-layout>
              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions v-if="modificacion.estado === 'Registrada'" class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 sm6 class="text-xs-center text-sm-left">
            <v-btn v-if="modificacion.id" @click="anular(1)" dark color="danger" :loading="loadingSubmit" v-text="'Anular'"></v-btn>
          </v-flex>
          <v-flex xs12 sm6 class="text-xs-center text-xs-right pt-1">
            <v-btn v-if="modificacion.estado !== 'Confirmada'" color="primary" @click.prevent="submit" :loading="loadingSubmit">Guardar</v-btn>
            <v-btn color="success" @click="confirmar" slot="activator"><span v-if="seEstaCreando">Guardar y Confirmar</span> <span v-else>Confirmar</span></v-btn>
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
  import {MODIFICACIONES_PRESUPUESTALES_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import { Validator } from 'vee-validate'

  export default {
    name: 'RegistroModificacion',
    props: ['parametros'],
    components: {
      Loading
    },
    data () {
      return {
        modificacion: null,
        dialog2: false,
        dialogThree: false,
        presupuesto: null,
        detalle_anulacion: null,
        presupuestos: [],
        rubro: {},
        rubros: [],
        rubrosFiltrados: [],
        rubrosCreados: '',
        presupuestoRubro: {},
        detalle: {},
        localLoading: false,
        seEstaCreando: false,
        tableLoading: false,
        loadingSubmit: false,
        dialog: false,
        menuDate: false,
        readOnly: false,
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
            text: 'Valor inicial',
            align: 'center',
            sortable: false,
            value: 'valorInicial'
          },
          {
            text: 'Naturaleza',
            align: 'center',
            sortable: false,
            value: 'naturaleza'
          },
          {
            text: 'Valor movimiento',
            align: 'center',
            sortable: false,
            value: 'valormovimiento'
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
    beforeMount () {
      this.formReset()
      this.resetDialog()
      this.validadorPostulador()
    },
    mounted () {
      if (this.parametros.entidad !== null) {
        this.getRegisto(this.parametros.entidad.id)
      } else {
        this.dialog2 = true
        this.seEstaCreando = true
      }
      this.resetDialog()

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
      iconoEstado () {
        if (this.modificacion.estado === 'Registrada') return 'stars'
        if (this.modificacion.estado === 'Confirmada') return 'check_circle'
        if (this.modificacion.estado === 'Anulada') return 'remove_circle'
      },
      colorEstado () {
        if (this.modificacion.estado === 'Registrada') return 'primary'
        if (this.modificacion.estado === 'Confirmada') return 'teal'
        if (this.modificacion.estado === 'Anulada') return 'red'
      },
      computedDateFormatted () {
        return this.formDate(this.modificacion.fecha)
      }
    },
    watch: {
      'modificacion.tipo' (value) {
        if (value) {
          this.dialog2 = false
          this.obtenerPresupuestos()
          this.seEstaCreando = false
        }
      },
      'detalle.pr_rubro_id' (value) {
        if (value) {
          this.detalle.rubro = this.rubros.find(rubro => {
            return rubro.id === value
          })
          this.obtenerPresupuestoRubro()
        }
      }
    },
    methods: {
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.formPresupuesto.$el
        })
        this.axios.get('pr_mod_presupuestales/' + id)
          .then(response => {
            this.modificacion = response.data.data
            if (response.data.data.estado === 'Registrada') this.obtenerPresupuesto(response.data.data.periodo)
            if (response.data.data.estado !== 'Registrada') {
              this.readOnly = true
              this.modificacion.periodo = response.data.data.periodo
              this.presupuesto = this.modificacion.tipo === 'Gasto' ? response.data.data.presupuesto_inicial_gasto : response.data.data.presupuesto_inicial_ingreso
              this.modificacion.gs_strgasto_id = (response.data.data.tipo === 'Gasto') ? response.data.data.presupuesto_inicial_gasto.id : null
              this.modificacion.detalles = response.data.data.detalles
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      obtenerPresupuesto (periodo) {
        let loader = this.$loading.show({
          container: this.$refs.formPresupuesto.$el
        })
        let tipo = this.modificacion.tipo
        this.axios.get('periodo_presupuestos?periodo=' + periodo + '&tipo=' + tipo)
          .then(response => {
            this.presupuesto = response.data.presupuesto
            this.rubros = response.data.rubros
            this.modificacion.gs_strgasto_id = tipo === 'Gasto' ? response.data.presupuesto.id : null
            console.log('ss', this.modificacion.gs_strgasto_id)
            this.modificacion.pr_stringreso_id = tipo === 'Ingreso' ? response.data.presupuesto.id : null
            this.modificacion.periodo = this.presupuesto.periodo
            // if (response.data !== '') {
            // }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      obtenerPresupuestos () {
        if (!this.modificacion.id) {
          let getPeriodos = this.modificacion.tipo === 'Ingreso' ? 'peridos_presupuesto_ingresos' : 'peridos_presupuesto_gastos'
          this.axios.get(getPeriodos)
            .then(res => {
              if (res.data.exists === true) {
                this.presupuestos = res.data.data
              } else {
                this.$store.commit(SNACK_SHOW, {msg: res.data.data, color: 'danger'})
              }
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al obtener presupuesto. ', error: e})
            })
        }
      },
      obtenerPresupuestoRubro () {
        let loader = this.$loading.show({
          container: this.$refs.dialogModificacion.$el
        })
        let presupuestoId = this.presupuesto.id
        this.axios.get(`presupuesto_rubro?presupuestoId=${presupuestoId}&rubroId=${this.detalle.rubro.id}&tipo=${this.modificacion.tipo}`)
          .then((response) => {
            if (response.data !== '') {
              this.presupuestoRubro = response.data.data
              this.detalle.valor_inicial = this.presupuestoRubro.valor_inicial
              this.detalle.tipo_gasto = this.presupuestoRubro.tipo_gasto
              this.detalle.tipo_ingreso = this.presupuestoRubro.tipo_ingreso
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      getRubrosUsados () {
        return this.modificacion.detalles.map(cod => { if (!(this.detalle.rubro.id != null && this.detalle.rubro.id === cod.rubro.id)) return cod.rubro.id })
      },
      abrirModal () {
        this.dialog = true
        this.rubrosCreados = this.getRubrosUsados()
      },
      anular (item) {
        let val = false
        this.modificacion.estado = (item === 1) ? 'Anulada' : 'Registrada'
        this.dialogThree = (item === 1) ? true : val
      },
      actualizarEstado (detalle) {
        this.modificacion.concepto_anulacion = detalle
        this.submit()
      },
      async agregarDetalle (detalle) {
        if (await this.validador('formDialogGastos')) {
          this.agregarValoresAlDetalle()
          if (typeof detalle.index !== 'number') {
            this.modificacion.detalles.push(detalle)
          } else {
            this.modificacion.detalles
              .splice(this.modificacion.detalles
                .findIndex(x => this.modificacion.detalles.indexOf(x) === detalle.index), 1, detalle)
          }
          this.dialog = false
          this.resetDialog()
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
        }
      },
      editarDetalle (index, detalle) {
        this.detalle = JSON.parse(JSON.stringify(detalle))
        this.detalle.index = index
        this.dialog = true
        this.rubrosCreados = this.getRubrosUsados()
      },
      eliminarDetalle (index) {
        this.modificacion.detalles.splice(index, 1)
        this.dialog = false
      },
      formReset () {
        this.modificacion = {
          id: null,
          consecutivo_presupuestal: null,
          periodo: null,
          fecha: null,
          documento: null,
          detalle_modificacion: null,
          tipo: null,
          estado: 'Registrada',
          gs_strgasto_id: null,
          pr_stringreso_id: null,
          concepto_anulacion: null,
          fecha_anulacion: null,
          detalles: []
        }

        this.rubrosCreados = this.getRubrosUsados()

        this.presupuesto = {
          entidad_resolucion: {periodo: ''},
          detalles: []
        }

        this.presupuestoRubro = {
          tipo_ingreso: {nombre: null},
          tipo_gasto: {nombre: null},
          valor_inicial: null
        }
      },
      resetDialog () {
        this.dialog = false
        this.detalle = {
          tipo_ingreso: {nombre: ''},
          tipo_gasto: {nombre: ''},
          valor_inicial: null,
          naturaleza: null,
          valor_movimiento: null,
          rubro: {id: ''}
        }
        this.$validator.reset()
        this.rubrosCreados = this.getRubrosUsados()
      },
      agregarValoresAlDetalle () {
        this.detalle.valor_inicial = this.presupuestoRubro.valor_inicial
        this.detalle.pr_detstrgasto_id = (this.modificacion.tipo === 'Gasto') ? this.presupuestoRubro.id : null
        this.detalle.pr_detstringreso_id = (this.modificacion.tipo === 'Ingreso') ? this.presupuestoRubro.id : null
        this.detalle.pr_rubro_id = this.detalle.rubro.id
        this.detalle.pr_tipo_gasto_id = (this.modificacion.tipo === 'Gasto') ? this.presupuestoRubro.tipo_gasto.id : null
        this.detalle.pr_tipo_ingreso_id = (this.modificacion.tipo === 'Ingreso') ? this.presupuestoRubro.tipo_ingreso.id : null
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
      confirmar () {
        this.modificacion.estado = 'Confirmada'
        this.submit()
      },
      async submit () {
        this.dialogThree = false
        let loader = this.$loading.show({
          container: this.$refs.cargar
        })
        if (await this.validador('formGastos')) {
          this.localLoading = true
          const typeRequest = !this.modificacion.id ? 'crear' : 'editar'
          let send = !this.modificacion.id ? this.axios.post('pr_mod_presupuestales', this.modificacion) : this.axios.put('pr_mod_presupuestales/' + this.modificacion.id, this.modificacion)
          send.then(response => {
            // console.log('xxxResponse', response)
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: `Item ${(!this.modificacion.id) ? 'creado' : 'actualizado'} satisfactoriamente`, color: 'success'})
            this.$store.commit(MODIFICACIONES_PRESUPUESTALES_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          }).catch(e => {
            loader.hide()
            this.localLoading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro. ', error: e})
          })
        } else {
          loader.hide()
          this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>
