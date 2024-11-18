<template>
  <div>
    <v-dialog v-model="dialog" width="500px" persistent>
      <v-form data-vv-scope="formEncargos">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12 sm6>
                <v-menu
                  ref="menuDateInicio"
                  :close-on-content-click="false"
                  v-model="menuDateInicio"
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
                    v-model="computedDateFormattedFechaInicio"
                    label="Fecha inicio"
                    prepend-icon="event"
                    readonly
                    name="fecha inicio"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha inicio')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="encargo.fecha_inicio"
                    @input="menuDateInicio = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha inicio')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>
              </v-flex>

              <v-flex xs12 sm6>
                <v-menu
                  ref="menuDateInicio"
                  :close-on-content-click="false"
                  v-model="menuDateFin"
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
                    v-model="computedDateFormattedFechaFin"
                    label="Fecha fin"
                    prepend-icon="event"
                    readonly
                    name="fecha fin"
                    required
                    data-vv-delay="1000"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha fin')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="encargo.fecha_fin"
                    @input="menuDateFin = false"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>
              </v-flex>

              <v-flex xs12>
                <postulador-v2
                  no-data="Busqueda por nombre o número de documento."
                  hint="email"
                  item-text="name"
                  data-title="email"
                  data-subtitle="name"
                  filter="name,identification,email"
                  label="Usuario"
                  entidad="usuarios"
                  preicon="person"
                  v-model="encargo.usuario"
                  @changeid="val => encargo.gs_usuario_id = val"
                  name="usuario"
                  :rules="'required'"
                  v-validate="'required|rangoValido:' + validarUsuarioFecha"
                  :error-messages="errors.collect('usuario')"
                  no-btn-edit
                />
              </v-flex>


              <v-flex xs12>
                <v-select
                  :items="tiposEncargo"
                  v-model="encargo.tipo_encargo"
                  label="Tipo"
                  name="tipo"
                  prepend-icon="input"
                  :error-messages="errors.collect('tipo')"
                  v-validate="'required|tipoValido'" required
                ></v-select>
              </v-flex>

              <v-flex xs12>
                <v-textarea
                  v-model="encargo.concepto_encargo"
                  outline
                  name="observacion"
                  label="Observación"
                ></v-textarea>
              </v-flex>

              <v-flex xs12>
                <v-subheader class="pa-0 ma-0">Estado</v-subheader>
                <v-switch class="ma-0 pa-0"  color="accent"
                          v-model="encargo.estado"  :true-value="1" :false-value="0"></v-switch>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="save" :loading="loadingSubmit">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" title="Encargos" subtitle="Registro y gestión" btnplus btnplustitle="Crear encargo" btnplustruncate @click="openDialog"/>
      <loading v-model="tableLoading" />
      <v-container fluid>
        <v-layout row wrap>
          <v-flex xs1 sm3 md6 class="text-xs-right">
            <v-tooltip top>
              <v-btn
                slot="activator"
                flat
                icon
                color="accent"
                @click="reloadPage"
              >
                <v-icon>cached</v-icon>
              </v-btn>
              <span>Actualizar registros</span>
            </v-tooltip>
          </v-flex>
          <v-flex xs12 sm3 md2>
            <v-select
              label="Registros por página"
              v-model="pagination.per_page"
              :items="items_page"
              item-text="text"
              item-value="value"
            />
          </v-flex>
          <v-flex xs12 sm6 md4>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              @input="buscar"
            />
          </v-flex>
        </v-layout>

        <v-data-table
          :headers="headers"
          :items="encargos"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="encargos.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.id  }}</td>
            <td>{{ props.item.fecha_inicio }}</td>
            <td>{{ props.item.fecha_fin }}</td>
            <td> <v-chip color="primary" text-color="white">{{ props.item.diferencia_dias }}</v-chip></td>
            <td>{{ props.item.usuario ? props.item.usuario.name : 'No registra' }}</td>
            <td>{{ props.item.tipo_encargo }}</td>
            <td>
              <v-speed-dial v-if="props.item.diferencia_dias !== 'Finalizado'"
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
                    @click="edit(props.item)"
                  >
                    <v-icon color="accent">mode_edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
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

          <template slot="footer">
            <td colspan="100%" class="text-xs-center">
              <v-pagination v-model="pagination.current_page" :total-visible="7"  :length="pagination.last_page" @input="reloadPage"></v-pagination>
            </td>
          </template>
        </v-data-table>
      </v-container>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import { Validator } from 'vee-validate'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'Encargos',
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        encargos: [],
        encargo: {},
        tiposEncargo: ['Representante Legal', 'Jefe de Presupuesto', 'Jefe Financiero'],
        fechaInicio: null,
        fechaFin: null,
        menuDateInicio: false,
        menuDateFin: false,
        search: '',
        dialog: false,
        loadingSubmit: false,
        tableLoading: false,
        items_page: [
          {
            text: 15,
            value: 15
          },
          {
            text: 50,
            value: 50
          },
          {
            text: 100,
            value: 100
          }
        ],
        pagination: {
          per_page: 15,
          current_page: 1
        },

        headers: [
          {
            text: 'Id',
            align: 'left',
            sortable: false,
            value: 'id'
          },
          {
            text: 'Fecha inicio',
            align: 'left',
            sortable: false,
            value: 'fechaInicio'
          },
          {
            text: 'Fecha fin',
            align: 'left',
            sortable: false,
            value: 'fechaFin'
          },
          {
            text: 'Días encargo',
            align: 'left',
            sortable: false,
            value: 'diasEncargo'
          },
          {
            text: 'Usuario',
            align: 'left',
            sortable: false,
            value: 'usuario'
          },
          {
            text: 'Tipo',
            align: 'left',
            sortable: false,
            value: 'tipo'
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
      this.validadorRangoFecha()
      this.validadorRangoTipo()
    },
    created () {
      this.formReset()
      this.reloadPage()
    },
    watch: {
      'pagination.per_page' (value) {
        this.pagination.current_page = 1
        this.reloadPage()
      }
    },
    computed: {
      validarUsuarioFecha () {
        let encargosPorUsuario = this.encargos.filter(encargo => {
          return encargo.gs_usuario_id === this.encargo.gs_usuario_id
        })

        let encargosFiltrado = encargosPorUsuario.filter(cod => { if (!(this.encargo.id != null && this.encargo.id === cod.id)) return cod })

        return encargosFiltrado.some(this.estaEnRangoDeFecha)
      },
      modalTitulo () {
        return !this.encargo.id ? 'Nuevo encargo' : 'Edición del encargo: ' + this.encargo.id
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('encargos')
      },
      computedDateFormattedFechaInicio () {
        return this.formDate(this.encargo.fecha_inicio)
      },
      computedDateFormattedFechaFin () {
        return this.formDate(this.encargo.fecha_fin)
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.axios.get('th_encargos?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.encargos = response.data.data
            this.tableLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            console.log('Hay un error. ' + e)
            return false
          })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      estaEnRangoDeFecha (encargo) {
        return this.moment(this.encargo.fecha_inicio).isBetween(encargo.fecha_inicio, encargo.fecha_fin, null, '[]')
      },
      estaTipoEnRangoDeFecha (encargo) {
        return this.moment(this.encargo.fecha_inicio).isBetween(encargo.fecha_inicio, encargo.fecha_fin, null, '[]')
      },
      formReset () {
        this.encargo = {
          id: '',
          concepto_encargo: null,
          tipo_encargo: null,
          fecha_fin: null,
          fecha_inicio: null,
          gs_usuario_id: null,
          usuario: null,
          estado: 1
        }
      },
      edit (item) {
        this.dialog = true
        this.encargo = JSON.parse(JSON.stringify(item))
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      openDialog () {
        this.dialog = true
        this.formReset()
      },
      close () {
        this.dialog = false
        this.loadingSubmit = false
        this.formReset()
        this.$validator.reset()
      },
      validadorRangoFecha () {
        Validator.extend('rangoValido', {
          validate: (value, prop) => new Promise((resolve) => {
            console.log('Value', value)
            console.log('Prop', prop)
            let response = {}

            if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
              prop[0] === 'true' ? response = {valido: false, mensaje: 'El usuario ya ha sido asignado en este rango de fecha'} : response = {valido: true, mensaje: null}

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
      validadorRangoTipo () {
        Validator.extend('tipoValido', {
          validate: (value, prop) => new Promise((resolve) => {
            let response = {}

            if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
              let encargosPorTipo = this.encargos.filter(encargo => {
                return encargo.tipo_encargo === this.encargo.tipo_encargo
              })

              let encargosFiltrado = encargosPorTipo.filter(cod => { if (!(this.encargo.id != null && this.encargo.id === cod.id)) return cod })

              encargosFiltrado.some(this.estaTipoEnRangoDeFecha) ? response = {valido: false, mensaje: 'El tipo ya ha sido asignado en este rango de fecha'} : response = {valido: true, mensaje: null}

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
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formEncargos')) {
          this.loadingSubmit = true
          let send = !this.encargo.id ? this.axios.post('th_encargos', this.encargo) : this.axios.put('th_encargos/' + this.encargo.id, this.encargo)
          send.then(response => {
            if (this.encargo.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'El encargo se actualizó correctamente', color: 'success'})
              this.encargos.splice(this.encargos.findIndex(encargo => encargo.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'El encargo se creó correctamente', color: 'success'})
              this.encargos.splice(0, 0, response.data.data)
            }
            this.close()
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al hacer la busqueda de registros. ', error: e})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>
