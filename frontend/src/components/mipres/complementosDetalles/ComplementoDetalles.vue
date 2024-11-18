<template>
  <v-expansion-panel
    v-model="panel"
    expand
    class="v-expansion-panel-pi"
  >
    <v-expansion-panel-content
      class="v-expansion-header-dark"
      v-for="(complemento, index) in complementos.filter(x => x.array.length)"
      :key="`expansion ${index}`"
    >
      <v-toolbar slot="header" dense class="pa-0 ma-0 elevation-0">
        <v-badge
          color="purple"
        >
          <span slot="badge">{{complemento.array.length}}</span>
          <span class="title">{{complemento.title}}</span>
        </v-badge>
      </v-toolbar>
      <v-card>
        <v-card-text>
          <component :is="complemento.componente" :item="item" :items="complemento.array"/>
        </v-card-text>
      </v-card>
    </v-expansion-panel-content>
  </v-expansion-panel>
</template>

<script>
    export default {
      name: 'ComplementoDetalles',
      props: ['item'],
      data: () => ({
        panel: [],
        complementos: []
      }),
      created () {
        this.complementos = [
          {
            title: 'Medicamentos',
            array: this.item.medicamentos,
            componente: () => import('@/components/mipres/complementosDetalles/complementos/Medicamentos')
          },
          {
            title: 'Procedimientos',
            array: this.item.procedimientos,
            componente: () => import('@/components/mipres/complementosDetalles/complementos/Procedimientos')
          },
          {
            title: 'Dispositivos Médicos',
            array: this.item.dispositivos,
            componente: () => import('@/components/mipres/complementosDetalles/complementos/DispositivosMedicos')
          },
          {
            title: 'Productos de Soporte Nutricional',
            array: this.item.nutricionales,
            componente: () => import('@/components/mipres/complementosDetalles/complementos/ProductosSoporteNutricional')
          },
          {
            title: 'Servicios Complementarios',
            array: this.item.complementarios,
            componente: () => import('@/components/mipres/complementosDetalles/complementos/ServiciosComplementarios')
          },
          {
            title: 'Novedades',
            array: this.item.novedades,
            componente: () => import('@/components/mipres/complementosDetalles/complementos/Novedades')
          }
        ]
      }
    }
</script>

<style scoped>

</style>
