<template>
  <v-card>
    <confirmar :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)" />
    <toolbar-list :info="infoComponent" title="Trámites de traslados" subtitle="Registro y gestión" btnplus btnplustitle="Crear trámite" />
    <data-table-v2
      ref="tableItemTrasladosAfiliados"
      v-model="traslateAffiliatumItems"
      @resetOption="item => resetOptions(item)"
      @imprimir="item => filePrint(`formulario-tramite-traslado&id=${item.id}`)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      @anular="item => registroAnular(item)"
      @apply-filters="aplicaFiltros"
      :con-buscador="false"
      :con-registro="false"
      :con-columnas="false"
    >
      <template slot="filters">
        <v-flex xs12 sm12 md3>
          <v-select
            label="Tipo documento"
            item-text="abreviatura"
            item-value="id"
            v-model="filtroRuta.tipo_id"
            :items="complementosID.tipdocidentidades"
            chips
            deletable-chips
            small-chips
            clearable
          ></v-select>
        </v-flex>
        <v-flex xs12 sm12 md3>
          <v-text-field
            label="Identificación"
            v-model="filtroRuta.identificacion"
            clearable
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md6></v-flex>
        <v-flex xs12 sm12 md3>
          <v-text-field
            label="Primer Nombre"
            v-model="filtroRuta.nombre1"
            v-upper="'filtroRuta.nombre1'"
            clearable
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md3>
          <v-text-field
            label="Segundo Nombre"
            v-model="filtroRuta.nombre2"
            v-upper="'filtroRuta.nombre2'"
            clearable
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md3>
          <v-text-field
            label="Primer Apellido"
            v-model="filtroRuta.apellido1"
            v-upper="'filtroRuta.apellido1'"
            clearable
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md3>
          <v-text-field
            label="Segundo Apellido"
            v-model="filtroRuta.apellido2"
            v-upper="'filtroRuta.apellido2'"
            clearable
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md6 v-if="filters[0].type === 'v-select' || filters[0].type === 'v-autocomplete'">
          <v-layout align-center>
            <component
              :is="filters[0].type"
              :key="'filter' + 0"
              :label="filters[0].label"
              :multiple="filters[0].multiple"
              v-model="filters[0].vModel"
              :item-text="filters[0].itemText"
              :item-value="filters[0].itemValue"
              :items="filters[0].items()"
              :clearable="filters[0].clearable"
            >
              <template
                slot="selection"
                slot-scope="{ item, index }"
              >
                <v-chip small v-if="0 === 0">
                  <span>{{ item[filters[0].itemText] }}</span>
                </v-chip>
              </template>
            </component>
          </v-layout>
        </v-flex>
        <v-flex xs12 sm12 md6 v-if="filters[1].type === 'v-range' && filters[1].items.type === 'date'">
          <v-layout align-center>
            <v-flex xs6>
              <v-menu
                class="mt-0"
                :key="'fechaMenu' + 1"
                :ref="filters[1].items.itemMin.label + 1"
                :close-on-content-click="false"
                v-model="filters[1].items.itemMin.menuDate"
                :nudge-right="40"
                :return-value.sync="filters[1].items.itemMin.vModel"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
              >
                <v-text-field
                  :key="'fechaText' + 1"
                  slot="activator"
                  v-model="filters[1].items.itemMin.vModel"
                  :label="filters[1].items.itemMin.label"
                  clearable
                  readonly
                />
                <v-date-picker
                  locale="es-co"
                  :key="'fechaPicker' + 1"
                  v-model="filters[1].items.itemMin.vModel"
                  @input="$refs[filters[1].items.itemMin.label + 1][0].save(filters[1].items.itemMin.vModel)"
                />
              </v-menu>
            </v-flex>
            <v-flex xs6>
              <v-menu
                class="mt-0"
                :key="'fechaMenuMax' + 1"
                :ref="filters[1].items.itemMax.label + 1"
                :close-on-content-click="false"
                v-model="filters[1].items.itemMax.menuDate"
                :nudge-right="40"
                :return-value.sync="filters[1].items.itemMax.vModel"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
              >
                <v-text-field
                  :key="'fechaTextMax' + 1"
                  slot="activator"
                  v-model="filters[1].items.itemMax.vModel"
                  :label="filters[1].items.itemMax.label"
                  clearable
                  readonly
                />
                <v-date-picker
                  locale="es-co"
                  :key="'fechaPickerMax' + 1"
                  v-model="filters[1].items.itemMax.vModel"
                  @input="$refs[filters[1].items.itemMax.label + 1][0].save(filters[1].items.itemMax.vModel)"
                />
              </v-menu>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex xs12 sm12 md6>
          <v-autocomplete
            label="Municipio Afiliado"
            v-model="filtroRuta.gn_municipio_id"
            :items="complementosID.municipios"
            item-value="id"
            item-text="nombre"
            :filter="filterMunicipios"
            clearable
          >
            <template slot="item" slot-scope="data">
              <template>
                <v-list-tile-content>
                  <v-list-tile-title v-html="data.item.nombre"/>
                  <v-list-tile-sub-title v-html="data.item.nombre_departamento"/>
                </v-list-tile-content>
              </template>
            </template>
          </v-autocomplete>
        </v-flex>
        <v-flex xs12 sm12 md3>
          <v-text-field
            label="Ficha sisbén"
            v-model="filtroRuta.ficha"
            clearable
          ></v-text-field>
        </v-flex>
      </template>
    </data-table-v2>

<!--    <h1>DIVISION</h1>-->
<!--    <data-table-->
<!--      v-model="dataTable"-->
<!--      @resetOption="item => resetOptions(item)"-->
<!--      @imprimir="item => filePrint(`formulario-tramite-traslado&id=${item.id}`)"-->
<!--      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"-->
<!--      @anular="item => registroAnular(item)"-->
<!--    />-->
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import DataTable from '@/components/general/DataTable'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Confirmar from '@/components/general/Confirmar'
  export default {
    name: 'Traslados',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList'),
      Confirmar,
      DataTable
    },
    data () {
      return {
        filterMunicipios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        traslateAffiliatumItems: {
          nameItemState: 'tableItemTrasladosAfiliados',
          route: null,
          advanceFilters: true,
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
              value: 'identificacion',
              classData: '',
              component: {
                template:
                  `<mini-card-detail :data="value.mini_afiliado"></mini-card-detail>`,
                props: ['value']
              }
            },
            {
              text: 'Municipio Afiliado',
              align: 'left',
              sortable: false,
              value: 'ficha_sisben',
              component: {
                template: `
                <v-list>
                  <v-list-tile-content>
                    <v-list-tile-title v-html="value.ubicacion"/>
                  </v-list-tile-content>
                 </v-list>`,
                props: ['value']
              }
            },
            {
              text: 'E.P.S.',
              align: 'left',
              sortable: false,
              value: 'nombreEps'
            },
            {
              text: 'Fecha',
              align: 'left',
              sortable: true,
              value: 'fecha_traslado'
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
              items: () => this.complementos.estadosTrasmovs,
              vModel: [],
              multiple: true,
              itemText: 'text',
              itemValue: 'value',
              value: 'estado',
              clearable: true
            },
            {
              label: 'Fecha',
              type: 'v-range',
              items: {
                menuDate: false,
                type: 'date',
                range: true,
                itemMin: {
                  label: 'Inicial',
                  vModel: null,
                  clearable: true,
                  value: 'fecha_traslado'
                },
                itemMax: {
                  menuDate: false,
                  label: 'Final',
                  vModel: null,
                  clearable: true,
                  value: 'fecha_traslado'
                }
              }
            }
          ]
        },
        dataTable: {
          nameItemState: 'itemTrastramite',
          route: 'formtrasmov',
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
              value: 'identificacion',
              classData: '',
              component: {
                component: {
                  template:
                    `<mini-card-detail :data="value.mini_afiliado"></mini-card-detail>`,
                  props: ['value']
                }
              }
            },
            {
              text: 'E.P.S.',
              align: 'left',
              sortable: false,
              value: 'nombreEps'
            },
            {
              text: 'Fecha',
              align: 'left',
              sortable: true,
              value: 'fecha_traslado'
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
              items: () => this.complementos.estadosTrasmovs,
              vModel: [],
              multiple: true,
              itemText: 'text',
              itemValue: 'value',
              value: 'estado',
              clearable: true
            },
            {
              label: 'Fecha',
              type: 'v-range',
              items: {
                menuDate: false,
                type: 'date',
                range: true,
                itemMin: {
                  label: 'Inicial',
                  vModel: null,
                  clearable: true,
                  value: 'fecha_traslado'
                },
                itemMax: {
                  menuDate: false,
                  label: 'Final',
                  vModel: null,
                  clearable: true,
                  value: 'fecha_traslado'
                }
              }
            }
          ]
        },
        rutaBase: '',
        filtroRuta: {
          tipo_id: null,
          identificacion: null,
          nombre1: null,
          nombre2: null,
          apellido1: null,
          apellido2: null,
          gn_municipio_id: null,
          ficha: null
        },
        filters: [
          {
            label: 'Estado',
            type: 'v-autocomplete',
            items: () => this.complementos.estadosTrasmovs,
            vModel: [],
            multiple: true,
            itemText: 'text',
            itemValue: 'value',
            value: 'estado',
            clearable: true
          },
          {
            label: 'Fecha',
            type: 'v-range',
            items: {
              menuDate: false,
              type: 'date',
              range: true,
              itemMin: {
                label: 'Fecha Inicial',
                vModel: null,
                clearable: true,
                value: 'fecha_traslado'
              },
              itemMax: {
                menuDate: false,
                label: 'Fecha Final',
                vModel: null,
                clearable: true,
                value: 'fecha_traslado'
              }
            }
          }
        ]
      }
    },
    created () {
      this.traslateAffiliatumItems.route = 'formtrasmov'
      this.rutaBase = this.clone(this.traslateAffiliatumItems.route)
    },
    computed: {
      complementosID () {
        let beforeComplement = this.clone(store.getters.complementosFormAfiliados)
        if (beforeComplement.tipdocidentidades) {
          beforeComplement.tipdocidentidades = beforeComplement.tipdocidentidades.filter(x => x.id !== 12)
        }
        return beforeComplement
      },
      complementos () {
        return store.getters.complementosTablaTraslados
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('tramitesTraslado')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.imprimir) item.options.push({event: 'imprimir', icon: 'far fa-file-pdf', tooltip: 'Imprimir'})
        if (this.infoComponent && this.infoComponent.permisos.agregar && (item.estado === 'Registrado')) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        if (this.infoComponent && this.infoComponent.permisos.anular && (item.estado === 'Registrado' || item.estado === 'Radicado')) item.options.push({event: 'anular', icon: 'delete', tooltip: 'Anular'})
        return item
      },
      aplicaFiltros () {
        let rutaTemp = this.rutaBase
        if (this.filtroRuta.tipo_id) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'tipo_id=' + this.filtroRuta.tipo_id
        }
        if (this.filtroRuta.identificacion) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'identificacion=' + this.filtroRuta.identificacion
        }
        if (this.filters[1].items.itemMin.vModel) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'fechaMin=' + this.filters[1].items.itemMin.vModel
        }
        if (this.filters[1].items.itemMax.vModel) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'fechaMax=' + this.filters[1].items.itemMax.vModel
        }
        if ((this.filters[0].vModel.length)) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'estados=' + this.filters[0].vModel
        }
        if (this.filtroRuta.nombre1) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'nombre1=' + this.filtroRuta.nombre1
        }
        if (this.filtroRuta.nombre2) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'nombre2=' + this.filtroRuta.nombre2
        }
        if (this.filtroRuta.apellido1) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'apellido1=' + this.filtroRuta.apellido1
        }
        if (this.filtroRuta.apellido2) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'apellido2=' + this.filtroRuta.apellido2
        }
        if (this.filtroRuta.gn_municipio_id) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'gn_municipio_id=' + this.filtroRuta.gn_municipio_id
        }
        if (this.filtroRuta.ficha) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'ficha=' + this.filtroRuta.ficha
        }
        this.traslateAffiliatumItems.route = rutaTemp
      },
      registroAnular (item) {
        this.dialogA.content = '¿Está seguro de anular el trámite de traslado de <strong>' + item.afiliado + '</strong>?'
        this.dialogA.registroID = item.id
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroID = null
      },
      confirmaAnulacion (motivo) {
        this.axios.post('formtrasmovs', {estado: 'Anulado', detalle_anulacion: motivo, id: this.dialogA.registroID})
          .then(() => {
            this.tableItemTrasladosAfiliados.find(x => x.id === this.dialogA.registroID).estado = 'Anulado'
            this.$store.commit(SNACK_SHOW, {msg: 'El trámite de traslado se anuló satisfactoriamente.', color: 'success'})
            this.cancelaAnulacion()
          }).catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar anular el trámite de traslado. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
