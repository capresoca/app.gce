<template>
  <toolbar-list :info="infoComponent" btnplus btnplustitle="Comprobantes de notas" title="Comprobantes de notas" subtitle="Gestion de comprobantes de notas"/>
</template>
<script>
  export default {
    name: 'ComprobantesNotas',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        comprobantes: ''
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('comprobantesnotas')
      }
    }
  }
</script>
