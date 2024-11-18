<template>
  <v-card>
    <registro-contrato-empleado ref="registroContratoEmpleado" @updateOrCreate="data => updateOrCreate(data)"></registro-contrato-empleado>
    <registro-liquidacion ref="liquidacionContrato" @updated="data => actualizar(data)"></registro-liquidacion>
    <loading v-model="loading"/>
    <toolbar-list
      :info="infoComponent"
      iconom="fas fa-file-archive"
      title="Contratos Empleado"
      subtitle="Mantenimiento"
      btnplus
      btnplustitle="Crear Contrato"
      btnplustruncate
      @click="openDialog"
    ></toolbar-list>
    <data-table-v2
      ref="tablaDetallesContratoEmpleado"
      v-model="dataTable"
      @resetOption="item => resetOptions(item)"
      @editar="item => editarItem(item)"
      @detallesExtraLaboral="item => $store.commit('NAV_ADD_ITEM', { ruta: 'detalleExtralabora', titulo: `Registro Detalles Laborares CC. ${item.scempleado ? item.scempleado.numero_identificacion : ' #'}`, parametros: {entidad: item, tabOrigin: this.$store.state.nav.currentItem.split('tab-')[1]} })"
      @detalleLiquidacion="item => openLiquidar(item)"
    >
    </data-table-v2>
  </v-card>
</template>

<script>
  import RegistroContratoEmpleado from './RegistroContratoEmpleado'
  import RegistroLiquidacion from './RegistroLiquidacion'
  export default {
    name: 'DetalleContratosEmpleado',
    components: {RegistroLiquidacion, RegistroContratoEmpleado},
    props: ['parametros'],
    data: () => ({
      infoComponent: null,
      dialog: false,
      loading: false,
      empleado: null,
      dataTable: {
        nameItemState: 'tableContratosEmpleado',
        route: null,
        makeHeaders: [
          {
            text: '# Contrato',
            align: 'center',
            sortable: false,
            value: 'contrato_empleado',
            classData: 'text-xs-center'
          },
          {
            text: 'Fecha',
            align: 'left',
            sortable: false,
            value: 'fecha_inicio',
            width: '200px',
            component: {
              template: `
                <v-list-tile class="content-v-list-tile-p0 cursor-pointer" slot="activator">
                  <v-list-tile-content class="truncate-content">
                    <v-list-tile-title class="body-2" v-text="'Inicio: ' + value.fecha_inicio"></v-list-tile-title>
                    <v-list-tile-title class="body-2" v-text="'Fin: ' + (!value.fecha_final ? '####-##-##' : value.fecha_final)"></v-list-tile-title>
                  </v-list-tile-content>
                </v-list-tile>
              `,
              props: ['value']
            }
          },
          {
            text: 'Cargo',
            align: 'left',
            sortable: false,
            value: 'contrato_empleado',
            component: {
              template: `
                <span v-text="value.tbcargo ? value.tbcargo.descripcion : 'N/A'"></span>
              `,
              props: ['value']
            }
          },
          {
            text: 'Tipo Contrato',
            align: 'left',
            sortable: false,
            value: 'contrato_empleado',
            component: {
              template: `
                <span v-text="value.tb_tipocontrato ? value.tb_tipocontrato.descripcion : 'N/A'"></span>
              `,
              props: ['value']
            }
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado',
            component: {
              template: `
                <span v-text="value.estado === 1 ? 'Vigente' : 'No Vigente'"></span>
              `,
              props: ['value']
            }
          },
          {
            text: 'Salario Base',
            align: 'left',
            sortable: false,
            value: 'base_prima',
            component: {
              template: `
                <span v-text="currency(value.base_prima)"></span>
              `,
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
      }
    }),
    watch: {
      dialog (value) {
        if (value === false) {
          this.close()
        }
      }
    },
    created () {
      if (this.parametros.entidad !== null) {
        let item = this.clone(this.parametros.entidad)
        delete item.show
        delete item.permisos
        this.empleado = item
        this.dataTable.route = `talentohumano/contratos-empleados?empleado=${this.empleado ? this.empleado.empleado : null}`
        this.infoComponent = this.clone(this.parametros.entidad.permisos)
      }
      console.log('MAS', this.parametros)
    },
    mounted () {
      this.$store.commit('reloadTable', 'tableContratosEmpleado')
    },
    methods: {
      resetOptions (item) {
        item.options = []
        item.permisos = this.infoComponent
        if (this.infoComponent && this.infoComponent.permisos.agregar) {
          item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
          item.options.push({event: 'detallesExtraLaboral', icon: 'fas fa-file-invoice-dollar', tooltip: 'Extras Laborales', color: 'teal'})
          if (item.estado === 1) item.options.push({event: 'detalleLiquidacion', icon: 'fas fa-handshake', tooltip: 'Liquidar', color: 'red darken-2'})
        } else {
          if (item.estado === 0) {
            item.permisos.agregar = 0
            item.options.push({event: 'detallesExtraLaboral', icon: 'fas fa-file-invoice-dollar', tooltip: 'Extras Laborales', color: 'teal'})
          }
        }
        return item
      },
      openDialog () {
        this.$refs.registroContratoEmpleado.assign(this.empleado, 'uno')
      },
      updateOrCreate (data) {
        let contrato = data
        const typeRequest = !contrato.contrato_empleado ? this.axios.post('talentohumano/contratos-empleados', contrato)
          : this.axios.put(`talentohumano/contratos-empleados/${contrato.contrato_empleado}`, contrato)
        typeRequest.then(response => {
          console.log('response', response)
          this.$store.commit('reloadTable', 'tableContratosEmpleado')
          this.$store.commit('SNACK_SHOW', {msg: `Se ${!contrato.contrato_empleado ? 'registro el contrato' : ('actualizo el contrato #' + contrato.contrato_empleado)} èxitosamente`, color: 'success'})
        }).catch(e => {
          this.$store.commit('SNACK_ERROR_LIST', {expression: ' al registrar el contrato.', error: e})
          // this.$refs.registroContratoEmpleado.assign(data, 'error')
        })
      },
      actualizar (item) {
        let liquidacion = this.clone(item)
        this.axios.put(`talentohumano/contratos-empleados/${liquidacion.contrato_empleado}`, liquidacion)
          .then(response => {
            this.$store.commit('reloadTable', 'tableContratosEmpleado')
            this.$store.commit('SNACK_SHOW', {msg: `Se realizo el registro éxitosamente`, color: 'success'})
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al registrar la liquidación.', error: e})
          })
      },
      openLiquidar (item) {
        this.$refs.liquidacionContrato.assign(item)
      },
      editarItem (props) {
        this.$refs.registroContratoEmpleado.assign(props, 'dos')
      },
      close () {
        this.$validator.reset()
        this.dialog = false
      }
    }
  }
</script>

<style scoped>

</style>
