<template>  
  <div>
    <!-- inicio formulario modal-->
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formTipo">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Agregar detalles</span>
          </v-card-title>
          <v-container fluid grid-list-md>
            <v-layout row wrap>

              <v-flex xs12>
                <v-autocomplete
                  label="Tipo Soporte"
                  v-model="detalle.consecutivo_soporte"
                  :items="complementosTiposSoportes.tiposoporte"
                  item-value="consecutivo_soporte"
                  item-text="descripcion"
                  name="tipo soporte"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('tipo soporte')"
                  :filter="filterTipo"> 

                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.descripcion"/>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>

              <v-flex xs12>
                <v-select
                  item-text="nombre"
                  item-value="nombre"
                  label="Activo"
                  :items="dicotomia"
                  name="activo"
                  v-validate="'required'"
                  :error-messages="errors.collect('activo')"
                  v-model="detalle.sw_activo"
                ></v-select>
              </v-flex>

              <v-flex xs12>
                <v-select
                  item-text="nombre"
                  item-value="nombre"
                  label="Obligatorio"
                  :items="dicotomia"
                  name="vivienda propia"
                  v-validate="'required'"
                  :error-messages="errors.collect('vivienda propia')"
                  v-model="detalle.sw_obligatorio"
                ></v-select>
              </v-flex>

            </v-layout>
          </v-container>
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="closeTable">Cancelar</v-btn>
            <v-btn color="primary" @click="saveDetalle" >Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>


    <v-card flat>
      <v-card >
        <v-card-text>
          <v-toolbar dense short flat>
            Detalles
            <v-spacer></v-spacer>
            <v-btn icon>
              <v-icon color="green" @click="dialog = true; evento = 'crear'" title="agregar">add</v-icon>
            </v-btn>
          </v-toolbar>
          <v-data-table
            :headers="headers"
            :items="dataDetalles.detalles"
            :loading="tableLoading"
            :total-items="dataDetalles.length"
            hide-actions
            class="elevation-1">
            <template slot="items" slot-scope="props">
              <td>{{ props.item.consecutivo_soporte | descripcionSoporte }}</td>
              <td>{{ props.item.sw_activo | descripcionDicotomia }}</td>
              <td>{{ props.item.sw_obligatorio | descripcionDicotomia }}</td>
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
                      @click="eliminarDetalle(props)"
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
                      @click="editarDetalle(props.item, props.index); evento = 'editar'"
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
                No tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
              </v-alert>
              <v-alert v-else :value="true" color="info" icon="info">
                Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
              </v-alert>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-card>
    <confirmar :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)" />
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Confirmar from '@/components/general/Confirmar2'
  export default {
    name: 'detalle',
    props: [
      'tipodetalles'
    ],
    components: {
      Confirmar
    },
    data () {
      return {
        evento: '',
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        dialog: false,
        tableLoading: false,
        headers: [
          {
            text: 'Tipo Soporte',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Activo',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Obligatorio',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: 'id'
          }
        ],
        tabs: null,
        dataDetalles: {
          detalles: []
        },
        dicotomia: [
          {
            value: '1',
            nombre: 'Si'
          },
          {
            value: '0',
            nombre: 'No'
          }
        ],
        detalles: {},
        detalle: {},
        filterTipo (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.descripcion + ' ' + item.profesion)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        }
      }
    },
    mounted () {
    },
    created () {
      this.reloadPage()
    },
    watch: {
    },
    filers: {
    },
    computed: {
      complementosTiposSoportes () {
        return JSON.parse(JSON.stringify(store.getters.complementosTipoSoporte))
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.axios.get('soportedetalle?idProceso=' + this.tipodetalles)
          .then((response) => {
            this.dataDetalles.detalles = response.data
            this.tableLoading = false
            this.localLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            return false
          })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async saveDetalle () {
        if (await this.validador('formTipo')) {
          this.detalle.consecutivo_proceso = this.tipodetalles
          this.localLoading = true
          // var send = !this.detalle.consecutivo_soporte ? this.axios.post('soportedetalle/crear', this.detalle) : this.axios.put('soportedetalle/actualizar/' + this.detalle.consecutivo_soporte, this.detalle)
          var send = ''
          if (this.evento === 'crear') {
            send = this.axios.post('soportedetalle/crear', this.detalle)
          } else if (this.evento === 'editar') {
            send = this.axios.put('soportedetalle/actualizar/' + this.detalle.consecutivo_proceso + '/' + this.detalle.consecutivo_soporte, this.detalle)
          }
          send.then(response => {
            this.localLoading = false
            if (this.evento === 'editar') {
              this.$store.commit(SNACK_SHOW, {msg: 'El detalle se actualizó correctamente', color: 'success'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Detalle agregado exitosamente', color: 'success'})
              this.reloadPage()
            }
            this.dialog = false
          }).catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Existe un registro de detalle con tipo soporte ya existente. Por favor verifique. ', color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
        this.formTableReset()
      },
      eliminarDetalle (item) {
        this.dialogA.content = '¿Está seguro de eliminar?'
        this.dialogA.registroID1 = item.item.consecutivo_proceso
        this.dialogA.registroID2 = item.item.consecutivo_soporte
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroID1 = null
        this.dialogA.registroID2 = null
      },
      confirmaAnulacion (motivo) {
        this.localLoading = true
        this.axios.delete('soportedetalle/eliminar/' + this.dialogA.registroID1 + '/' + this.dialogA.registroID2)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'El detalle se eliminó correctamente', color: 'success'})
            this.reloadPage()
            this.cancelaAnulacion()
          })
          .catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al eliminar el registro. ' + e.response, color: 'danger'})
          })
      },
      editarDetalle (item, index) {
        this.dialog = true
        this.detalle = Object.assign({}, item)
        this.i = index
        if (this.detalle.sw_activo === 1) {
          this.detalle.sw_activo = 'Si'
        } else {
          this.detalle.sw_activo = 'No'
        }
        if (this.detalle.sw_obligatorio === 1) {
          this.detalle.sw_obligatorio = 'Si'
        } else {
          this.detalle.sw_obligatorio = 'No'
        }
      },
      closeTable () {
        this.formTableReset()
      },
      formTableReset () {
        this.i = null
        this.evento = ''
        this.detalle = {
          consecutivo_proceso: null,
          consecutivo_soporte: null,
          sw_activo: null,
          sw_obligatorio: null
        }
        this.dialog = false
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      }
    },
    filters: {
      descripcionSoporte: function (value) {
        for (let i = 0; i < store.getters.complementosTipoSoporte.tiposoporte.length; i++) {
          const element = store.getters.complementosTipoSoporte.tiposoporte[i]
          if (value === element.consecutivo_soporte) {
            return element.descripcion
          }
        }
      },
      descripcionDicotomia: function (value) {
        if (value === 1) {
          return 'Si'
        } else {
          return 'No'
        }
      }
    }
  }
</script>

<style scoped>
  .tipo {
    height: 10px;
    padding: 0;
  }
  .no-padding {
    padding: 0;
  }
</style>
