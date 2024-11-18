<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Afiliados" subtitle="gestión"/>
    <v-tabs
      grow
      fixed-tabs
      color="cyan"
      dark
    >
      <v-tabs-slider color="accent"></v-tabs-slider>
      <v-tab href="#tab-1">
        <v-icon left>fas fa-clipboard-list</v-icon>
        Incidencias
      </v-tab>
      <v-tab href="#tab-2">
        <v-icon left>fas fa-users</v-icon>
        Afiliados
      </v-tab>
      <v-tab-item value="tab-1">
        <v-card flat>
          <incidencias></incidencias>
        </v-card>
      </v-tab-item>
      <v-tab-item value="tab-2">
        <all-afiliados></all-afiliados>
<!--        <h1>DIVISIÓN</h1>-->
<!--        <v-card flat>-->
<!--          <data-table-->
<!--            v-model="dataTable"-->
<!--            @resetOption="item => resetOptions(item)"-->
<!--            @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"-->
<!--            @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"-->
<!--          />-->
<!--        </v-card>-->
      </v-tab-item>
    </v-tabs>
  </v-card>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  import AllAfiliados from './AllAfiliados'
  export default {
    name: 'Afiliados',
    components: {
      AllAfiliados,
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Incidencias: () => import('@/components/misional/aseguramiento/afiliados/incidencias/Incidencias'),
      DataTable
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemAfiliado',
          route: 'afiliados?estado_afiliado:codigo=!PR',
          makeHeaders: [
            {
              text: 'Afiliado',
              align: 'left',
              sortable: false,
              value: 'identificacion',
              component: {
                component: {
                  template:
                    `<mini-card-detail :data="value.mini_afiliado"/>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Ficha SISBEN',
              align: 'left',
              sortable: false,
              value: 'ficha_sisben'
            },
            {
              text: 'Puntaje SISBEN',
              align: 'center',
              sortable: true,
              value: 'puntaje_sisben',
              classData: 'text-xs-center'
            },
            {
              text: 'Régimen',
              align: 'left',
              sortable: true,
              value: 'regimen'
            },
            {
              text: 'Estado',
              align: 'center',
              sortable: true,
              value: 'estado',
              classData: 'text-xs-center'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'justify-center layout px-0'
            }
          ],
          filters: [
            {
              label: 'Puntaje sisben',
              type: 'v-range',
              items: {
                type: 'number',
                range: true,
                itemMin: {
                  label: 'Mínimo',
                  vModel: null,
                  clearable: true,
                  value: 'puntaje_sisben'
                },
                itemMax: {
                  label: 'Maximo',
                  vModel: null,
                  clearable: true,
                  value: 'puntaje_sisben'
                }
              }
            }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('afiliados')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'detalle', icon: 'find_in_page', tooltip: 'Ver detalle'})
        return item
      }
    }
  }
</script>

<style scoped>
</style>
