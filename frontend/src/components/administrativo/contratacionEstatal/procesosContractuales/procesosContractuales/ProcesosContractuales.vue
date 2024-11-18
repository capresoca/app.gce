<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Procesos contractuales" subtitle="Registro y gestión" btnplus btnplustitle="Crear proceso" />
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      @copiaEstudioPrevio="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, copiar: true, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
    />
  </v-card>
</template>

<script>
  import DataTable from '@/components/general/DataTable'
  import ToolbarList from '@/components/general/ToolbarList'
  export default {
    name: 'ProcesosContractuales',
    components: {
      ToolbarList,
      DataTable
    },
    data () {
      return {
        dataTable: {
          nameItemState: 'itemProcesoContractual',
          route: 'ce_procontractuales',
          expand: {
            component: {
              template:
                `<v-card>
                  <v-subheader>Objeto contractual:</v-subheader>
                  <v-card-text class="pt-0 pb-1" v-html="value.objeto">
                  </v-card-text>
                </v-card>`,
              props: ['value']
            }
          },
          optionsPerPage: [
            {
              text: 15,
              value: 15
            },
            {
              text: 50,
              value: 50
            },
            {
              text: 100,
              value: 100
            }
          ],
          makeHeaders: [
            {
              text: 'Consecutivo',
              align: 'center',
              sortable: true,
              value: 'consecutivo',
              classData: 'text-xs-center',
              tooltip: 'Consecutivo'
            },
            {
              text: 'Dependencia',
              align: 'left',
              sortable: true,
              value: 'dependencia',
              component: {
                component: {
                  template:
                    `
                    <div>
                      <span>{{value.dependencia}}</span>
                      <v-chip small color="primary" v-if="value.es_servicio_salud">
                        Salud
                      </v-chip>
                    </div>
                    `,
                  props: ['value']
                }
              }
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
              singlesActions: true,
              width: '120px',
              classData: 'text-xs-center'
            }
          ],
          filters: []
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('procesosContractuales')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'copiaEstudioPrevio', icon: 'fas fa-copy', color: 'success', tooltip: 'Copiar a estudio previo'})
        return item
      }
    }
  }
</script>

<style scoped>

</style>
