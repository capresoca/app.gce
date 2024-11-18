<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{gestion}} tipo de soporte</span>
          </v-card-title>
          <!-- formulario de creacion-->
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              
              <v-flex xs12>
                <v-text-field v-model="soporte.descripcion"
                              label="Descripción" key="descripcion"
                              name="descripcion" v-validate="'required|max:200|'" required
                              :error-messages="errors.collect('descripcion')"></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <v-textarea
                  v-model="soporte.observacion"
                  solo
                  name="observacion"
                  label="Observación"
                  rows="1"
                ></v-textarea>               
              </v-flex>

              <v-flex xs12>
                <v-select
                  label="Posicion en formulario"
                  v-model="soporte.posicion_formulario"
                  :items="recobros"
                  item-text="nombre"
                  item-value="value"
                  v-show="mostrarcampete"
                />
              </v-flex>

              <v-flex xs12>
                <v-text-field v-model="soporte.codigo_recobro"
                              label="Código de recobros" key="codigo"
                              name="codigo" v-validate="'max:50'"
                              :error-messages="errors.collect('codigo')"
                              v-show="mostrarcampete"></v-text-field>                 
              </v-flex>

              <v-flex xs12>
                <v-select
                  label="Identificación recobro"
                  v-model="soporte.identificacion_recobro"
                  :items="recobros"
                  item-text="nombre"
                  item-value="value"
                  v-show="mostrarcampete"
                />
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
      <toolbar-list
        :info="infoComponent"
        title="Tipos de soporte"
        subtitle="Gestión"
        btnplus btnplustitle="Crear registro" btnplustruncate @click="dialog = true; gestion = 'Crear'"
      />
      <data-table
        v-model="dataTable"
        ref="childComponent"
        @resetOption="item => resetOptions(item)"
        @editar="item => editar(item)"
      />
    </v-card>
  </div>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'tipoSoportes',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    data () {
      return {
        dialog: false,
        mostrarcampete: false,
        gestion: '',
        soporte: {},
        recobros: [
          {
            value: '1',
            nombre: '1'
          },
          {
            value: '0',
            nombre: '0'
          }
        ],
        dataTable: {
          nameItemState: 'itemAuSolicitudAutorizacion',
          route: 'soporte',
          makeHeaders: [
            {
              text: 'Tipo soporte',
              align: 'left',
              sortable: false,
              value: 'consecutivo_soporte'
            },
            {
              text: 'Descripción',
              align: 'left',
              sortable: true,
              value: 'descripcion'
            },
            {
              text: 'Observación',
              align: 'left',
              sortable: false,
              value: 'observacion'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              singlesActions: true,
              classData: 'text-xs-center'
            }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('tiposoporte')
      }
    },
    methods: {
      async save () {
        if (await this.validador('formPago')) {
          this.dialog = false
          this.localLoading = true
          var send = !this.soporte.consecutivo_soporte ? this.axios.post('soporte/crear', this.soporte) : this.axios.put('soporte/actualizar/' + this.soporte.consecutivo_soporte, this.soporte)
          send.then(response => {
            this.localLoading = false
            if (this.soporte.consecutivo_soporte) {
              this.$store.commit(SNACK_SHOW, {msg: 'El tipo de soporte se actualizó correctamente', color: 'success'})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Registro exitoso', color: 'success'})
              this.$refs.childComponent.reloadPage()
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
      editar (item) {
        this.dialog = true
        this.gestion = 'Editar'
        this.soporte = Object.assign({}, item)
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formReset () {
        this.soporte = {
          descripcion: null,
          observacion: null,
          posicion_formulario: null,
          codigo_recobro: null,
          identificacion_recobro: null
        }
      },
      resetOptions (item) {
        item.options = []
        item.options.push({event: 'editar', color: 'success', icon: 'mode_edit', tooltip: 'Editar'})
        return item
      }
    }
  }
</script>

<style scoped>
</style>
