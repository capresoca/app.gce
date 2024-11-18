<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">Envio 2.7 Prestaciones Económicas</span>
          </v-card-title>
          <!-- formulario de creacion-->
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              
              <v-flex xs12>

                <v-menu
                  ref="menuDateFecha1"
                  :close-on-content-click="false"
                  v-model="menuDateFecha"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    ref="prueba"
                    slot="activator"
                    v-model="computedDateFormattedFecha"
                    label="Pago inicio"
                    prepend-icon="event"
                    readonly
                    name="pago inicio"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('pago inicio')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="envio.pago_inicio"
                    @input="menuDateFecha = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'pago inicio')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>

                <v-menu
                  ref="menuDateFecha2"
                  :close-on-content-click="false"
                  v-model="menuDateFecha2"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  max-width="290px"
                  min-width="290px"
                >
                  <v-text-field
                    ref="prueba"
                    slot="activator"
                    v-model="computedDateFormattedFecha2"
                    label="Pago fin"
                    prepend-icon="event"
                    readonly
                    name="pago fin"
                    v-validate="'required|date_format:YYYY/MM/DD'"
                    :error-messages="errors.collect('pago fin')"
                  ></v-text-field>
                  <v-date-picker
                    v-model="envio.pago_fin"
                    @input="menuDateFecha2 = false"
                    @change="() => {
                      let index = $validator.errors.items.findIndex(x => x.field === 'pago fin')
                      $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                    }"
                    locale='es'
                    no-title
                  ></v-date-picker>
                </v-menu>  

                <v-text-field v-model="envio.descripcion"
                              label="Descripción" key="descripcion"
                              name="descripcion" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('descripcion')"></v-text-field>                 
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
      <toolbar-list :info="infoComponent" title= "Envio 2.7 Prestaciones Económicas" subtitle="Registro y gestión" btnplus btnplustitle="Crear registro" btnplustruncate @click="dialog = true"/>
      <data-table
        v-model="dataTableIncapacidad"
        ref="childComponent"
        @resetOption="item => resetOptions(item)"
        />
      <confirmar :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)" />
    </v-card>
  </div>
</template>
<script>
  import ToolbarList from '@/components/general/ToolbarList'
  import DataTable from '@/components/general/DataTable'
  import Confirmar from '@/components/general/Confirmar2'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'EnvioPrestaciones',
    components: {
      ToolbarList,
      DataTable,
      Confirmar
    },
    data () {
      return {
        dialog: false,
        envio: {},
        menuDateFecha: false,
        menuDateFecha2: false,
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        dataTableIncapacidad: {
          nameItemState: 'itemEnvioPrestacion',
          route: 'envioprestacion',
          optionsPerPage: [
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
          makeHeaders: [
            {
              text: 'Envio 2.7',
              align: 'left',
              sortable: false,
              value: 'consecutivo_envio'
            },
            {
              text: 'Pago Inicio',
              align: 'left',
              sortable: false,
              value: 'pago_inicio'
            },
            {
              text: 'Pago Fin',
              align: 'left',
              sortable: false,
              value: 'pago_fin'
            },
            {
              text: 'Descripción',
              align: 'left',
              sortable: false,
              value: 'descripcion'
            },
            {
              text: 'Cantidad Registros',
              align: 'left',
              sortable: false,
              value: 'cantidad_prestaciones'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'justify-center layout px-0'
            }
          ]
        }
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        item.permisos = this.infoComponent ? this.infoComponent : null
        if (this.infoComponent && this.infoComponent.permisos.agregar) {
          item.options.push({event: 'editar', icon: 'edit', tooltip: 'Gestionar'})
        }
        return item
      },
      eliminar (item) {
        this.dialogA.content = '¿Está seguro de eliminar?'
        // this.dialogA.registroID = item.empleado
        // this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroID = null
      },
      confirmaAnulacion (motivo) {
        this.axios.delete('empleadosc/eliminar/' + this.dialogA.registroID)
          .then(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Se elimino el empleado correctamente.', color: 'success'})
            this.$refs.childComponent.reloadPage()
            this.cancelaAnulacion()
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar anular el trámite de afiliación. ', error: e})
          })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formPago')) {
          this.dialog = false
          // var send = !this.envio.consecutivo_envio ? this.axios.post('envioprestacion/crear', this.envio) : this.axios.put('zzzzzz' + this.envio.consecutivo_envio, this.envio)
          var send = this.axios.post('enviaractualizar', this.envio)
          send.then(response => {
            // if (this.envio.consecutivo_envio) {
            //   this.$store.commit(SNACK_SHOW, {msg: 'Se guardó la información correctamente', color: 'success'})
            // } else {
            //   this.$store.commit(SNACK_SHOW, {msg: 'Registro exitoso', color: 'success'})
            //   this.$refs.childComponent.reloadPage()
            // }
            alert('a procesar enviaractualizar')
            this.dialog = false
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
        this.formReset()
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      formReset () {
        this.envio = {}
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('envioPrestacion')
      },
      computedDateFormattedFecha () {
        return this.formDate(this.envio.pago_inicio)
      },
      computedDateFormattedFecha2 () {
        return this.formDate(this.envio.pago_fin)
      }
    }
  }
</script>
