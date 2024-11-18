<template>
  <v-card>
    <toolbar-list
      :info="infoComponent"
      title="Asignación Masiva"
      subtitle="Procesos de asignación masiva"
      btnplus
      btnplustitle="Crear proceso de asignación"
    ></toolbar-list>
    <data-table-v2
      ref="tableAsignacionMasiva"
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @anular="item => anular(item)"
      @verdetalle="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleAsignacionMasiva', titulo: `detalle asignación masiva: ${item.id}`, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
    ></data-table-v2>
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
  export default {
    name: 'AsignacionesMasivas',
    components: {
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
          nameItemState: 'tableAsignacionMasiva',
          route: 'redservicios/asignamasivos',
          makeHeaders: [
            {
              text: 'ID',
              align: 'center',
              sortable: true,
              value: 'id',
              classData: 'text-xs-center'
            },
            {
              text: 'Tipo proceso',
              align: 'left',
              sortable: true,
              value: 'tipo_proceso'
            },
            {
              text: 'Fecha Registro',
              align: 'left',
              sortable: false,
              classData: 'text-xs-left',
              component: {
                template:
                  `<span>{{value.created_at ? moment(value.created_at.date).format('YYYY-MM-DD HH:mm') : ''}}</span>`,
                props: ['value']
              }
            },
            {
              text: 'Observacion',
              align: 'left',
              sortable: false,
              value: 'observacion',
              classData: 'text-xs-left'
            },
            {
              text: 'Usuario',
              align: 'left',
              sortable: true,
              value: 'id',
              component: {
                template:
                  `
                      <v-list-tile class="content-v-list-tile-p0">
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.usuario.email}}</v-list-tile-title>
                          <v-list-tile-title class="caption grey--text">{{value.usuario.name}}</v-list-tile-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    `,
                props: ['value']
              }
            },
            {
              text: 'Servicios asignados',
              align: 'center',
              sortable: false,
              value: 'id',
              classData: 'text-xs-center',
              width: '120px',
              component: {
                template:
                  `<v-tooltip top>
                      <v-avatar color="teal" size="30" slot="activator" class="cursor-pointer">
                          <span class="white--text caption">{{value.services_tot}}</span>
                        </v-avatar>
                      <span>{{ value.servicios_asignados.map(x => x.nombre).join(', ')}}</span>
                    </v-tooltip>`,
                props: ['value']
              }
            },
            {
              text: 'Cantidad afiliados',
              align: 'center',
              sortable: true,
              value: 'total_registros',
              classData: 'text-xs-center',
              width: '120px',
              component: {
                template: `
                    <v-chip label color="indigo" dark>
                      <span class="white--text caption">{{value.afiliados_con_servicios}}</span>
                    </v-chip>
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
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('asignacionmasiva')
      }
    },
    methods: {
      anular (item) {
        this.dialogA.registro = JSON.parse(JSON.stringify(item))
        this.dialogA.content = `<strong>El proceso de asignación masiva No. ${this.dialogA.registro.id} será anulado.</strong><br/> ¿Está seguro de continuar?`
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
        this.axios.delete(`asignacionmasiva/${this.dialogA.registro.id}`)
          .then(() => {
            this.$store.commit('SNACK_SHOW', {msg: 'El proceso de asignación masiva se anuló correctamente.', color: 'success'})
            this.$store.commit('ASIGNACION_MASIVA_RELOAD_ITEM', {item: this.dialogA.registro, estado: 'reload', key: null})
            this.cancelaConfirmacion()
          })
          .catch(e => {
            this.dialogA.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al realizar la anulación del proceso de asignación masiva. ', error: e})
          })
      },
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'verdetalle', icon: 'find_in_page', tooltip: 'Ver detalle', color: 'primary'})
        // if (this.infoComponent && this.infoComponent.permisos.anular && this.$refs.tableAsignacionMasiva.returnIndex(item)item.estado === 'Registrado') item.options.push({event: 'eliminar', icon: 'delete_forever', tooltip: 'Eliminar', color: 'error'})
        return item
      }
    }
  }
</script>

<style scoped>
</style>
