<template>
  <v-tabs
    v-model="active"
    color="indigo"
    dark
    slider-color="orange"
  >
    <template v-for="(tab, iTab) in itemsTabs">
      <v-tab ripple :key="'tab' + iTab">
        {{tab.title}}
      </v-tab>
      <v-tab-item :key="'tabItem' + iTab" lazy>
        <v-card flat>
          <component :ref="tab.ref" :afiliado-id="afiliadoId" :is="tab.component"></component>
        </v-card>
      </v-tab-item>
    </template>
  </v-tabs>
</template>

<script>
  export default {
    name: 'DetalleGeneralTramites',
    props: {
      afiliado: {
        type: Object,
        default: null
      },
      afiliadoId: {
        type: Number,
        default: 0
      }
    },
    data: () => ({
      active: null,
      itemsTabs: [
        { ref: 'ref1', title: 'Afiliación', component: () => import('@/components/misional/aseguramiento/afiliados/detalleGeneralTramites/Afiliacion') },
        { ref: 'ref2', title: 'Novedades', component: () => import('@/components/misional/aseguramiento/afiliados/detalleGeneralTramites/Novedades') },
        { ref: 'ref3', title: 'Traslados', component: () => import('@/components/misional/aseguramiento/afiliados/detalleGeneralTramites/Traslado') }
      ]
    }),
    methods: {
      getData () {
        if (this.$refs.ref1 && this.$refs.ref1[0]) this.$refs.ref1[0].getData()
        if (this.$refs.ref2 && this.$refs.ref2[0]) this.$refs.ref2[0].getData()
        if (this.$refs.ref3 && this.$refs.ref3[0]) this.$refs.ref3[0].getData()
      }
    }
  }
</script>

<style scoped>

</style>
