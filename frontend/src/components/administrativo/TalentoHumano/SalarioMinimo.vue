<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <!-- formulario de creacion-->
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              
              <v-flex xs12>
                <v-text-field v-model="salario.descripcion"
                              label="Descripción" key="descripcion"
                              name="descripcion" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('descripcion')"></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <!-- <v-text-field v-model="salario.ano"
                              label="Año" key="ano"
                              name="ano" v-validate="'required|max:11|'" required
                              :error-messages="errors.collect('ano')"></v-text-field>                  -->
                <v-text-field v-model="salario.ano" 
                              type="number" label="Año" name="ano"
                              append-outer-icon="add" @click:append-outer="incrementA" 
                              prepend-icon="remove" @click:prepend="decrementA"
                              :error-messages="errors.collect('ano')">></v-text-field>
              </v-flex>

              <v-flex xs12>
                <!-- <v-text-field v-model="salario.valor"
                              label="Valor" key="valor"
                              name="valor" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('valor')"></v-text-field> -->
                <v-text-field v-model="salario.valor" 
                              type="number" label="Valor" name="valor" prefix="$"
                              append-outer-icon="add" @click:append-outer="increment" 
                              prepend-icon="remove" @click:prepend="decrement"
                              :error-messages="errors.collect('valor')">></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <!-- <v-text-field v-model="salario.subsidio_transporte"
                              label="Subsidio transporte" key="subsidio_transporte"
                              name="subsidio_transporte" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('subsidio_transporte')"></v-text-field>-->
                <v-text-field v-model="salario.subsidio_transporte" 
                              type="number" label="Subsidio transporte" name="subsidio_transporte" prefix="$"
                              append-outer-icon="add" @click:append-outer="incrementS" 
                              prepend-icon="remove" @click:prepend="decrementS"
                              :error-messages="errors.collect('subsidio_transporte')">></v-text-field> 
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
      <toolbar-list :info="infoComponent" title="Salario mínimo" subtitle="Registro y gestión" btnplus btnplustitle="Crear registro" btnplustruncate @click="dialog = true"/>
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
          :items="salarios"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="salarios.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.ano}}</td>
            <td>{{ props.item.valor | money}}</td>
            <td>{{ props.item.subsidio_transporte | money}}</td>
            <td>{{ props.item.descripcion  }}</td>
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
                    @click="eliminar(props.item.consecutivo_salario)"
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
  import Loading from '@/components/general/Loading'
  import Confirmar from '@/components/general/Confirmar2'

  export default {
    name: 'salariominimo',
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
        salarios: [],
        salario: {},
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
            text: 'Año',
            align: 'left',
            sortable: false,
            value: 'ano'
          },
          {
            text: 'Valor',
            align: 'left',
            sortable: false,
            value: 'valor'
          },
          {
            text: 'Subsidio de trasporte',
            align: 'left',
            sortable: false,
            value: 'subsidio_transporte'
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
            value: 'consecutivo_salario'
          }
        ]
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {

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
        return this.$store.getters.getInfoComponent('salariominimo')
      },
      modalTitulo () {
        return !this.salario.consecutivo_salario ? 'Nuevo salario mínimo' : 'Editar salario mínimo del año: ' + this.salario.ano
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
        this.axios.get('salario?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.salarios = response.data.data
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
        this.salario = {
          ano: null,
          valor: null,
          descripcion: null,
          subsidio_transporte: null
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
        this.axios.delete('salario/eliminar/' + this.dialogA.registroID)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'El salario se eliminó correctamente', color: 'success'})
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
        this.salario = Object.assign({}, item)
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
          var send = !this.salario.consecutivo_salario ? this.axios.post('salario/crear', this.salario) : this.axios.put('salario/actualizar/' + this.salario.consecutivo_salario, this.salario)
          send.then(response => {
            this.localLoading = false
            if (this.salario.consecutivo_salario) {
              this.$store.commit(SNACK_SHOW, {msg: 'El salario se actualizó correctamente', color: 'success'})
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
      increment () {
        this.salario.valor = parseFloat(this.salario.valor) + 50
      },
      decrement () {
        this.salario.valor = parseInt(this.salario.valor) - 50
      },
      incrementS () {
        this.salario.subsidio_transporte = parseFloat(this.salario.subsidio_transporte) + 50
      },
      decrementS () {
        this.salario.subsidio_transporte = parseFloat(this.salario.subsidio_transporte) - 50
      },
      incrementA () {
        this.salario.ano = parseInt(this.salario.ano) + 1
      },
      decrementA () {
        this.salario.ano = parseInt(this.salario.ano) - 1
      }
    },
    filters: {
      money: function (value) {
        const formatter = new Intl.NumberFormat('sp-CO', {
          style: 'currency',
          currency: 'COP',
          minimumFractionDigits: 0
        })
        return formatter.format(value)
      }
    }

  }
</script>

<style scoped>


</style>
