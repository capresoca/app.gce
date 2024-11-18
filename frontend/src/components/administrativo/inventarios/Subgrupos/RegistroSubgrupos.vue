<template>
  <div>
    <v-card>
      <v-card-title class="headline grey lighten-2" primary-title>
        Registro de subgrupos
      </v-card-title>
      <v-card-text>
        <v-form data-vv-scope="formRegistroSubgruposInvetarios">
          <v-container fluid grid-list-xl >
            <v-layout row wrap>
              <v-flex xs12 sm12 md3 lg2>
                <v-text-field label="Codigo"
                  v-model="grupo.codigo"
                  key="codigo"
                  v-validate="'required'"
                  name="Codigo"
                  :error-messages="errors.collect('Codigo')"
                  :disabled="ordenEdit"
                  @keyup.enter="findByCodigo"
                  @blur="findByCodigo"
                  persistent-hint
                  :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md6 lg8>
                <v-text-field label="Nombre"
                  v-model="grupo.nombre"
                  key="nombre"
                  v-validate="'required'"
                  name="Nombre"
                  :error-messages="errors.collect('Nombre')"
                  required>
                </v-text-field>
              </v-flex>
            </v-layout>
            <v-layout row wrap>
              <v-flex xs12 sm12 md12 lg12>
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
        <v-card-title class="headline">Codigo del subgrupo</v-card-title>
        <v-card-text>El codigo del subgrupo ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
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
  import {SUBGRUPOS_INVENTARIOS_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroSubgruposInventario',
    props: ['parametros'],
    data () {
      return {
        buscandoCodigo: false,
        dialogCodigo: false,
        ordenEdit: false,
        grupo: {}
      }
    },
    methods: {
      findByCodigo () {
        if (this.grupo.codigo) {
          this.buscandoCodigo = true
          this.axios.get('in_subgrupos/codigo/' + this.grupo.codigo)
            .then(response => {
              if (response.data.exists) {
                this.$store.commit(SNACK_SHOW, {msg: 'El codigo del grupo existe', color: 'success'})
                this.formReset()
                this.grupo = response.data.data
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
        if (await this.validador('formRegistroSubgruposInvetarios')) {
          if (this.grupo.id) {
            this.axios.put('in_subgrupos/' + this.grupo.id, this.grupo)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El grupo se actualizo correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(SUBGRUPOS_INVENTARIOS_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('in_subgrupos', this.grupo)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El grupo se creó correctamente', color: 'success'})
                this.formReset()
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(SUBGRUPOS_INVENTARIOS_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
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
        this.grupo = {}
        this.buscandoCodigo = false
        this.dialogCodigo = false
        this.ordenEdit = false
      },
      getRegistro (id) {
        this.axios.get('in_subgrupos/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el cliente. ', error: e})
          })
      },
      edit (item) {
        this.ordenEdit = true
        this.grupo = item
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
    }
  }
</script>
