<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <!-- formulario de creacion periodos-->
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              <v-flex xs12>
                <v-text-field v-model="periodos.descripcion"
                              label="Descripción" key="descripcion"
                              name="descripcion" v-validate="'required|max:500|'" required
                              :error-messages="errors.collect('descripcion')"></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <v-menu
                  ref="menuDateRangoInicial"
                  :close-on-content-click="false"
                  v-model="menuDateFechaInicial"
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
                    v-model="computedDateFormattedFechaInicial"
                    label="Fecha inicial"
                    prepend-icon="event"
                    readonly
                    name="fecha inicial"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha inicial')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="periodos.fecha_inicio"
                    @input="menuDateFechaInicial = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha inicial')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>
              </v-flex>

              <v-flex xs12>
                <v-menu
                  ref="menuDateRangoFinal"
                  :close-on-content-click="false"
                  v-model="menuDateFechaFinal"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    ref="prueba"
                    slot="activator"
                    v-model="computedDateFormattedFechaFinal"
                    label="Fecha final"
                    prepend-icon="event"
                    readonly
                    name="fecha final"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha final')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="periodos.fecha_fin"
                    @input="menuDateFechaFinal = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'fecha final')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>
              </v-flex>

            </v-layout>
          </v-container>
          <!-- fin formulario -->
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="save" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <confirmar ref="confirmo" :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)" />
    <v-card>
      <toolbar-list :info="infoComponent" title="Periodos de liquidación" subtitle="Registro y gestión" btnplus btnplustitle="Crear registro" btnplustruncate @click="dialog = true"/>
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

        <loading v-model="localLoading" />
        <v-data-table
          :headers="headers"
          :items="periodosLiquidacion"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="periodosLiquidacion.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.consecutivo_liquidacion }}</td>
            <td>{{ props.item.descripcion}}</td>
            <td>{{ props.item.fecha_inicio }}</td>
            <td>{{ props.item.fecha_fin }}</td>
            <td>
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
                    @click="eliminar(props.item.consecutivo_liquidacion)"
                  >
                    <v-icon color="accent">delete</v-icon>
                  </v-btn>
                  <span>Eliminar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="editar(props.item)"
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
              <v-pagination v-model="pagination.current_page" :total-visible="7" :length="pagination.last_page" @input="reloadPage"></v-pagination>
            </td>
          </template>
        </v-data-table>
      </v-container>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  // import store from '../../../store/complementos/index'
  import Loading from '@/components/general/Loading'
  import Confirmar from '@/components/general/Confirmar2'

  export default {
    name: 'periodoLiquidacion',
    components: {
      Loading,
      Confirmar,
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        fechaInicial: '',
        fechaFinal: '',
        menuDateFechaInicial: false,
        menuDateFechaFinal: false,
        periodosLiquidacion: [],
        periodos: {},
        search: '',
        dialog: false,
        tableLoading: false,
        localLoading: false,
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
            text: 'Consecutivo liquidación',
            align: 'left',
            sortable: false,
            value: 'consecutivo_liquidacion'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
          },
          {
            text: 'Fecha inicio',
            align: 'left',
            sortable: false,
            value: 'fecha_inicio'
          },
          {
            text: 'Fecha fin',
            align: 'left',
            sortable: false,
            value: 'fecha_fin'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'consecutivo_liquidacion'
          }
        ]
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      const dict = {
        custom: {
          codigo: {
            not_in: 'Este código ya fue registrado.'
          },
          nombre: {
            not_in: 'Este nombre ya fue registrado.'
          }
        }
      }

      this.$validator.localize('es', dict)
    },
    created () {
      this.reloadPage()
    },
    watch: {
      'pagination.per_page' () {
        this.pagination.current_page = 1
        this.reloadPage()
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('periodoLiquidacion')
      },
      modalTitulo () {
        return !this.periodos.consecutivo_liquidacion ? 'Nuevo periodo de liquidación' : 'Editar periodo: ' + this.periodos.consecutivo_liquidacion
      },
      complementos () {
        // return JSON.parse(JSON.stringify(store.getters.complementosPeriodoLiquidacion))
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      },
      computedDateFormattedFechaInicial () {
        return this.formDate(this.periodos.fecha_inicio)
      },
      computedDateFormattedFechaFinal () {
        return this.formDate(this.periodos.fecha_fin)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.axios.get('periodo?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.periodosLiquidacion = response.data.data
            this.tableLoading = false
            this.localLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            return false
          })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      formReset () {
        this.periodos = {
          consecutivo_liquidacion: '',
          descripcion: null,
          fecha_inicio: null,
          fecha_fin: null
        }
      },
      eliminar (item) {
        this.dialogA.content = '¿Está seguro de eliminar?'
        this.dialogA.registroID = item
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroID = null
      },
      confirmaAnulacion (motivo) {
        this.localLoading = true
        this.axios.delete('periodo/eliminar/' + this.dialogA.registroID)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'El periodo de liquidación se eliminó correctamente', color: 'success'})
            this.reloadPage()
            this.cancelaAnulacion()
            this.localLoading = false
            this.$refs.confirmo.pararCarga()
          })
          .catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al eliminar el registro. ' + e.response, color: 'danger'})
          })
      },
      editar (item) {
        this.dialog = true
        this.periodos = Object.assign({}, item)
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formPago')) {
          this.dialog = false
          this.localLoading = true
          var send = !this.periodos.consecutivo_liquidacion ? this.axios.post('periodo/crear', this.periodos) : this.axios.put('periodo/actualizar/' + this.periodos.consecutivo_liquidacion, this.periodos)
          send.then(response => {
            this.localLoading = false
            if (this.periodos.consecutivo_liquidacion) {
              this.$store.commit(SNACK_SHOW, {msg: 'El periodo de liquidación se actualizó correctamente', color: 'success'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Registro exitoso', color: 'success'})
              this.reloadPage()
            }
            this.dialog = false
          }).catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
        this.formReset()
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      }
    }

  }
</script>

<style scoped>


</style>
