<template>
  <v-card>
    <cancelar-portabilidad ref="cancelarPortabilidad" @cancelar="datos => aceptarCancelacion(datos)"></cancelar-portabilidad>
    <toolbar-list :info="infoComponent" title="Portabilidad" subtitle="Aprobación"></toolbar-list>
    <data-table-v2
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @cancel="item => cancelar(item)"
      @verdetalle="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detallePortabilidad', titulo: `Detalle portabilidad No. ${item.consecutivo}`, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
    ></data-table-v2>
  </v-card>
</template>

<script>
  import CancelarPortabilidad from './CancelarPortabilidad'
  export default {
    name: 'AprobacionPortabilidad',
    components: {CancelarPortabilidad},
    data: () => ({
      dialog: false,
      dataTable: {
        nameItemState: 'tablePortabilidadAprobacion',
        route: 'portabilidades',
        makeHeaders: [
          {
            text: 'No.',
            align: 'left',
            sortable: false,
            value: 'consecutivo'
          },
          {
            text: 'Afiliado',
            align: 'left',
            sortable: false,
            value: 'afiliado',
            component: {
              template:
                `<mini-card-detail :data="value.afiliado.mini_afiliado"></mini-card-detail>`,
              props: ['value']
            }
          },
          {
            text: 'Origen',
            align: 'left',
            sortable: false,
            value: 'municipio_origen'
          },
          {
            text: 'Destino',
            align: 'left',
            sortable: false,
            value: 'municipio_destino'
          },
          {
            text: 'Fechas',
            align: 'left',
            sortable: false,
            value: 'fecha_inicio',
            component: {
              template:
                `<v-list-tile class="content-v-list-tile-p0">
                        <v-list-tile-content>
                          <v-list-tile-title class="caption black--text">{{'Fecha Inicio: ' + value.fecha_inicio}}</v-list-tile-title>
                          <v-list-tile-title class="caption black--text">{{'Fecha Fin: ' + value.fecha_fin}}</v-list-tile-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    `,
              props: ['value']
            }
          },
          {
            text: 'Usuario',
            align: 'left',
            sortable: false,
            value: 'name',
            component: {
              template:
                `<v-list-tile class="content-v-list-tile-p0">
                        <v-list-tile-content>
                          <v-list-tile-title class="caption font-weight-bold">{{value.name}}</v-list-tile-title>
                          <v-list-tile-title class="caption grey--text">{{value.email}}</v-list-tile-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    `,
              props: ['value']
            }
          },
          {
            text: 'Estado',
            align: 'center',
            sortable: false,
            value: 'estado'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            actions: true,
            singlesActions: true,
            classData: 'text-xs-center'
          }
        ]
      }
    }),
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('portabilidad')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        let estado = JSON.parse(JSON.stringify(item.estado))
        item.options.push({event: 'verdetalle', icon: 'find_in_page', color: 'success', tooltip: 'Ver detalle'})
        if (estado === `Aceptado` || estado === `Registrado`) {
          item.options.push({event: 'cancel', icon: 'fas fa-times-circle', color: 'red', tooltip: 'Cancelar'})
        }
        return item
      },
      cancelar (item) {
        this.$refs.cancelarPortabilidad.assign(JSON.parse(JSON.stringify(item)))
      },
      aceptarCancelacion (datos) {
        this.axios.post(`cancelar_portabilidad/${datos.rs_portabilidade_id}`, datos)
          .then(res => {
            this.$store.commit('SNACK_SHOW', {msg: 'Se cancela la portabilidad #' + res.data.data.consecutivo, color: 'success'})
            this.$store.commit('reloadTable', 'tablePortabilidadAprobacion')
          }).catch((e) => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ` al cancelar la portabilidad. `, error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
