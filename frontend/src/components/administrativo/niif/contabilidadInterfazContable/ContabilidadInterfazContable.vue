<template>
  <div>
    <v-dialog v-model="dialog" width="720px" persistent>
      <v-form data-vv-scope="formDialog">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Cargar archivo</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12>
                <input-file-v2
                  label="Archivo"
                  v-model="file"
                  accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                  :hint="'Extenciones aceptadas: xls, xlsx'"
                  prepend-icon="fas fa-paperclip"
                  name="historia clínica"
                  v-validate="'fileExtension:xls, xlsx|required'"
                  :error-messages="errors.collect('historia clínica')"
                ></input-file-v2>
              </v-flex>

              <v-flex xs12>
                <h2 v-if="errores.length">Se encontraron errores en el archivo:</h2>
                <p v-for="error in errores">{{error}}</p>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" :loading="tableLoading" @click="save">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" title="Interfaz Contable" subtitle="Registro y gestión" btnicon="fas fa-upload" btnplus btnplustitle="Cargar archivo" btnplustruncate @click="openDialog"/>
      <loading v-model="tableLoading" />
      <v-container fluid>
        <v-layout row wrap>
          <v-flex xs12 sm3>
            <v-tooltip top>
              <v-btn
                slot="activator"
                color="blue-grey"
                class="white--text"
                fab
                small
                :href="axios.defaults.baseURL + '/../files/FORMATO_INTERFAZ_CONTABLE.xlsx'"
              ><v-icon dark>archive</v-icon>
              </v-btn>
              <span>Descargar planilla</span>
            </v-tooltip>

            <v-tooltip top>
              <v-btn
                slot="activator"
                color="primary"
                class="white--text"
                fab
                small
                :href="axios.defaults.baseURL + '/../files/Interface_Contable_GCE_Software.pdf'"
                target="_blank"
              ><v-icon dark>assignment</v-icon>
              </v-btn>
              <span>Descargar documento interfaz contable</span>
            </v-tooltip>

          </v-flex>

          <v-flex xs1 sm3 md2 class="text-xs-right">
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
          :items="registros"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="registros.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.nfcomdiario.consecutivo  }}</td>
            <td>{{ props.item.nfcomdiario.fecha}}</td>
            <td>{{ props.item.nfcomdiario.detalle}}</td>
            <td>{{ props.item.nfcomdiario.estado}}</td>
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
                    @click="eliminarRegistro(props.item.nfcomdiario.id)"
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
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'ContabilidadInterfazContable',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      InputFileV2: () => import('@/components/general/InputFileV2'),
      Loading

    },
    data () {
      return {
        registros: [],
        errores: [],
        payload: null,
        file: null,
        search: '',
        dialog: false,
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
            text: 'Consecutivo',
            align: 'left',
            sortable: false
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false
          },
          {
            text: 'Detalle',
            align: 'left',
            sortable: false
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false
          }
        ]
      }
    },
    created () {
      this.formReset()
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
        return this.$store.getters.getInfoComponent('contabilidadInterfazContable')
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.axios.get('interfaz-contable/comdiario-lotes?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.registros = response.data.data
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
      formReset () {
        this.file = null
      },
      openDialog () {
        this.dialog = true
        this.formReset()
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
        this.errores = []
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      eliminarRegistro (id) {
        this.tableLoading = true
        this.axios.delete('interfaz-contable/comdiario-lote/' + id)
          .then(response => {
            this.$store.commit(SNACK_SHOW, {msg: 'El item se eliminó correctamente', color: 'success'})
            this.reloadPage()
          })
          .catch(e => {
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al eliminar el registro. ' + e.response, color: 'danger'})
          })
      },
      addFormData () {
        let data = new FormData()
        data.append('excelComprobante', typeof this.file === 'undefined' ? '' : this.file)
        this.payload = data
      },
      async save () {
        this.errores = []
        if (await this.validador('formDialog')) {
          this.tableLoading = true
          this.addFormData()
          this.axios.post('interfaz-contable/importar-comdiario', this.payload)
            .then(response => {
              this.$store.commit(SNACK_SHOW, {msg: 'El item se creó correctamente', color: 'success'})
              this.reloadPage()
              this.close()
            }).catch(e => {
              this.tableLoading = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. Verifique que el archivo tenga un formato correcto.', color: 'danger'})
              this.errores = e.response.data
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

