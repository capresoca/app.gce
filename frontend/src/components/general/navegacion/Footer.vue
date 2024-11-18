<template>
  <v-footer class="pa-2" fixed>
    <div>&copy; {{ new Date().getFullYear() }} Sistemas Inteligentes Todos los derechos reservados</div>
    <v-spacer></v-spacer>
    <div>Empresa: {{getUserDb.toUpperCase()}}</div>
  </v-footer>
</template>

<script>
  import {mapGetters} from 'vuex'
  export default {
    name: 'FooterApp',
    computed: {
      ...mapGetters(['isAuthenticated', 'getUserName', 'getUserDb'])
    }
  }
</script>

<style scoped>

</style>
