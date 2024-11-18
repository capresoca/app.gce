<template>
    <v-card>
      <v-toolbar dense>
        <v-list-tile class="content-v-list-tile-p0">
          <v-list-tile-avatar color="primary">
            <v-icon class="white--text">{{infoComponent ? infoComponent.icono : ''}}</v-icon>
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title class="font-weight-bold title">Proceso de asignación masiva</v-list-tile-title>
            <v-list-tile-sub-title v-if="proceso && proceso.usuario">{{proceso.usuario.tipo}}</v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
        <template v-if="proceso && proceso.usuario">
          <v-spacer></v-spacer>
          <v-chip class="text-xs-right" label color="white" text-color="red" dark absolute right top>
            <span class="subheading">ID</span>&nbsp;
            <span class="subheading">{{proceso.usuario.id_proceso}}</span>
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
            label="Fecha registro"
            :text="moment(proceso && proceso.usuario.fecha_proceso).format('YYYY-MM-DD HH:mm')"
            prepend-icon="event"
          ></input-detail-flex>
          <input-detail-flex
            flex-class="xs12 sm6 md4 lg3"
            label="Usuario"
            :text="proceso.usuario.name"
            :hint="`${proceso.usuario.email}`"
            prepend-icon="fas fa-user"
          ></input-detail-flex>
        </v-layout>
        <v-layout row wrap>
          <v-flex xs12>
            <v-card>
              <v-subheader class="pa-2 title">
                <v-icon left color="indigo">fas fa-users</v-icon> Afiliados
              </v-subheader>
              <v-divider></v-divider>
              <v-card-text class="pa-1">
                <v-data-table
                  :headers="headersAfiliados"
                  :items="proceso.datos"
                  rows-per-page-text="Registros por página"
                  :rows-per-page-items='[20,50,100,500,1000,2000,{"text":"Todos","value":-1}]'>
                  disable-initial-sort
                  >
                  <template slot="items" slot-scope="props">
                    <td class="text-xs-left">
                      <mini-card-detail :data="props.item.mini_afiliado"></mini-card-detail>
                    </td>
                    <td>
                      {{props.item.grupo_anterior && props.item.grupo_anterior.descrip}}
                    </td>
                    <td>
                      {{props.item.grupo_nuevo && props.item.grupo_nuevo.descrip}}
                    </td>
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
          text: 'Grupo Prestadores Anterior',
          align: 'left',
          sortable: false
        },
        {
          text: 'Grupo Prestadores Posterior',
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
        this.axios.get(`rs_servicios/masivos/${id}`)
          .then((response) => {
            console.log('response rs_servicios/masivos/{id}', response)
            this.proceso = {usuario: response.data.usuario, datos: response.data.datos}
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
