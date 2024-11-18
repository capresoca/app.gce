<template>
  <v-alert v-model="visible" :value="visible" :type="type" dismissible>
    <h3>Lo sentimos, el sistema ha experimentado un error interno.</h3>
    <h3>{{message}}</h3>
    <template v-if="items.length">
      <br/>
      <p v-for="(item, index) in items">{{index + 1}}: {{item}}</p>
    </template>
  </v-alert>
</template>

<script>
  import {SNACK_SHOW} from '../../store/modules/general/snackbar'
  export default {
    name: 'Alert',
    data () {
      return {
        visible: false,
        type: 'error',
        message: '',
        items: []
      }
    },
    methods: {
      show (response, params) {
        this.visible = true
        this.items = []
        this.message = response.data.message !== '' ? response.data.message : response.statusText
        if (response.data.errors) {
          Object.values(response.data.errors).forEach(value => {
            this.items.push(`${value}`)
          })
        }
        this.axios.post('reporte-error', {params: params, response: response})
          .catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Se estan experimentado problemas con el servidor, por favor actualice la página; si el problema persiste, reporte éste inconveniente directamente al administrador.', color: 'error'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
