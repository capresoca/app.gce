<template>
    <v-card>
      <v-data-table
        :items="items"
        hide-actions
        :headers="headers"
        no-data-text="No hay direccionamientos registrados"
      >
        <template slot="items" slot-scope="props">
          <td>{{props.item.tramite.fecha_radicacion}}</td>
          <td>{{props.item.tramite.estado}}</td>
          <td>{{props.item.tramite.clase_tramite}}</td>
          <td>{{props.item.tramite.tipo_tramite}}</td>
          <td>
            <v-list-tile class="content-v-list-tile-p0" v-if="props.item.tramite.gs_usuario">
              <v-list-tile-content>
                <v-list-tile-title>{{props.item.tramite.gs_usuario.name}}</v-list-tile-title>
                <v-list-tile-sub-title><v-icon size="small">fas fa-phone-alt</v-icon> {{props.item.tramite.gs_usuario.phone}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
          </td>
          <td>{{props.item.observaciones}}</td>
          <td class="text-xs-center">
            <v-dialog
              v-model="dialog"
              width="900"
              scrollable
            >
              <template v-slot:activator="{ on }">
                <v-tooltip left>
                  <v-btn flat icon slot="activator" color="danger" @click.stop="seleccionarNovedad(props.item)">
                    <v-icon>find_in_page</v-icon>
                  </v-btn>
                  <span>Detalle de novedad</span>
                </v-tooltip>
              </template>
              <v-card>
                <v-card-title
                  class="title grey lighten-2 py-1"
                  primary-title
                >
                  Detalle de novedad
                  <v-spacer></v-spacer>
                  <v-btn flat icon color="grey" @click="dialog = false">
                    <v-icon>close</v-icon>
                  </v-btn>
                </v-card-title>
                <v-card-text>
                  <detalle-novedad v-if="novedadSeleccionada" v-model="novedadSeleccionada"/>
                </v-card-text>
              </v-card>
            </v-dialog>
          </td>
        </template>
      </v-data-table>
      <loading v-model="loading"></loading>
    </v-card>
</template>

<script>
  import DetalleNovedad from '@/components/misional/aseguramiento/tramites/novedades/DetalleNovedad'
  export default {
    name: 'Novedades',
    props: {
      afiliadoId: {
        type: Number,
        default: 0
      }
    },
    components: {
      DetalleNovedad
    },
    data: () => ({
      novedadSeleccionada: null,
      dialog: false,
      loading: false,
      items: [],
      headers: [
        {
          text: 'Fecha radicación',
          sortable: false
        },
        {
          text: 'estado',
          sortable: false
        },
        {
          text: 'Clase',
          sortable: false
        },
        {
          text: 'Tipo',
          sortable: false
        },
        {
          text: 'Usuario',
          sortable: false
        },
        {
          text: 'Observaciones',
          sortable: false
        },
        {
          text: '',
          sortable: false,
          align: 'center'
        }
      ]
    }),
    created () {
      this.getData()
    },
    methods: {
      seleccionarNovedad (novedad) {
        this.novedadSeleccionada = novedad
        this.dialog = true
      },
      getData () {
        this.loading = true
        this.axios.get(`novedadtramites/${this.afiliadoId}`)
          .then((response) => {
            this.items = response.data.data
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer las novedades del afiliado. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
