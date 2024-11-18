<template>
  <v-card>
    <v-toolbar dense class="py-1">
      <v-list-tile class="content-v-list-tile-p0">
        <v-list-tile-avatar color="primary">
          <v-icon class="white--text">{{infoComponent ? infoComponent.icono : ''}}</v-icon>
        </v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title class="font-weight-bold title">Grupos de IPS</v-list-tile-title>
          <v-list-tile-sub-title>Registro y Gestión</v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>
      <v-spacer></v-spacer>
      <v-tooltip left v-if="infoComponent.permisos.agregar">
        <v-btn flat icon color="pink" @click.stop="crearGrupo" slot="activator">
          <v-icon>add</v-icon>
        </v-btn>
        <span>Crear Grupo</span>
      </v-tooltip>
    </v-toolbar>
    <data-table-v2
      ref="tableGruposIPS"
      v-model="dataTable"
      @resetOption="itemGrupo => resetOptions(itemGrupo)"
      @editar="itemGrupo => editarGrupo(itemGrupo)"
    >
    </data-table-v2>
    <registro-grupo-i-p-s ref="registroGrupo" :icon="infoComponent ? infoComponent.icono : ''"></registro-grupo-i-p-s>
  </v-card>
</template>

<script>
  export default {
    name: 'GruposIPS',
    components: {
      RegistroGrupoIPS: () => import('@/components/misional/redservicios/gruposIPS/RegistroGrupoIPS')
    },
    data: () => ({
      dataTable: {
        nameItemState: 'tableGruposIPS',
        route: 'rs_servicios/grupos_ips',
        makeHeaders: [
          {
            text: 'Id',
            align: 'center',
            sortable: false,
            value: 'id',
            classData: 'text-xs-center'
          },
          {
            text: 'Portabilidad',
            align: 'center',
            sortable: false,
            value: 'portable',
            classData: 'text-xs-center',
            component: {
              template:
                `<span>{{value.portable ? 'SI' : 'NO'}}</span> `,
              props: ['value']
            }
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descrip'
          },
          {
            text: 'Municipio',
            align: 'left',
            sortable: false,
            component: {
              template:
                `
                      <v-list-tile class="content-v-list-tile-p0">
                        <v-list-tile-content>
                          <v-list-tile-title class="body-2">{{value.municipio.nombre}}</v-list-tile-title>
                          <v-list-tile-sub-title class="caption grey--text">{{value.municipio.departamento.nombre}}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    `,
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
        return this.$store.getters.getInfoComponent('gruposIPS')
      }
    },
    methods: {
      crearGrupo (item) {
        this.$refs.registroGrupo.open()
      },
      editarGrupo (item) {
        this.$refs.registroGrupo.open(item)
      },
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'fas fa-pen', tooltip: 'Editar'})
        return item
      }
    }
  }
</script>

<style scoped>

</style>
