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
                <v-text-field v-model="salario.descripcion"
                              label="Descripción" key="descripcion"
                              name="descripcion" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('descripcion')"></v-text-field>                 
              </v-flex>

              <v-flex xs12 md6 lg6>  
                <v-select
                  label="Subsidio transporte"
                  :items="tipo"
                  name="subsidio transporte"
                  v-validate="'required'"
                  :error-messages="errors.collect('subsidio transporte')"
                  v-model="salario.sw_subsidio_transporte"
                ></v-select>           

              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="salario.porcentaje_salud" type="number"
                              label="Porcentaje salud" key="porcentaje salud"
                              name="porcentaje salud" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('porcentaje salud')"></v-text-field>               
              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="salario.porcentaje_pension" type="number"
                              label="Porcentaje pensión" key="porcentaje pension"
                              name="porcentaje pension" v-validate="'required|max:11|'" required
                              :error-messages="errors.collect('porcentaje pension')"></v-text-field>                 

              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="salario.porcentaje_solidaridad_pensional" type="number"
                              label="Porcentaje solidaridad pensional" key="porcentaje solidaridad pensional"
                              name="porcentaje solidaridad pensional" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('porcentaje solidaridad pensional')"></v-text-field>               
              </v-flex>

                            <v-flex xs12 md6 lg6>
                <v-text-field v-model="salario.porcentaje_arp" type="number"
                              label="Porcentaje ARP" key="porcentaje arp"
                              name="porcentaje arp" v-validate="'required|max:11|'" required
                              :error-messages="errors.collect('porcentaje arp')"></v-text-field>                 

              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="salario.porcentaje_sena" type="number"
                              label="Porcentaje SENA" key="porcentaje sena"
                              name="porcentaje sena" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('porcentaje sena')"></v-text-field>               
              </v-flex>

                            <v-flex xs12 md6 lg6>
                <v-text-field v-model="salario.porcentaje_caja" type="number"
                              label="Porcentaje caja" key="porcentaje caja"
                              name="porcentaje caja" v-validate="'required|max:11|'" required
                              :error-messages="errors.collect('porcentaje caja')"></v-text-field>                 

              </v-flex>

              <v-flex xs12 md6 lg6>
                <v-text-field v-model="salario.porcentaje_icbf" type="number"
                              label="Porcentaje ICBF" key="porcentaje icbf"
                              name="porcentaje icbf" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('porcentaje icbf')"></v-text-field>               
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
      <toolbar-list :info="infoComponent" title="Configuración salario mínimo" subtitle="Registro y gestión" btnplus btnplustitle="Crear registro" btnplustruncate @click="dialog = true"/>
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
            <td>{{ props.item.configuracion_salarial}}</td>
            <td>{{ props.item.descripcion}}</td>
            <td>
              <span v-if="props.item.sw_subsidio_transporte == 1">{{ 'Si' }}</span>
              <span v-if="props.item.sw_subsidio_transporte == 0">{{ 'No' }}</span>
            </td>
            <td>{{ props.item.porcentaje_salud}} %</td>
            <td>{{ props.item.porcentaje_pension}} %</td>
            <td>{{ props.item.porcentaje_solidaridad_pensional}} %</td>
            <td>{{ props.item.porcentaje_arp}} %</td>
            <td>{{ props.item.porcentaje_sena}} %</td>
            <td>{{ props.item.porcentaje_caja}} %</td>
            <td>{{ props.item.porcentaje_icbf}} %</td>
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
                    @click="eliminar(props.item.configuracion_salarial)"
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
        tipo: [
          'Si',
          'No'
        ],
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
            value: 'configuracion_salarial'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
          },
          {
            text: 'S. Transp.',
            align: 'left',
            sortable: false,
            value: 'sw_subsidio_transporte'
          },
          {
            text: 'Salud',
            align: 'left',
            sortable: false,
            value: 'porcentaje_salud'
          },
          {
            text: 'Pensión',
            align: 'left',
            sortable: false,
            value: 'porcentaje_pension'
          },
          {
            text: 'Solidaridad',
            align: 'left',
            sortable: false,
            value: 'porcentaje_solidaridad_pensional'
          },
          {
            text: 'ARP',
            align: 'left',
            sortable: false,
            value: 'porcentaje_arp'
          },
          {
            text: 'Sena',
            align: 'left',
            sortable: false,
            value: 'porcentaje_sena'
          },
          {
            text: 'Caja',
            align: 'left',
            sortable: false,
            value: 'porcentaje_caja'
          },
          {
            text: 'ICBF',
            align: 'left',
            sortable: false,
            value: 'porcentaje_icbf'
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'configuracion_salarial'
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
        return this.$store.getters.getInfoComponent('configuracionsalario')
      },
      modalTitulo () {
        return !this.salario.configuracion_salarial ? 'Nueva configuración de salario mínimo' : 'Editar configuración salario mínimo: ' + this.salario.configuracion_salarial
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
        this.axios.get('confsalario?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
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
          descripcion: null,
          sw_subsidio_transporte: null,
          porcentaje_salud: null,
          porcentaje_pension: null,
          porcentaje_solidaridad_pensional: null,
          porcentaje_arp: null,
          porcentaje_sena: null,
          porcentaje_caja: null,
          porcentaje_icbf: null
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
        this.axios.delete('confsalario/eliminar/' + this.dialogA.registroID)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'La configuración de salario se eliminó correctamente', color: 'success'})
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
        if (item.sw_subsidio_transporte === 1) {
          this.salario.sw_subsidio_transporte = 'Si'
        } else {
          this.salario.sw_subsidio_transporte = 'No'
        }
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
          var send = !this.salario.configuracion_salarial ? this.axios.post('confsalario/crear', this.salario) : this.axios.put('confsalario/actualizar/' + this.salario.configuracion_salarial, this.salario)
          send.then(response => {
            this.localLoading = false
            if (this.salario.configuracion_salarial) {
              this.$store.commit(SNACK_SHOW, {msg: 'La configuración de salario se actualizó correctamente', color: 'success'})
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
      }
    },
    filters: {
    }

  }
</script>

<style scoped>


</style>
