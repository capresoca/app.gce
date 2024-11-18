<template>
    <v-card>
      <object v-if="items.length === 1" :data="url" width="100%" height="900"></object>
      <v-data-table
        v-else
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
              v-if="props.item.as_formafiliacion_id"
              v-model="dialog"
              width="1200"
              scrollable
            >
              <template v-slot:activator="{ on }">
                <v-tooltip left>
                  <v-btn flat icon slot="activator" color="danger" @click.stop="generarFormAfiliacion(props.item.as_formafiliacion_id)">
                    <v-icon>far fa-file-pdf</v-icon>
                  </v-btn>
                  <span>Formulario de afiliación</span>
                </v-tooltip>
              </template>

              <v-card>
                <v-card-title
                  class="headline grey lighten-3"
                  primary-title
                >
                  Formulario de afiliación
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
      generarFormAfiliacion (id) {
        this.loading = true
        this.axios.get(`ruta-pdf-formafiliacion?id=${id}`)
          .then((response) => {
            this.url = response.data
            this.loading = false
            this.dialog = true
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los datos de la afiliación. ', error: e})
          })
      },
      getData () {
        this.loading = true
        this.axios.get(`afitramites/${this.afiliadoId}`)
          .then((response) => {
            this.items = response.data.data
            this.loading = false
            if (this.items.length === 1) {
              this.loading = true
              this.axios.get(`ruta-pdf-formafiliacion?id=${this.items[0].as_formafiliacion_id}`)
                .then((response) => {
                  this.url = response.data
                  this.loading = false
                })
                .catch(e => {
                  this.loading = false
                  this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los datos de la afiliación. ', error: e})
                })
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los trámites de afiliación. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
