<template>
  <div>
    <v-card>
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ ordenEdit ? 'Edicion de grado de profesionalismo' : 'Registro de grado de profesionalismo' }} </v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-form data-vv-scope="formRegistroGradoProfesionalismo">
          <v-container fluid grid-list-xl >
            <v-layout row wrap>

              <v-flex xs12 class="pb-4">
                <v-card>
                  <v-toolbar dense class="grey lighten-4 elevation-0">
                    <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> General</v-toolbar-title>
                  </v-toolbar>
                  <v-card-text>
                    <v-layout row wrap>

                      <v-flex xs12 sm4>
                        <v-text-field label="Código"
                                      v-model="grado.codigo"
                                      key="codigo"
                                      v-validate="'required|max:5'"
                                      name="Codigo"
                                      prepend-icon="list"
                                      :error-messages="errors.collect('Codigo')"
                                      :disabled="ordenEdit"
                                      @keyup.enter="findByCodigo"
                                      @blur="findByCodigo"
                                      persistent-hint
                                      :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''"
                                      required>
                        </v-text-field>
                      </v-flex>

                      <v-flex xs12 sm8>
                        <v-text-field label="Nombre"
                                      v-model="grado.nombre"
                                      key="nombre"
                                      v-validate="'required'"
                                      name="Nombre"
                                      prepend-icon="person"
                                      :error-messages="errors.collect('Nombre')"
                                      required>
                        </v-text-field>
                      </v-flex>

                    </v-layout>

                  </v-card-text>
                </v-card>
              </v-flex>

            </v-layout>
          </v-container>
        </v-form>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions v-if="estado !== 'Confirmada' && estado !== 'Anulada'" class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs12 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit" >Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
    <v-dialog v-model="dialogCodigo" persistent max-width="290">      
      <v-card>
        <v-card-title class="headline">Codigo del grado de profesionalismo</v-card-title>
        <v-card-text>El codigo del grado de profesionalismo ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click="editarByCodigo">Cargar datos</v-btn>
          <v-btn color="green darken-1" flat @click="cerrarByCodigo">Cerrar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import store from '../../../../store/complementos/index'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {GRADOS_PROFESIONALISMO_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroGradoProfesionalismo',
    props: ['parametros'],
    data () {
      return {
        buscandoCodigo: false,
        dialogCodigo: false,
        ordenEdit: false,
        loadingSubmit: false,
        grado: {}
      }
    },
    computed: {
      complementosFormSubgruposEmpleados () {
        return store.getters.complementosFormComDiario
      }
    },
    methods: {
      findByCodigo () {
        if (this.grado.codigo) {
          this.buscandoCodigo = true
          this.axios.get('th_profesionalismos/codigo/' + this.grado.codigo)
            .then(response => {
              if (response.data.exists) {
                this.$store.commit(SNACK_SHOW, {msg: 'El codigo del grado de profesionalismo existe', color: 'success'})
                this.formReset()
                this.grado = response.data.data
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              this.buscandoCodigo = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
            })
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formRegistroGradoProfesionalismo')) {
          if (this.grado.id) {
            this.axios.put('th_profesionalismos/' + this.grado.id, this.grado)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El grado de profesionalismo se actualizo correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(GRADOS_PROFESIONALISMO_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('th_profesionalismos', this.grado)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El grado de profesionalismo se creó correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(GRADOS_PROFESIONALISMO_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      seleccionCenCosto (val) {
        if (val !== null) {
          this.grado.cencosto = val
          this.grado.nf_cencosto_id = val.id
        }
      },
      editarByCodigo () {
        this.dialogCodigo = false
        this.ordenEdit = true
      },
      cerrarByCodigo () {
        this.formReset()
        this.ordenEdit = false
        this.dialogCodigo = false
      },
      formReset () {
        this.grado = {}
        this.buscandoCodigo = false
        this.dialogCodigo = false
        this.ordenEdit = false
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      getRegistro (id) {
        this.axios.get('th_profesionalismos/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el grado de profesionalismo', error: e})
          })
      },
      edit (item) {
        this.ordenEdit = true
        this.grado = item
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
    }
  }
</script>
