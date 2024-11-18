<template>
  <div>
    <v-card>
      <v-card-title class="grey lighten-3 elevation-0 headline">
        <span class="title" v-text="(!ordenEdit ? 'Registrar' : 'Editar') + ' Auditor'"></span>
      </v-card-title>
      <v-card-text>
        <v-form data-vv-scope="formAuditor">
          <v-container fluid grid-list-xl>
            <v-layout row wrap>
              <v-flex xs12 sm12 md2 lg2 v-if="auditor.id">
                <v-text-field label="Codigo del auditor" v-model="auditor.codigo" 
                  key="codigo"
                  name="codigo"
                  v-validate="'required|max:16'"
                  :error-messages="errors.collect('codigo')"
                  :disabled="ordenEdit"
                  :hint="buscandoCodigo ? 'Estamos buscando el codigo' : ''"
                  persistent-hint
                  @keyup.enter="auditorByCod"
                  @blur="auditorByCod"
                  required
                  disabled
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 :class="{'md10 lg10': auditor.id}">
                <postulador
                  nodata="Busqueda por identificacion o nombre"
                  required
                  hint="email"
                  itemtext="name"
                  datatitle="email"
                  datasubtitle="name"
                  filter="name,identification,email"
                  label="Usuario"
                  scope="formAuditor"
                  lite
                  ref="postuladorAuditor"
                  entidad="usuarios"
                  preicon="person"
                  @change="val => auditor.gs_usuario_id = val"
                  @objectReturn="val => auditor.usuario = val"
                  :value="auditor.usuario"
                  clearable
                />
              </v-flex>
              <v-flex xs12 sm12 md4 lg4>
                <v-select
                  :items="complementos.tipoAuditor"
                  label="Tipo de auditor"
                  v-model="auditor.tipo"
                  key="tipo de auditor"
                  name="tipo de auditor" v-validate="'required'"
                  :error-messages="errors.collect('tipo de auditor')"
                ></v-select>
              </v-flex>
              <v-flex xs12 sm4 md2 lg2>
                <v-switch  color="accent" :label="auditor.auditor_concurrente === 0 ? 'No concurrente' : 'Concurrente'"
                           v-model="auditor.auditor_concurrente" :true-value="1" :false-value="0"></v-switch>
              </v-flex>
              <v-flex xs12 sm4 md2 lg2>
                <v-switch  color="accent" :label="auditor.tecnico === 0 ? 'No técnico' : 'Técnico'"
                           v-model="auditor.tecnico" :true-value="1" :false-value="0"></v-switch>
              </v-flex>
              <v-flex xs12 sm4 md2 lg2>
                <v-switch  color="accent" :label="auditor.estado === 0 ? 'Inactivo' : 'Activo'"
                           v-model="auditor.estado" :true-value="1" :false-value="0"></v-switch>
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
        <v-card-title class="headline">Codigo del auditor</v-card-title>
        <v-card-text>El codigo del auditor ya esta siendo utilizado, desea cargar los datos para su actualizacion o utilizar otro codigo</v-card-text>
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
  import {AUDITORES_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroAuditores',
    props: ['parametros'],
    data () {
      return {
        dialogSave: false,
        dialogCodigo: false,
        buscandoCodigo: false,
        ordenEdit: false,
        complementos: {
          tipoAuditor: []
        },
        param: {},
        auditor: {}
      }
    },
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    watch: {
      'auditor.tecnico' (val) {
        if (val === 1) {
          this.auditor.auditor_concurrente = 0
        }
      },
      'auditor.auditor_concurrente' (val) {
        if (val === 1) {
          this.auditor.tecnico = 0
        }
      }
    },
    methods: {
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async auditorByCod () {
        if (this.auditor.codigo) {
          this.buscandoCodigo = true
          this.axios.get('ac_auditores/codigo/' + this.auditor.codigo)
            .then((response) => {
              if (response.data.exists) {
                this.auditor = response.data.auditor
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
            })
        }
      },
      async submit () {
        let errorPostulador = await this.$refs.postuladorAuditor.validate()
        if (await this.validador('formAuditor') && errorPostulador) {
          if (this.auditor.id) {
            this.axios.put('ac_auditores/' + this.auditor.id, this.auditor)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: response.data.message, color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(AUDITORES_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
              }).catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' Hay un error al guardar  el registro. ', error: e})
              })
          } else {
            this.axios.post('ac_auditores', this.auditor)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'El auditor se creo correctamente', color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(AUDITORES_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
              }).catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' Hay un error al guardar  el registro. ', error: e})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      },
      getRegistro (id) {
        this.axios.get('ac_auditores/' + id)
          .then((response) => {
            if (response.data.exists) {
              this.edit(response.data.data)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el auditor. ', error: e})
          })
      },
      returnDialogo (val) {
        this.auditor.vendedor = val.data
      },
      edit (item) {
        this.ordenEdit = true
        this.auditor = item
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
        this.auditor = {
          id: null,
          estado: 1,
          auditor_concurrente: 0,
          tecnico: 0,
          tipo: null
        }
      }
    },
    created () {
      this.formReset()
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.axios.get('ac_auditores/complementos')
        .then((response) => {
          if (response.data.state) {
            this.complementos = response.data.data
          }
        })
        .catch(e => {
          this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el auditor. ', error: e})
        })
      this.$validator.localize('es')
    }
  }
</script>
