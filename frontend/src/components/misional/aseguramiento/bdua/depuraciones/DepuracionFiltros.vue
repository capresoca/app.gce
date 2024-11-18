<template>
  <v-card flat>
    <data-table-v2
      ref="tableDepuracionMs"
      v-model="affiliateDataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => $store.commit('NAV_ADD_ITEM', { ruta: 'registroMS', titulo: 'Depuración BDUA', parametros: {entidad: item, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
      @apply-filters="aplicaFiltros"
      :con-buscador="false"
      :con-registro="false"
      :con-columnas="false"
      :filter-static="true"
    >
      <template slot="filters">
      <v-flex xs12 sm12 md3>
		<postulador-v2
          no-data="Buscar vigencia."
          hint="descripcion"
          item-text="descripcion"
          data-title="descripcion"
          data-subtitle="descripcion"
          label="Vigencia"
          entidad="vigencia"
          v-model="vigenciabuscar"
          name="vigencia"
          rules="required"
          v-validate="'required'"
          :error-messages="errors.collect('vigencia')"
          no-btn-create
          :min-characters-search="3"
        />
      </v-flex>
      <v-flex xs12 sm12 md3>
		<postulador-v2
          no-data="Buscar Log Traslado"
          hint="descripcion"
          item-text="descripcion"
          data-title="descripcion"
          data-subtitle="descripcion"
          label="Log Traslado"
          entidad="logtrasladovigencia"
          :route-params="`vigencia=${filtroRuta.vigencia_id}`"
          v-model="filtroRuta.logtraslado"
          name="logtraslado"
          rules="required"
          v-validate="'required'"
          :error-messages="errors.collect('logtraslado')"
          no-btn-create
          :min-characters-search="3"
        />
      </v-flex>
      <v-flex xs12 sm12 md3>
          <v-text-field
            label="Identificación"
            v-model="filtroRuta.identificacion"
            clearable
          ></v-text-field>
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
        
        </v-flex>
      </template>
    </data-table-v2>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'DepuracionFiltros',
    data: () => ({
      affiliateDataTable: {
        nameItemState: 'tableDepuracionMs',
        route: null,
        advanceFilters: true,
        makeHeaders: [
          {
            text: 'Afiliado',
            align: 'left',
            sortable: false,
            value: 'fecha',
            component: {
              template:
                `<mini-card-detail :data="value.mini_afiliado"></mini-card-detail>`,
              props: ['value']
            }
          },
          {
            text: 'Fecha Nacimiento',
            align: 'center',
            sortable: false,
            value: 'fecha_nacimiento',
            component: {
              template:
                `<span>{{value.fecha_nacimiento}}</span>`,
              props: ['value']
            },
            classData: 'text-xs-center'
          },
          {
            text: 'Ubicación',
            align: 'left',
            sortable: false,
            value: 'municipio',
            component: {
              template:
                `<v-tooltip right>
                      <v-list-tile class="content-v-list-tile-p0 cursor-pointer" slot="activator">
                            <v-list-tile-content class="truncate-content">
                              <v-list-tile-title slot="activator" class="body-2">{{value.municipio}}</v-list-tile-title>
                              <v-list-tile-title class="caption grey--text">{{value.departamento}}</v-list-tile-title>
                            </v-list-tile-content>
                      </v-list-tile>
                      <span>{{value.municipio + ', ' + value.departamento}}</span>
                </v-tooltip>`,
              props: ['value']
            }
          },
          {
            text: 'Estado',
            align: 'center',
            sortable: true,
            value: 'estado_proceso',
            classData: 'text-xs-center',
            component: {
              template:
                `<span>{{(value.estado_proceso)}}</span>`,
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
        vigencia: null,
        logtraslado: null,
        logtrasla_id: null,
        vigencia_id: null,
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
      },
      vigenciabuscar: null
    }),
    watch: {
      'vigenciabuscar' (val) {
        this.filtroRuta.vigencia_id = val.consecutivo_vigencia
      },
      'filtroRuta.logtraslado' (val) {
        this.filtroRuta.logtrasla_id = val.consecutivo_log_ms
      }
    },
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
      this.affiliateDataTable.route = 'bduas/depuracionms'
      this.rutaBase = this.clone(this.affiliateDataTable.route)
      this.vigenciabuscar = null
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && item.estado_proceso === 'Pendiente NEG') item.options.push({event: 'editar', color: 'accent', icon: 'fas fa-stream', tooltip: 'Edición de Novedad'})
        return item
      },
      aplicaFiltros () {
        if (this.filtroRuta.vigencia_id === null || this.filtroRuta.logtrasla_id === null) {
          this.affiliateDataTable.route = null
          this.$store.commit('SNACK_SHOW', {msg: 'Debe seleccionar una vigencia y un Log de Traslado.', color: 'error'})
        } else {
          let rutaTemp = this.rutaBase + '?inicio=ok'
          if (this.filtroRuta.vigencia) {
            rutaTemp = rutaTemp + '&vigencia=' + this.filtroRuta.vigencia.consecutivo_vigencia
          }
          if (this.filtroRuta.logtraslado) {
            rutaTemp = rutaTemp + '&logtraslado=' + this.filtroRuta.logtraslado.consecutivo_log_ms
          }
          if (this.filtroRuta.tipo_id) {
            rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'tipo_id=' + this.filtroRuta.tipo_id
          }
          if (this.filtroRuta.identificacion) {
            rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'identificacion=' + this.filtroRuta.identificacion
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
          this.affiliateDataTable.route = rutaTemp
        }
      }
    }
  }
</script>

<style scoped>

</style>
