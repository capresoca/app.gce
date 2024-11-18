<template>
  <div>
    <v-card>
      <v-card-title class="headline grey lighten-2" primary-title v-if="!ordenEdit">
        Registrar Interventor
      </v-card-title>
      <v-card-title class="headline grey lighten-2" primary-title v-else>
        Editar Interventor
      </v-card-title>
      <v-card-text>
        <v-form data-vv-scope="formInterventor">
          <v-container fluid grid-list-xl>
            <v-layout row wrap>
              <v-flex xs12 sm12 md2 lg2>
                <v-text-field label="Codigo" v-model="interventor.codigo" 
                  key="codigo"
                  name="codigo"
                  v-validate="'required|max:16'"
                  :error-messages="errors.collect('codigo')"
                  :disabled="ordenEdit"
                  :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''"
                  persistent-hint
                  @keyup.enter="interventorByCod"
                  @blur="interventorByCod"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md10 lg10>
                <postulador
                  nodata="Busqueda por identificacion o nombre"
                  required
                  hint="nombre_completo"
                  itemtext="identificacion_completa"
                  datatitle="identificacion_completa"
                  datasubtitle="nombre_completo"
                  filter="identificacion_completa,nombre_completo"
                  label="Tercero"
                  scope="formInterventor"
                  ref="postuladorTercero"
                  entidad="terceros"
                  preicon="person"
                  @change="val => interventor.gn_tercero_id = val"
                  @objectReturn="val => terceroSeleccionado(val)"
                  :value="interventor.tercero"
                  clearable
                />
              </v-flex>
              <v-flex xs12 sm12 md12 lg12>
                <v-text-field label="Nombre" v-model="interventor.nombre"
                  key="nombre"
                  name="nombre"
                  v-validate="'required|max:255'"
                  required
                  :error-messages="errors.collect('nombre')">                      
                </v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
        color="primary"
        flat
        @click="submit"
        >
          Guardar
        </v-btn>
      </v-card-actions>
    </v-card>
    <v-dialog v-model="dialogCodigo" persistent max-width="290">      
      <v-card>
        <v-card-title class="headline">Codigo del Interventor</v-card-title>
        <v-card-text>El codigo del Interventor ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
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
  import {INTERVENTOR_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroInterventor',
    props: ['parametros'],
    data () {
      return {
        dialogSave: false,
        dialogCodigo: false,
        buscandoCodigo: false,
        ordenEdit: false,
        param: {},
        interventor: {
          id: null,
          tercero: {
            nombre_completo: '',
            identificacion_completa: ''
          }
        }
      }
    },
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    watch: {
    },
    methods: {
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async interventorByCod () {
        if (this.interventor.codigo) {
          this.buscandoCodigo = true
          this.axios.get('ce_interventores/codigo/' + this.interventor.codigo)
            .then((response) => {
              if (response.data.exists) {
                this.interventor = response.data.interventor
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
            })
        }
      },
      async submit () {
        if (await this.validador('formInterventor')) {
          if (this.interventor.id) {
            this.axios.put('ce_interventores/' + this.interventor.id, this.interventor)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: response.data.message, color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(INTERVENTOR_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('ce_interventores', this.interventor)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El Interventor se creo correctamente', color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(INTERVENTOR_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      getRegistro (id) {
        this.axios.get('ce_interventores/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el interventor. ', error: e})
          })
      },
      edit (item) {
        this.ordenEdit = true
        this.interventor = item
      },
      editarByCodigo () {
        this.dialogCodigo = false
        this.ordenEdit = true
      },
      cerrarByCodigo () {
        this.formReset()
        this.dialogCodigo = false
        this.ordenEdit = false
      },
      formReset () {
        this.dialogCodigo = false
        this.ordenEdit = false
        this.interventor = {}
      },
      terceroSeleccionado (val) {
        if (this.interventor.id != null) {
          return
        }
        if (val != null) {
          this.interventor.tercero = val
          this.interventor.gn_tercero_id = val.id
          this.interventor.nombre = val.razon_social
        }
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
    }
  }
</script>