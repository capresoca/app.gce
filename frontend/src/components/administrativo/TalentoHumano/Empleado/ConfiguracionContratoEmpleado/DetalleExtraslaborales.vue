<template>
    <v-card>
      <registro-extra-laboral ref="extralaboral" @updateOrCreate="data => saveExtra(data, data.valor)"></registro-extra-laboral>
      <v-tabs grow fixed-tabs color="cyan" dark>
        <v-tabs-slider color="accent"></v-tabs-slider>
        <v-tab href="#tab-1">
          Extras Laborales
        </v-tab>
        <v-tab href="#tab-2">
          Remuneraciones
        </v-tab>
        <v-tab-item
          value="tab-1"
        >
          <v-card flat>
            <toolbar-list
              :info="infoComponent"
              iconom="fas fa-file-invoice-dollar"
              :title="title"
              btnplus
              btnplustitle="Agregar Extra"
              btnplustruncate
              @click="openDialog('uno')"
            ></toolbar-list>
            <data-table-v2
              v-model="dataTable"
              @resetOption="item => resetOptions(item)"
              @editar="item => editarItem(item, 'a')">
            </data-table-v2>
          </v-card>
        </v-tab-item>
        <v-tab-item value="tab-2">
          <v-card flat>
            <toolbar-list
              :info="infoComponent"
              iconom="fas fa-hand-holding-usd"
              :title="titleDos"
              btnplus
              btnplustitle="Agregar Remuneración"
              btnplustruncate
              @click="openDialog('tres')"
            ></toolbar-list>
            <data-table-v2
              v-model="dataTableDos"
              @resetOption="item => resetOptions(item)"
              @editar="item => editarItem(item,'b')">
            </data-table-v2>
          </v-card>
        </v-tab-item>
      </v-tabs>
    </v-card>
</template>

<script>
  import RegistroExtraLaboral from './RegistroExtraLaboral'
  export default {
    name: 'DetalleExtraslaborales',
    components: {RegistroExtraLaboral},
    props: ['parametros'],
    data: () => ({
      infoComponent: null,
      contratoEmpleado: null,
      remuneracion: null,
      dataTable: {
        nameItemState: 'tableContratosExtrasEmpleado',
        route: null,
        makeHeaders: [
          {
            text: 'Extra Laboral',
            align: 'left',
            sortable: false,
            value: 'contrato_empleado_extra',
            width: '250px',
            component: {
              template: `
                <span v-text="value.extralaboral ? value.extralaboral.descripcion : ''"></span>
              `,
              props: ['value']
            }
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
                    <v-list-tile-title class="body-2" v-text="'Fin: ' + (value.fecha_fin ? value.fecha_fin : '####-##-##')"></v-list-tile-title>
                  </v-list-tile-content>
                </v-list-tile>
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
            text: 'Causación',
            align: 'left',
            sortable: false,
            value: 'nombre_causacion'
          },
          {
            text: 'Valor Extra',
            align: 'left',
            sortable: false,
            value: 'valor_extra',
            classData: 'text-xs-right',
            width: '200px',
            component: {
              template: `
                <span v-text="currency(value.valor_extra)"></span>
              `,
              props: ['value']
            }
          },
          {
            text: 'Porcentaje Salarial',
            align: 'left',
            sortable: false,
            value: 'porcentaje_extra',
            classData: 'text-xs-center',
            component: {
              template: `
                <span v-text="value.porcentaje_extra + ' %'"></span>
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
      },
      dataTableDos: {
        nameItemState: 'tableContratosRemuneracionesEmpleado',
        route: null,
        makeHeaders: [
          {
            text: 'Año',
            align: 'left',
            sortable: false,
            value: 'contrato_empleado',
            component: {
              template: `
                <span v-text="value.contrato ? moment(value.contrato.fecha_inicio).format('Y') : '####'"></span>
              `,
              props: ['value']
            }
          },
          {
            text: 'Salario Base',
            align: 'left',
            sortable: false,
            value: 'salario_base',
            component: {
              template: `
                <span v-text="currency(value.salario_base)"></span>
              `,
              props: ['value']
            }
          }, {
            text: 'Observación',
            align: 'left',
            sortable: false,
            value: 'observacion'
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
    created () {
      console.log('aaa', this.parametros)
      if (this.parametros.entidad !== null) {
        this.getContratoEmpleado(this.parametros.entidad.contrato_empleado)
        this.dataTable.route = `talentohumano/contratosempleados/extras?contrato_empleado=${this.parametros.entidad.contrato_empleado}`
        this.dataTableDos.route = `talentohumano/contratosempleados/remuneraciones?contrato_empleado=${this.parametros.entidad.contrato_empleado}`
        this.infoComponent = this.parametros.entidad.permisos
      }
    },
    computed: {
      title () {
        return `Extras Laborales del Contrato # ${this.contratoEmpleado ? this.contratoEmpleado.contrato_empleado : null}`
      },
      titleDos () {
        return `Remuneraciones del Contrato # ${this.contratoEmpleado ? this.contratoEmpleado.contrato_empleado : null}`
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.agregar) {
          item.options.push({event: 'editar', icon: 'edit', tooltip: 'Editar'})
        }
        return item
      },
      editarItem (data, val) {
        if (val === 'a') {
          this.$refs.extralaboral.assign(data, 'dos')
        } else if (val === 'b') {
          this.$refs.extralaboral.assign(data, 'cuatro')
        }
      },
      openDialog (val) {
        if (val === 'uno') {
          this.$refs.extralaboral.assign(this.contratoEmpleado, 'uno')
        }
        if (val === 'tres') {
          this.$refs.extralaboral.assign(this.contratoEmpleado, 'tres')
        }
        console.log('val', val)
      },
      saveExtra (data, val) {
        let datos = this.clone(data)
        let typeRequest
        if (val === 'tres' || val === 'cuatro') {
          typeRequest = !data.contrato_empleado_remuneracion ? this.axios.post(`talentohumano/contratosempleados/remuneraciones`, datos)
            : this.axios.put(`talentohumano/contratosempleados/remuneraciones/${data.contrato_empleado_remuneracion}`, datos)
        } else if (val === 'uno' || val === 'dos') {
          typeRequest = !data.contrato_empleado_extra ? this.axios.post(`talentohumano/contratosempleados/extras`, datos)
            : this.axios.put(`talentohumano/contratosempleados/extras/${data.contrato_empleado_extra}`, datos)
        }
        typeRequest.then(response => {
          if (typeof data.contrato_empleado_remuneracion !== 'undefined') {
            this.$store.commit('SNACK_SHOW', {msg: `Se ${!datos.contrato_empleado_remuneracion ? 'registro de la remuneración' : ('actualizo la remuneración #' + datos.contrato_empleado_remuneracion)} éxitosamente`, color: 'success'})
            this.$store.commit('reloadTable', 'tableContratosRemuneracionesEmpleado')
          } else {
            this.$store.commit('SNACK_SHOW', {msg: `Se ${!datos.contrato_empleado_extra ? 'registro el extra labora' : ('actualizo el extra laboral #' + datos.contrato_empleado_extra)} éxitosamente`, color: 'success'})
            this.$store.commit('reloadTable', 'tableContratosExtrasEmpleado')
          }
        }).catch(e => {
          this.$store.commit('SNACK_ERROR_LIST', {expression: ' al registrar.', error: e})
        })
      },
      getContratoEmpleado (id) {
        this.axios.get(`talentohumano/contratos-empleados/${id}`)
          .then(response => {
            this.contratoEmpleado = response.data.data
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al cargar el contrato empleado.', error: e})
          })
      }
    }

  }
</script>

<style scoped>

</style>
