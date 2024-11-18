<template>
    <v-window v-model="onboarding">

      <v-window-item key="tabla-visitas">
        <tabla-visitas @verDetalle="verDetalle" :concurrenciaId="concurrenciaId" :concurrencia="concurrenciaObj"></tabla-visitas>
      </v-window-item>

      <v-window-item key="detalle-visita">
        <detalle-visita v-model="data" @volver="prev" :concurrencia="concurrenciaObj"></detalle-visita>
      </v-window-item>

    </v-window>
</template>

<script>
  import TablaVisitas from '@/components/administrativo/auditoriaCuentas/concurrencias/TablaVisitas'
  import DetalleVisita from '@/components/administrativo/auditoriaCuentas/concurrencias/DetalleVisita'

  export default {
    name: 'ConVisitas',
    components: {
      TablaVisitas,
      DetalleVisita
    },
    props: {
      concurrenciaId: {
        type: Number,
        default: 0
      },
      concurrenciaObj: {
        type: Object,
        defatul: {}
      }
    },
    data () {
      return {
        data: null,
        length: 3,
        onboarding: 0,
        items: [
          { icon: 'folder_shared', avatarColor: 'primary', title: 'Tabla Visitas', subTitle: 'Información', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/TablaVisitas') },
          { icon: 'assignment', avatarColor: 'success', title: 'Detalle Visitas', subTitle: 'Alto Costo', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/DetalleVisita') }
        ]
      }
    },
    methods: {
      next () {
        this.onboarding = this.onboarding + 1 === this.length
          ? 0
          : this.onboarding + 1
      },
      prev () {
        this.onboarding = this.onboarding - 1 < 0
          ? this.length - 1
          : this.onboarding - 1
      },
      verDetalle (prop) {
        this.data = prop.item
        this.next()
      }
    }
  }
</script>

<style scoped>

</style>
