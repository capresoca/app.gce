<template>
  <v-card>
    <cancelar-portabilidad ref="cancelarPortabilidad" @cancelar="datos => aceptarCancelacion(datos)"></cancelar-portabilidad>
    <toolbar-list :info="infoComponent" title="Portabilidad" subtitle="Registro y gestión" btnplus btnplustitle="Crear portabilidad"></toolbar-list>
    <data-table-v2
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @cancel="item => cancelar(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: `Edición portabilidad No. ${item.consecutivo}`, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      @verformato="item => impresion(item)"
    ></data-table-v2>
  </v-card>
</template>
<script>
  export default {
    name: 'Portabilidad',
    components: {CancelarPortabilidad: () => import('./CancelarPortabilidad')},
    data: () => ({
      dataTable: {
        nameItemState: 'tablePortabilidad',
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
                        <v-list-tile-title class="body-2">{{value.name}}</v-list-tile-title>
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
            singlesActions: false,
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
        if (this.infoComponent && this.infoComponent.permisos.agregar && estado === `Registrado`) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        item.options.push({event: 'verformato', icon: 'far fa-file-pdf', color: 'red', tooltip: 'Formato portabilidad'})
        if (estado === `Aceptado` || estado === `Registrado`) {
          item.options.push({event: 'cancel', icon: 'fas fa-times-circle', color: 'red', tooltip: 'Cancelar'})
        }
        return item
      },
      impresion (item) {
        this.filePrint(`solicitud-portabilidad-pdf&id=${item.id}`)
      },
      cancelar (item) {
        this.$refs.cancelarPortabilidad.assign(JSON.parse(JSON.stringify(item)))
      },
      aceptarCancelacion (datos) {
        this.axios.post(`cancelar_portabilidad/${datos.rs_portabilidade_id}`, datos)
          .then(res => {
            this.$store.commit('SNACK_SHOW', {msg: 'Se cancela la portabilidad #' + res.data.data.consecutivo, color: 'success'})
            this.$store.commit('reloadTable', 'tablePortabilidad')
          }).catch((e) => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ` al cancelar la portabilidad. `, error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
