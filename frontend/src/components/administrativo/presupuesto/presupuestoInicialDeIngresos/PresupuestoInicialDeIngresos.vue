<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formCajas">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Nuevo rubro</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12>
                <v-autocomplete
                  label="Rubro"
                  v-model="detalle.rubro"
                  @change="val => detalle.pr_rubro_id = val ? val.id : null"
                  :items="rubros"
                  :filter="filterRubros"
                  :hint="detalle.rubro ? detalle.rubro.codigo : ''"
                  persistent-hint
                  item-text="nombre"
                  item-value="id"
                  name="rubro"
                  prepend-icon="subtitles"
                  required
                  v-validate="'required|rubroValido'" required
                  return-object
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
                <v-autocomplete
                  label="Tipo ingreso"
                  v-model="detalle.tipo_ingreso"
                  @change="val => detalle.pr_tipo_ingreso_id = val ? val.id : null"
                  :items="complementos.tipoIngresos"
                  item-value="id"
                  item-text="nombre"
                  name="tipo ingreso"
                  prepend-icon="compare_arrows"
                  required
                  return-object
                  v-validate="'required'"
                  :error-messages="errors.collect('tipo ingreso')">

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

              <v-flex xs12>
                <v-text-field
                  v-model="detalle.valor_inicial"
                  label="Valor"
                  prepend-icon="attach_money"
                  name="valor" v-validate="'required|numeric'" required
                  :error-messages="errors.collect('valor')"></v-text-field>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn @click="agregarDetalle(detalle)" color="primary" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card ref="formPresupuesto">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> Presupuesto inicial de ingresos </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-chip v-if="!seEstaCreando" :color="colorEstado" text-color="white">
          <v-avatar>
            <v-icon>{{ iconoEstado }}</v-icon>
          </v-avatar>
          {{ presupuesto.estado }}
        </v-chip>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12>
            <v-form data-vv-scope="formLiquidaciones">
              <v-container fluid grid-list-md>
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4 v-if="this.parametros.entidad !== null">
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
                        <v-toolbar-title class="subheading"><v-icon>monetization_on</v-icon> Presupuesto</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>

                        <v-layout row wrap>

                          <v-flex xs12>
                            <v-card>
                              <v-card-title><h4>Resolución</h4></v-card-title>
                              <v-divider></v-divider>
                              <v-list dense>
                                <v-list-tile>
                                  <v-list-tile-content>{{ presupuesto.entidad_resolucion.resolucion }}</v-list-tile-content>
                                </v-list-tile>

                              </v-list>
                            </v-card>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-card>
                              <v-card-title><h4>Valor resolución</h4></v-card-title>
                              <v-divider></v-divider>
                              <v-list dense>
                                <v-list-tile>
                                  <v-list-tile-content class="align-center">{{ presupuesto.entidad_resolucion.valor | numberFormat(0,'$') }}</v-list-tile-content>
                                </v-list-tile>

                              </v-list>
                            </v-card>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-card>
                              <v-card-title><h4>Valor presupuesto</h4></v-card-title>
                              <v-divider></v-divider>
                              <v-list dense>
                                <v-list-tile>
                                  <v-list-tile-content class="align-center">{{ valorPresupuesto | numberFormat(0,'$') }}</v-list-tile-content>
                                </v-list-tile>

                              </v-list>
                            </v-card>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-card>
                              <v-card-title><h4>Diferencia</h4></v-card-title>
                              <v-divider></v-divider>
                              <v-list dense>
                                <v-list-tile>
                                  <v-list-tile-content class="align-center">{{ (parseInt(presupuesto.entidad_resolucion.valor) - valorPresupuesto) | numberFormat(0,'$',['.', '.', '-']) }}</v-list-tile-content>
                                </v-list-tile>

                              </v-list>
                            </v-card>
                          </v-flex>

                          <v-flex xs12>
                            <template>
                              <loading v-model="localLoading" />
                              <v-card>
                                <v-toolbar dense class="grey lighten-4 elevation-0">
                                  <v-toolbar-title class="subheading">Rubros</v-toolbar-title>
                                  <v-spacer></v-spacer>
                                  <v-btn v-if="presupuesto.estado !== 'Confirmada'" small color="primary" @click.prevent="abrirModal"><v-icon>add</v-icon> Agregar Rubro</v-btn>
                                </v-toolbar>
                                <v-data-table :headers="headers"
                                              :items="presupuesto.detalles"
                                              :loading="tableLoading"
                                              hide-actions rows-per-page-text="Registros por página"
                                              :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                                  <template slot="items" slot-scope="props">
                                    <td>{{ props.item.rubro.codigo }}</td>
                                    <td>{{ props.item.rubro.nombre }}</td>
                                    <td>{{ props.item.tipo_ingreso.nombre }}</td>
                                    <td class="text-xs-right">{{ props.item.valor_inicial | numberFormat(0,'$')  }}</td>
                                    <td class="text-xs-center">
                                      <v-speed-dial v-if="presupuesto.estado !== 'Confirmada'"
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

                        </v-layout>

                      </v-card-text>
                    </v-card>
                  </v-flex>


                </v-layout>
              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions v-if="presupuesto.estado !== 'Confirmada'" class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 class="text-xs-right">

            <v-btn v-if="presupuesto.estado !== 'Confirmada'" color="primary" @click.prevent="submit" :loading="loadingSubmit">Guardar</v-btn>

            <v-tooltip top v-if="presupuesto.estado !== 'Confirmada' && parseInt(presupuesto.entidad_resolucion.valor) !== valorPresupuesto">
              <v-btn color="success" @click="confirmar" slot="activator" :disabled="parseInt(presupuesto.entidad_resolucion.valor) !== valorPresupuesto"><span v-if="seEstaCreando">Guardar y Confirmar</span> <span v-else>Confirmar</span></v-btn>
              <span>El valor de la resolución y el valor del presupuesto deben ser iguales</span>
            </v-tooltip>
            <v-btn v-else color="success" @click="confirmar" slot="activator"><span v-if="seEstaCreando">Guardar y Confirmar</span> <span v-else>Confirmar</span></v-btn>
            <v-btn v-if="presupuesto.id" color="accent" @click="imprimir(presupuesto.id)">
              <v-icon v-text="'far fa-file-pdf'"></v-icon>
            </v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import store from '../../../../store/complementos/index'
  import Loading from '@/components/general/Loading'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {PRESUPUESTO_INGRESOS_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import { Validator } from 'vee-validate'

  export default {
    name: 'PresupuestoInicialDeIngresos',
    props: ['parametros'],
    components: {
      Loading
    },
    data () {
      return {
        presupuesto: null,
        entidad: null,
        presupuestos: [],
        rubros: [],
        detalle: {},
        dialogConfirm: false,
        detalleAEliminar: {},
        localLoading: false,
        valorPresupuesto: 0,
        seEstaCreando: false,
        rubrosCreados: '',
        tableLoading: false,
        loadingSubmit: false,
        dialog: false,
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
            text: 'Tipo',
            align: 'left',
            sortable: false,
            value: 'tipo'
          },
          {
            text: 'Valor',
            align: 'left',
            sortable: false,
            value: 'valor'
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
      this.presupuesto = {
        entidad_resolucion: {
          id: '',
          periodo: null,
          rubro: null,
          codigo: null,
          resolucion: null,
          valor: null,
          nombre: null
        },
        detalles: [],
        estado: ''
      }
      this.validadorPostulador()
    },
    mounted () {
      this.parametros.entidad !== null ? this.getRegisto(this.parametros.entidad.id) : this.obtenerPresupuestos()
      this.formReset()
      this.obtenerArbol()
      this.obtenerRubros()

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
        let complementos = JSON.parse(JSON.stringify(store.getters.complementosPresupuesto))
        return complementos
      },
      iconoEstado () {
        if (this.presupuesto.estado === 'Registrada') return 'stars'
        if (this.presupuesto.estado === 'Confirmada') return 'check_circle'
      },
      colorEstado () {
        if (this.presupuesto.estado === 'Registrada') return 'primary'
        if (this.presupuesto.estado === 'Confirmada') return 'teal'
      }
    },
    watch: {
      'detalle.pr_rubro_id' (value) {
        if (value) {
          this.detalle.rubro = this.rubros.find(rubro => {
            return rubro.id === value
          })
        }
      }
    },
    methods: {
      imprimir (id) {
        this.axios.get('firmar-ruta?nombre_ruta=reporte-pr-ingresos&id=' + id)
          .then((res) => {
            let url = res.data
            let win = window.open(url, '_blank')
            win.focus()
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al abrir el archivo de impresión. ', color: 'danger'})
          })
      },
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.formPresupuesto.$el
        })
        this.axios.get('pr_stringresos/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.presupuesto = response.data.data
              this.calcularValorPresupuesto()
              this.formReset()
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
        this.axios.get('entidades_resolucion/' + periodo)
          .then((response) => {
            if (response.data !== '') {
              this.entidad = response.data.data
              this.llenarPresupuesto()
              this.calcularValorPresupuesto()
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ' + e, color: 'danger'})
          })
      },
      obtenerPresupuestos () {
        this.seEstaCreando = true
        let loader = this.$loading.show({
          container: this.$refs.formPresupuesto.$el
        })
        this.axios.get('periodos_stringresos')
          .then((res) => {
            if (res.data !== '') {
              this.presupuestos = res.data
            }
            loader.hide()
          })
          .catch((e) => {
            loader.hide()
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al obtener presupuesto. ', error: e})
          })
      },
      obtenerArbol () {
        this.axios.get('presupuesto/arbol?tipo_rubro=Ingresos')
          .then((response) => {
            this.options = response.data
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el árbol. ' + e, color: 'danger'})
          })
      },
      obtenerRubros () {
        let loader = this.$loading.show({
          container: this.$refs.formPresupuesto.$el
        })
        this.axios.get('rubros?tipo_rubro=Ingresos')
          .then((res) => {
            if (res.data.data !== '') {
              this.rubros = res.data.data
            }
            loader.hide()
          })
          .catch((e) => {
            loader.hide()
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al obtener rubros. ', error: e})
          })
      },
      llenarPresupuesto () {
        this.presupuesto.periodo = this.entidad.periodo
        this.presupuesto.pr_entidad_resolucion_id = this.entidad.id
        this.presupuesto.estado = 'Registrada'
        this.presupuesto.entidad_resolucion = this.entidad
      },
      calcularValorPresupuesto () {
        this.valorPresupuesto = 0
        this.presupuesto.detalles.map((detalle) => {
          this.valorPresupuesto += parseInt(detalle.valor_inicial)
        })
      },
      getRubrosUsados () {
        return this.presupuesto.detalles.map(cod => { if (!(this.detalle.rubro.id != null && this.detalle.rubro.id === cod.rubro.id)) return cod.rubro.id })
      },
      formReset () {
        this.detalle = {
          rubro: {id: '', codigo: ''},
          tipo_ingreso: {nombre: null},
          rubro_sin_pagar: 0,
          valor_inicial: null
        }
        this.$validator.reset()
      },
      abrirModal () {
        this.dialog = true
        this.rubrosCreados = this.getRubrosUsados()
      },
      agregarDetalle (detalle) {
        if (typeof detalle.index !== 'number') {
          this.presupuesto.detalles.push(detalle)
        } else {
          this.presupuesto.detalles
            .splice(this.presupuesto.detalles
              .findIndex(x => this.presupuesto.detalles.indexOf(x) === detalle.index), 1, detalle)
        }

        this.calcularValorPresupuesto()
        this.dialog = false
        this.formReset()
      },
      editarDetalle (index, detalle) {
        this.detalle = JSON.parse(JSON.stringify(detalle))
        this.detalle.index = index
        this.rubrosCreados = this.getRubrosUsados()
        this.abrirModal()
      },
      eliminarDetalle (index) {
        this.presupuesto.detalles.splice(index, 1)
        this.calcularValorPresupuesto()
        this.dialogConfirm = false
      },
      filterRubros (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.codigo)
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
      close () {
        this.dialog = false
        this.formReset()
      },
      confirmar () {
        this.presupuesto.estado = 'Confirmada'
        this.submit()
      },
      submit () {
        this.localLoading = true
        const typeRequest = this.seEstaCreando ? 'crear' : 'editar'
        let send = this.seEstaCreando ? this.axios.post('pr_stringresos', this.presupuesto) : this.axios.put('pr_stringresos/' + this.presupuesto.id, this.presupuesto)
        send.then(response => {
          if (this.seEstaCreando) {
            this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
            this.$store.commit(PRESUPUESTO_INGRESOS_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          } else {
            this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
            this.$store.commit(PRESUPUESTO_INGRESOS_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          }
        }).catch(e => {
          this.localLoading = false
          this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e, color: 'danger'})
        })
      }
    }
  }
</script>

<style scoped>

</style>
