<template>
  <div>
    <v-dialog v-model="dialog" width="500px" scrollable>
      <v-form data-vv-scope="formBienesYServicios">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <v-container fluid grid-list-md :style="heightDialog">
            <v-layout row wrap >
              <v-flex xs12>
                <v-text-field v-model="bienYServicio.nombre"
                              label="Nombre" key="nombre"
                              name="nombre" v-validate="'required|max:150|not_in:'  + nombresUsados" required
                              :error-messages="errors.collect('nombre')"></v-text-field>
              </v-flex>

              <v-flex xs12>
                <treeselect
                  v-model="value"
                  :options="options"
                  :disable-branch-nodes="true"
                  :normalizer="normalizer" placeholder="Clase..."
                  @open="agrandarModal"/>
              </v-flex>

</v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="save" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" title="Bienes y Servicios" subtitle="Registro y gestión" btnplus btnplustitle="Crear Item" btnplustruncate @click.native="dialog = true"/>
      <loading v-model="tableLoading" />
      <v-container fluid grid-list-xl>
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
              item-value="value">
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md4>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              @input="buscar">
            </v-text-field>
          </v-flex>
        </v-layout>

        <v-data-table
          :headers="headers"
          :items="bienesYServicios"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="bienesYServicios.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.codigo }}</td>
            <td>{{ props.item.nombre }}</td>
            <td>{{ props.item.nombre_clase}}</td>
            <td>{{ props.item.nombre_familia}}</td>
            <td>{{ props.item.nombre_segmento}}</td>
            <td>{{ props.item.estado}}</td>
            <td class="text-xs-center">
              <v-tooltip top>
                <v-btn icon class="mx-0" slot="activator" @click="edit(props.item)">
                  <v-icon color="accent">edit</v-icon>
                </v-btn>
                <span>Editar</span>
              </v-tooltip>
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
            <td colspan="100%">
              <div class="text-xs-center">
                <v-pagination v-model="pagination.current_page" :length="pagination.last_page" @input="reloadPage"></v-pagination>
                <!--<v-pagination v-model="pagination.current_page" :length="pagination.last_page" @input="reloadPage"></v-pagination>-->
              </div>
            </td>
          </template>
        </v-data-table>
      </v-container>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import ToolbarList from '@/components/general/ToolbarList'
  import Treeselect from '@riophae/vue-treeselect'
  import '@riophae/vue-treeselect/dist/vue-treeselect.css'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'BienesYServicios',
    components: {ToolbarList, Treeselect, Loading},
    data () {
      return {
        // define default value
        value: null,
        // define options
        options: [],
        normalizer (node) {
          return {
            id: node.codigo + node.id,
            label: node.nombre,
            children: node.children
          }
        },
        bienesYServicios: [],
        bienYServicio: {},
        search: '',
        dialog: false,
        heightDialog: '',
        tableLoading: false,
        nombresUsados: [],
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
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Clase',
            align: 'left',
            sortable: false,
            value: 'clase'
          },
          {
            text: 'Familia',
            align: 'left',
            sortable: false,
            value: 'familia'
          },
          {
            text: 'Segmento',
            align: 'left',
            sortable: false,
            value: 'segmento'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
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
      'pagination.per_page' (value) {
        this.pagination.current_page = 1
        this.reloadPage()
      },
      'value' () {
        if (this.value !== undefined && this.value !== null) {
          this.bienYServicio.ce_clasbienservicio_id = this.value.substring(6)
          this.getNombresUsados(this.bienYServicio.ce_clasbienservicio_id)
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('bienesYServicios')
      },
      modalTitulo () {
        return !this.bienYServicio.id ? 'Nuevo Bien y Servicio' : 'Edición de: ' + this.bienYServicio.nombre
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      formReset () {
        this.bienYServicio = {
          id: '',
          gs_usuario_id: null,
          ce_clasbienservicio_id: null,
          codigo: null,
          nombre: null,
          estado: 'Inactivo'
        }
      },
      agrandarModal () {
        this.heightDialog = 'height: 450px;'
      },
      reloadPage () {
        this.tableLoading = true
        this.axios.get('ce_bienservicios?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.bienesYServicios = response.data.data
            this.tableLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })

        this.axios.get('segbienservicios')
          .then((response) => {
            this.options = response.data
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al hacer la busqueda de registros. ' + e.response, color: 'danger'})
          })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      async edit (item) {
        await this.axios.get('ce_bienservicios/' + item.id).then(response => {
          this.bienYServicio = response.data.data
          this.value = this.bienYServicio.clasbienservicio.codigo + this.bienYServicio.ce_clasbienservicio_id
        })

        this.getNombresUsados(this.bienYServicio.ce_clasbienservicio_id)
        this.dialog = true
      },
      async getNombresUsados (id) {
        await this.axios.get('clasbienservicios/' + id).then(response => {
          let nombres = response.data.data.bienservicios.map(nom => nom.nombre)
          this.nombresUsados = nombres
        })

        if (this.bienYServicio.id) {
          this.nombresUsados = this.nombresUsados.map(nom => { if (nom !== this.bienYServicio.nombre) return nom })
        }
      },
      close () {
        this.dialog = false
        this.heightDialog = ''
        this.value = undefined
        this.nombresUsados = ''
        this.formReset()
        this.$validator.reset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formBienesYServicios')) {
          let send = !this.bienYServicio.id ? this.axios.post('ce_bienservicios', this.bienYServicio) : this.axios.put('ce_bienservicios/' + this.bienYServicio.id, this.bienYServicio)
          send.then(response => {
            if (this.bienYServicio.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'El item se actualizó correctamente', color: 'success'})
              this.bienesYServicios.splice(this.bienesYServicios.findIndex(bien => bien.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'El item se creó correctamente', color: 'success'})
              this.bienesYServicios.splice(0, 0, response.data.data)
            }
            this.dialog = false
            this.heightDialog = ''
            this.value = undefined
            this.nombresUsados = ''
            this.close()
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
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
