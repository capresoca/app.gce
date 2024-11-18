<template>
  <div>
    <v-dialog v-model="dialog" width="720px">
      <v-form data-vv-scope="formJuzgados">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12 sm6 md6>
                <v-text-field v-model="juzgado.nombre"
                              label="Nombre" key="nombre"
                              name="nombre" v-validate="'required|max:150|not_in:'  + nombresUsados" required
                              :error-messages="errors.collect('nombre')"></v-text-field>
              </v-flex>

              <v-flex xs12 sm6 md6>
                <v-autocomplete
                  label="Municipio Origen"
                  v-model="juzgado.gn_municipio_id"
                  :items="complementos.municipios"
                  item-value="id"
                  item-text="nombre"
                  name="municipio origen"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('municipio origen')"
                  :filter="filterMunicipios">

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

              <v-flex xs12 sm6 md6>
                <v-text-field v-model="juzgado.direccion"
                              label="Dirección" key="direccion"
                              name="direccion"></v-text-field>
              </v-flex>

              <v-flex xs12 sm6 md6>
                <v-text-field v-model="juzgado.telefono"
                              label="Teléfono" key="direccion"
                              name="telefono"></v-text-field>
              </v-flex>

              <v-flex xs12 sm6 md6>
                <v-text-field v-model="juzgado.email"
                              label="Email" key="email"
                              name="email"></v-text-field>
              </v-flex>

              <v-flex xs12 sm6 md6>
                <v-text-field v-model="juzgado.codigo"
                              label="Código" key="codigo"
                              name="codigo" v-validate="'not_in:' + codigosUsados"
                              :error-messages="errors.collect('codigo')"></v-text-field>


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
      <toolbar-list :info="infoComponent" title="Juzgados" subtitle="Registro y gestión" btnplus btnplustitle="Crear Juzgado" btnplustruncate @click="openDialog"/>
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
          :items="juzgados"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="juzgados.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.nombre  }}</td>
            <td>{{ props.item.municipio.nombre}}</td>
            <td>{{ props.item.direccion ? props.item.direccion : 'No registra'}}</td>
            <td>{{ props.item.codigo ? props.item.codigo : 'No registra' }}</td>
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
                    @click="edit(props.item)"
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
                  >
                    <v-icon color="accent">far fa-file-pdf</v-icon>
                  </v-btn>
                  <span>Imprimir</span>
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
  import store from '../../../../store/complementos/index'
  import Loading from '@/components/general/Loading'

  export default {
    name: 'Juzgados',
    components: {
      Postulador: () => import('@/components/general/Postulador'),
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Loading
    },
    data () {
      return {
        juzgados: [],
        juzgado: {},
        codigosUsados: '',
        nombresUsados: '',
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
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Municipio',
            align: 'left',
            sortable: false,
            value: 'municipio'
          },
          {
            text: 'Dirección',
            align: 'left',
            sortable: false,
            value: 'direccion'
          },
          {
            text: 'Código',
            align: 'left',
            sortable: false,
            value: 'codigo'
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
    created () {
      this.formReset()
      this.reloadPage()
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
    watch: {
      'pagination.per_page' (value) {
        this.pagination.current_page = 1
        this.reloadPage()
      }
    },
    computed: {
      complementos () {
        let beforeComplement = JSON.parse(JSON.stringify(store.getters.complementosTercerosFormBasicos))

        return beforeComplement
      },
      modalTitulo () {
        return !this.juzgado.id ? 'Nuevo Juzgado' : 'Edición del juzgado: ' + this.juzgado.nombre
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('juzgados')
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.axios.get('oj_juzgados?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.juzgados = response.data.data
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
        this.juzgado = {
          id: '',
          codigo: null,
          nombre: null,
          gn_municipio_id: null,
          direccion: null,
          telefono: null,
          email: null
        }
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      edit (item) {
        this.dialog = true
        this.juzgado = JSON.parse(JSON.stringify(item))
        this.codigosUsados = this.getCodigosUsados()
        this.nombresUsados = this.getNombresUsados()
      },
      filterMunicipios (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      openDialog () {
        this.dialog = true
        this.formReset()
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      getCodigosUsados () {
        return this.juzgados.map(cod => { if (!(this.juzgado.codigo != null && this.juzgado.codigo === cod.codigo)) return cod.codigo })
      },
      getNombresUsados () {
        return this.juzgados.map(nom => { if (!(this.juzgado.nombre != null && this.juzgado.nombre === nom.nombre)) return nom.nombre })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formJuzgados')) {
          let send = !this.juzgado.id ? this.axios.post('oj_juzgados', this.juzgado) : this.axios.put('oj_juzgados/' + this.juzgado.id, this.juzgado)
          send.then(response => {
            if (this.juzgado.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'El juzgado se actualizó correctamente', color: 'success'})
              this.juzgados.splice(this.juzgados.findIndex(juzgado => juzgado.id === response.data.data.id), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'El juzgado se creó correctamente', color: 'success'})
              this.juzgados.splice(0, 0, response.data.data)
            }
            this.dialog = false
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
