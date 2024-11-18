<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Notas credito" title="Notas credito" subtitle="gestión"/>
    </v-card>
</template>

<script>
  export default {
    name: 'NotasCredito',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('notascredito')
      }
    }
  }
</script>

<style scoped>

</style>
