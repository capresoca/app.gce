<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <!-- formulario de creacion vigencias-->
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              
              <v-flex xs12> 
                <v-text-field v-model="extra.descripcion"
                              label="Descripción" key="descripcion"
                              name="descripcion" v-validate="'required|max:150|'" required
                              :error-messages="errors.collect('descripcion')"></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <v-select
                  item-text="nombre"
                  item-value="nombre"
                  label="Tipo valor nómina"
                  :items="tipoContable"
                  name="tipo valor"
                  v-validate="'required'"
                  :error-messages="errors.collect('tipo valor')"
                  v-model="extra.tipo_valor_nomina"
                ></v-select>
              </v-flex>

              <v-flex xs12>
                <v-select
                  item-text="nombre"
                  item-value="nombre"
                  label="Tipo documento nómina"
                  :items="tipo"
                  name="tipo documento"
                  v-validate="'required'"
                  :error-messages="errors.collect('tipo documento')"
                  v-model="extra.tipo_documento_nomina"
                ></v-select>
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
      <toolbar-list :info="infoComponent" title="Extra laboral" subtitle="Registro y gestión" btnplus btnplustitle="Crear registro" btnplustruncate @click="dialog = true"/>
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
          :items="extras"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="extras.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.consecutivo_extra_laboral }}</td>
            <td>{{ props.item.descripcion}}</td>
            <td>{{ props.item.tipo_documento_nomina | documentoNomina(tipo) }}</td>
            <td>{{ props.item.tipo_valor_nomina | valorNomina(tipoContable) }}</td>
            <!-- <td>{{ 'add' }}</td>
            <td>{{ 'add' }}</td> -->
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
                    @click="eliminar(props.item.consecutivo_extra_laboral)"
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
  // import store from '../../../store/complementos/index'
  import Loading from '@/components/general/Loading'
  import Confirmar from '@/components/general/Confirmar2'

  export default {
    name: 'extraLaboral',
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
        extras: [],
        extra: {

        },
        tipoContable: [
          {
            tipo_valor_nomina: '38',
            nombre: 'Vacaciones'
          },
          {
            tipo_valor_nomina: '8',
            nombre: 'Descuento de Seguro de Vida Colectivo'
          },
          {
            tipo_valor_nomina: '11',
            nombre: 'Hora Extra Diurna'
          },
          {
            tipo_valor_nomina: '12',
            nombre: 'Hora Extra Festiva Nocturna'
          },
          {
            tipo_valor_nomina: '13',
            nombre: 'Hora Extra Nocturna'
          },
          {
            tipo_valor_nomina: '14',
            nombre: 'Hora Festiva Diurna'
          },
          {
            tipo_valor_nomina: '15',
            nombre: 'Hora Festiva Nocturna'
          },
          {
            tipo_valor_nomina: '17',
            nombre: 'Incapacidad General'
          },
          {
            tipo_valor_nomina: '20',
            nombre: 'Licencia de Maternidad'
          },
          {
            tipo_valor_nomina: '21',
            nombre: 'Licencia No Remunerada'
          },
          {
            tipo_valor_nomina: '22',
            nombre: 'Otros Deducidos'
          },
          {
            tipo_valor_nomina: '23',
            nombre: 'Otros Devengados'
          },
          {
            tipo_valor_nomina: '25',
            nombre: 'Pago Mera Liberalidad'
          },
          {
            tipo_valor_nomina: '30',
            nombre: 'Recargo Nocturno'
          },
          {
            tipo_valor_nomina: '31',
            nombre: 'Retención en la Fuente'
          },
          {
            tipo_valor_nomina: '39',
            nombre: 'Auxilio de Rodamiento'
          }
        ],
        tipo: [
          {
            tipo_documento_nomina: '1',
            nombre: 'Deducido'
          },
          {
            tipo_documento_nomina: '2',
            nombre: 'Devengado'
          },
          {
            tipo_documento_nomina: '3',
            nombre: 'Empresa'
          },
          {
            tipo_documento_nomina: '4',
            nombre: 'Deducido Constitutivo Salarial'
          },
          {
            tipo_documento_nomina: '5',
            nombre: 'Devengado Constitutivo Salarial'
          }
        ],
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
            text: 'Consecutivo',
            align: 'left',
            sortable: false,
            value: 'consecutivo_extra_laboral'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
          },
          {
            text: 'Documento nómina',
            align: 'left',
            sortable: false,
            value: 'tipo_documento_nomina'
          },
          {
            text: 'T. valor nómina',
            align: 'left',
            sortable: false,
            value: 'tipo_valor_nomina'
          },
          // {
          //   text: 'Cons. cta débito',
          //   align: 'left',
          //   sortable: false,
          //   value: 'consecutivo_cuenta_debito'
          // },
          // {
          //   text: 'Cons. cta crédito',
          //   align: 'left',
          //   sortable: false,
          //   value: 'consecutivo_cuenta_credito'
          // },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'consecutivo_extra_laboral'
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
        return this.$store.getters.getInfoComponent('extraLaboral')
      },
      modalTitulo () {
        return !this.extra.consecutivo_extra_laboral ? 'Nuevo extra laboral' : 'Editar extra laboral: ' + this.extra.consecutivo_extra_laboral
      },
      complementos () {
        // return JSON.parse(JSON.stringify(store.getters.complementosPeriodoLiquidacion))
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
        this.axios.get('extra?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.extras = response.data.data
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
        this.extra = {
          consecutivo_extra_laboral: null,
          tipo_documento_nomina: null,
          tipo_valor_nomina: null
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
        this.axios.delete('extra/eliminar/' + this.dialogA.registroID)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'El extra laboral se eliminó correctamente', color: 'success'})
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
        this.extra = Object.assign({}, item)
        for (let i = 0; i < this.tipoContable.length; i++) {
          const element = this.tipoContable[i]
          if (item.tipo_valor_nomina.toString() === element.tipo_valor_nomina) {
            this.extra.tipo_valor_nomina = element.nombre
          }
        }
        for (let i = 0; i < this.tipo.length; i++) {
          const element = this.tipo[i]
          if (item.tipo_documento_nomina.toString() === element.tipo_documento_nomina) {
            this.extra.tipo_documento_nomina = element.nombre
          }
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
          var send = !this.extra.consecutivo_extra_laboral ? this.axios.post('extra/crear', this.extra) : this.axios.put('extra/actualizar/' + this.extra.consecutivo_extra_laboral, this.extra)
          send.then(response => {
            this.localLoading = false
            if (this.extra.consecutivo_extra_laboral) {
              this.$store.commit(SNACK_SHOW, {msg: 'El extra laboral se actualizó correctamente', color: 'success'})
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
      valorNomina: function (value, tip) {
        for (let i = 0; i < tip.length; i++) {
          const element = tip[i]
          if (value.toString() === element.tipo_valor_nomina) {
            return element.nombre
          }
        }
      },
      documentoNomina: function (value, tipo) {
        for (let i = 0; i < tipo.length; i++) {
          const element = tipo[i]
          if (value.toString() === element.tipo_documento_nomina) {
            return element.nombre
          }
        }
      }
    }

  }
</script>

<style scoped>


</style>
