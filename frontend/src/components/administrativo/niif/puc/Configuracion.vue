<template>
  <div>
    <!--<h1>Las NIIF</h1> xs12 sm12 md6 lg4 || xs12 sm12 md6 lg12-->
    <v-container fluid grid-list-sm grid-list-xl>
      <v-layout row wrap>
        <v-flex xs12 sm12 md6 lg5>
          <v-expansion-panel>
            <v-expansion-panel-content lazy v-if="infoComponent.nivelCuenta && infoComponent.nivelCuenta.permisos.ver">
              <v-toolbar-title slot="header">Niveles de Cuentas </v-toolbar-title>
              <v-card>
                <v-divider></v-divider>
                <niveles-cuenta></niveles-cuenta>
              </v-card>
            </v-expansion-panel-content>
            <v-expansion-panel-content lazy v-if="infoComponent.clasCuenta && infoComponent.clasCuenta.permisos.ver">
              <v-toolbar-title slot="header">Clases de Cuentas </v-toolbar-title>
              <v-card>
                <v-divider></v-divider>
                <clases-cuenta></clases-cuenta>
              </v-card>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-flex>
        <v-flex xs12 sm12 md6 lg7 v-if="infoComponent.puc && infoComponent.puc.permisos.ver">
          <v-card>
            <puc></puc>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>
  import ClasesCuenta from './ClasesCuenta'
  import NivelesCuenta from './NivelesCuenta'
  import Puc from './Puc'
  export default {
    name: 'Configuracion',
    components: {Puc, NivelesCuenta, ClasesCuenta},
    data () {
      return {
        clase: 'grey lighten-4'
      }
    },
    methods: {
      colorFondo () {
        return this.clase
      }
    },
    computed: {
      infoComponent () {
        return {
          clasCuenta: this.$store.getters.getInfoComponent('clasesCuenta'),
          nivelCuenta: this.$store.getters.getInfoComponent('nivelesCuenta'),
          puc: this.$store.getters.getInfoComponent('niifs')
        }
      }
    }
  }
</script>

<style scoped>

</style>
