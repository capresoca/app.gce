<template>
  <div>
    <v-card>
      <v-card-title class="headline grey lighten-2" primary-title>
      {{ ordenEdit ? 'Edicion de tipo de ingreso' : 'Registro de tipo de ingreso' }}
      </v-card-title>
      <v-card-text>
        <v-form data-vv-scope="formRegistroTipoIngreso">
          <v-container fluid grid-list-xl >
            <v-layout row wrap>
              <v-flex xs12 sm12 md4 lg4>
                <v-text-field label="Codigo"
                  v-model="tipoingreso.codigo"
                  key="codigo"
                  v-validate="'required|max:16'"
                  name="Codigo"
                  :error-messages="errors.first('Codigo')"
                  :disabled="ordenEdit"
                  @keyup.enter="findByCodigo"
                  @blur="findByCodigo"
                  persistent-hint
                  :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''">
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md8 lg8>
                <v-text-field label="Nombre"
                  v-model="tipoingreso.nombre"
                  key="nombre"
                  v-validate="'required'"
                  name="Nombre"
                  :error-messages="errors.first('Nombre')"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md12 lg12>
                <v-select
                  :items="complementosTipoIngreso.definicion"
                  label="Definicion"
                  v-model="tipoingreso.definicion"
                ></v-select>
              </v-flex>
            </v-layout>
            <v-layout row wrap>
              <v-flex xs12 sm12 md4 lg12>
                <v-btn
                  color="success"
                  @click="submit"
                >
                Guardar
                </v-btn>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
      </v-card-text>
    </v-card>
    <v-dialog v-model="dialogCodigo" persistent max-width="290">      
      <v-card>
        <v-card-title class="headline">Codigo del tipo de ingreso</v-card-title>
        <v-card-text>El codigo del tipo de ingreso ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
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
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {TIPO_INGRESO_PR_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroTipoIngreso',
    props: ['parametros'],
    data () {
      return {
        buscandoCodigo: false,
        dialogCodigo: false,
        ordenEdit: false,
        complementosTipoIngreso: {
          definicion: []
        },
        tipoingreso: {
        }
      }
    },
    methods: {
      findByCodigo () {
        if (this.tipoingreso.codigo) {
          this.buscandoCodigo = true
          this.axios.get('pr_tipo_ingresos/codigo/' + this.tipoingreso.codigo)
            .then(response => {
              if (response.data.exists) {
                this.$store.commit(SNACK_SHOW, {msg: 'El codigo del tipo de ingreso existe', color: 'success'})
                this.formReset()
                this.tipoingreso = response.data.data
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              console.log(e)
              this.buscandoCodigo = false
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al consultar el codigo. ' + e.response, color: 'danger'})
            })
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formRegistroTipoIngreso')) {
          if (this.tipoingreso.id) {
            this.axios.put('pr_tipo_ingresos/' + this.tipoingreso.id, this.tipoingreso)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El registro del tipo de ingreso se actualizo correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(TIPO_INGRESO_PR_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('pr_tipo_ingresos', this.tipoingreso)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El registro del tipo de ingreso se creó correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(TIPO_INGRESO_PR_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                console.log(e)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
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
        this.tipoingreso = {
          tercero: {
            identificacion: '',
            nombre_completo: '',
            municipio: {
              nombre: '',
              nombre_departamento: ''
            }
          }
        }
        this.buscandoCodigo = false
        this.dialogCodigo = false
        this.ordenEdit = false
      },
      getRegistro (id) {
        this.axios.get('pr_tipo_ingresos/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el subgrupo. ', error: e})
          })
      },
      edit (item) {
        this.ordenEdit = true
        this.tipoingreso = item
      }
    },
    mounted () {
      this.axios.get('pr_tipo_ingresos/complementos')
        .then((response) => {
          this.complementosTipoIngreso = response.data.data
        }).catch(e => {
          this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
        })
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
    }
  }
</script>
