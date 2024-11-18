<template>
  <div>
    <v-dialog v-model="dialog" width="720px">
      <v-form data-vv-scope="formTutelas">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title text-uppercase">Generar Archivo</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-subheader class="pa-0 ma-0">Rango de fecha del reporte</v-subheader>
            <v-layout row wrap>
              <v-flex xs12 sm6>
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
                    v-model="computedDateFormattedRangoInicial"
                    label="Fecha inicial"
                    prepend-icon="event"
                    readonly
                    name="fecha inicial"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha inicial')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="rangoInicial"
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

              <v-flex xs12 sm6>
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
                    slot="activator"
                    v-model="computedDateFormattedRangoFinal"
                    label="Fecha final"
                    prepend-icon="event"
                    readonly
                    name="fecha final"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('fecha final')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="rangoFinal"
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

            <v-subheader class="pa-0 ma-0">Formato de archivo a generar</v-subheader>
            <v-layout row wrap>

              <v-radio-group v-model="file" row>
                <v-radio label="PDF" color="red" value="application/pdf"></v-radio>
                <v-radio label="CSV" color="success" value="application/xls"></v-radio>
              </v-radio-group>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close" :loading="loadingSubmit">Cancelar</v-btn>
            <v-btn color="primary" @click="generar(2)" :loading="loadingSubmit" :disabled="errors.any()">Generar Indicador</v-btn>
            <v-btn color="primary" @click="generar(1)" :loading="loadingSubmit" :disabled="errors.any()">Generar Reporte Supersalud</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" title="Tutelas" subtitle="Registro y gestión" btnplus btnplustitle="Registrar Tutela"/>
      <loading v-model="tableLoading" />
      <v-container fluid>
        <v-layout row wrap>
          <v-flex xs12 sm3 md4>
            <v-btn
              color="blue-grey"
              class="white--text"
              @click.native="dialog = true"
            >
              Generar reporte
              <v-icon right dark>assignment</v-icon>
            </v-btn>
          </v-flex>
          <v-flex xs12 sm3 md2 class="text-xs-right">
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
          <v-flex xs12 sm3 md4>
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
          :items="tutelas"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="tutelas.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.no_tutela }}</td>
            <td class="text-xs-center">{{ props.item.fecha }}</td>
            <td class="text-xs-center">{{ props.item.fecha_notificacion }}</td>
            <td>{{ props.item.nombre_juzgado }}</td>
            <!-- <td width="150px">{{ props.item.identificacion_completa }}</td> -->
            <td width="150px" v-if="props.item.afiliados_tutelas[0]">{{ props.item.afiliados_tutelas[0].identificacion }}</td>
            <td class="text-xs-left pa-0" width="300px">
              <div v-for="(afiliado, i) in props.item.afiliados_tutelas" >
                <span class="d-block" v-text="(afiliado.afiliado ? (afiliado.afiliado.sexo.nombre === 'Masculino' ? ' 👨 ' : ' 👩 ') : ' 🛑 ') + afiliado.nombre_completo" :key="i"></span>
              </div>
              <!--{{ props.item.nombre_afiliado }}-->
            </td>
            <td>{{ props.item.estado }}</td>
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
                    @click="confirmarEliminar(props.item.id)"
                  >
                    <v-icon color="accent">delete_forever</v-icon>
                  </v-btn>
                  <span>Eliminar</span>
                </v-tooltip> <!-- btn-eliminar -->

                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
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
                    @click="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleTutela', titulo: 'Detalle tutela', parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
                  >
                    <v-icon color="accent">find_in_page</v-icon>
                  </v-btn>
                  <span>Ver detalle</span>
                </v-tooltip>

                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    :href="props.item.url_signed"
                    target="_blank"
                  >
                    <v-icon color="accent">remove_red_eye</v-icon>
                  </v-btn>
                  <span>Ver documento</span>
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
  import {mapState} from 'vuex'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'
  import Swal from 'sweetalert2'

  export default {
    name: 'Tutelas',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        tutelas: [],
        rangoInicial: '',
        rangoFinal: '',
        menuDateFechaInicial: false,
        menuDateFechaFinal: false,
        indicador: null,
        file: 'application/pdf',
        loadingSubmit: false,
        show: false,
        search: '',
        dialog: false,
        picker: '',
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
            text: 'No.',
            align: 'left',
            sortable: false,
            value: 'numero'
          },
          {
            text: 'Fecha Radicación',
            align: 'center',
            sortable: false,
            value: 'fecha'
          },
          {
            text: 'Fecha Notificación',
            align: 'center',
            sortable: false,
            value: 'fecha_notificacion'
          },
          {
            text: 'Juzgado',
            align: 'left',
            sortable: false,
            value: 'juzgado'
          },
          {
            text: 'Accionante',
            align: 'left',
            sortable: false,
            value: 'accionante'
          },
          {
            text: 'Afiliados',
            align: 'left',
            sortable: false,
            value: 'afiliados_tutelas',
            width: '200px'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
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
    created () {
      this.reloadPage()
    },
    watch: {
      'itemTutelas' (value) {
        value.item.show = false
        if (value.estado === 'crear') {
          this.tutelas.splice(0, 0, value.item)
        } else if (value.estado === 'editar') {
          this.tutelas.splice(this.tutelas.findIndex(x => x.id === value.item.id), 1, value.item)
        }
      },
      'pagination.per_page' () {
        this.pagination.current_page = 1
        this.reloadPage()
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('tutelas')
      },
      ...mapState({
        itemTutelas: state => state.tables.itemTutelas
      }),
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      },
      computedDateFormattedRangoInicial () {
        return this.formDate(this.rangoInicial)
      },
      computedDateFormattedRangoFinal () {
        return this.formDate(this.rangoFinal)
      }
    },
    methods: {
      formReset () {
        this.rangoInicial = ''
        this.rangoFinal = ''
        this.file = 'application/pdf'
      },
      reloadPage () {
        this.tableLoading = true
        this.axios.get('oj_tutelas?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.tutelas = response.data.data
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
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      close () {
        this.dialog = false
        this.indicador = null
        this.loadingSubmit = false
        this.formReset()
        this.$validator.reset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async generar (val) {
        console.log('generar', val)
        this.indicador = parseInt(val)
        if (await this.validador('formTutelas')) {
          this.loadingSubmit = true
          if (this.indicador === 2) this.file = 'application/xls'
          this.axios.get(`reporte_oj_tutelas?rangoInicial=${this.rangoInicial}&rangoFinal=${this.rangoFinal}&mimeType=${this.file}&item=${this.indicador}`)
            .then((res) => {
              let url = res.data
              console.log(res)
              if (this.file === 'application/pdf') {
                window.open(url)
              } else {
                window.fileDownload(url)
              }
              this.close()
            })
            .catch(e => {
              this.close()
              this.$store.commit(SNACK_SHOW, {msg: 'Error al generar el archivo. ' + e.response, color: 'danger'})
            })
        }
      },
      // mis metodos RJPT
      confirmarEliminar (idTutela) {
        Swal.fire({
          title: '<font face=\'Roboto\' >¿Eliminar tutela?</font>',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: '<font face=\'Roboto\' >Eliminar</font>',
          cancelButtonText: '<font face=\'Roboto\' >Cancelar</font>'
        }).then((result) => {
          if (result.value) {
            this.eliminar(idTutela)
          }
        })
      },
      eliminar (id) {
        this.axios.delete(`borrarTutela/${id}`)
          .then(() => {
            this.$store.commit('SNACK_SHOW', {msg: `La tutela fue borrada correctamente.`, color: 'success'})
            this.reloadPage()
          })
          .catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: `al borrar la tutela`, error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
