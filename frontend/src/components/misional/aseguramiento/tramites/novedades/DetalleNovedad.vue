<template>
  <v-layout row wrap v-if="value">
    <v-flex xs12 v-if="value.afiliado">
      <v-label>Afiliado</v-label>
      <v-expansion-panel key="panel2" focusable>
        <v-expansion-panel-content>
          <v-list-tile
            @click=""
            slot="header"
          >
            <v-list-tile-content>
              <v-list-tile-title>{{value.afiliado.nombre_completo}}</v-list-tile-title>
              <v-list-tile-sub-title>{{value.afiliado.identificacion_completa}}</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
          <detalle-afiliado :item="value.afiliado" class="elevation-1" />
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-flex>
    <v-flex xs12 class="mt-3">
      <v-label v-if="value.afiliado">Novedad</v-label>
      <v-flex xs12 class="elevation-1">
        <v-layout align-center justify-center row fill-height>
          <v-flex class="py-3 pl-3 pr-2">
            <v-avatar color="primary" size="34">
              <span class="white--text">{{value.novedad.codigo}}</span>
            </v-avatar>
          </v-flex>
          <v-flex class="text-xs-justify py-3 pr-3 pl-2">
            <p class="ma-0 subheading">{{value.novedad.descripcion}}</p>
            <span class="caption">{{value.novedad.observaciones}}</span>
            <p></p>
            <p>
            	<b>
            		Observaciones:
            	</b>
            	<span class="caption">{{value.observaciones}}</span>
            </p>
          </v-flex>
        </v-layout>
        <v-divider/>
        <v-container
          fluid
          grid-list-xl
        >
          <v-layout row wrap>
            <v-flex xs12 sm6 md4>
              <span class="caption grey--text">Tipo trámite</span>
              <p class="ma-0 subheading">{{value.tramite.tipo_tramite}}</p>
            </v-flex>
            <v-flex xs12 sm6 md4>
              <span class="caption grey--text">Clase trámite</span>
              <p class="ma-0 subheading">{{value.tramite.clase_tramite}}</p>
            </v-flex>
            <v-flex xs12 sm6 md4>
              <span class="caption grey--text">Estado</span>
              <p class="ma-0 subheading">{{value.tramite.estado}}</p>
            </v-flex>
            <v-flex xs12 sm6 md4>
              <span class="caption grey--text">Fecha radicado</span>
              <p class="ma-0 subheading">{{value.tramite.fecha_radicacion}}</p>
            </v-flex>
            <v-flex xs12 sm6 md4>
              <span class="caption grey--text">Fecha inicio</span>
              <p class="ma-0 subheading">{{value.fecha_ini_novedad}}</p>
            </v-flex>
          </v-layout>
        </v-container>
      </v-flex>
    </v-flex>
    <v-flex xs12 class="mt-3" v-if="value.v1 || value.v2 || value.v3 || value.v4 || value.v5 || value.v6 || value.v7">
      <v-label>Variables afectadas</v-label>
      <v-flex xs12 class="elevation-1">
        <v-container
          fluid
          grid-list-xl
        >
          <v-layout row wrap>
            <v-flex xs12 sm6 md4 v-if="value.v1">
              <span class="caption grey--text">v1:</span>
              <p class="ma-0 subheading">{{value.v1}}</p>
            </v-flex>
            <v-flex xs12 sm6 md4 v-if="value.v2">
              <span class="caption grey--text">v2:</span>
              <p class="ma-0 subheading">{{value.v2}}</p>
            </v-flex>
            <v-flex xs12 sm6 md4 v-if="value.v3">
              <span class="caption grey--text">v3:</span>
              <p class="ma-0 subheading">{{value.v3}}</p>
            </v-flex>
            <v-flex xs12 sm6 md4 v-if="value.v4">
              <span class="caption grey--text">v4:</span>
              <p class="ma-0 subheading">{{value.v4}}</p>
            </v-flex>
            <v-flex xs12 sm6 md4 v-if="value.v5">
              <span class="caption grey--text">v5:</span>
              <p class="ma-0 subheading">{{value.v5}}</p>
            </v-flex>
            <v-flex xs12 sm6 md4 v-if="value.v6">
              <span class="caption grey--text">v6:</span>
              <p class="ma-0 subheading">{{value.v6}}</p>
            </v-flex>
            <v-flex xs12 sm6 md4 v-if="value.v7">
              <span class="caption grey--text">v7:</span>
              <p class="ma-0 subheading">{{value.v7}}</p>
            </v-flex>
          </v-layout>
        </v-container>
      </v-flex>
    </v-flex>
  </v-layout>
</template>

<script>
  import store from '@/store/complementos/index'
  import DetalleAfiliado from '@/components/misional/aseguramiento/afiliados/DetalleAfiliado'
  export default {
    name: 'DetalleNovedad',
    props: ['value'],
    components: {
      DetalleAfiliado
    },
    data () {
      return {
      }
    },
    watch: {
      'value' (val) {
        val && this.getRegistro(val)
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosFormPagadores
      }
    },
    methods: {
      getRegistro (id) {
      }
    }
  }
</script>

<style scoped>

</style>
