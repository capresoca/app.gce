<template>
  <div>
  <!-- // INICIO HTML AGREGADOS RAFAEL PEDRAZA -->
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Subir archivo CUMS</span>
          </v-card-title>
          <!-- formulario de creacion-->
          <v-container fluid grid-list-md>
            <v-layout row wrap>                
              <v-flex xs12>
                <input-file
                        ref="archivo"
                        label="Archivo"
                        v-model="archivo"
                        required="required"
                        accept=".txt"
                        :hint="'Extenciones aceptadas: txt'"
                        prepend-icon="description"
                />           
              </v-flex>
            </v-layout>
          </v-container>
          <!-- fin formulario -->
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="dialog = false">Cancelar</v-btn>
            <v-btn color="primary" @click="subir()" :disabled="errors.any()" :loading="loadingSubmit">Subir</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <!-- // FIN HTML AGREGADOS RAFAEL PEDRAZA -->
    <v-card>
      <toolbar-list :info="infoComponent" title="Códigos únicos de medicamentos" subtitle="Registro y gestión" btnplus btnplustitle="Crear Item"/>
      <loading v-model="tableLoading" />
      <v-container fluid>
        <v-layout row wrap>
          <!-- // INICIO HTML AGREGADOS RAFAEL PEDRAZA -->
          <v-flex xs12 sm1 md2>
            <v-btn small color="primary" @click="cargar">
              <v-icon left>far fa-file-alt</v-icon>
                CARGA MASIVA CUMS
            </v-btn>
          </v-flex>
          <v-flex xs12 sm1 md2>
            <v-btn small color="accent" @click="descargar">
              <v-icon left>far fa-file-alt</v-icon>
                DESCARGAR CUMS
            </v-btn>
          </v-flex>
          <!-- // FIN HTML AGREGADOS RAFAEL PEDRAZA -->
          <v-flex xs1 sm2 md2 class="text-xs-right">
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
          :items="cums"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="cums.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.codigo}}</td>
            <td>{{ props.item.producto}}</td>
            <td>{{ props.item.titular}}</td>
            <td>{{ props.item.estado_registro}}</td>
            <td>{{ props.item.descripcion_comercial}}</td>
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
                    @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: infoComponent.titulo_registro, parametros: {entidad: props.item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
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
  import {mapState} from 'vuex'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'Cums',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      InputFile: () => import('@/components/general/InputFile'),
      Loading
    },
    data () {
      return {
        dialog: false,
        archivo: '',
        loadingSubmit: false,
        cums: [],
        search: '',
        tableLoading: false,
        info: null,
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
            text: 'Código',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Producto',
            align: 'left',
            sortable: false,
            value: 'producto'
          },
          {
            text: 'Laboratorio',
            align: 'left',
            sortable: false,
            value: 'laboratorio'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
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
      'itemCums' (value) {
        if (value.estado === 'crear') {
          this.cums.splice(0, 0, value.item)
        } else if (value.estado === 'editar') {
          this.cums.splice(this.cums.findIndex(x => x.id === value.item.id), 1, value.item)
        }
      },
      'pagination.per_page' () {
        this.pagination.current_page = 1
        this.reloadPage()
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('cums')
      },
      ...mapState({
        itemCums: state => state.tables.itemCums
      }),
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.axios.get('rs_cums?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.cums = response.data.data
            this.tableLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Error al traer los registros. ' + e, color: 'danger'})
            return false
          })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      // INICIO METODOS AGREGADOS RAFAEL PEDRAZA
      cargar () {
        this.dialog = true
      },
      subir () {
        this.dialog = false
        this.loadingSubmit = true
        let data = new FormData()
        data.append('archivo', this.archivo)
        this.axios.post('cargamasivacums', data)
          .then(response => {
            this.loadingSubmit = false
            this.close()
            this.$store.commit('SNACK_SHOW', {msg: 'Se ha cargado el archivo.', color: 'success'})
          }).catch(e => {
            this.$store.commit('SNACK_SHOW', {msg: 'error al cargar archivo', color: 'error'})
            this.loadingSubmit = false
          })
      },
      descargar () {
        if (this.fileDownload('cums_descarga')) {
          this.$store.commit('SNACK_SHOW', {msg: 'Comenzo la descarga del archivo.', color: 'success'})
        } else {
          this.$store.commit('SNACK_SHOW', {msg: 'error al descargar archivo', color: 'error'})
        }
      },
      close () {
        this.archivo = null
        this.dialog = false
      }
      // FIN METODOS AGREGADOS RAFAEL PEDRAZA
    }
  }
</script>

<style scoped>

</style>
