<template>
  <v-card>
    <v-dialog
      v-model="dialogDetalle.visible"
      width="800"
    >
      <v-card>
        <v-card-title
          class="title grey lighten-2 py-1"
          primary-title
        >
          Detalle de novedad
          <v-spacer></v-spacer>
          <v-btn flat icon color="grey" @click="dialogDetalle.visible = false">
            <v-icon>close</v-icon>
          </v-btn>
        </v-card-title>

        <v-card-text>
          <detalle-novedad v-model="novedad"/>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            flat
            @click="dialogDetalle.visible = false"
          >
            Cerrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    
    <confirmar :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)" />
    <toolbar-list :info="infoComponent" title="Trámites de novedad" subtitle="Registro y gestión" btnplus btnplustitle="Crear trámite" />
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @imprimir="item => imprimir(item)"
      @anular="item => registroAnular(item)"
      @detalle="item => verDetalle(item)"
    />
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import DetalleNovedad from '@/components/misional/aseguramiento/tramites/novedades/DetalleNovedad'
  import Confirmar from '@/components/general/Confirmar'
  import DataTable from '@/components/general/DataTable'
  export default {
    name: 'Novedades',
    components: {
      DetalleNovedad,
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Confirmar,
      DataTable
    },
    data () {
      return {
        novedad: null,
        dialogDetalle: {
          visible: false,
          registroId: null
        },
        dialogA: {
          visible: false,
          content: null,
          registroId: null
        },
        dataTable: {
          nameItemState: 'itemNovtramite',
          route: 'novtramites',
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
            { text: 'Tipo novedad',
              align: 'center',
              sortable: false,
              value: 'novedad.codigo',
              classData: 'text-xs-center',
              component: {
                component: {
                  template:
                    `<v-tooltip top>
                    <v-avatar slot="activator" color="primary" size="34">
                      <span class="white--text caption">{{value.novedad.codigo}}</span>
                    </v-avatar>
                      <span>{{value.novedad.descripcion}}</span>
                  </v-tooltip>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'Afiliado',
              align: 'left',
              sortable: false,
              value: 'identificacion',
              classData: '',
              component: {
                component: {
                  template: `<mini-card-detail :data="value.mini_afiliado"/>`,
                  props: ['value']
                }
              }
            },
            // {
            //   text: 'Identificación afiliado',
            //   align: 'left',
            //   sortable: false,
            //   value: 'identificacion',
            //   classData: ''
            // },
            // {
            //   text: 'Nombre afiliado',
            //   align: 'left',
            //   sortable: true,
            //   value: 'afiliado',
            //   classData: ''
            // },
            {
              text: 'Estado',
              align: 'center',
              sortable: false,
              value: 'estado',
              classData: 'text-xs-center'
            },
            {
              text: 'Radicación',
              align: 'left',
              sortable: true,
              value: 'fecha_radicacion',
              classData: ''
            },
            {
              text: 'Opciones',
              align: 'center',
              sortable: false,
              actions: true,
              classData: 'justify-center layout px-0'
            }
          ],
          filters: [
            {
              label: 'Fecha radicación',
              type: 'v-range',
              items: {
                menuDate: false,
                type: 'date',
                range: true,
                itemMin: {
                  label: 'Inicial',
                  vModel: null,
                  clearable: true,
                  value: 'fecha_radicacion'
                },
                itemMax: {
                  menuDate: false,
                  label: 'Final',
                  vModel: null,
                  clearable: true,
                  value: 'fecha_radicacion'
                }
              }
            }
          ]
        }
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('tramitesNovedad')
      },
      complementos () {
        return store.getters.complementosTablaNovedades
      }
    },
    methods: {
      verDetalle (item) {
        if (this.novedad && (item.id === this.novedad.id)) {
          this.dialogDetalle.visible = true
        } else {
          this.axios.get('novtramites/' + item.id)
            .then(response => {
              if (response.data !== '') {
                this.novedad = response.data.data
                this.dialogDetalle.visible = true
              }
            })
            .catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el detalle de la novedad. ', error: e})
            })
        }
      },
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'detalle', icon: 'find_in_page', tooltip: 'Ver detalle'})
        if (this.infoComponent && this.infoComponent.permisos.imprimir) item.options.push({event: 'imprimir', icon: 'far fa-file-pdf', tooltip: 'Imprimir'})
        if (this.infoComponent && this.infoComponent.permisos.anular && (item.estado === 'Registrado' || item.estado === 'Radicado')) item.options.push({event: 'anular', icon: 'delete', tooltip: 'Anular'})
        return item
      },
      imprimir (item) {
        // console.log(item.mini_afiliado.id)
        // window.open(this.axios.defaults.baseURL + '/novtramites/pdf/' + item.id, '_blank')
        this.axios.get('novedadtramites-pdf?id=' + item.id)
          .then(response => {
            if (response.data !== '') {
              window.open(response.data, '_blank')
            }
            // console.log(response.data)
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al generar el documento. ', error: e})
          })
      },
      registroAnular (item) {
        this.dialogA.content = '¿Está seguro de anular la novedad de <strong>' + item.afiliado + '</strong>?'
        this.dialogA.registroId = item.id
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroId = null
      },
      confirmaAnulacion (motivo) {
        this.axios.put('novtramites/' + this.dialogA.registroId, {estado: 'Anulado', detalle_anulacion: motivo})
          .then(() => {
            this.dataTable.items.find(x => x.id === this.dialogA.registroId).estado = 'Anulado'
            this.resetOptions(this.dataTable.items.find(x => x.id === this.dialogA.registroId))
            this.$store.commit(SNACK_SHOW, {msg: 'El trámite de novedad se anuló satisfactoriamente.', color: 'success'})
            this.cancelaAnulacion()
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar anular el trámite de novedad. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
