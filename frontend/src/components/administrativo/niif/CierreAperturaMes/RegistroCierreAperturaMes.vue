<template>
    <div>
      <!--height="442px  :info="infoComponent""-->
      <v-card>
        <toolbar-list title="Cierre y Apertura de Mes" subtitle="Gestión"/>
        <v-data-table
          :headers="headers"
          :items="datos"
          :loading="tableLoading">
          <template slot="items" slot-scope="props">
            <td>
              <v-btn fab dark small class="elevation-0 pa-1" color="primary" v-text="props.index + 1 "></v-btn>
            </td>
            <td>{{ props.item.mes }}</td>
            <td>{{ props.item.year }}</td>
            <td>{{ props.item.estado }}</td>
            <td class="text-xs-center">
              <v-tooltip top>
                <v-btn
                  small class="mx-0 elevation-0"
                  fab @click="props.item.estado === 'Abierto' ? cerrarMes(props.item) : abrirMes(props.item)"
                  slot="activator">
                  <v-icon :color="props.item.estado === 'Abierto' ? 'red' : 'green'" v-text="'fas fa-' + (props.item.estado === 'Abierto' ? 'door-closed' : 'door-open')">lock</v-icon>
                </v-btn>
                <span v-text="(props.item.estado === 'Abierto' ? 'Cerrar' : 'Abrir') + ' Mes'"></span>
              </v-tooltip>
            </td>
          </template>
          <template slot="no-data">
            <v-alert  v-if="tableLoading" :value="true" color="error" icon="warning">
              Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
            </v-alert>
            <v-alert v-else :value="true" color="info" icon="info">
              Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
            </v-alert>
          </template>
        </v-data-table>
      </v-card>
      <div>
        <v-dialog v-model="dialogCierreMes" persistent max-width="290">      
          <v-card>
            <v-card-title class="headline">Cerrar Mes</v-card-title>
            <v-card-text>¿Esta seguro de que desea cerrar el mes?</v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="green darken-1" flat @click="ordenCerrarMes">Cerrar Mes</v-btn>
              <v-btn color="grey darken-1" flat @click="cancelarCerrarMes">Cancelar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </div>
      <div>
        <v-dialog v-model="dialogAbrirMes" persistent max-width="290">      
          <v-card>
            <v-card-title class="headline">Abrir Mes</v-card-title>
            <v-card-text>¿Esta seguro de abrir el mes?</v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="green darken-1" flat @click="ordenAbrirMes">Abrir Mes</v-btn>
              <v-btn color="grey darken-1" flat @click="cancelarCerrarMes">Cancelar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </div>
  </div>
</template>
<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import moment from 'moment'
  export default {
    name: 'RegistroCierreAperturaMes',
    components: {
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        fechaActual: '2018-01-01',
        fechaComprobanteDestino: false,
        mensaje: 'asda',
        dialogCierreMes: false,
        dialogAbrirMes: false,
        headers: [
          {
            text: '',
            align: 'left',
            sortable: false,
            value: 'mes'
          },
          {
            text: 'Mes',
            align: 'left',
            sortable: false,
            value: 'mes'
          },
          {
            text: 'Año',
            align: 'left',
            sortable: false,
            value: 'year'
          },
          {
            text: 'Estado',
            align: 'left',
            sortable: false,
            value: 'estado'
          },
          {
            text: 'Acciones',
            align: 'center',
            sortable: false,
            value: 'id'
          }
        ],
        datos: [],
        tableLoading: false,
        comprobante: {
          fecha: null,
          comprobanteorigen: null
        },
        registro: {
          id: null,
          mes: null,
          year: null,
          estado: null
        }
      }
    },
    methods: {
      cerrarMes (data) {
        this.registro = data
        this.dialogCierreMes = true
      },
      abrirMes (data) {
        this.registro = data
        this.dialogAbrirMes = true
      },
      ordenCerrarMes () {
        this.axios.post('cerrar-mes-comdiario', this.registro)
          .then(response => {
            if (response.data.state === 'ok') {
              this.$store.commit(SNACK_SHOW, {msg: 'El mes se cerro correctamente', color: 'success'})
              this.formReset()
              this.datos = response.data.datos
            }
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al obtener el consecutivo ' + e.response, color: 'danger'})
          })
      },
      cancelarCerrarMes () {
        this.dialogAbrirMes = false
        this.dialogCierreMes = false
        this.registro = {
          id: null,
          mes: null,
          year: null,
          estado: null
        }
      },
      ordenAbrirMes () {
        this.axios.post('abrir-mes-comdiario', this.registro)
          .then(response => {
            if (response.data.state === 'ok') {
              this.$store.commit(SNACK_SHOW, {msg: 'El mes se abrio correctamente', color: 'success'})
              this.formReset()
              this.datos = response.data.datos
            }
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Error al obtener el consecutivo ' + e.response, color: 'danger'})
          })
      },
      cancelarAbrirMes () {
        this.dialogAbrirMes = false
        this.dialogCierreMes = false
        this.registro = {
          id: null,
          mes: null,
          year: null,
          estado: null
        }
      },
      formReset () {
        this.dialogAbrirMes = false
        this.dialogCierreMes = false
        this.registro = {
          id: null,
          mes: null,
          year: null,
          estado: null
        }
        this.datos = []
      }
    },
    mounted () {
      this.tableLoading = true
      this.axios.get('cierre-apertura-meses')
        .then(response => {
          if (response.data.datos.length > 0) {
            this.datos = response.data.datos
          } else {
          }
          this.tableLoading = false
        }).catch(e => {
          this.$store.commit(SNACK_SHOW, {msg: 'Error al obtener el consecutivo ' + e.response, color: 'danger'})
        })
      this.fechaActual = moment().format('YYYY-MM') + '-01'
    }
  }
</script>
