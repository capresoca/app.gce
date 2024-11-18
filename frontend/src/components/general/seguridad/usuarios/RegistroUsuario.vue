<template>
  <v-form autocomplete="off" ref="formUsuario">
    <v-card>
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
      </v-toolbar>
      <v-card-text>
        <v-container grid-list-md style="max-width: inherit">
          <v-layout wrap>
            <v-flex xs12 sm6 md4>
              <v-layout align-center class="pt-2 px-2">
                <v-text-field
                  label="Identificación"
                  v-model="usuario.identification"
                  name="Identificación"
                  required
                  v-validate="'required|max:15|identificacionValida:' + validacionIdentificacion"
                  :error-messages="errors.collect('Identificación')"
                />
                <v-tooltip top>
                  <v-btn
                    :disabled="!usuario.identification"
                    slot="activator"
                    flat
                    icon
                    color="accent"
                    @click="verificarIdentificacion"
                  >
                    <v-icon>{{ identificacionVerifiedIcon }}</v-icon>
                  </v-btn>
                  <span>{{verified ? 'Verificar identificación' : 'Identificación verificada'}}</span>
                </v-tooltip>
              </v-layout>
            </v-flex>
            <v-flex xs12 sm12 md4>
              <v-text-field v-model="usuario.name"
                            label="Nombres y Apellidos" key="nombre"
                            name="nombre" required v-validate="'required'"
                            :error-messages="errors.collect('nombre')">
              </v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md4>
              <v-text-field v-model="usuario.phone"
                            label="Telefono" key="telefono"
                            name="telefono" required v-validate="'required'"
                            :error-messages="errors.collect('telefono')">
              </v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md4>
              <v-select
                :items="tiposUsuarios"
                v-model="usuario.tipo"
                label="Tipo de Usuario"
                name="tipo"
                :error-messages="errors.collect('Tipo')"
                required v-validate="'required'"
              ></v-select>
            </v-flex>
            <v-flex xs12 sm12 md8 v-if="usuario.tipo === 'Entidad'">
              <postulador-v2
                no-data="Busqueda por NIT, razon social u código de habilitación."
                hint="nombre"
                item-text="nombre"
                data-title="nombre"
                data-subtitle="nombre"
                label="Entidad"
                entidad="entidades"
                preicon="location_city"
                @changeid="val => usuario.rs_entidad_id = val"
                v-model="usuario.entidad"
                name="entidad"
                rules="required"
                v-validate="'required'"
                :error-messages="errors.collect('entidad')"
                no-btn-create
                :min-characters-search="3"
              />
            </v-flex>
            <v-flex xs12 md4>
              <v-layout align-center class="pt-1 px-2">
                <v-text-field
                  browser-autocomplete="false"
                  label="Correo Electrónico"
                  v-model="usuario.email"
                  name="email"
                  required
                  v-validate="'required|email|emailValido:' + validacionEmail"
                  :error-messages="errors.collect('email')"
                />
                <v-tooltip top>
                  <v-btn
                    :disabled="!usuario.email"
                    slot="activator"
                    flat
                    icon
                    color="accent"
                    @click="verificarEmail"
                  >
                    <v-icon>{{ emailVerifiedIcon }}</v-icon>
                  </v-btn>
                  <span>{{ verifiedEmail ? 'Verificar correo' : 'Correo verificado' }}</span>
                </v-tooltip>
              </v-layout>
            </v-flex>
            <v-flex xs12 sm12 md4>
              <v-text-field v-model="usuario.password"
                            browser-autocomplete="false"
                            type="password"
                            label="Contraseña" key="password"
                            name="Contraseña" required v-validate="'required'"
                            :readonly="!isEditing"
                            :error-messages="errors.collect('contrasena')">
                <v-slide-x-reverse-transition
                  mode="out-in"
                  slot="append-outer"
                >
                  <v-icon
                    :color="isEditing ? 'success' : 'accent'"
                    :key="`icon-${isEditing}`"
                    v-text="isEditing ? 'done' : 'edit'"
                    @click="isEditing = !isEditing"
                  ></v-icon>
                </v-slide-x-reverse-transition>
              </v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md12 v-if="usuario.tipo !== 'Entidad'">
              <v-select
                color="success"
                class="v-chips"
                v-model="usuario.roles"
                :items="listaRoles"
                item-text="nombre"
                item-value="id"
                label="Roles"
                chips
                return-object
                multiple
                deletable-chips
                small-chips
              ></v-select>
            </v-flex>
            <v-flex xs12 sm12 md12 v-if="usuario.id">
              <v-switch  color="success" :label="usuario.state"
                         v-model="usuario.state"  true-value="Activo" false-value="Inactivo"></v-switch>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="refresh(null)" :loading="loadingSubmit">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close()" >Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="save" :loading="loadingSubmit">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '../../../../store/modules/general/nav'
  import {USUARIO_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import SelectorEntidad from '@/components/misional/redservicios/entidades/Selector'
  import Complementos from '@/store/complementos/index'
  import { Validator } from 'vee-validate'

  export default {
    name: 'RegistroUsuario',
    props: ['parametros'],
    components: {
      SelectorEntidad,
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    data () {
      return {
        isEditing: false,
        usuario: {
          id: null,
          identification: null,
          name: null,
          email: null,
          password: null,
          phone: null,
          state: 'Activo',
          tipo: null,
          rs_entidad_id: null,
          roles: []
        },
        listaRoles: [],
        verified: false,
        verifiedEmail: false,
        loadingSubmit: false,
        emailVerifiedIcon: 'done',
        identificacionVerifiedIcon: 'done'
      }
    },
    computed: {
      validacionIdentificacion () {
        return this.verified
      },
      validacionEmail () {
        return this.verifiedEmail
      },
      tapTitulo () {
        return !this.usuario.id ? 'Nuevo usuario' : 'Edición del usuario: ' + this.usuario.name + '(' + this.usuario.email + ')'
      },
      tiposUsuarios () {
        return Complementos.getters.complementosFormUsuario
      }
    },
    watch: {
      'verified' (val) {
        if (!val) {
          this.$validator.reset()
        }
      }
    },
    created () {
      this.validadorIdentificacion()
      this.validadorEmail()
    },
    mounted () {
      this.refresh()
      this.formReset()
      this.getRoles()
      if (this.parametros.entidad !== null) this.getRegisto(this.parametros.entidad.id)
    },
    methods: {
      getRegisto (id) {
        let loader = this.$loading.show({container: this.$refs.formUsuario.$el})
        this.axios.get('usuarios/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.usuario = response.data.data
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el usuario. ' + e.response, color: 'danger'})
          })
      },
      async verificarIdentificacion () {
        if (this.usuario.identification) {
          await this.axios.get('usuarios/identificacion/' + this.usuario.identification)
            .then(response => {
              this.verified = response.data.exists
              this.verified ? this.identificacionVerifiedIcon = 'done' : this.identificacionVerifiedIcon = 'done_all'
            }).catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Error al realizar la validación', color: 'danger'})
            })
        }
      },
      async verificarEmail () {
        if (this.usuario.email) {
          await this.axios.get('usuarios/email/' + this.usuario.email)
            .then(response => {
              this.verifiedEmail = response.data.exists
              this.verifiedEmail ? this.emailVerifiedIcon = 'done' : this.emailVerifiedIcon = 'done_all'
            }).catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Error al realizar la validación', color: 'danger'})
            })
        }
      },
      getRoles () {
        this.axios.get('gs_roles')
          .then((response) => {
            if (response.data !== '') {
              this.listaRoles = response.data.data.roles
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los roles. ' + e.response, color: 'danger'})
          })
      },
      formReset () {
        this.usuario = {
          id: null,
          identification: null,
          name: null,
          email: null,
          password: null,
          phone: null,
          state: 'Activo',
          tipo: null,
          rs_entidad_id: null
        }
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      refresh () {
        this.$validator.reset()
        this.usuario = JSON.parse(JSON.stringify(this.usuario))
      },
      validadorIdentificacion () {
        Validator.extend('identificacionValida', {
          validate: (value, prop) => new Promise((resolve) => {
            let response = {}
            if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
              prop[0] === 'true' ? response = {valido: false, mensaje: 'Ya existe un usuario con esta identificación'} : response = {valido: true, mensaje: 'Usuario disponible'}
              return resolve({
                valid: response.valido,
                data: {
                  message: response.mensaje
                }
              })
            }
          }),
          getMessage: (field, params, data) => data.message
        })
      },
      validadorEmail () {
        Validator.extend('emailValido', {
          validate: (value, prop) => new Promise((resolve) => {
            let response = {}
            if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
              prop[0] === 'true' ? response = {valido: false, mensaje: 'Este email ya se encuentra registrado'} : response = {valido: true, mensaje: 'Disponible'}
              return resolve({
                valid: response.valido,
                data: {
                  message: response.mensaje
                }
              })
            }
          }),
          getMessage: (field, params, data) => data.message
        })
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formUsuario')) {
          this.loadingSubmit = true
          const typeRequest = this.usuario.id ? 'editar' : 'crear'
          let send = !this.usuario.id ? this.axios.post('usuarios', this.usuario) : this.axios.put('usuarios/' + this.usuario.id, this.usuario)
          send.then(response => {
            if (this.usuario.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'El usuario se actualizó correctamente', color: 'success'})
              this.$store.commit(USUARIO_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'El usuario se creó correctamente', color: 'success'})
              this.$store.commit(USUARIO_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
            }
            this.dialog = false
            this.close()
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar usuario ', error: e})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped >

</style>
