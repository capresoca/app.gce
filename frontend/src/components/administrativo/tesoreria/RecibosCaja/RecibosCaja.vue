<template>
  <toolbar-list :info="infoComponent" btnplus btnplustitle="Recibos de caja" title="Recibos de caja" subtitle="Gestion de recibos de caja"/>
</template>
<script>
  export default {
    name: 'TesoreriaRecibosCaja',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        caja: ''
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('reciboscaja')
      }
    }
  }
</script>
