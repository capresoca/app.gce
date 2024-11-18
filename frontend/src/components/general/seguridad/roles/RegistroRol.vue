<template>
  <v-card v-if="rol" class="grey lighten-4">
    <v-divider></v-divider>
    <v-subheader>{{rol.id ? ('Edición del rol: ' + rol.nombre) : 'nuevo Rol'}}</v-subheader>
    <v-divider></v-divider>
    <v-container fluid grid-list-xl>
      <v-form data-vv-scope="formNewRol" v-if="value">
        <v-layout row wrap>
          <v-flex>
            <v-text-field
              label="Nombre"
              v-model="rol.nombre"
              name="Nombre"
              v-validate="'required'"
              :error-messages="errors.collect('Nombre')"
            ></v-text-field>
          </v-flex>
          <v-flex>
            <v-text-field
              label="Descripción"
              v-model="rol.descripcion"
              name="Descripción"
              v-validate="'required'"
              :error-messages="errors.collect('Descripción')"
            ></v-text-field>
          </v-flex>
        </v-layout>
      </v-form>
    </v-container>
    <v-divider/>
    <v-card-actions>
      <v-spacer/>
      <v-btn :loading="loadingBtnRol" small flat @click.native="$emit('cancelar')">Cancelar</v-btn>
      <v-btn :loading="loadingBtnRol" small color="primary" @click.native="submitRol">Registrar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'registroRol',
    props: ['value'],
    data () {
      return {
        loadingBtnRol: false,
        rol: null
      }
    },
    components: {
    },
    watch: {
      'value' (val) {
        if (val) {
          this.rol = JSON.parse(JSON.stringify(this.value))
          this.$validator.reset()
        }
      }
    },
    mounted () {
      this.rol = JSON.parse(JSON.stringify(this.value))
      this.$validator.reset()
    },
    computed: {
    },
    methods: {
      submitRol () {
        this.$validator.validateAll('formNewRol').then(result => {
          if (result) {
            this.loadingBtnRol = true
            const typeRequest = this.rol.id ? 'editar' : 'crear'
            let request = typeRequest === 'editar' ? this.axios.put(`gs_roles/${this.rol.id}`, this.rol) : this.axios.post('gs_roles', this.rol)
            request
              .then((response) => {
                this.$store.commit(SNACK_SHOW, {msg: 'El rol se ha ' + (typeRequest === 'editar' ? 'editado' : 'creado') + ' satisfactoriamente.', color: 'success'})
                this.loadingBtnRol = false
                this.$emit('return', response.data.data)
              })
              .catch(e => {
                this.loadingBtnRol = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar guardar los datos del rol. ', error: e})
              })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
