<template>
  <v-card>
    <toolbar-list :info="infoComponent.general" title="Aportes y Compensaciones"/>
    <v-container fluid grid-list-xl>
      <v-layout row wrap>
        <v-flex xs12>
          <aportes v-if="infoComponent.aportes"/>
        </v-flex>
        <!--<v-flex xs12 sm12 md6>-->
          <!--<compensaciones v-if="infoComponent.compensaciones"/>-->
        <!--</v-flex>-->
      </v-layout>
    </v-container>
  </v-card>
</template>

<script>
  export default {
    name: 'AportesCompensaciones',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Aportes: () => import('@/components/misional/aseguramiento/relacionesLaborales/aportes/Aportes'),
      Compensaciones: () => import('@/components/misional/aseguramiento/relacionesLaborales/compensaciones/Compensaciones')
    },
    computed: {
      infoComponent () {
        return {
          general: this.$store.getters.getInfoComponent('aportesCompensaciones'),
          aportes: this.$store.getters.getInfoComponent('aportes'),
          compensaciones: this.$store.getters.getInfoComponent('compensaciones')
        }
      }
    },
    methods: {
    }
  }
</script>

<style scoped>
</style>
