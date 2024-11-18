<template>
  <v-layout row justify-center>
    <v-dialog v-model="model" persistent max-width="400">
      <v-card v-if="isAuthenticated">
        <loading v-model="loading" />
        <v-card-text class="title font-weight-light text-xs-center">
          Cambio de contraseña Usuario: <strong>{{getUserName}}</strong>
        </v-card-text>
        <v-form>
          <v-container fluid grid-list-xs>
            <v-layout row wrap>
              <v-flex xs12>
                <v-text-field
                  v-model="current_password"
                  :append-icon="showCurrentPassword ? 'visibility_off' : 'visibility'"
                  :type="showCurrentPassword ? 'text' : 'password'"
                  label="Contraseña Actual"
                  name="currentPassword"
                  data-vv-as="Contraseña Actual"
                  v-validate="'required'"
                  browser-autocomplete="false"
                  :error-messages="errors.collect('currentPassword')"
                  @click:append="showCurrentPassword = !showCurrentPassword"
                  @input="() => {
                  if (!current_password) password = null
                  }"
                />
              </v-flex>
              <v-flex xs12 v-if="current_password">
                <v-text-field
                  v-model="password"
                  :append-icon="showPassword ? 'visibility_off' : 'visibility'"
                  :type="showPassword ? 'text' : 'password'"
                  label="Nueva Contraseña"
                  name="password"
                  data-vv-as="Nueva Contraseña"
                  browser-autocomplete="false"
                  v-validate="{min:6, required: true, is_not:current_password}"
                  :error-messages="errors.collect('password')"
                  @click:append="showPassword = !showPassword"
                  @input="() => {
                  if (!password) password_confirmation = null
                  }"
                />
              </v-flex>
              <v-flex xs12 v-if="current_password && password">
                <v-text-field
                  v-model="password_confirmation"
                  :append-icon="showPasswordConfirmation ? 'visibility_off' : 'visibility'"
                  :type="showPasswordConfirmation ? 'text' : 'password'"
                  label="Confirmar Contraseña"
                  name="passwordConfirmation"
                  data-vv-as="Confirmar Contraseña"
                  browser-autocomplete="false"
                  v-validate="{min:6, required: true, is:password, is_not:current_password}"
                  :error-messages="errors.collect('passwordConfirmation')"
                  @click:append="showPasswordConfirmation = !showPasswordConfirmation"
                  :disabled="!password"
                />
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
        <v-card-actions>
          <v-spacer/>
          <v-btn flat @click.stop="model = false">Cancelar</v-btn>
          <v-btn color="primary" @click.stop="submit">Aceptar</v-btn>
        </v-card-actions>
      </v-card>
      <v-alert v-else :value="true" color="error" icon="warning">
        Lo sentimos, necesita estar autenticado para realizar este proceso. <v-icon>sentiment_very_dissatisfied</v-icon>
      </v-alert>
    </v-dialog>
  </v-layout>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'
  import {mapGetters} from 'vuex'
  export default {
    name: 'Confirmar',
    props: {
      value: {
        type: Boolean,
        default: false
      }
    },
    components: {
      Confirmar: () => import('@/components/general/Confirmar'),
      Loading
    },
    data () {
      return {
        current_password: null,
        password: null,
        password_confirmation: null,
        showCurrentPassword: false,
        showPassword: false,
        showPasswordConfirmation: false,
        model: false,
        loading: false
      }
    },
    computed: {
      ...mapGetters(['getProfile', 'isAuthenticated', 'isProfileLoaded', 'getUserName'])
    },
    watch: {
      'model' (val) {
        if (!val) {
          this.$validator.reset()
          this.resetData()
          this.$emit('input', false)
        }
      },
      'value' (val) {
        if (val) {
          this.model = JSON.parse(JSON.stringify(val))
        }
      }
    },
    methods: {
      resetData () {
        this.current_password = null
        this.password = null
        this.password_confirmation = null
        this.showCurrentPassword = false
        this.showPassword = false
        this.showPasswordConfirmation = false
      },
      async submit () {
        if (await this.$validator.validateAll()) {
          this.loading = true
          this.axios.post('changepassword', {current_password: this.current_password, password: this.password, password_confirmation: this.password_confirmation})
            .then(response => {
              this.$store.commit(SNACK_SHOW, {msg: 'La contraseña se ha cambiado satisfactoriamente.', color: 'success'})
              this.loading = false
              this.model = false
            }).catch(e => {
              this.loading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: `al intentar cambiar la contraseña.`, error: e})
            })
        }
      }
    }
  }
</script>

<style scoped>

</style>
