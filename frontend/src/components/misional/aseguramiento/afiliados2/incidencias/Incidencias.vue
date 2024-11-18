<template>
  <v-container fluid grid-list-md>
    <loading v-model="loading"></loading>
    <v-tabs
      v-model="active"
      color="primary"
      dark
      slider-color="yellow"
    >
      <v-tab ripple>
        <v-badge color="red" class="mr-3">
          <template v-slot:badge>
            <span>{{inexistentes.length}}</span>
          </template>
          <span>Pacientes no Afiliados</span>
        </v-badge>
      </v-tab>
      <v-tab-item>
        <inexistentes :incidencias="inexistentes"></inexistentes>
      </v-tab-item>
      <v-tab ripple>
        <v-badge color="red" class="mr-3">
          <template v-slot:badge>
            <span>{{fallecidos.length}}</span>
          </template>
          <span>Fallecidos</span>
        </v-badge>
      </v-tab>
      <v-tab-item>
        <fallecidos :incidencias="fallecidos"></fallecidos>
      </v-tab-item>
    </v-tabs>
  </v-container>
</template>

<script>
  export default {
    name: 'Incidencias',
    components: {
      Inexistentes: () => import('@/components/misional/aseguramiento/afiliados/incidencias/Inexistentes'),
      Fallecidos: () => import('@/components/misional/aseguramiento/afiliados/incidencias/Fallecidos')
    },
    data: () => ({
      loading: false,
      active: null,
      inexistentes: [],
      fallecidos: []
    }),
    created () {
      this.getIncidencias()
    },
    methods: {
      getIncidencias () {
        this.loading = true
        this.axios.get(`afiliados/incidencias`)
          .then(response => {
            this.inexistentes = response.data.data.inexistentesReportadosPorConcurrencias
            this.fallecidos = response.data.data.fallecidos
            this.loading = false
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al recuperar las incidencias. ', error: e})
            this.loading = false
          })
      }
    }
  }
</script>

<style scoped>

</style>
