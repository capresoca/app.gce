<template>
  <section>
    <v-dialog
      id="xxxs"
      v-model="dialog"
      max-width="400"
      lazy
      persistent
      transition="dialog-bottom-transition"
      class="elevation-10">
      <v-card class="pa-3">
        <article v-if="autenticado">
          <v-card-title class="headline" >Hola {{this.$store.getters.getUserName}}</v-card-title>
          <v-divider></v-divider>
          <v-card-text>Su session ha vencido. Por favor ingresa de nuevo su Contraseña</v-card-text>
        </article>
        <article v-else>
          <v-card-title class="headline">Bienvenido</v-card-title>
          <v-divider></v-divider>
          <v-card-text >Por favor ingrese sus credenciales para continuar</v-card-text>
        </article>
        <v-form v-on:submit.prevent="login" >
          <v-text-field
            v-show="!autenticado"
            :disabled="autenticado"
            type="email"
            placeholder="Correo Electronico"
            prepend-icon="perm_identity"
            name="email"
            v-validate="'required|email'"
            data-vv-delay="1500"
            :error-messages="errors.collect('email')"
            v-model="newEmail">
          </v-text-field>
          <v-text-field
            type="password"
            placeholder="Contraseña"
            prepend-icon="lock"
            autofocus
            v-validate="'required'"
            name="password"
            data-vv-delay="1500"
            :error-messages="errors.collect('password')"
            v-model="password">
          </v-text-field>
          <v-alert
            v-if="errorUser"
            :value="true"
            type="error">
            {{errorUser.data.error}}
          </v-alert>
          <v-card-actions>
            <v-btn @click="close" color="primary darken-1" flat v-text="'Cancelar'"></v-btn>
            <v-spacer></v-spacer>
            <v-btn v-if="autenticado" color="red darken-1" flat @click.native="dialog = false">Salir</v-btn>
            <v-btn color="primary darken-1" type="submit" flat >Ingresar</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
  </section>
</template>

<script>
  import {SNACK_SHOW} from '../../../store/modules/general/snackbar'
  import {AUTH_LOGOUT, AUTH_REAUTH_REQUEST, AUTH_REQUEST} from '../../../store/modules/general/auth'
  import {mapState} from 'vuex'

  export default {
    name: 'ReAuthLogin',
    data: () => ({
      password: '',
      valid: false,
      newEmail: ''
    }),
    watch: {
      'dialog' (val) {
        if (!val || !this.autenticado) {
          this.password = ''
          this.newEmail = ''
        } else {
          this.newEmail = this.email
        }
      }
    },
    methods: {
      async login () {
        if (await this.$validator.validateAll()) {
          this.$store.dispatch(AUTH_REQUEST, {email: this.newEmail, password: this.password, changeUser: this.newEmail !== this.email})
            .then((response) => {
              this.dialog = false
              this.$router.push('/main')
            }).catch((error) => {
              this.$store.commit(SNACK_SHOW, {msg: error.message, color: 'error'})
            })
        }
      },
      logout () {
        this.$store.dispatch(AUTH_LOGOUT)
      },
      close () {
        this.dialog = false
        this.$router.push('/')
      }
    },
    computed: {
      ...mapState({
        errorUser: state => state.auth.errorUser,
        email: state => state.user.profile.email,
        autenticado: state => !!state.user.profile.email
      }),
      dialog: {
        get () { return this.$store.getters.reauth },
        set (value) { this.$store.commit(AUTH_REAUTH_REQUEST, value) }
      }
    }
  }
</script>

<style >

  .overlay--active::before{
    opacity: 1;
  }
</style>
