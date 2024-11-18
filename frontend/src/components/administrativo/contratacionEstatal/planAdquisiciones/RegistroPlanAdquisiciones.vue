<template>
  <div>
    <v-dialog v-model="dialogDetalle" width="720px">
      <v-form data-vv-scope="formDetalles">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar Detalle</span>
          </v-card-title>
          <v-container fluid grid-list-lg>
            <v-layout row wrap>

              <v-flex xs12 sm4>
                <postulador
                  nodata="Busqueda por código o nombre."
                  required
                  hint="Bien y servicio"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="Bien y servicio"
                  scope="formPlanesAdquisiciones"
                  ref="buscarCuenta"
                  entidad="ce_bienservicios"
                  preicon="person"
                  @change="val => detalle.ce_bienservicio_id = val"
                  @objectReturn="val => detalle.bien_servicio = val"
                  :value="detalle.bien_servicio"
                  clearable/>
              </v-flex>

              <v-flex xs12 sm4>
                <v-autocomplete
                  :label="detalle.pg_uniapoyo_id === null ? 'Buscar unidad de apoyo' : 'Unidad de apoyo'"
                  v-model="detalle.uni_apoyo"
                  @change="val => detalle.pg_uniapoyo_id = val ? val.id: null"
                  :items="complementosUniapoyos.uniapoyos"
                  item-value="id"
                  item-text="cod_nombre"
                  name="unidad de apoyo"
                  persistent-hint
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('unidad de apoyo')"
                  return-object
                  clearable>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.codigo"/>
                        <v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-flex xs12 sm4>
                <v-autocomplete
                  label="Modelo de contratación"
                  v-model="detalle.mod_contratacione"
                  @change="val => detalle.ce_modcontratacione_id = val ? val.id : null"
                  :items="complementosModcontrataciones.modcontrataciones"
                  item-value="id"
                  item-text="mod_cod_nombre"
                  name="modelo contratacion"
                  persistent-hint
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('modelo contratacion')"
                  return-object
                  clearable>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.codigo"/>
                        <v-list-tile-sub-title v-html="data.item.nombre"></v-list-tile-sub-title>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-flex xs12 sm4>
                <v-menu
                  ref="menuDateProceso"
                  :close-on-content-click="false"
                  v-model="menuDateProceso"
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
                    v-model="computedDateFormattedProceso"
                    label="Fecha Proceso"
                    prepend-icon="event"
                    readonly
                    name="fecha Proceso"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha Proceso')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="detalle.fecha_proceso"
                    @input="menuDateProceso = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha Proceso')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>
              </v-flex>

              <v-flex xs12 sm4>
                <v-menu
                  ref="menuDateOfertas"
                  :close-on-content-click="false"
                  v-model="menuDateOfertas"
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
                    v-model="computedDateFormattedOfertas"
                    label="Fecha Ofertas"
                    prepend-icon="event"
                    readonly
                    name="Fecha Ofertas"
                    required
                    data-vv-delay="1000"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('Fecha Ofertas')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="detalle.fecha_ofertas"
                    @input="menuDateOfertas = false"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>
              </v-flex>

              <v-flex xs12 sm4>
                <v-text-field v-model="detalle.duracion"
                              label="Duración en días" key="duracion"
                              name="Duracion" v-validate="'required|numeric'" required
                              :error-messages="errors.collect('Duracion')">

                </v-text-field>
              </v-flex>

              <v-flex xs12 sm4>
                <v-text-field v-model="detalle.valor"
                              label="Valor" key="valor"
                              name="Valor" v-validate="'required|numeric'" required
                              :error-messages="errors.collect('Valor')">

                </v-text-field>
              </v-flex>

              <v-flex xs12 sm4>
                <v-select
                  v-model="detalle.rubros"
                  :items="listaRubros"
                  item-value="id"
                  item-text="descripcion"
                  return-object
                  label="Rubros"
                  multiple
                  chips
                  hint="Rubros del plan"
                  persistent-hint
                ></v-select>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="agregarDetalle(detalle)" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>

      </v-form>
    </v-dialog>
    <v-dialog v-model="dialogConfirm" persistent max-width="290">
      <v-card>
        <v-card-title class="headline grey lighten-3">Alerta</v-card-title>
        <v-card-text>¿Está seguro que desea eliminar este registro?</v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click.native="dialogConfirm = false">Cancelar</v-btn>
          <v-btn color="primary" flat @click.native="eliminarDetalle(detalleAEliminar)">Aceptar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card ref="formPlanesAdquisiciones">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12 sm12 md12 lg12>
            <v-form data-vv-scope="formPlanesAdquisiciones">
              <v-container fluid grid-list-md>
                <v-layout row wrap>

                  <v-flex xs12 sm12 md12 class="pb-5">
                    <template>
                      <v-card>
                        <v-card-title class="grey lighten-3">
                          <h3>Información del plan</h3>
                          <v-spacer></v-spacer>
                          <v-chip v-if="this.planAdquisicion.id" :color="colorEstado" text-color="white">
                            <v-avatar>
                              <v-icon>{{ iconoEstado }}</v-icon>
                            </v-avatar>
                            {{ planAdquisicion.estado }}
                          </v-chip>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-form data-vv-scope="formPlanesAdquisiciones">
                          <v-container fluid grid-list-lg>
                            <v-layout row wrap>

                              <v-flex xs12 sm3>
                                <v-text-field v-model="planAdquisicion.anio"
                                              label="Año" key="año"
                                              name="año" v-validate="'required|max:4|numeric|not_in:' + aniosUsados" required
                                              prepend-icon="calendar_today"
                                              :error-messages="errors.collect('año')">

                                </v-text-field>
                              </v-flex>

                              <v-flex xs12 sm3 v-show="!planAdquisicion.id">
                                <v-autocomplete
                                  label="Estado"
                                  v-model="planAdquisicion.estado"
                                  :items="complementosPlanEstado.planadquisicionEstado"
                                  name="Estado Plan Adquisición"
                                  required
                                  :disabled="disabledEstado"
                                  v-validate="'required'"
                                  prepend-icon="notifications"
                                  :error-messages="errors.collect('Estado Plan Adquisición')">

                                  <template slot="item" slot-scope="data">
                                    <template>
                                      <v-list-tile-content>
                                        <v-list-tile-title v-html="data.item"/>
                                      </v-list-tile-content>
                                    </template>
                                  </template>
                                </v-autocomplete>
                              </v-flex>

                              <v-flex xs12>
                                <v-textarea
                                  v-model="planAdquisicion.descripcion"
                                  color="teal"
                                  prepend-icon="assignment"
                                  name="descripcion"
                                  required
                                  v-validate="'required'"
                                  key="descripcion"
                                  :error-messages="errors.collect('descripcion')"
                                >
                                  <div slot="label">
                                    Descripcion
                                  </div>
                                </v-textarea>
                              </v-flex>

                              <v-flex xs12 class="text-xs-right pr-0">
                                <div sm10>
                                  <v-btn color="primary" :disabled="botonChanged" :loading="loadingSubmit" @click.prevent="submit"> {{ botonPlanAdquisicion }}</v-btn>
                                </div>
                              </v-flex>

                            </v-layout>
                          </v-container>
                        </v-form>
                        <v-divider></v-divider>

                      </v-card>
                    </template>
                  </v-flex>

                  <v-flex xs12 sm12 md12 v-if="planAdquisicion.id">
                    <template>
                      <loading v-model="localLoading" />
                      <v-card>
                        <v-card-title class="grey lighten-3">
                          <h3>Detalles</h3>
                          <v-spacer></v-spacer>
                          <v-btn small color="primary" @click.prevent="dialogDetalle = true"><v-icon>add</v-icon> Agregar Detalle</v-btn>
                        </v-card-title>
                        <v-data-table v-if="planAdquisicion.detalles.length > 0"
                                      :headers="headers"
                                      :items="planAdquisicion.detalles"
                                      :loading="tableLoading"
                                      hide-actions rows-per-page-text="Registros por página"
                                      :rows-per-page-items='[5,10,25,{"text":"Todos","value":-1}]'>
                          <template slot="items" slot-scope="props">
                            <td>{{ props.item.bien_servicio.nombre }}</td>
                            <td>{{ props.item.uni_apoyo.nombre }}</td>
                            <td>{{ props.item.mod_contratacione.nombre }}</td>
                            <td>{{ props.item.fecha_proceso }}</td>
                            <td>{{ props.item.fecha_ofertas }}</td>
                            <td>{{ props.item.valor }}</td>
                            <td class="text-xs-center">
                              <v-speed-dial
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
                                    @click="editarDetalle(props.item)"
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
                                    @click="cargarDetalleAEliminar(props.index, props.item.id)"
                                  >
                                    <v-icon color="accent">delete</v-icon>
                                  </v-btn>
                                  <span>Eliminar</span>
                                </v-tooltip>

                              </v-speed-dial>
                            </td>
                          </template>
                          <template slot="no-data">
                            <v-alert  v-if="tableLoading" :value="true" color="error" icon="warning">
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
                        <v-alert v-else :value="true" type="info"                        >
                          No hay detalles registrados.
                        </v-alert>
                      </v-card>
                    </template>
                  </v-flex>

                </v-layout>
              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {PLAN_ADQUISICIONES_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import store from '../../../../store/complementos/index'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'RegistroPlanAdquisiciones',
    components: {
      Postulador: () => import('@/components/general/Postulador'),
      Loading
    },
    props: ['parametros'],
    data () {
      return {
        planesAdquisiciones: [],
        planAdquisicion: {},
        detalle: {},
        itemsDetalle: [],
        detalleAEliminar: null,
        dialogConfirm: false,
        dialogDetalle: false,
        listaRubros: [],
        tiposEstado: [],
        aniosUsados: '',
        loadingSubmit: false,
        menuDateProceso: false,
        menuDateOfertas: false,
        localLoading: false,
        tableLoading: false,
        botonChanged: true,
        headers: [
          {
            text: 'Bien y servicio',
            align: 'left',
            sortable: false,
            value: 'bienYServicio'
          },
          {
            text: 'Unidad de apoyo',
            align: 'left',
            sortable: false,
            value: 'unidadApoyo'
          },
          {
            text: 'Modelo contratación',
            align: 'left',
            sortable: false,
            value: 'modeloContratacion'
          },
          {
            text: 'Fecha proceso',
            align: 'left',
            sortable: false,
            value: 'fechasProceso'
          },
          {
            text: 'Fecha ofertas',
            align: 'left',
            sortable: false,
            value: 'fechaOfertas'
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
      this.planAdquisicion = {
        id: '',
        anio: null,
        descripcion: null,
        gs_usuario_id: null,
        estado: null,
        detalles: []
      }
    },
    mounted () {
      const dict = {
        custom: {
          anio: {
            not_in: 'Este anio ya fue registrado.'
          }
        }
      }

      this.$validator.localize('es', dict)
      if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
      this.obtenerPlanesYRubros()
    },
    computed: {
      tapTitulo () {
        return !this.planAdquisicion.id ? 'Nuevo plan' : 'Edición del plan: ' + this.planAdquisicion.anio
      },
      botonPlanAdquisicion () {
        return this.planAdquisicion.id ? 'Guardar' : 'Crear Plan'
      },
      computedDateFormattedProceso () {
        return this.formDate(this.detalle.fecha_proceso)
      },
      computedDateFormattedOfertas () {
        return this.formDate(this.detalle.fecha_ofertas)
      },
      complementosPlanEstado () {
        return store.getters.complementosPlanAdquisicionEstado
      },
      complementosUniapoyos () {
        return store.getters.complementosFormCxp
      },
      complementosModcontrataciones () {
        return store.getters.complementosModcontrataciones
      },
      disabledEstado () {
        return !!this.planAdquisicion.id
      },
      iconoEstado () {
        if (this.planAdquisicion.estado === 'Registrado') return 'stars'
        if (this.planAdquisicion.estado === 'Activo') return 'check_circle'
        if (this.planAdquisicion.estado === 'Inactivo') return 'remove_circle'
        if (this.planAdquisicion.estado === 'Radicado') return 'assignment_turned_in'
      },
      colorEstado () {
        if (this.planAdquisicion.estado === 'Registrado') return 'teal'
        if (this.planAdquisicion.estado === 'Activo') return 'primary'
        if (this.planAdquisicion.estado === 'Inactivo') return 'red'
        if (this.planAdquisicion.estado === 'Radicado') return 'green'
      }
    },
    watch: {
      'planAdquisicion.anio' () {
        this.cambiarEstadoBotonGuardarPorAnio()
      },
      'planAdquisicion.descripcion' () {
        this.cambiarEstadoBotonGuardarPorDescripcion()
      }
    },
    methods: {
      obtenerPlanesYRubros () {
        this.axios.get('ce_planadquisiciones')
          .then((res) => {
            this.planesAdquisiciones = res.data.data
            this.aniosUsados = this.getAniosUsados()
          })
          .catch(e => {
            console.log('Hay un error. ' + e)
            return false
          })

        this.axios.get('pr_rubros')
          .then((res) => {
            this.listaRubros = res.data.data
          })
          .catch(e => {
            console.log('Hay un error. ' + e)
            return false
          })
      },
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.formPlanesAdquisiciones.$el
        })
        this.axios.get('ce_planadquisiciones/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.planAdquisicion = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la Cuenta Bancaria. ' + e.response, color: 'danger'})
          })
      },
      cambiarEstadoBotonGuardarPorAnio () {
        if (this.fields.$formPlanesAdquisiciones.anio.changed === false) this.botonChanged = true
        if (this.fields.$formPlanesAdquisiciones.anio.changed !== false) this.botonChanged = false
      },
      cambiarEstadoBotonGuardarPorDescripcion () {
        if (this.fields.$formPlanesAdquisiciones.descripcion.changed === false) this.botonChanged = true
        if (this.fields.$formPlanesAdquisiciones.descripcion.changed !== false) this.botonChanged = false
      },
      async agregarDetalle (detalle) {
        this.localLoading = true
        if (await this.validador('formDetalles')) {
          this.dialogDetalle = false
          this.detalle.ce_planadquisicione_id = this.planAdquisicion.id
          let send = !detalle.id
            ? this.axios.post('ce_planadquisiciones/' + this.planAdquisicion.id + '/detalles', this.detalle)
            : this.axios.put('ce_planadquisiciones/' + this.planAdquisicion.id + '/detalles/' + this.detalle.id, this.detalle)
          send.then(response => {
            if (detalle.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'Detalle actualizado satisfactoriamente', color: 'success'})
              this.planAdquisicion.detalles.splice(this.planAdquisicion.detalles
                .findIndex(det => det.id === response.data.data.id), 1, response.data.data)
              this.localLoading = false
            } else {
              detalle.id = response.data.data.id
              this.$store.commit(SNACK_SHOW, {msg: 'Detalle creado satisfactoriamente', color: 'success'})
              this.planAdquisicion.detalles.splice(0, 0, detalle)
              this.localLoading = false
            }
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
            this.localLoading = false
          })
          this.$validator.reset()
          this.formDetallesReset()
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al registrar el detalle', color: 'danger'})
          this.localLoading = false
        }
      },
      editarDetalle (detalle) {
        this.dialogDetalle = true
        this.detalle = JSON.parse(JSON.stringify(detalle))
      },
      cargarDetalleAEliminar (index, idDetalle) {
        this.dialogConfirm = true
        this.detalleAEliminar = {index, idDetalle}
      },
      eliminarDetalle (detalle) {
        this.localLoading = true
        this.axios.delete('ce_planadquisiciones/' + this.planAdquisicion.id + '/detalles/' + detalle.idDetalle)
          .then(response => {
            this.dialogConfirm = false
            this.$store.commit(SNACK_SHOW, {msg: 'Detalle eliminado satisfactoriamente', color: 'success'})
            this.planAdquisicion.detalles.splice(detalle.index, 1)
            this.localLoading = false
          }).catch(e => {
            this.dialogConfirm = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al eliminar el registro. ' + e.response, color: 'danger'})
            this.localLoading = false
          })
      },
      formDetallesReset () {
        this.detalle = {
          ce_planadquisicione_id: null,
          ce_bienservicio_id: null,
          pg_uniapoyo_id: null,
          ce_modcontratacione_id: null,
          fecha_proceso: null,
          fecha_ofertas: null,
          duracion: null,
          valor: null
        }
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      getAniosUsados () {
        return this.planesAdquisiciones.map(plan => { if (!(this.planAdquisicion.anio != null && this.planAdquisicion.anio === plan.anio)) return plan.anio })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      close () {
        this.dialogDetalle = false
        this.formDetallesReset()
        this.$validator.reset()
      },
      cargarDatosDelPlan (data) {
        this.planAdquisicion.id = data.id
        this.planAdquisicion.anio = data.anio
        this.planAdquisicion.estado = data.estado
        this.planAdquisicion.descripcion = data.descripcion
        this.planAdquisicion.detalles = []
      },
      async submit () {
        if (await this.validador('formPlanesAdquisiciones')) {
          this.loadingSubmit = true
          const typeRequest = this.planAdquisicion.id ? 'editar' : 'crear'
          let send = !this.planAdquisicion.id ? this.axios.post('ce_planadquisiciones', this.planAdquisicion) : this.axios.put('ce_planadquisiciones/' + this.planAdquisicion.id, this.planAdquisicion)
          send.then(response => {
            if (this.planAdquisicion.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'Plan actualizado satisfactoriamente', color: 'success'})
              this.loadingSubmit = false
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Plan creado satisfactoriamente', color: 'success'})
              this.cargarDatosDelPlan(response.data.data)
              this.loadingSubmit = false
            }
            this.$store.commit(PLAN_ADQUISICIONES_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        }
      }
    }

  }
</script>

<style scoped>

</style>
