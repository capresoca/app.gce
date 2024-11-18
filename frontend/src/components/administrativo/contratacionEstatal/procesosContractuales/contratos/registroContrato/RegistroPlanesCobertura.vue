<template>
  <v-card-text>
    <v-layout row wrap id="flag-top">
      <loading v-model="loading" />
      <v-flex xs12>
        <v-card>
          <v-toolbar dense class="elevation-0">
            <v-toolbar-title>Planes de Cobertura</v-toolbar-title>
            <small class="mt-2 ml-1"> Registro y gestión</small>
            <template v-if="infoComponent && infoComponent.permisos.agregar">
              <v-spacer/>
              <v-tooltip top>
                <v-btn
                  slot="activator"
                  fab
                  right
                  small
                  color="accent"
                  @click="$store.commit('NAV_ADD_ITEM',
        { ruta: infoComponent.ruta_registro,
          titulo: infoComponent.titulo_registro,
          parametros: {
            entidad: null,
            contrato: value,
            tabOrigin: $store.state.nav.currentItem.split('tab-')[1]
          }
        })"
                >
                  <v-icon>add</v-icon>
                </v-btn>
                <span>Crear plan de cobertura</span>
              </v-tooltip>
            </template>
          </v-toolbar>
          <data-table
            v-if="dataTable.route"
            :filters="false"
            ref="tablaPPlanesCobertura"
            v-model="dataTable"
            @resetOption="item => resetOptions(item)"
            @eliminar="item => eliminar(item)"
            @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, contrato: value, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
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
      </v-flex>
    </v-layout>
  </v-card-text>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST, SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroPlanesCobertura',
    props: ['value'],
    components: {
      Loading,
      DataTable: () => import('@/components/general/DataTable'),
      Confirmar: () => import('@/components/general/Confirmar')
    },
    data: () => ({
      loading: false,
      dialogA: {
        loading: false,
        visible: false,
        content: null,
        registro: null
      },
      dataTable: {
        simplePaginate: true,
        nameItemState: 'itemPlanCobertura',
        route: null,
        makeHeaders: [
          {
            text: 'Nombre',
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
    }),
    watch: {
      'itemPlanCobertura' (val) {
        if (this.value && this.value.id && this.$refs && this.$refs.tablaPPlanesCobertura && val && val.item && (val.item.ce_proconminuta_id === this.value.id)) {
          this.$refs.tablaPPlanesCobertura.reloadPage()
        }
      },
      'value' (val) {
        if (val && this.$refs.tablaPPlanesCobertura) this.$refs.tablaPPlanesCobertura.reloadPage()
      }
    },
    computed: {
      itemPlanCobertura () {
        return this.$store.state.tables.itemPlanCobertura
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('planesCobertura')
      }
    },
    created () {
      this.dataTable.route = `contratos?ce_proconminuta_id=${this.value.id}`
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
            this.$store.commit(SNACK_SHOW, {msg: 'El plan de cobertura se ha eliminado correctamente.', color: 'success'})
            this.$refs.tablaPPlanesCobertura.reloadPage()
          })
          .catch(e => {
            this.dialogA.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al realizar la eliminación del plan de cobertura. ', error: e})
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
