<template>
  <div>
    <v-dialog v-model="dialog" width="1000px">
      <v-form data-vv-scope="formPago">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <!-- formulario de creacion-->
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12>
                <v-autocomplete
                  label="Fondo"
                  v-model="fondos.clase_fondo"
                  :items="complementos.clasefondo"
                  item-value="clase_fondo"
                  item-text="descripcion"
                  name="fondo"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('fondo')"> 

                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.descripcion"/>
                        <v-list-tile-sub-title v-html="data.item.clase_fondo"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>
              
              <v-flex xs12 md6 lg6>
                <v-text-field v-model="fondos.codigo"
                              label="Código" key="codigo"
                              name="codigo"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="fondos.descripcion"
                              label="Descripción" key="descripcion"
                              name="descripcion" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('descripcion')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="fondos.nit"
                              label="NIT " key="nit"
                              name="nit" v-validate="'required|max:20|'" required
                              :error-messages="errors.collect('nit')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="fondos.digito_verificacion"
                              label="Dígito verificación " key="digito_verificacion"
                              name="digito verificacion" v-validate="'|max:1|'"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="fondos.direccion"
                              label="Dirección " key="direccion"
                              name="direccion" v-validate="'required|max:200|'"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="fondos.telefono"
                              label="Teléfono " key="telefono"
                              name="telefono" v-validate="'required|max:50|'"></v-text-field>                 
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
    <v-card>
      <toolbar-list :info="infoComponent" title="Fondo" subtitle="Registro y gestión" btnplus btnplustitle="Crear registro" btnplustruncate @click="dialog = true"/>
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
          :items="fondosC"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="fondosC.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.codigo}}</td>
            <td>{{ props.item.descripcion}}</td>
            <td>{{ props.item.nit}}</td>
            <td>{{ props.item.telefono}}</td>
            <td>{{ props.item.descripciontcf }}</td>
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
                    @click="eliminar(props.item.fondo)"
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
    <confirmar ref="confirmo" :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)" />
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import store from '../../../../store/complementos/index'
  import Loading from '@/components/general/Loading'
  import Confirmar from '@/components/general/Confirmar2'

  export default {
    name: 'fondos',
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
        fondosC: [],
        fondos: {},
        segmento: '1',
        clasesFondos: [],
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
            text: 'Código',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
          },
          {
            text: 'NIT',
            align: 'left',
            sortable: false,
            value: 'nit'
          },
          {
            text: 'Teléfono',
            align: 'left',
            sortable: false,
            value: 'telefono'
          },
          {
            text: 'Fondo',
            align: 'left',
            sortable: false,
            value: 'fondo'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'clase_fondo'
          }
        ]
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      this.listaClaseFondo()
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
        return this.$store.getters.getInfoComponent('fondo')
      },
      modalTitulo () {
        return !this.fondos.fondo ? 'Nuevo fondo' : 'Editar fondo: ' + this.fondos.fondo
      },
      complementos () {
        return JSON.parse(JSON.stringify(store.getters.complementosClasesFondos))
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.axios.get('fondo?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25' + '&segm=' + this.segmento)
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.fondosC = response.data.data
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
        this.fondos = {
          fondo: null,
          codigo: null,
          descripcion: null,
          digito_verificacion: null,
          direccion: null,
          nit: null,
          telefono: null
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
        this.axios.delete('fondo/eliminar/' + this.dialogA.registroID)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'El fondo se eliminó correctamente', color: 'success'})
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
        this.fondos = Object.assign({}, item)
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
          var send = !this.fondos.fondo ? this.axios.post('fondo/crear', this.fondos) : this.axios.put('fondo/actualizar/' + this.fondos.fondo, this.fondos)
          send.then(response => {
            this.localLoading = false
            if (this.fondos.fondo) {
              this.$store.commit(SNACK_SHOW, {msg: 'El fondo se actualizó correctamente', color: 'success'})
              // this.traslados.splice(this.traslados.findIndex(traslados => traslados.consecutivo_vigencia === response.data.data.consecutivo_vigencia), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Registro exitoso', color: 'success'})
              this.reloadPage()
              // this.traslados.splice(0, 0, response.data.data)
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
      listaClaseFondo () {
        this.axios.get('clasefondo')
          .then((response) => {
            this.clasesFondos = response.data.data
          })
          .catch(e => {
            return false
          })
      }
    },
    filters: {
      fondo: function (value, fondo) {
        for (let i = 0; i < fondo.length; i++) {
          const element = fondo[i]
          if (value === element.clase_fondo) {
            return element.descripcion
          }
        }
      }
    }

  }
</script>

<style scoped>


</style>
