<template>
  <v-card>
    <v-toolbar dense>
      <template v-if="infoComponent">
        <v-icon v-if="infoComponent">{{infoComponent ? infoComponent.icono : ''}}</v-icon>
        <v-toolbar-title>Depuración Glosas</v-toolbar-title>
        <small class="mt-2 ml-1"> Procesos BDUA</small>
        <v-spacer></v-spacer>
<!--        <v-btn-->
<!--          small-->
<!--          color="accent"-->
<!--          @click="$store.commit('NAV_ADD_ITEM',-->
<!--                { ruta: infoComponent.ruta_registro,-->
<!--                  titulo: infoComponent.titulo_registro,-->
<!--                  parametros: {-->
<!--                    entidad: null,-->
<!--                    tabOrigin: $store.state.nav.currentItem.split('tab-')[1]-->
<!--                  }-->
<!--                })"-->
<!--          v-if="infoComponent.permisos.agregar">-->
<!--&lt;!&ndash;          <v-icon>add</v-icon>&ndash;&gt;-->
<!--&lt;!&ndash;          Crear solicitud de autorización&ndash;&gt;-->
<!--        </v-btn>-->
      </template>
    </v-toolbar>
    <v-tabs
      grow
      fixed-tabs
      color="cyan"
      dark
    >
      <v-tabs-slider color="accent"></v-tabs-slider>
      <v-tab href="#tab-1">
        <v-icon left>file_copy</v-icon>
        NS - NC
      </v-tab>
      <v-tab href="#tab-2">
        <v-icon left>file_copy</v-icon>
        MS - MC
      </v-tab>
      <v-tab-item value="tab-1">
        <v-card flat>
          <data-table-v2
            ref="tableNovedades"
            v-model="dataTableDepuracion"
            @resetOption="item => resetOptions(item)"
            @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: infoComponent.ruta_registro, titulo: 'Depuración ' + infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
          >
          </data-table-v2>
        </v-card>
      </v-tab-item>
      <v-tab-item value="tab-2">
        <v-card flat>
        	<depuracion-filtros></depuracion-filtros>
          <!--<data-table-v2
            ref="tableGlosasMS"
            v-model="dataTableMS"
            @resetOption="item => resetOptions(item)"
            @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: 'registroMS', titulo: 'Depuración BDUA', parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
          	:con-columnas="false"
          	:con-registro="false"
          	:con-buscador="false"
          >
          </data-table-v2>
          -->
        </v-card>
      </v-tab-item>
    </v-tabs>
  </v-card>
</template>

<script>
  import DepuracionFiltros from './DepuracionFiltros'
  export default {
    name: 'DepuracionBDUA',
    components: {
      DepuracionFiltros
    },
    data: () => ({
      dataTableDepuracion: {
        nameItemState: 'tableDepuracionNovedades',
        route: 'bduas/depuraciones',
        makeHeaders: [
          {
            text: 'Afiliado',
            align: 'left',
            sortable: false,
            value: 'fecha',
            component: {
              template:
                `<mini-card-detail :data="value.mini_afiliado"></mini-card-detail>`,
              props: ['value']
            }
          },
          {
            text: 'Fecha Nacimiento',
            align: 'center',
            sortable: false,
            value: 'fecha_nacimiento',
            component: {
              template:
                `<span>{{value.fecha_nacimiento}}</span>`,
              props: ['value']
            },
            classData: 'text-xs-center'
          },
          {
            text: 'Ubicación',
            align: 'left',
            sortable: false,
            value: 'municipio',
            component: {
              template:
                `<v-tooltip right>
                      <v-list-tile class="content-v-list-tile-p0 cursor-pointer" slot="activator">
                            <v-list-tile-content class="truncate-content">
                              <v-list-tile-title slot="activator" class="body-2">{{value.municipio}}</v-list-tile-title>
                              <v-list-tile-title class="caption grey--text">{{value.departamento}}</v-list-tile-title>
                            </v-list-tile-content>
                      </v-list-tile>
                      <span>{{value.municipio + ', ' + value.departamento}}</span>
                </v-tooltip>`,
              props: ['value']
            }
          },
          {
            text: 'Estado',
            align: 'center',
            sortable: true,
            value: 'estado_proceso',
            classData: 'text-xs-center',
            component: {
              template:
                `<span>{{(value.estado_proceso)}}</span>`,
              props: ['value']
            }
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
      },
      dataTableMS: {
        nameItemState: 'tableDepuracionMs',
        route: 'bduas/depuracionms',
        makeHeaders: [
          {
            text: 'Afiliado',
            align: 'left',
            sortable: false,
            value: 'fecha',
            component: {
              template:
                `<mini-card-detail :data="value.mini_afiliado"></mini-card-detail>`,
              props: ['value']
            }
          },
          {
            text: 'Fecha Nacimiento',
            align: 'center',
            sortable: false,
            value: 'fecha_nacimiento',
            component: {
              template:
                `<span>{{value.fecha_nacimiento}}</span>`,
              props: ['value']
            },
            classData: 'text-xs-center'
          },
          {
            text: 'Ubicación',
            align: 'left',
            sortable: false,
            value: 'municipio',
            component: {
              template:
                `<v-tooltip right>
                      <v-list-tile class="content-v-list-tile-p0 cursor-pointer" slot="activator">
                            <v-list-tile-content class="truncate-content">
                              <v-list-tile-title slot="activator" class="body-2">{{value.municipio}}</v-list-tile-title>
                              <v-list-tile-title class="caption grey--text">{{value.departamento}}</v-list-tile-title>
                            </v-list-tile-content>
                      </v-list-tile>
                      <span>{{value.municipio + ', ' + value.departamento}}</span>
                </v-tooltip>`,
              props: ['value']
            }
          },
          {
            text: 'Estado',
            align: 'center',
            sortable: true,
            value: 'estado_proceso',
            classData: 'text-xs-center',
            component: {
              template:
                `<span>{{(value.estado_proceso)}}</span>`,
              props: ['value']
            }
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
        return this.$store.getters.getInfoComponent('depuracionBDUA')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && item.estado_proceso === 'Pendiente NEG') item.options.push({event: 'editar', color: 'accent', icon: 'fas fa-stream', tooltip: 'Edición de Novedad'})
        return item
      }
    }
  }
</script>

<style scoped>

</style>
