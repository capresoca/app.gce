<template>
  <detalle-red-servicios ref="detalleRedServicios" :afiliado-id="afiliadoId"></detalle-red-servicios>
</template>

<script>
  import DetalleRedServicios from '@/components/misional/redservicios/DetalleRedServicios'
  export default {
    name: 'DetalleGeneralRedServicios',
    props: {
      afiliadoId: {
        type: [String, Number],
        default: null
      }
    },
    components: {
      DetalleRedServicios
    },
    methods: {
      getData () {
        this.$refs.detalleRedServicios.getData()
      }
    }
  }
</script>

<style scoped>

</style>
