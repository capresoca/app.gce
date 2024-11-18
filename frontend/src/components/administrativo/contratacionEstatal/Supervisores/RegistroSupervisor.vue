<template>
  <div>
    <v-card>
      <v-card-title class="headline grey lighten-2" primary-title v-if="!ordenEdit">
        Registrar Supervisor
      </v-card-title>
      <v-card-title class="headline grey lighten-2" primary-title v-else>
        Editar Supervisor
      </v-card-title>
      <v-card-text>
        <v-form data-vv-scope="formSupervisor">
          <v-container fluid grid-list-xl>
            <v-layout row wrap>
              <v-flex xs12 sm12 md2 lg2>
                <v-text-field label="Codigo" v-model="supervisor.codigo" 
                  key="codigo"
                  name="codigo"
                  v-validate="'required|max:16'"
                  :error-messages="errors.collect('codigo')"
                  :disabled="ordenEdit"
                  :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''"
                  persistent-hint
                  @keyup.enter="supervisorByCod"
                  @blur="supervisorByCod"
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
                  scope="formSupervisor"
                  ref="postuladorTercero"
                  entidad="terceros"
                  preicon="person"
                  @change="val => supervisor.gn_tercero_id = val"
                  @objectReturn="val => terceroSeleccionado(val)"
                  :value="supervisor.tercero"
                  clearable
                />
              </v-flex>
              <v-flex xs12 sm12 md12 lg12>
                <v-text-field label="Nombre" v-model="supervisor.nombre"
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
        <v-card-title class="headline">Codigo del Supervisor</v-card-title>
        <v-card-text>El codigo del Supervisor ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
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
  import {SUPERVISOR_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroSupervisor',
    props: ['parametros'],
    data () {
      return {
        dialogSave: false,
        dialogCodigo: false,
        buscandoCodigo: false,
        ordenEdit: false,
        param: {},
        supervisor: {
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
      async supervisorByCod () {
        if (this.supervisor.codigo) {
          this.buscandoCodigo = true
          this.axios.get('ce_supervisores/codigo/' + this.supervisor.codigo)
            .then((response) => {
              if (response.data.exists) {
                this.supervisor = response.data.supervisor
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
            })
        }
      },
      async submit () {
        if (await this.validador('formSupervisor')) {
          if (this.supervisor.id) {
            this.axios.put('ce_supervisores/' + this.supervisor.id, this.supervisor)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: response.data.message, color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(SUPERVISOR_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('ce_supervisores', this.supervisor)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El Supervisor se creo correctamente', color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(SUPERVISOR_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      getRegistro (id) {
        this.axios.get('ce_supervisores/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el supervisor. ', error: e})
          })
      },
      edit (item) {
        this.ordenEdit = true
        this.supervisor = item
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
        this.supervisor = {}
      },
      terceroSeleccionado (val) {
        if (this.supervisor.id != null) {
          return
        }
        if (val != null) {
          this.supervisor.tercero = val
          this.supervisor.gn_tercero_id = val.id
          this.supervisor.nombre = val.razon_social
        }
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
    }
  }
</script>