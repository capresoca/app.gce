<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Agregar empleado" title= "Empleado" subtitle="Registro y gestión"/>
    <data-table
      v-model="dataTableAlmacen"
      ref="childComponent"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Editar empleados', parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      @eliminar="item => eliminar(item)"
      @detalleContratos="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleContratosEmpleado', titulo: `Contratos Empleado CC.${item.numero_identificacion}`, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      />
    <confirmar ref="confirmo" :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)" />
  </v-card>
</template>
<script>
  import ToolbarList from '@/components/general/ToolbarList'
  import DataTable from '@/components/general/DataTable'
  import Confirmar from '@/components/general/Confirmar2'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'Empleados',
    components: {
      ToolbarList,
      DataTable,
      Confirmar
    },
    data () {
      return {
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        dataTableAlmacen: {
          nameItemState: 'itemEmpleadoSC',
          route: 'empleadosc',
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
              text: 'Empleado',
              align: 'left',
              sortable: false,
              value: 'empleado'
            },
            {
              text: 'Primer nombre',
              align: 'left',
              sortable: false,
              value: 'primer_nombre'
            },
            {
              text: 'Primer apellido',
              align: 'left',
              sortable: false,
              value: 'primer_apellido'
            },
            {
              text: 'Número de identificación',
              align: 'left',
              sortable: false,
              value: 'numero_identificacion'
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
          item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
          item.options.push({event: 'eliminar', icon: 'delete_forever', tooltip: 'Eliminar'})
        }
        item.options.push({event: 'detalleContratos', icon: 'fas fa-table', tooltip: 'Ver Contratos'})
        return item
      },
      eliminar (item) {
        this.dialogA.content = '¿Está seguro de eliminar?'
        this.dialogA.registroID = item.empleado
        this.dialogA.visible = true
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
            this.$refs.confirmo.pararCarga()
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar anular el trámite de afiliación. ', error: e})
          })
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('scempleados')
      }
    }
  }
</script>
