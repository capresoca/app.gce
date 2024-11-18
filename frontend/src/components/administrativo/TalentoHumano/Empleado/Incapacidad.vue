<template>
  <v-card>
    <toolbar-list :info="infoComponent" title= "Recepción de Solicitud Incapacidad" subtitle="Registro y gestión"/>
    <!-- <toolbar-list :info="infoComponent" btnplus btnplustitle="Agregar incapacidad" title= "Incapacidad" subtitle="Registro y gestión"/> -->
    <data-table
      v-model="dataTableIncapacidad"
      ref="childComponent"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: 'Gestionar incapacidad', parametros: {afiliado: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
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
    name: 'Incapacidad',
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
          nameItemState: 'itemIncapacidad',
          route: 'afiliados',
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
              text: 'Identificación',
              align: 'left',
              sortable: false,
              value: 'identificacion'
            },
            {
              text: 'Nombre',
              align: 'left',
              sortable: false,
              value: 'nombre_completo'
            },
            {
              text: 'Fecha nacimiento',
              align: 'left',
              sortable: false,
              value: 'fecha_nacimiento'
            },
            {
              text: 'Edad',
              align: 'left',
              sortable: false,
              value: 'edad'
            },
            {
              text: 'Ubicación',
              align: 'left',
              sortable: false,
              value: 'ubicacion'
            },
            {
              text: 'Estado',
              align: 'left',
              sortable: false,
              value: 'estado'
            },
            {
              text: 'Régimen',
              align: 'left',
              sortable: true,
              value: 'regimen'
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
        return this.$store.getters.getInfoComponent('incapacidad')
      }
    }
  }
</script>
