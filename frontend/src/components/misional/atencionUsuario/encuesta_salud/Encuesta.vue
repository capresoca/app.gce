<template>
  <v-card>
    <toolbar-list :info="infoComponent" btnplus btnplustitle="Encuesta Estado Salud" title="Encuestas del Estado Salud" subtitle="Interviús"/>
    <data-table
      v-model="dataTableReferencia"
      @resetOption="item => resetOptions(item)"
      @imprimir="item => imprimirEncuesta(item.id)"
    />
    <!--@editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"-->
  </v-card>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'Encuesta',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      DataTable
    },
    data () {
      return {
        dataTableReferencia: {
          nameItemState: 'itemEncuesta',
          route: 'au_encuestas',
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
              text: 'Fecha',
              align: 'left',
              sortable: false,
              value: 'fecha'
            },
            {
              text: 'Identificación Afiliado',
              align: 'left',
              sortable: false,
              value: 'afiliado.identificacion_completa'
            },
            {
              text: 'Nombre Afiliado',
              align: 'left',
              sortable: false,
              value: 'afiliado.nombre_completo'
            },
            {
              text: 'Municipio',
              align: 'left',
              sortable: false,
              value: 'municipio.nombre'
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
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('encuestas')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'imprimir', icon: 'far fa-file-pdf', tooltip: 'Imprimir Encuesta'})
        return item
      },
      imprimirEncuesta (item) {
        let id = item
        this.axios.get('firmar-ruta?nombre_ruta=encuesta_salud&id=' + id)
          .then((res) => {
            let url = res.data
            let win = window.open(url, '_blank')
            win.focus()
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al Imprimir. ' + e.response, color: 'danger'})
          })
      }
    }
  }
</script>

<style scoped>

</style>
