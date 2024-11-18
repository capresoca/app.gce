<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Notas debito" title="Notas debito" subtitle="gestión"/>
    </v-card>
</template>

<script>
  export default {
    name: 'NotasDebito',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('notasdebito')
      }
    }
  }
</script>

<style scoped>

</style>
