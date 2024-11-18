<template>
    <v-container>
      <v-form ref="form-login" lazy-validation>
        <v-text-field :label="'Usuario'" v-validate="'required|email'" name="usuario" v-model="credenciales.email" ></v-text-field>
        <v-text-field :label="'Contraseña'" v-validate="'required'" name="contraseña" :type="'password'" v-model="credenciales.password"></v-text-field>
        <v-btn @click="submit" color="success" >Enviar</v-btn>
      </v-form>
    </v-container>
</template>

<script>
  import {AUTH_REQUEST} from '../../../store/modules/general/auth'
  import {SNACK_SHOW} from '../../../store/modules/general/snackbar'

  export default {
    name: 'Login',
    data: () => ({
      credenciales: {
        email: '',
        password: '',
        changeUser: false
      },
      emailRules: [
        v => !!v || 'Email es requerido',
        v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'Ingrese un email valido'
      ],
      snackbar: false,
      snackbarMessage: ''
    }),
    methods: {
      submit () {
        if (this.$validator.validateAll()) {
          this.$store.dispatch(AUTH_REQUEST, this.credenciales)
            .then(() => {
              this.$router.push('/')
            }).catch((error) => {
              console.log('error', error)
              this.$store.commit(SNACK_SHOW, {msg: error.message, color: 'error'})
            })
        }
      }
    }
  }
</script>

<style scoped>

</style>
