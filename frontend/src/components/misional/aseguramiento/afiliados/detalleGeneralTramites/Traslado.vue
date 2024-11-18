<template>
    <v-card>
      <v-data-table
        :items="items"
        hide-actions
        :headers="headers"
        no-data-text="No hay registros para mostrar"
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
          <td>{{props.item.tramite.observaciones}}</td>
          <td class="text-xs-center">
            <v-dialog
              v-model="dialog"
              width="1200"
              scrollable
            >
              <template v-slot:activator="{ on }">
                <v-tooltip left>
                  <v-btn flat icon slot="activator" color="danger" @click.stop="generarFormTraslado(props.item.as_formtrasmov_id)">
                    <v-icon>far fa-file-pdf</v-icon>
                  </v-btn>
                  <span>Formulario de traslado</span>
                </v-tooltip>
              </template>

              <v-card>
                <v-card-title
                  class="title grey lighten-2 py-1"
                  primary-title
                >
                  Formulario de traslado
                  <v-spacer></v-spacer>
                  <v-btn flat icon color="grey" @click="dialog = false">
                    <v-icon>close</v-icon>
                  </v-btn>
                </v-card-title>
                <object :data="url" width="100%" height="900"></object>
              </v-card>
            </v-dialog>
          </td>
        </template>
      </v-data-table>
      <loading v-model="loading"></loading>
    </v-card>
</template>

<script>
  export default {
    name: 'Afiliacion',
    props: {
      afiliadoId: {
        type: Number,
        default: 0
      }
    },
    data: () => ({
      dialog: false,
      loading: false,
      url: null,
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
      generarFormTraslado (id) {
        this.loading = true
        this.axios.get(`firmar-ruta?nombre_ruta=formulario-tramite-traslado&id=${id}`)
          .then((response) => {
            this.url = response.data
            this.loading = false
            this.dialog = true
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los datos del traslado. ', error: e})
          })
      },
      getData () {
        this.loading = true
        this.axios.get(`traslatramites/${this.afiliadoId}`)
          .then((response) => {
            this.items = response.data.data
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los trámites de traslado. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
