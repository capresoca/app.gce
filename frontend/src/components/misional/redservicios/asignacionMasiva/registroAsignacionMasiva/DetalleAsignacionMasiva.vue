<template>
  <v-card>
    <v-toolbar dense>
      <v-list-tile class="content-v-list-tile-p0">
        <v-list-tile-avatar color="primary">
          <v-icon class="white--text">{{infoComponent ? infoComponent.icono : ''}}</v-icon>
        </v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title class="font-weight-bold title">Proceso de asignación masiva</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <template v-if="proceso">
        <v-spacer></v-spacer>
        <v-chip class="text-xs-right" label color="white" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading">{{proceso.id}}</span>
        </v-chip>
      </template>
    </v-toolbar>
    <loading v-model="loading"></loading>
    <v-container
      v-if="proceso"
      fluid
      grid-list-md
    >
      <v-layout row wrap>
        <input-detail-flex
          flex-class="xs12 sm6 md4 lg3"
          label="Tipo de proceso"
          :text="proceso.tipo_proceso"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm6 md4 lg3"
          label="Fecha registro"
          :text="moment(proceso.created_at.date).format('YYYY-MM-DD HH-mm')"
          prepend-icon="event"
        ></input-detail-flex>
        <input-detail-flex
          flex-class="xs12 sm6 md4 lg3"
          label="Usuario"
          :text="proceso.usuario.name"
          :hint="`<i class='fas fa-phone-alt'></i> ${proceso.usuario.phone}`"
          prepend-icon="fas fa-user"
        ></input-detail-flex>
      </v-layout>
      <v-layout row wrap>
        <v-flex xs12 sm5 md5 lg4>
          <v-card>
            <v-subheader class="pa-1">
              <v-icon color="indigo" class="mr-1">widgets</v-icon> Servicios asignados
            </v-subheader>
            <v-card-text class="pa-1">
              <v-layout row wrap>
                <template v-if="proceso.tipo_proceso === 'Trasladar servicios'">
                  <v-flex xs12>
                    <v-card color="purple darken-2" class="white--text px-2 py-1">
                      <div class="body-1">{{proceso.servicio_anterior.servicio.codigo}} - {{proceso.servicio_anterior.servicio.nombre}}</div>
                    </v-card>
                    <v-flex xs12>
                      <v-card color="cyan darken-2" class="white--text px-2 py-1">
                        <div class="body-1">I.P.S. DE ORIGEN</div>
                        <v-divider></v-divider>
                        <div class="caption font-weight-light">{{proceso.servicio_anterior.ips.nombre}}</div>
                        <div class="caption"><v-icon size="small">fas fa-map-marker-alt</v-icon> {{proceso.servicio_anterior.ips.municipio.nombre}} - {{proceso.servicio_anterior.ips.municipio.departamento.nombre}} <v-icon size="small">fas fa-map-signs</v-icon> {{proceso.servicio_anterior.ips.direccion_sede}}</div>
                        <div class="caption"><v-icon size="small">fas fa-phone-alt</v-icon> {{proceso.servicio_anterior.ips.telefono_sede}}</div>
                      </v-card>
                    </v-flex>
                    <v-flex xs12>
                      <v-card color="cyan darken-2" class="white--text px-2 py-1">
                        <div class="body-1">I.P.S. DE DESTINO</div>
                        <v-divider></v-divider>
                        <div class="caption font-weight-light">{{proceso.servicios_asignados[0].ips.nombre}}</div>
                        <div class="caption"><v-icon size="small">fas fa-map-marker-alt</v-icon> {{proceso.servicios_asignados[0].ips.municipio.nombre}} - {{proceso.servicios_asignados[0].ips.municipio.departamento.nombre}} <v-icon size="small">fas fa-map-signs</v-icon> {{proceso.servicios_asignados[0].ips.direccion_sede}}</div>
                        <div class="caption"><v-icon size="small">fas fa-phone-alt</v-icon> {{proceso.servicios_asignados[0].ips.telefono_sede}}</div>
                      </v-card>
                    </v-flex>
                  </v-flex>
                </template>
                <template v-else v-for="(item, index) in proceso.servicios_asignados">
                  <v-flex xs12 :key="'card' + index">
                    <v-card color="cyan darken-2" class="white--text px-2 py-1">
                      <div class="body-1">{{item.servicio.codigo}} - {{item.servicio.nombre}}</div>
                      <v-divider></v-divider>
                      <div class="caption font-weight-light">{{item.ips.nombre}}</div>
                      <div class="caption"><v-icon size="small">fas fa-map-marker-alt</v-icon> {{item.ips.municipio.nombre}} - {{item.ips.municipio.departamento.nombre}} <v-icon size="small">fas fa-map-signs</v-icon> {{item.ips.direccion_sede}}</div>
                      <div class="caption"><v-icon size="small">fas fa-phone-alt</v-icon> {{item.ips.telefono_sede}}</div>
                    </v-card>
                  </v-flex>
                </template>
              </v-layout>
            </v-card-text>
          </v-card>
        </v-flex>
        <v-flex xs12 sm7 md7 lg8>
          <v-card>
            <v-subheader class="pa-1">
              <v-icon color="indigo" class="mr-1">fas fa-users</v-icon> Afiliados
            </v-subheader>
            <v-card-text class="pa-1">
              <v-data-table
                :headers="headersAfiliados"
                :items="proceso.afiliados"
                rows-per-page-text="Registros por página"
                :rows-per-page-items='[20,50,100,500,1000,2000,{"text":"Todos","value":-1}]'>
                disable-initial-sort
                >
                <template slot="items" slot-scope="props">
                  <td class="text-xs-left">
                    <mini-card-detail :data="props.item.mini_afiliado"></mini-card-detail>
                  </td>
                  <td>
                    {{props.item.fecha_afiliacion ? moment(props.item.fecha_afiliacion).format('YYYY-MM-DD') : ''}}
                  </td>
                </template>
                <template slot="pageText" slot-scope="props">
                  {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-card>
</template>
<script>
  export default {
    name: 'DetalleAsignacionMasiva',
    props: ['parametros'],
    components: {
      InputDetailFlex: () => import('@/components/general/InputDetailFlex')
    },
    data: () => ({
      proceso: null,
      loading: false,
      headersAfiliados: [
        {
          text: 'Afiliado',
          align: 'left',
          sortable: false
        },
        {
          text: 'Fecha afiliación',
          align: 'left',
          sortable: false
        }
      ]
    }),
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('asignacionmasiva')
      }
    },
    watch: {
    },
    created () {
      this.getData(this.parametros.entidad.id)
    },
    methods: {
      getData (id) {
        this.loading = true
        this.axios.get(`redservicios/asignamasivos/${id}`)
          .then((response) => {
            this.proceso = response.data.data
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los datos del proceso de asignación masiva. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
