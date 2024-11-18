<template>
  <v-card>
    <toolbar-list :info="infoComponent" title= "Auditoría Prestaciones Económicas" subtitle="Registro y gestión"/>
    <!-- <toolbar-list :info="infoComponent" btnplus btnplustitle="Agregar incapacidad" title= "Incapacidad" subtitle="Registro y gestión"/> -->
    <data-table
      v-model="dataTableIncapacidad"
      ref="childComponent"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Gestionar auditoría', parametros: {afiliado: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      @modificar="item => $store.commit('NAV_ADD_ITEM', { ruta: 'modificarSoportesAuditoria', titulo: 'Editar soportes', parametros: {afiliado: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
    <confirmar :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)" />
  </v-card>
</template>
<script>
  import ToolbarList from '@/components/general/ToolbarList'
  import DataTable from '@/components/general/DataTable'
  import Confirmar from '@/components/general/Confirmar2'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'AuditoriaPrestacionEconomica',
    components: {
      ToolbarList,
      DataTable,
      Confirmar
    },
    data () {
      return {
        fechas: {},
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        dataTableIncapacidad: {
          nameItemState: 'itemLicencia',
          route: 'licencias',
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
              text: 'Solicitud',
              align: 'left',
              sortable: false,
              value: 'solicitud'
            },
            {
              text: 'Fecha Solicitud',
              align: 'left',
              sortable: false,
              value: 'fecha_solicitud'
            },
            {
              text: 'Identificación',
              align: 'left',
              sortable: false,
              value: 'identificacion'
            },
            {
              text: 'Afiliado',
              align: 'left',
              sortable: false,
              value: 'afiliado'
            },
            {
              text: 'Aportante',
              align: 'left',
              sortable: false,
              value: 'aportante'
            },
            {
              text: 'Tipo Incapacidad',
              align: 'left',
              sortable: false,
              value: 'tipo_incapacidad',
              component: {
                component: {
                  template: `
                    <div>
                      {{value.tipo_incapacidad|tipo}}
                    </div>
                  `,
                  props: ['value'],
                  filters: {
                    tipo: function (val) {
                      if (val === 2) {
                        return 'Incapacidad Enfermedad General'
                      } else if (val === 4) {
                        return 'Incapacidad por Accidente de Trabajo'
                      } else if (val === 5) {
                        return 'Incapacidad por Accidente de Tránsito'
                      } else if (val === 7) {
                        return 'Licencia de Maternidad'
                      } else if (val === 8) {
                        return 'Licencia de Paternidad'
                      } else if (val === 17) {
                        return 'Licencia Fallecimiento Madre'
                      }
                    }
                  }
                }
              }
            },
            {
              text: 'Fecha Inicio',
              align: 'left',
              sortable: true,
              value: 'fecha_inicio'
            },
            {
              text: 'Fecha Fin',
              align: 'left',
              sortable: true,
              value: 'fecha_fin'
            },
            {
              text: 'Estado',
              align: 'left',
              sortable: true,
              value: 'estado'
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'justify-center layout px-0'
            }
          ]
        }
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        item.permisos = this.infoComponent ? this.infoComponent : null
        if (this.infoComponent && this.infoComponent.permisos.agregar) {
          item.options.push({event: 'editar', icon: 'edit', tooltip: 'Gestionar'})
          item.options.push({event: 'modificar', icon: 'line_weight', tooltip: 'Modificar'})
        }
        return item
      },
      eliminar (item) {
        this.dialogA.content = '¿Está seguro de eliminar?'
        // this.dialogA.registroID = item.empleado
        // this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroID = null
      },
      confirmaAnulacion (motivo) {
        this.axios.delete('empleadosc/eliminar/' + this.dialogA.registroID)
          .then(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Se elimino el empleado correctamente.', color: 'success'})
            this.$refs.childComponent.reloadPage()
            this.cancelaAnulacion()
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar anular el trámite de afiliación. ', error: e})
          })
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('auditoriaprestaciones')
      }
    }
  }
</script>
