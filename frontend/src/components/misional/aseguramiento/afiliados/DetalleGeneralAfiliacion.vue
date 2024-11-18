<template>
  <v-container fluid grid-list-xl class="pa-0">
    <loading v-model="loading" />
    <v-slide-y-transition group>
      <v-layout row wrap v-if="item" key="seccion1_1">
        <input-detail-flex
          label="Régimen"
          :text="item.regimen && item.regimen.nombre"
        />
        <input-detail-flex
          label="Fecha afiliación"
          :text="item.fecha_afiliacion | dateDMA"
        />
        <input-detail-flex
          flex-class="xs12 sm12 md6"
          label="Tipo afiliado"
          :text="item.tipo_afiliado && (item.tipo_afiliado.codigo_planos + ' - ' + item.tipo_afiliado.nombre)"
        />
        <input-detail-flex
          flex-class="xs12 sm12 md6"
          label="Tipo cotizante"
          :text="item.clase_cotizante && item.clase_cotizante.descripcion"
        />
        <input-detail-flex
          tooltip="Fecha de afiliación al sistema general de seguridad social en salud"
          label="Fecha SGSSS"
          :text="item.fecha_sgsss | dateDMA"
        />
        <input-detail-flex
          label="Estado"
          :text="item.estado_afiliado.codigo + ' - ' + item.estado_afiliado.nombre"
        />
        <v-flex xs12 sm12 md12>
          <div class="caption grey--text">IPS</div>
          <v-expansion-panel focusable v-if="item.ips" class="v-expansion-panel-pi">
            <v-expansion-panel-content>
              <v-list-tile slot="header" @click="">
                <v-list-tile-action>
                  <v-icon large color="accent">location_city</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                  <v-list-tile-title>{{item.ips.nombre_completo}}</v-list-tile-title>
                  <v-list-tile-sub-title>{{'Código habilitación: ' + item.ips.cod_habilitacion}}</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
              <detalle-entidad :item="item.ips" class="elevation-1" />
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-flex>
      </v-layout>
    </v-slide-y-transition>
  </v-container>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'DetalleGeneralAfiliacion',
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
    data () {
      return {
        loading: false,
        item: null
      }
    },
    components: {
      Loading,
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      DetalleEntidad: () => import('@/components/misional/redservicios/entidades/DetalleEntidad')
    },
    filters: {
      dateDMA (val) {
        if (val) {
          let date = val.split('-')
          return date[2] + '-' + date[1] + '-' + date[0]
        }
        return ''
      }
    },
    created () {
      this.afiliado && this.afiliado.id ? this.item = this.afiliado : this.getData()
    },
    methods: {
      getData () {
        this.loading = true
        this.axios.get(`afiliados/${this.afiliadoId}`)
          .then((response) => {
            if (response.data !== '') {
              this.item = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los datos de la afiliación. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
