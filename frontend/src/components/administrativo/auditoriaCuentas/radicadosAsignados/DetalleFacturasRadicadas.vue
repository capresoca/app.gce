<template>
  <v-card>
    <v-toolbar v-if="esAuditor">
      <v-list-tile class="content-v-list-tile-p0">
        <v-list-tile-avatar color="primary">
          <v-icon class="white--text">{{infoComponent ? infoComponent.icono : ''}}</v-icon>
        </v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title class="font-weight-bold">Listado de facturas asignadas</v-list-tile-title>
          <v-list-tile-sub-title class="caption"><strong>Audita:</strong> {{currentUser.name}}</v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-toolbar>
    <v-toolbar v-else dense>
      <v-list-tile class="content-v-list-tile-p0">
        <v-list-tile-avatar color="primary">
          <v-icon class="white--text">{{infoComponent ? infoComponent.icono : ''}}</v-icon>
        </v-list-tile-avatar>
        <v-list-tile-content>
          <v-list-tile-title class="font-weight-bold title">Listado de facturas radicadas</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-toolbar>
    <data-table-v2
      ref="tableFacturasRadicadas"
      select-all
      v-if="dataTable.route"
      v-model="dataTable"
      @resetOption="itemFactura => resetOptions(itemFactura)"
      @auditar="itemFactura => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleFacturaRadicada', titulo: `Auditoría Factura: ${itemFactura.no_factura}`, parametros: {entidad: itemFactura, esdetalle: false, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
      @verdetalle="itemFactura => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleFacturaRadicada', titulo: `Detalle Factura: ${itemFactura.no_factura}`, parametros: {entidad: itemFactura, esdetalle: true, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
      @asignar="itemFactura => openDialogAuditores(itemFactura)"
      @apply-filters="aplicaFiltros"
      @selecteds="val => facturasSeleccionadas = val"
      @editarcapitado="val => editarCapitado(val)"
    >
      <template slot="filters">
        <v-flex xs12 sm12 md6>
          <v-select
            label="Montos"
            item-text="descrip"
            item-value="id"
            multiple
            v-model="filtroRuta.montos"
            :items="filters.montos"
            chips
            deletable-chips
            small-chips
            clearable
          ></v-select>
        </v-flex>
        <v-flex xs12 sm12 md6 v-if="!esAuditor">
          <v-autocomplete
            clearable
            label="Auditores"
            v-model="filtroRuta.auditores"
            multiple
            :items="filters.auditores"
            :filter="filterAuditores"
            item-value="id"
          >
            <template slot="item" slot-scope="data">
              <v-list-tile-content class="truncate-content" style="width: 100% !important;">
                <v-list-tile-title>{{data.item.tipo}} - {{data.item.name}}</v-list-tile-title>
                <v-list-tile-sub-title>{{data.item.identificacion_completa}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </template>
            <template slot="selection" slot-scope="data">
              <v-chip small close color="accent" text-color="white" @input="filtroRuta.auditores.splice(data.index, 1)">
                {{data.item.name}}
              </v-chip>
            </template>
          </v-autocomplete>
        </v-flex>
        <v-flex xs12 sm12 md6>
          <v-autocomplete
            clearable
            label="Entidad"
            item-text="nombre"
            return-object
            v-model="filtroRuta.entidad"
            :items="filters.entidades"
            :filter="filterLocalEntidades"
            :hint="filtroRuta.entidad ? 'Código habilitación: ' + filtroRuta.entidad.cod_habilitacion + ' -- Municipio: ' + filtroRuta.entidad.municipio.nombre + ', ' + filtroRuta.entidad.municipio.nombre_departamento : ''"
            persistent-hint
            @input="val => val && (filtroRuta.radicado = null)"
          >
            <template slot="item" slot-scope="data">
              <v-list-tile-content class="truncate-content" style="width: 100% !important;">
                <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                <v-list-tile-sub-title>Código habilitación: {{data.item.cod_habilitacion}} -- Municipio: {{data.item.municipio.nombre}}, {{data.item.municipio.nombre_departamento}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </template>
          </v-autocomplete>
        </v-flex>
        <v-flex xs12 sm12 md6>
          <v-autocomplete
            clearable
            label="Radicado"
            item-text="numero_radicado"
            return-object
            v-model="filtroRuta.radicado"
            :items="filtroRuta.entidad ? filters.radicados.filter(x => x.rs_entidad_id === filtroRuta.entidad.id) : filters.radicados"
            :hint="filtroRuta.radicado ? 'Fecha: ' + filtroRuta.radicado.fecha_radicado : ''"
            persistent-hint
            @input="val => val && filters.entidades.find(x => x.id === val.rs_entidad_id) && (filtroRuta.entidad = filters.entidades.find(x => x.id === val.rs_entidad_id))"
          >
            <template slot="item" slot-scope="data">
              <v-list-tile-content class="truncate-content" style="width: 100% !important;">
                <v-list-tile-title>{{data.item.numero_radicado}}</v-list-tile-title>
                <v-list-tile-sub-title>Fecha: {{data.item.fecha_radicado}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </template>
          </v-autocomplete>
        </v-flex>
      </template>
    </data-table-v2>
    <dialog-auditores ref="dialogAuditores" @asigna="reloadTable"></dialog-auditores>
    <v-bottom-nav
      fixed
      dark
      :value="facturasSeleccionadas.length"
      color="primary"
    >
      <v-list-tile class="white--text">
        <v-list-tile-content>
          <v-list-tile-title>Facturas seleccionadas: <strong>{{facturasSeleccionadas.length}}</strong></v-list-tile-title>
<!--          {{currency(lodash.sumBy(facturasSeleccionadas, 'valor_neto'))}}-->
          <v-list-tile-title>Valor seleccionado: <strong>{{currency(sum_by(facturasSeleccionadas, 'valor_neto'))}}</strong></v-list-tile-title>
<!--          <v-list-tile-title>Valor seleccionado: <strong>{{currency(lodash.sumBy(facturasSeleccionadas, 'valor_neto'))}}</strong></v-list-tile-title>-->
        </v-list-tile-content>
      </v-list-tile>
      <template v-if="this.esAuditor">

      </template>
      <template v-else>
        <v-btn
          dark
          flat
          value="recent"
          @click.stop="asignarAuditores"
        >
          <span>asignar auditores</span>
          <v-icon>fas fa-user-plus</v-icon>
        </v-btn>
      </template>
    </v-bottom-nav>
    <editar-factura ref="edicionFactura"></editar-factura>
  </v-card>
</template>

<script>
  import {mapState} from 'vuex'
  export default {
    name: 'DetalleFacturasRadicadas',
    props: ['parametros'],
    components: {
      DialogAuditores: () => import('@/components/administrativo/auditoriaCuentas/radicadosAsignados/DialogAuditores'),
      EditarFactura: () => import('@/components/administrativo/auditoriaCuentas/radicadosAsignados/EditarFactura')
    },
    data: () => ({
      loading: true,
      radicado: null,
      facturasSeleccionadas: [],
      dataTable: {
        nameItemState: 'tableFacturasRadicadas',
        route: null,
        advanceFilters: true,
        makeHeaders: [
          {
            selectable: true
          },
          {
            text: 'Número',
            align: 'left',
            sortable: false,
            component: {
              template:
                `
                      <v-list-tile class="content-v-list-tile-p0">
                        <v-list-tile-content>
                          <v-list-tile-title class="body-2" v-if="value.entidad">Factura: {{value.no_factura}}</v-list-tile-title>
                          <v-list-tile-sub-title class="caption grey--text" v-if="value.entidad">Radicado: {{value.consecutivo_radicado}}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    `,
              props: ['value']
            }
          },
          {
            text: 'Tipo',
            align: 'left',
            sortable: false,
            value: 'tipo_facturacion'
          },
          {
            text: 'Plan Beneficio',
            align: 'left',
            sortable: false,
            value: 'plan_beneficio'
          },
          {
            text: 'Entidad',
            align: 'left',
            width: '300px',
            sortable: false,
            component: {
              template:
                `
                      <v-tooltip right>
                        <v-list-tile class="content-v-list-tile-p0 cursor-pointer" slot="activator">
                          <v-list-tile-content class="truncate-content">
                            <v-list-tile-title class="body-2" v-if="value.entidad">{{value.entidad.tipo.tipo}}: {{value.entidad.nombre}}</v-list-tile-title>
                            <v-list-tile-sub-title class="caption grey--text" v-if="value.entidad">Código habilitación: {{value.entidad.cod_habilitacion}}</v-list-tile-sub-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      <span>{{value.entidad.nombre}}</span>
                      </v-tooltip>
                    `,
              props: ['value']
            }
          },
          {
            text: 'Estado',
            align: 'center',
            sortable: true,
            value: 'estado',
            classData: 'text-xs-center'
          },
          {
            text: 'Valor Neto',
            align: 'right',
            sortable: false,
            value: 'valor_neto',
            classData: 'text-xs-right',
            component: {
              template:
                `<span>{{currency(value.valor_neto)}}</span>`,
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
      filters: {
        entidades: [],
        radicados: [],
        montos: [],
        auditores: []
      },
      filtroRuta: {
        entidad: null,
        radicado: null,
        montos: [],
        auditores: []
      },
      rutaBase: '',
      filterLocalEntidades (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.cod_habilitacion + ' ' + item.nombre)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      filterAuditores (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.codigo + ' ' + item.tipo + ' ' + item.identificacion_completa + ' ' + item.name)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      }
    }),
    computed: {
      ...mapState({
        currentUser: state => state.user.profile
      }),
      esAuditor () {
        return this.currentUser && this.currentUser.roles && this.currentUser.roles.find(x => x.id === 43)
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('facturasradicadas')
      }
    },
    watch: {
    },
    created () {
      this.reloadComplementos()
      this.radicado = (this.parametros && this.parametros.entidad !== null) ? this.parametros.entidad : null
      this.dataTable.route = (this.parametros && this.parametros.entidad !== null) ? `cm_radicado/${this.parametros.entidad.id}/facturas` : 'facturas_all'
      this.rutaBase = JSON.parse(JSON.stringify(this.dataTable.route))
    },
    methods: {
      editarCapitado (item) {
        // console.log('llega aquiiii')
        this.$refs.edicionFactura.assign(item)
      },
      reloadTable () {
        this.$refs.tableFacturasRadicadas.reloadPage()
      },
      aplicaFiltros () {
        let rutaTemp = this.rutaBase
        if (this.filtroRuta.entidad) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'radicado:rs_entidad_id=' + this.filtroRuta.entidad.id
        }
        if (this.filtroRuta.radicado) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'id_radicado=' + this.filtroRuta.radicado.id
        }
        if (this.filtroRuta.montos.length) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'montos=' + this.filtroRuta.montos
        }
        if (this.filtroRuta.auditores.length) {
          rutaTemp = rutaTemp + (rutaTemp.indexOf('?') > -1 ? '&' : '?') + 'auditores=' + this.filtroRuta.auditores
        }
        this.dataTable.route = rutaTemp
      },
      reloadComplementos () {
        this.axios.get(`complementos/facturas`)
          .then((response) => {
            // console.log('data filtros', response)
            this.filters = response.data.data
          })
          .catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ` al traer los complementos para crear los filtros. `, error: e})
          })
      },
      asignarAuditores () {
        this.$refs.dialogAuditores.open(this.facturasSeleccionadas)
      },
      openDialogAuditores (radicado) {
        this.$refs.dialogAuditores.open([radicado])
      },
      resetOptions (item) {
        item.selectable = true
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar && !this.esAuditor) item.options.push({event: 'asignar', icon: 'fas fa-users-cog', tooltip: 'Auditores'})
        // if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'auditar', icon: 'fas fa-tasks', tooltip: 'Auditar', color: 'orange'})
        if (this.infoComponent && this.infoComponent.permisos.agregar && (item.estado === 'Asignada' || item.estado === 'Auditando') && this.esAuditor) item.options.push({event: 'auditar', icon: 'fas fa-tasks', tooltip: 'Auditar', color: 'orange'})
        if (this.infoComponent && this.infoComponent.permisos.ver) item.options.push({event: 'verdetalle', icon: 'fas fa-file-invoice', tooltip: 'Ver detalle', color: 'success'})
        if (this.infoComponent && this.infoComponent.permisos.agregar && item.tipo_facturacion === 'Capita') item.options.push({event: item.feha_repcapita ? '' : 'editarcapitado', icon: 'far fa-edit', tooltip: item.feha_repcapita ? `La factura ya fue editada, fecha: ${item.feha_repcapita}` : 'Editar Factura', color: item.feha_repcapita ? 'grey' : 'indigo'})
        return item
      }
    }
  }
</script>

<style scoped>

</style>
