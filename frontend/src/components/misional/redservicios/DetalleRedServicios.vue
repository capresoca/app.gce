<template>
  <div>
    <v-expansion-panel class="mb-1" v-if="afiliado && afiliado.portabilidad_activa">
      <v-expansion-panel-content>
        <template v-slot:header>
          <span class="font-weight-bold">Portabilidad número {{afiliado.portabilidad_activa.consecutivo}}</span>
        </template>
        <v-container fluid grid-list-sm>
          <v-layout row wrap>
            <input-detail-flex
              flex-class="xs12 sm6 md4 lg3"
              label="Consecutivo"
              :text="afiliado.portabilidad_activa.consecutivo"
            ></input-detail-flex>
            <input-detail-flex
              flex-class="xs12 sm6 md4 lg3"
              label="Estado"
              :text="afiliado.portabilidad_activa.estado"
            ></input-detail-flex>
            <input-detail-flex
              flex-class="xs12 sm6 md4 lg3"
              label="Fecha inicio"
              :text="afiliado.portabilidad_activa.fecha_inicio"
              prepend-icon="event"
            ></input-detail-flex>
            <input-detail-flex
              flex-class="xs12 sm6 md4 lg3"
              label="Fecha vencimiento"
              :text="afiliado.portabilidad_activa.fecha_fin"
              prepend-icon="event"
            ></input-detail-flex>
            <input-detail-flex
              flex-class="xs12 sm12 md6"
              label="Municipio"
              :text="`${afiliado.portabilidad_activa.municipio_receptor.nombre}, ${afiliado.portabilidad_activa.municipio_receptor.nombre_departamento}`"
            ></input-detail-flex>
            <input-detail-flex
              flex-class="xs12 sm12 md6"
              label="Dirección"
              :text="afiliado.portabilidad_activa.direccion"
            ></input-detail-flex>
            <input-detail-flex
              flex-class="xs12 sm6 md4 lg3"
              label="Teléfono"
              :text="afiliado.portabilidad_activa.telefono"
            ></input-detail-flex>
            <input-detail-flex
              flex-class="xs12 sm6 md4 lg3"
              label="Correo electrónico"
              :text="afiliado.portabilidad_activa.email"
            ></input-detail-flex>
            <input-detail-flex
              flex-class="xs12"
              label="Motivo"
              :text="afiliado.portabilidad_activa.motivo"
            ></input-detail-flex>
            <input-detail-flex
              flex-class="xs12"
              label="Observaciones"
              :text="afiliado.portabilidad_activa.observaciones"
            ></input-detail-flex>
          </v-layout>
        </v-container>
      </v-expansion-panel-content>
    </v-expansion-panel>
    <v-data-table
      :loading="loading"
      :headers="headers"
      :items="primarios"
      hide-actions
      no-data-text="No hay datos para mostrar"
    >
      <template slot="items" slot-scope="props">
        <td width="40%">
          {{props.item.codigo}} - {{ props.item.nombre }}
        </td>
        <td width="60%">
          <template v-if="!!habilitados.find(x => x.rs_servicio_id === props.item.id)">
            <v-list-tile-content class="truncate-content">
              <v-list-tile-title>{{habilitados.find(x => x.rs_servicio_id === props.item.id).entidad.nombre}}</v-list-tile-title>
              <v-list-tile-sub-title class="caption"><v-icon size="small">fas fa-map-marker-alt</v-icon> {{habilitados.find(x => x.rs_servicio_id === props.item.id).entidad.direccion_sede}} - <v-icon size="small">fas fa-phone-alt</v-icon> {{afiliado.servicios_habilitados.find(x => x.rs_servicio_id === props.item.id).entidad.telefono_sede}}</v-list-tile-sub-title>
            </v-list-tile-content>
          </template>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
    export default {
      name: 'DetalleRedServicios',
      props: {
        afiliadoId: {
          type: [String, Number],
          default: null
        }
      },
      data: () => ({
        loading: false,
        headers: [
          {
            text: 'Servicio',
            align: 'left',
            sortable: false
          },
          {
            text: 'Prestador',
            align: 'left',
            sortable: false
          }
        ],
        primarios: [],
        habilitados: [],
        afiliado: null
      }),
      watch: {
        'afiliadoId' (val) {
          val && this.getData()
        }
      },
      created () {
        this.afiliadoId && this.getData()
      },
      methods: {
        getData () {
          this.loading = true
          this.axios.get(`afiliados/${this.afiliadoId}`)
            .then((response) => {
              this.afiliado = response.data.data
              this.habilitados = response.data.data.servicios_habilitados.filter(x => x.entidad)
              this.axios.get(`servicios/primarios`)
                .then((responsex) => {
                  this.primarios = responsex.data.data
                  this.loading = false
                })
                .catch(e => {
                  this.loading = false
                  this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los servicios del afiliado. ', error: e})
                })
            })
            .catch(e => {
              this.loading = false
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al consultar los servicios primarios. ', error: e})
            })
        }
      }
    }
</script>

<style scoped>

</style>
