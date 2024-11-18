<template>
    <v-card>
      <v-container
        class="pa-3"
        fluid
        grid-list-md
      >
        <v-layout row wrap v-if="factura">
          <v-flex xs12>
            <div class="caption grey--text">Entidad</div>
            <v-expansion-panel class="v-expansion-panel-pi">
              <v-expansion-panel-content>
                <v-list-tile slot="header" @click="">
                  <v-list-tile-action>
                    <v-icon large>location_city</v-icon>
                  </v-list-tile-action>
                  <v-list-tile-content class="content-v-list-tile-p0">
                    <v-list-tile-title>{{factura.radicado.entidad.tercero.nombre_completo}}</v-list-tile-title>
                    <v-list-tile-sub-title class="body-1">{{factura.radicado.entidad.tercero.identificacion_completa}}</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
                <detalle-entidad :item="factura.radicado.entidad"></detalle-entidad>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-flex>
          <input-detail-flex
            v-for="(item, i) in itemsFactura"
            :key="`itemFactura${i}`"
            :flex-class="item.flexClass"
            :label="item.label"
            :text="item.text"
            :hint="item.hint"
            :prepend-icon="item.icon"
          ></input-detail-flex>
        </v-layout>
      </v-container>
    </v-card>
</template>

<script>
  export default {
    name: 'DetalleInformacionBasica',
    props: ['value'],
    components: {
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      DetalleEntidad: () => import('@/components/misional/redservicios/entidades/DetalleEntidad')
    },
    data: () => ({
      factura: null,
      itemsFactura: []
    }),
    watch: {
      'value' (val) {
        val && this.assign()
      }
    },
    created () {
      this.assign()
    },
    methods: {
      assign () {
        this.itemsFactura = []
        this.factura = this.value
        this.itemsFactura.push(
          {label: 'Factura', text: this.factura.consecutivo, hint: null, icon: null, flexClass: null},
          {label: 'Estado', text: this.factura.estado, hint: null, icon: null, flexClass: null},
          {label: 'Fecha', text: this.moment(this.factura.fecha).format('YYYY-MM-DD'), hint: null, icon: 'event', flexClass: null},
          {label: 'Contrato', text: this.factura.no_contrato, hint: null, icon: null, flexClass: null},
          {label: 'Plan beneficio', text: this.factura.plan_beneficio, hint: null, icon: null, flexClass: null},
          {label: 'Periodo de facturación', text: `Inicio: ${this.moment(this.factura.radicado.periodo_inicio).format('YYYY-MM-DD HH:MM')} - Fin: ${this.moment(this.factura.radicado.periodo_fin).format('YYYY-MM-DD HH:MM')}`, hint: null, icon: null, flexClass: null},
          {label: 'Fecha radicación', text: this.moment(this.factura.radicado.fecha_radicado).format('YYYY-MM-DD HH:MM'), hint: null, icon: 'event', flexClass: null}
        )
      }
    }
  }
</script>

<style scoped>

</style>
