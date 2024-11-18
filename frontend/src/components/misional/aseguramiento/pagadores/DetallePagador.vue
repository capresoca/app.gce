<template>
  <v-card>
    <v-container fluid grid-list-xl>
      <v-layout row wrap key="seccion1">
        <input-detail-flex
          label="Tipo aportante"
          :text="item.as_tipaportante_id && complementos && complementos.tipaportantes.find(x => x.id === item.as_tipaportante_id).nombre"
        />
      </v-layout>
      <v-layout>
        <v-flex xs12 sm12 md12>
          <detalle-tercero v-if="item.tercero" :item="item.tercero"/>
          <v-subheader v-else>Tercero no disponible... <v-icon>sentiment_very_dissatisfied</v-icon></v-subheader>
        </v-flex>
      </v-layout>
    </v-container>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'DetalleEntidad',
    props: ['item'],
    components: {
      DetalleTercero: () => import('@/components/administrativo/niif/terceros/DetalleTercero'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex')
    },
    computed: {
      complementos () {
        return store.getters.complementosFormPagadores
      }
    }
  }
</script>

<style scoped>

</style>
