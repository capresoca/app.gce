<template>
  <v-card flat>
    <data-table-v2
      ref="tableItemAffiliate"
      v-model="affiliateDataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: this.infoComponent.ruta_registro, titulo: this.infoComponent.titulo_registro, parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
      @detalle="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
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
            :items="complementos.tipdocidentidades"
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
        <v-flex xs12 sm12 md6 v-if="filters.type === 'v-range' && filters.items.type === 'number'">
          <v-layout align-center>
            <v-text-field
              type="number"
              v-model.number="filters.items.itemMin.vModel"
              :label="filters.items.itemMin.label"
              clearable
            />
            <v-text-field
              type="number"
              v-model.number="filters.items.itemMax.vModel"
              :label="filters.items.itemMax.label"
              clearable
            />
          </v-layout>
        </v-flex>
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
        <v-flex xs12 sm12 md6>
          <v-autocomplete
            label="Municipio Afiliado"
            v-model="filtroRuta.gn_municipio_id"
            :items="complementos.municipios"
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
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'AllAfiliados',
    data: () => ({
      filterMunicipios (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      affiliateDataTable: {
        nameItemState: 'tableItemAffiliate',
        route: null,
        advanceFilters: true,
        makeHeaders: [
          {
            text: 'Afiliado',
            align: 'left',
            sortable: false,
            value: 'identificacion',
            component: {
              template:
                `<mini-card-detail :data="value.mini_afiliado"/>`,
              props: ['value']
            }
          },
          {
            text: 'Municipio',
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
            text: 'Ficha SISBEN',
            align: 'left',
            sortable: true,
            value: 'puntaje_sisben',
            classData: 'text-xs-center'
          },
          {
            text: 'Puntaje SISBEN',
            align: 'center',
            sortable: true,
            value: 'puntaje_sisben',
            classData: 'text-xs-center'
          },
          {
            text: 'Régimen',
            align: 'left',
            sortable: true,
            value: 'regimen'
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
            classData: 'justify-center layout px-0'
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
      filters: {
        label: 'Puntaje sisben',
        type: 'v-range',
        items: {
          type: 'number',
          range: true,
          itemMin: {
            label: 'Puntaje Mínimo Sisben',
            vModel: null,
            clearable: true,
            value: 'puntaje_sisben'
          },
          itemMax: {
            label: 'Puntaje Máximo Sisben',
            vModel: null,
            clearable: true,
            value: 'puntaje_sisben'
          }
        }
      }
    }),
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('afiliados')
      },
      complementos () {
        let beforeComplement = this.clone(store.getters.complementosFormAfiliados)
        if (beforeComplement.tipdocidentidades) {
          beforeComplement.tipdocidentidades = beforeComplement.tipdocidentidades.filter(x => x.id !== 12)
        }
        return beforeComplement
      }
    },
    created () {
      this.affiliateDataTable.advanceFilters = true
      this.affiliateDataTable.route = 'afiliados?estado_afiliado:codigo=!PR'
      this.rutaBase = this.clone(this.affiliateDataTable.route)
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'detalle', icon: 'find_in_page', tooltip: 'Ver detalle', color: 'teal'})
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
        if (this.filters.items.itemMin.vModel) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'rangMin=' + this.filters.items.itemMin.vModel
        }
        if (this.filters.items.itemMax.vModel) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'rangMax=' + this.filters.items.itemMax.vModel
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
        this.affiliateDataTable.route = rutaTemp
      }
    }
  }
</script>

<style scoped>

</style>
