<template>
  <v-card>
    <loading v-model="loading"></loading>
    <dialog-descarga-ftp ref="dialogFTP"></dialog-descarga-ftp>
    <toolbar-list :info="infoComponent" title="Compensaciones" subtitle="Carga de archivos" />
<!--    btnplus btnplustruncate @click="showForm = true" btnplustitle="Nueva carga de archivos"-->
    <v-slide-y-transition>
      <filtro-registro-compensaciones
        ref="compensacionesTable"
        v-show="showForm"
        v-model="showForm"
        @filterOrSet="item => filterOrSet(item)"
        @cancel="showForm = false"
      >
      </filtro-registro-compensaciones>
    </v-slide-y-transition>
    <data-table-v2
      ref="tableReccompensaciones"
      v-model="dataTableCom"
      @resetOption="item => resetOptions(item)"
      @descargar="item => descargarOriginal(item)"
      @eliminarItem="item => eliminarItem(item)"
      :con-registro="false"
      :con-columnas="false"
      :con-buscador="false"
    ></data-table-v2>
  </v-card>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import FiltroRegistroCompensaciones from './FiltroRegistroCompensaciones'
  import DialogDescargaFtp from './DialogDescargaFtp'
  export default {
    name: 'Recompensaciones',
    components: {Loading, DialogDescargaFtp, FiltroRegistroCompensaciones},
    data: () => ({
      loading: false,
      showForm: true,
      rutaBaseUno: null,
      dataTableCom: {
        nameItemState: 'tableReccompensaciones',
        route: 'reccompensaciones/pilas',
        makeHeaders: [
          {
            text: 'Fecha de Pago',
            align: 'left',
            sortable: false,
            value: 'fecha_pago',
            classData: '',
            component: {
              template:
                `<span>{{value.fecha_pago ? value.fecha_pago : ''}}</span>`,
              props: ['value']
            }
          },
          {
            text: 'Operador',
            align: 'left',
            sortable: false,
            value: 'operador',
            classData: '',
            component: {
              template:
                `<span>{{value.operador ? value.operador : ''}}</span>`,
              props: ['value']
            }
          },
          {
            text: 'Número Planilla',
            align: 'left',
            sortable: false,
            value: 'numero_planilla'
          },
          {
            text: 'Periodo Pago',
            align: 'left',
            sortable: false,
            value: 'periodo_pago'
          },
          {
            text: 'Tipo Archivo',
            align: 'left',
            sortable: false,
            value: 'tipo_archivo'
          },
          {
            text: 'Tipo Identificación',
            align: 'left',
            sortable: false,
            value: 'tipo_documento'
          },
          {
            text: 'N° Identificación',
            align: 'left',
            sortable: false,
            value: 'numero_documento'
          },
          {
            text: 'Fecha Descarga',
            align: 'left',
            sortable: false,
            value: 'fecha_descarga'
          },
          {
            text: 'Fecha Cargue',
            align: 'left',
            sortable: false,
            value: 'fecha_cargue'
          },
          {
            text: 'Resultado',
            align: 'left',
            sortable: false,
            value: 'resultado',
            component: {
              template:
                `<span>
                  {{'CARGUE ' + (value.resultado
                      ? (value.resultado === 1
                        ? 'EXITOSO'
                        : (value.resultado === 0
                          ? 'CON INCONSISTENCIAS'
                          : (value.resultado === 2
                            ? 'CON INCONSISTENCIAS CONTENIDO'
                            : (value.resultado === 3
                              ? 'ACTIVO'
                              : 'SIN REGISTRO')))) : '')}}</span>`,
              props: ['value']
            }
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            actions: true,
            classData: 'justify-center layout px-0',
            value: 'id'
          }
        ]
      }
    }),
    created () {
      this.rutaBaseUno = this.clone(this.dataTableCom.route)
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('recCompensaciones')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        if (this.infoComponent && this.infoComponent.permisos.imprimir) item.options.push({event: 'descargar', icon: 'save_alt', tooltip: 'Descargue PILA'})
        if (this.infoComponent && this.infoComponent.permisos.agregar) item.options.push({event: 'eliminarItem', icon: 'save_alt', tooltip: 'Eliminar PILA'})
        return item
      },
      filterOrSet (item) {
        console.log('CARGUE', item)
        switch (item.opcion) {
          case 'uno':
            let rutaTemporal = this.clone(this.rutaBaseUno)
            if ((item.fecha_inicio !== null) || (item.fecha_fin !== null) || (item.numero_planilla !== null) || (item.numero_documento !== null) ||
              (item.periodo_pago !== null) || (item.tipo_archivo !== null) || (item.estadoC !== null) || (item.otraEPS !== null)) {
              let pila = {
                fechaInicio: item.fecha_inicio,
                fechaFin: item.fecha_fin,
                numeroPlanilla: item.numero_planilla,
                numeroDocumento: item.numero_documento,
                periodoPago: item.periodo_pago,
                tipoArchivo: item.tipo_archivo,
                estadoC: item.estadoC,
                otraEPS: item.otraEPS
              }
              if (rutaTemporal !== null && (rutaTemporal.indexOf('param') === 0)) rutaTemporal = this.rutaBaseUno
              let param = JSON.stringify(pila)
              rutaTemporal = `${rutaTemporal + ((rutaTemporal.indexOf('?') > -1) ? '&' : '?')}collection=${param}`
            }
            this.dataTableCom.route = rutaTemporal
            break
          case 'dos':
            this.dataTableCom.route = this.rutaBaseUno
            this.$refs.compensacionesTable.opcionEvent('ocho')
            break
          case 'tres':
            this.$refs.dialogFTP.assign(null)
            break
          case 'seis':
            this.exportar(item)
            break
        }
      },
      exportar (item) {
        if ((item.fecha_inicio === null) || (item.fecha_fin === null)) {
          this.$store.commit('SNACK_SHOW', {msg: 'Error: Fecha Inicio y Fecha Fin son obligatorios. ', color: 'error'})
        } else if (item.estadoC === null) {
          this.$store.commit('SNACK_SHOW', {msg: 'Error: Debe seleccionar un resultado de cargue. ', color: 'error'})
        } else if (item.tipo_archivo !== 'I' && item.tipo_archivo !== 'IP') {
          this.$store.commit('SNACK_SHOW', {msg: 'Error: El tipo de archivo está vacío o no es válido para exportar masivo. ', color: 'error'})
        } else {
          let pila = {
            fechaInicio: item.fecha_inicio,
            fechaFin: item.fecha_fin,
            numeroPlanilla: item.numero_planilla,
            numeroDocumento: item.numero_documento,
            periodoPago: item.periodo_pago,
            tipoArchivo: item.tipo_archivo,
            estadoC: item.estadoC,
            otraEPS: item.otraEPS
          }
          let param = JSON.stringify(pila)
          this.filePrint(`reportes-reccompensaciones-pila&collection=${param}`)
        }
      },
      descargarOriginal (item) {
        this.loading = true
        this.filePrint(`reporte-pila&ìd=${item.consecutivo_recaudo}&error=${false}`)
        this.loading = !this.loading
      },
      async eliminarItem (item) {
        await this.axios.get(`reccompensaciones/planilla-conciliada/${item.consecutivo_recaudo}`)
          .then((response) => {
            // if ((typeof response.data.sw_conciliacion !== 'undefined') && (response.data.sw_conciliacion !== null)) {
            if (typeof response.data.data !== 'undefined' && (response.data.data === false)) {
              this.axios.delete(`reccompensaciones/eliminar-conciliada/${item.consecutivo_recaudo}`)
                .then(response => {
                  if (response.data.data) {
                    this.$store.commit('SNACK_SHOW', {msg: 'La planilla conciliada se ha eliminado correctamente.', color: 'success'})
                  } else {
                    this.$store.commit('SNACK_SHOW', {msg: 'Error No Manejado.', color: 'error'})
                  }
                }).catch(e => {
                  this.$store.commit('SNACK_ERROR_LIST', {expression: ' al eliminar el registro. ', error: e})
                })
            } else {
              this.$store.commit('SNACK_SHOW', {msg: 'No es posible eliminar la planilla, ya se encuentra conciliada. ', color: 'error'})
            }
          }).catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al realizar la validación. ', error: e})
          })
        console.log('RESULTADO', item)
      }
    }
  }
</script>

<style scoped>

</style>
