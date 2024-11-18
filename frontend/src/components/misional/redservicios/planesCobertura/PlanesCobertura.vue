<template>
  <v-card>
    <toolbar-list :info="infoComponent" title="Planes de Cobertura" subtitle="Registro y gestión" btnplus btnplustitle="Crear plan de cobertura" />
    <data-table
      ref="tablaPPlanesCobertura"
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @eliminar="item => eliminar(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
    />
    <confirmar
      :loading="dialogA.loading"
      :value="dialogA.visible"
      :content="dialogA.content"
      @cancelar="cancelaConfirmacion"
      :requiere-motivo="false"
      @aceptar="aceptaConfirmacion"
    />
  </v-card>
</template>

<script>
  import {SNACK_ERROR_LIST, SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'PlanesCobertura',
    components: {
      DataTable: () => import('@/components/general/DataTable'),
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Confirmar: () => import('@/components/general/Confirmar')
    },
    data () {
      return {
        dialogA: {
          loading: false,
          visible: false,
          content: null,
          registro: null
        },
        dataTable: {
          nameItemState: 'itemPlanCobertura',
          route: 'contratos',
          makeHeaders: [
            {
              text: 'Entidad',
              align: 'left',
              sortable: false,
              value: 'value.contrato.entidad.nombre',
              component: {
                component: {
                  template:
                    `
                      <v-list-tile>
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.contrato.entidad.nombre}}</v-list-tile-title>
                          <v-list-tile-title class="caption grey--text">Contrato: {{value.contrato.numero_contrato}}</v-list-tile-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    `,
                  props: ['value']
                }
              }
            },
            {
              text: 'Nombre Plan',
              align: 'left',
              sortable: false,
              value: 'nombre'
            },
            {
              text: 'Régimen atendido',
              align: 'left',
              sortable: false,
              classData: 'text-xs-left',
              component: {
                component: {
                  template:
                    `
                      <v-list-tile>
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.regimen_atendido === 1 ? 'Contributivo' : 'Subsidiado' }}</v-list-tile-title>
                          <v-list-tile-title class="caption grey--text">Portabilidad: {{value.portabilidad ? 'SI' : 'NO'}}</v-list-tile-title>
                        </v-list-tile-content>
                      </v-list-tile>
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
              classData: 'text-xs-center'
            }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('planesCobertura')
      }
    },
    methods: {
      eliminar (item) {
        this.dialogA.registro = JSON.parse(JSON.stringify(item))
        this.dialogA.content = `<strong>El contrato ${this.dialogA.registro.numero_contrato} con la entidad ${this.dialogA.registro.entidad} será eliminado.</strong><br/> ¿Está seguro de eliminar el contrato?`
        this.dialogA.visible = true
      },
      cancelaConfirmacion () {
        this.dialogA.loading = false
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registro = null
      },
      async aceptaConfirmacion () {
        this.dialogA.loading = true
        this.axios.delete(`contratos/${this.dialogA.registro.id}`)
          .then(() => {
            this.cancelaConfirmacion()
            this.$store.commit(SNACK_SHOW, {msg: 'El contrato se ha eliminado correctamente.', color: 'success'})
            this.$refs.tablaPPlanesCobertura.reloadPage()
          })
          .catch(e => {
            this.dialogA.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al realizar la eliminación del contrato. ', error: e})
          })
      },
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'build', tooltip: 'Gestionar', color: 'primary'})
        if (this.infoComponent && this.infoComponent.permisos.anular && item.estado === 'Registrado') item.options.push({event: 'eliminar', icon: 'delete_forever', tooltip: 'Eliminar', color: 'error'})
        return item
      }
    }
  }
</script>

<style scoped>
</style>
