<template>
	<div><v-dialog
      v-model="dialogSave"
      width="500"
      persistent
      >
      <v-form data-vv-scope="formVendedor">
      <v-card>
        <v-card-title class="headline grey lighten-2" primary-title v-if="!ordenEdit">
          Registrar vendedor
        </v-card-title>
        <v-card-title class="headline grey lighten-2" primary-title v-else>
          Editar vendedor
        </v-card-title>
        <v-card-text>
          <v-container fluid grid-list-xl>
          <v-layout row wrap>
            <v-flex xs12 sm4 md4 lg4>
              <v-text-field label="Codigo" 
                :disabled="ordenEdit"
                key="codigo"
                name="codigo"
                v-model="vendedor.codigo" 
                v-validate="'required|max:10'"
                :error-messages="errors.collect('codigo')"
                :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''"
                persistent-hint
                @keyup.enter="vendedorByCod"
                @blur="vendedorByCod"
                required></v-text-field>
            </v-flex>
            <v-flex xs12 sm8 md8 lg8>
              <postulador
                nodata="Busqueda por nombre o número de documento."
                required
                hint="nombre_completo"
                itemtext="identificacion"
                datatitle="identificacion_completa"
                datasubtitle="nombre_completo"
                filter="identificacion_completa,nombre_completo"
                label="Tercero"
                scope="formPrincipal"
                ref="postulaTercero"
                entidad="terceros"
                preicon="person"
                @change="val => vendedor.gn_tercero_id = val"
                @objectReturn="val => terceroSeleccionado(val)"
                :value="vendedor.tercero"
                clearable
              />
            </v-flex>
            <v-flex xs12 sm12 md12 lg12>
              <v-text-field label="Nombre" 
                key="nombre"
                name="nombre"
                v-model="vendedor.nombre"
                v-validate="'required|max:255'"
                :error-messages="errors.collect('nombre')"
                required></v-text-field>
            </v-flex>
          </v-layout>
          </v-container>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            flat
            :disabled="errors.any()"
            @click="submit"
          >
          Guardar
          </v-btn>
          <v-btn
            color="primary"
            flat
            @click="cerrarModal"
          >
          Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
    </v-dialog>
    <v-dialog v-model="dialogCodigo" persistent max-width="290">      
      <v-card>
        <v-card-title class="headline">Codigo del vendedor</v-card-title>
        <v-card-text>El codigo del vendedor ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
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
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroVendedores',
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    props: ['open', 'parametros'],
    data () {
      return {
        search: '',
        buscandoCodigo: false,
        exist: false,
        dialogSave: false,
        ordenEdit: false,
        dialogCodigo: false,
        indice: 0,
        vendedor: {
          tercero: {
            identificacion_completa: '',
            identificacion: '',
            nombre_completo: '',
            tipo_id: {}
          }
        }
      }
    },
    watch: {
      'vendedor.tercero' (val) {
        if (val) {
          this.vendedor.nombre = val.nombre_completo
        }
      },
      '$store.state.nav.currentItem' (val) {
        if (val) {
          this.dialogSave = false
        }
      },
      'open' (val) {
        this.dialogSave = val
      },
      'parametros' (val) {
        if (val.vendedor) {
          this.edit(val.vendedor)
          console.log(val.indice)
          if (val.indice) {
            this.indice = val.indice
          }
        }
      }
    },
    methods: {
      terceroSeleccionado (val) {
        if (val !== null && this.dialogSave === false) {
          if (val.id !== null && val.id !== '' && val.id !== undefined) {
            this.dialogSave = true
            this.axios.get('clientes/tercero/' + val.id)
              .then((response) => {
                if (response.data.exists) {
                  this.vendedor.tercero = response.data.tercero
                } else {
                  this.$store.commit(SNACK_SHOW, {msg: 'No podemos recuperar los datos del tercero. Intente de nuevo', color: 'danger'})
                }
              }).catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
              })
          }
        } else {
          if (val !== null) {
            this.vendedor.tercero = val
          }
        }
      },
      edit (item) {
        this.dialogSave = true
        this.ordenEdit = true
        this.exist = true
        this.vendedor = item
      },
      async vendedorByCod () {
        if (this.vendedor.codigo) {
          this.buscandoCodigo = true
          this.axios.get('vendedor/codigo/' + this.vendedor.codigo)
            .then((response) => {
              if (response.data.exists) {
                this.vendedor = response.data.vendedor
                this.dialogCodigo = true
                this.buscandoCodigo = false
              } else {
                this.buscandoCodigo = false
                var codigo = this.vendedor.codigo
                this.vendedor = {
                  id: '',
                  codigo: codigo,
                  tercero: {
                    identificacion: '',
                    identificacion_completa: '',
                    nombre_completo: '',
                    tipo_id: {}
                  }
                }
                this.dialogCodigo = false
                this.exist = true
              }
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
            })
        }
      },
      editarByCodigo () {
        this.dialogCodigo = false
        this.dialogSave = true
        this.ordenEdit = true
        this.exist = true
      },
      cerrarByCodigo () {
        this.formReset()
        this.dialogCodigo = false
        this.ordenEdit = false
        this.$emit('dialog', false)
      },
      cerrarModal () {
        this.formReset()
        this.dialogSave = false
        this.$emit('dialog', false)
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formVendedor')) {
          if (this.vendedor.id) {
            this.axios.put('vendedor/' + this.vendedor.id, this.vendedor)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El vendedor se actualizo correctamente', color: 'success'})
                this.dialogSave = false
                this.$emit('objectReturn', {
                  data: response.data.data,
                  indice: this.indice,
                  tipo: 'updated'
                })
                this.formReset()
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('vendedor', this.vendedor)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El vendedor se creó correctamente', color: 'success'})
                this.dialogSave = false
                this.$emit('objectReturn', {
                  data: response.data.data,
                  indice: this.indice,
                  tipo: 'new'
                })
                this.formReset()
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      formReset () {
        this.vendedor = {
          tercero: {
            identificacion: '',
            identificacion_completa: '',
            nombre_completo: '',
            tipo_id: {}
          }
        }
        this.indice = 0
        this.dialogCodigo = false
        this.ordenEdit = false
        this.exist = false
      }
    },
    mounted () {
      this.$validator.localize('es')
    }
  }
</script>

<style scoped>

</style>
