<template>
  <v-card>
    <v-toolbar dense class="elevation-0">
      <v-icon>{{infoComponent ? infoComponent.icono : ''}}</v-icon>
      <v-toolbar-title v-html="'Relaciones laborales'"/>
      <small class="mt-2 ml-1"> Gestión</small>
      <v-spacer/>
<!--      <v-tooltip top>-->
<!--        <v-btn-->
<!--          slot="activator"-->
<!--          :loading="loadingBtnMorosos"-->
<!--          :disabled="loadingBtnMorosos"-->
<!--          color="warning"-->
<!--          class="white&#45;&#45;text"-->
<!--          @click.native="modal = true"-->
<!--        >-->
<!--          Relaciones en mora-->
<!--          <v-icon right dark>cloud_download</v-icon>-->
<!--        </v-btn>-->
<!--        <span>Archivo de relaciones laborales en mora</span>-->
<!--      </v-tooltip>-->
    </v-toolbar>
    <v-dialog
      ref="dialog"
      v-model="modal"
      :return-value.sync="periodoMora"
      persistent
      lazy
      full-width
      width="290px"
    >
      <div style="background-color: #2196f3 !important; border-color: #2196f3 !important;" class="pt-2 px-3">
        <span class='title white--text subheading'>Seleccionar periodo</span>
      </div>
      <v-date-picker
        v-model="periodoMora"
        type="month"
        locale='es'
        :max="maxDateMora"
        :min="minDateMora"
      >
        <v-btn flat color="primary" @click="modal = false">Cancelar</v-btn>
        <v-spacer></v-spacer>
        <v-btn flat color="primary" @click="descargarMorosos">
          Generar archivo
        </v-btn>
      </v-date-picker>
      <!--<v-date-picker v-model="periodoMora" scrollable>-->
        <!--<v-spacer></v-spacer>-->
        <!--<v-btn flat color="primary" @click="modal = false">Cancel</v-btn>-->
        <!--<v-btn flat color="primary" @click="$refs.dialog.save(periodoMora)">OK</v-btn>-->
      <!--</v-date-picker>-->
    </v-dialog>
    <data-table
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleRelacionLaboral', titulo: 'Detalle relación laboral', parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
    />
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RelacionesLaborales',
    components: {
      DataTable: () => import('@/components/general/DataTable')
    },
    data () {
      return {
        modal: false,
        periodoMora: new Date().toISOString().substr(0, 7),
        maxDateMora: this.moment().format('YYYY-MM-DD'),
        minDateMora: this.moment('1900-01-01').format('YYYY-MM-DD'),
        loadingBtnMorosos: false,
        dataTable: {
          nameItemState: 'itemRelacionLaboral',
          route: 'afiliadoaportantes',
          simplePaginate: true,
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
              text: 'Afiliado',
              align: 'left',
              sortable: false,
              value: 'identificacion_afiliado',
              classData: '',
              component: {
                component: {
                  template:
                    `<mini-card-detail :data="value.mini_afiliado"/>`,
                  props: ['value']
                }
              }
            },
            // {
            //   text: 'Nombre afiliado',
            //   align: 'left',
            //   sortable: false,
            //   value: 'nombre_afiliado',
            //   classData: '',
            //   component: {
            //     component: {
            //       template: `
            //         <div>
            //           <v-tooltip right>
            //             <a slot="activator" style="cursor: pointer !important; text-decoration: none !important; color: rgba(0,0,0,0.87) !important;" @click.prevent="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: {id: value.id_afiliado}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})" v-text="value.nombre_afiliado"></a>
            //             <span v-text="'Ver más información'"></span>
            //           </v-tooltip>
            //         </div>
            //       `,
            //       props: ['value']
            //     }
            //   }
            // },
            {
              text: 'Identificación aportante',
              align: 'left',
              sortable: false,
              value: 'identificacion_aportante',
              classData: ''
            },
            {
              text: 'Nombre aportante',
              align: 'left',
              sortable: true,
              value: 'nombre_aportante',
              classData: ''
            },
            {
              text: 'Fecha vinculación',
              align: 'left',
              sortable: true,
              value: 'fecha_vinculacion',
              classData: ''
            },
            {
              text: 'IBC',
              align: 'right',
              sortable: false,
              component: {
                component: {
                  template:
                    `<span>{{'$'}}{{value.ibc | numberFormat(2)}}</span>`,
                  props: ['value']
                }
              },
              value: 'ibc',
              classData: 'text-xs-right'
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
              classData: 'justify-center layout px-0'
            }
          ],
          filters: [
            {
              label: 'Estado',
              type: 'v-autocomplete',
              items: () => this.complementos.estadosRelacionesLaborales,
              vModel: [],
              multiple: true,
              itemText: 'text',
              itemValue: 'value',
              value: 'estado',
              clearable: true
            },
            {
              label: 'Fecha vinculación',
              type: 'v-range',
              items: {
                menuDate: false,
                type: 'date',
                range: true,
                itemMin: {
                  label: 'Inicial',
                  vModel: null,
                  clearable: true,
                  value: 'fecha_vinculacion'
                },
                itemMax: {
                  menuDate: false,
                  label: 'Final',
                  vModel: null,
                  clearable: true,
                  value: 'fecha_vinculacion'
                }
              }
            }
          ]
        }
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosTablaRelacionesLaborales
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('relacionesLaborales')
      }
    },
    methods: {
      descargarMorosos () {
        this.$refs.dialog.save(this.periodoMora)
        this.loadingBtnMorosos = true
        this.axios.get(`firmar-ruta?nombre_ruta=relaciones-morosas&csv=1&periodo=${this.periodoMora}`)
          .then(response => {
            if (response.data !== '') {
              window.fileDownload(response.data)
              this.loadingBtnMorosos = false
            }
          })
          .catch(e => {
            this.loadingBtnMorosos = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al generar el documento. ', error: e})
          })
      },
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'detalle', icon: 'find_in_page', tooltip: 'Ver detalle'})
        return item
      }
    }
  }
</script>

<style scoped>
</style>
