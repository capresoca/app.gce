<template>
  <v-card>
    <v-dialog v-model="dialogCargue" persistent max-width="600px">
      <v-card>
        <toolbar-list :title="`Cargue Log ${bolItem ? 'Financiero' : 'Bancario'}`"/>
        <v-form data-vv-scope="formLog" v-if="!bolItem">
          <v-container grid-list-sm>
            <v-layout row wrap>
              <v-flex xs12 sm12>
                <input-file
                  ref="archivoAdjunto"
                  label="Adjuntar Archivo"
                  v-model="item.archivo"
                  :file-name="item.archivo ? item.archivo.nombre : null"
                  accept="application/x-zip-compressed"
                  :hint="'Extenciones aceptadas: zip'"
                  class="mb-3"
                  prepend-icon="attach_file"
                />
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
        <v-slide-y-transition v-else>
          <v-container >
            <v-layout>
              <v-flex xs12 sm12>
                <v-list-tile avatar v-for="(item, index) in resultado" :key="index">
                  <v-list-tile-content>
                    <v-list-tile-title :class="item.color" v-html="`${item.name}`" ></v-list-tile-title>
                  </v-list-tile-content>
                  <v-list-tile-avatar>
                    <v-btn
                      fab
                      flat
                      v-html="`<b>${item.value}</b>`"
                    />
                  </v-list-tile-avatar>
<!--                  <v-tooltip top>-->
<!--                    <v-btn-->
<!--                      small-->
<!--                      slot="activator"-->
<!--                      ripple-->
<!--                      icon-->
<!--                    >-->
<!--                      <v-icon color="accent">mode_edit</v-icon>-->
<!--                    </v-btn>-->
<!--                    <span>Editar</span>-->
<!--                  </v-tooltip>-->
                </v-list-tile>
              </v-flex>
            </v-layout>
          </v-container>
        </v-slide-y-transition>
        <v-card-actions class="grey lighten-3">
          <v-spacer></v-spacer>
          <v-btn flat @click="close" v-text="!bolItem ? 'Cancelar' : 'Cerrar'"></v-btn>
          <v-btn v-if="!bolItem" color="blue darken-1" @click.prevent="validar()" flat v-text="'Cargar'"></v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="dialogLoader"
      hide-overlay
      persistent
      width="300"
    >
      <v-card
        color="primary"
        dark
      >
        <v-card-text>
          <span v-text="message"></span>
          <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
          ></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
    <toolbar-list :info="infoComponent" title="Log Bancarios" subtitle="Carga de archivos" />
    <!--    btnplus btnplustruncate @click="showForm = true" btnplustitle="Nueva carga de archivos"-->
    <v-slide-y-transition>
      <filtro-log-bancarios
        ref="logbancarios"
        v-show="showForm"
        v-model="showForm"
        @filterOrSet="item => filterOrSet(item)"
        @cancel="showForm = false">
      </filtro-log-bancarios>
    </v-slide-y-transition>
    <data-table-v2
      ref="tableReflogbancario"
      v-model="dataTableLog"
      @resetOption="item => resetOptions(item)"
      :con-registro="false"
      :con-columnas="false"
      :con-buscador="false"
    ></data-table-v2>
  </v-card>
</template>

<script>
  export default {
    name: 'Relogbancario',
    components: {
      InputFile: () => import('../../../../general/InputFile'),
      FiltroLogBancarios: () => import('./FiltroLogBancarios')
    },
    data () {
      return {
        showForm: true,
        rutaBaseUno: null,
        item: null,
        resultado: [],
        dialogCargue: false,
        dialogLoader: false,
        message: '',
        bolItem: false,
        dataTableLog: {
          nameItemState: 'tableReflogbancario',
          route: 'reccompensaciones/log_bancarindexs',
          makeHeaders: [
            {
              text: 'Fecha de Pago',
              align: 'left',
              sortable: false,
              value: 'fecha_pago',
              component: {
                template:
                  `<span>{{value.fecha_pago ? moment(value.fecha_pago).format('YYYY/MM/DD') : ''}}</span>`,
                props: ['value']
              }
            },
            {
              text: 'Tipo Archivo',
              align: 'left',
              sortable: false,
              value: 'tipo_archivo',
              component: {
                template:
                  `<span>{{value.tipo_archivo ? 'Recaudo ' + (value.tipo_archivo === 1 ? 'Aportante' : 'SGP') : ''}}</span>`,
                props: ['value']
              }
            },
            {
              text: 'Cantidad de Recaudos',
              align: 'left',
              sortable: false,
              classData: 'text-xs-center',
              value: 'cantidad_archivos'
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
              value: 'estado',
              component: {
                template:
                  `<span>{{value.estado ? 'Cargue ' + (value.estado === 1 ? 'Exitoso' : 'Inconsistencias') : ''}}</span>`,
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
      }
    },
    created () {
      this.rutaBaseUno = this.clone(this.dataTableLog.route)
      this.formReset()
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('recLogBancario')
      }
    },
    methods: {
      resetOptions (item) {
        item.options = []
        return item
      },
      filterOrSet (item) {
        switch (item.opcion) {
          case 'uno':
            let rutaTemporal = this.clone(this.rutaBaseUno)
            if ((item.fecha_inicio !== null) || (item.fecha_fin !== null) || (item.banco !== null) || (item.tipo_archivo !== null)) {
              let logBancario = {
                fecha_inicio: item.fecha_inicio,
                fecha_fin: item.fecha_fin,
                banco: item.banco,
                tipo_archivo: item.tipo_archivo
              }
              if (rutaTemporal !== null && (rutaTemporal.indexOf('param') === 0)) rutaTemporal = this.rutaBaseUno
              let param = JSON.stringify(logBancario)
              rutaTemporal = `${rutaTemporal + ((rutaTemporal.indexOf('?') > -1) ? '&' : '?')}collection=${param}`
            }
            this.dataTableLog.route = rutaTemporal
            break
          case 'dos':
            this.dataTableLog.route = this.rutaBaseUno
            this.$refs.logbancarios.optionEvent('ocho')
            break
          case 'tres':
            this.dialogCargue = !this.dialogCargue
            this.dataTableLog.route = this.rutaBaseUno
            break
        }
      },
      formReset () {
        this.item = {
          archivo: null
        }
        this.resultado = [
          {
            name: 'Cantidad Archivos Procesados',
            value: 0,
            color: 'black--text'
          },
          {
            name: 'Cantidad Archivos Cargados Èxitosamente',
            value: 0,
            color: 'green--text'
          },
          {
            name: 'Cantidad Archivos Con Inconsistencias',
            value: 0,
            color: 'red--text'
          }
        ]
      },
      close () {
        this.formReset()
        this.dialogCargue = false
        this.dialogLoader = false
        this.message = ''
        this.bolItem = false
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async validar () {
        let validateFile = await this.$refs.archivoAdjunto.validate()
        if (await this.validador('formLog') && validateFile && (this.item.archivo !== null)) {
          this.dialogLoader = true
          this.message = 'Validando archivo...'
          let formData = new FormData()
          formData.append('archivo', typeof this.item.archivo === 'undefined' ? '' : this.item.archivo)
          this.axios.post(`reccompensaciones/logs_bancarios`, formData)
            .then(response => {
              let data = this.clone(response.data.data)
              this.resultado[0].value = data.archivosProcesados
              this.resultado[1].value = data.cargadosConExito
              this.resultado[2].value = data.cargadosConInconsistencia
              this.bolItem = !this.bolItem
              this.dialogLoader = false
              this.message = ''
              this.$store.commit('SNACK_SHOW', {msg: 'Validación y Cargue correctamente realizado.', color: 'success'})
            }).catch(e => {
              this.dialogLoader = false
              this.message = ''
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' Error en el cargue del archivo. ', error: e})
            })
        } else {
          this.$store.commit('SNACK_SHOW', {msg: 'Se debe cargar un archivo para iniciar el proceso.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>
