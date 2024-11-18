<template>
  <div>
    <v-dialog v-model="dialog" width="500px">
      <v-form data-vv-scope="formPago">
        <v-card>
          <v-card-title class="grey lighten-3 elevation-0">
            <span class="title">{{ modalTitulo }}</span>
          </v-card-title>
          <!-- formulario de creacion-->
          <v-container fluid grid-list-md>
            <v-layout row wrap>
              
              <v-flex xs12>
                <v-text-field v-model="preferencia.descripcion"
                              label="Descripción" key="descripcion"
                              name="descripcion" v-validate="'required|max:500|'" required
                              :error-messages="errors.collect('descripcion')"></v-text-field> 

                <v-flex xs12>
                  <v-autocomplete
                    label="Tipo preferencia"
                    v-model="preferencia.tipo_preferencia"
                    :items="tiposPreferencias"
                    item-value="tipo_preferencia"
                    item-text="nombre"
                    name="tipo preferencia"
                    required
                    v-validate="'required'"
                    :error-messages="errors.collect('tipo preferencia')"
                    :filter="filterTipo"> 

                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.nombre"/>
                        </v-list-tile-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </v-flex>

                <v-text-field v-model="preferencia.valor"
                              label="Valor" key="valor"
                              name="valor" type="number"
                              :error-messages="errors.collect('valor')"></v-text-field>

                <v-text-field v-model="preferencia.valor_texto"
                              label="Valor texto" key="valor_texto"
                              name="valor texto" v-validate="'max:200'"
                              :error-messages="errors.collect('valor texto')"></v-text-field>               
              </v-flex>

            </v-layout>
          </v-container>
          <!-- fin formulario -->
          <v-card-actions class="grey lighten-4">
            <v-spacer></v-spacer>
            <v-btn @click="close">Cancelar</v-btn>
            <v-btn color="primary" @click="save" :disabled="errors.any()">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-card>
      <toolbar-list :info="infoComponent" title="Preferencias" subtitle="Registro y gestión" btnplus btnplustitle="Crear registro" btnplustruncate @click="dialog = true"/>
      <v-container fluid>
        <v-layout row wrap>
          <v-flex xs1 sm3 md6 class="text-xs-right">
            <v-tooltip top>
              <v-btn
                slot="activator"
                flat
                icon
                color="accent"
                @click="reloadPage"
              >
                <v-icon>cached</v-icon>
              </v-btn>
              <span>Actualizar registros</span>
            </v-tooltip>
          </v-flex>
          <v-flex xs12 sm3 md2>
            <v-select
              label="Registros por página"
              v-model="pagination.per_page"
              :items="items_page"
              item-text="text"
              item-value="value"
            />
          </v-flex>
          <v-flex xs12 sm6 md4>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              @input="buscar"
            />
          </v-flex>
        </v-layout>

        <loading v-model="localLoading" />
        <v-data-table
          :headers="headers"
          :items="preferencias"
          :loading="tableLoading"
          :pagination.sync="pagination"
          :total-items="preferencias.length"
          :search="search"
          hide-actions
          class="elevation-1">
          <template slot="items" slot-scope="props">
            <td>{{ props.item.consecutivo_preferencia}}</td>
            <td>{{ props.item.descripcion}}</td>
            <td>{{ props.item.tipo_preferencia | tipos(tiposPreferencias) }}</td>
            <td>{{ props.item.valor}}</td>
            <td>{{ props.item.valor_texto}}</td>
            <td>
              <v-speed-dial
                v-model="props.item.show"
                direction="left"
                open-on-hover
                transition="slide-x-transition"
              >
                <v-btn
                  slot="activator"
                  v-model="props.item.show"
                  color="blue darken-2"
                  dark
                  fab
                  small
                >
                  <v-icon>chevron_left</v-icon>
                  <v-icon>close</v-icon>
                </v-btn>
                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="eliminar(props.item.consecutivo_preferencia)"
                  >
                    <v-icon color="accent">delete</v-icon>
                  </v-btn>
                  <span>Eliminar</span>
                </v-tooltip>
                <v-tooltip top>
                  <v-btn
                    fab
                    dark
                    small
                    color="white"
                    slot="activator"
                    @click="editar(props.item)"
                  >
                    <v-icon color="accent">mode_edit</v-icon>
                  </v-btn>
                  <span>Editar</span>
                </v-tooltip>
              </v-speed-dial>
            </td>
          </template>
          <template slot="no-data">
            <v-alert  v-if="!tableLoading" :value="true" color="error" icon="warning">
              Lo sentimos, no tenemos registros para mostrar. <v-icon>sentiment_very_dissatisfied</v-icon>
            </v-alert>
            <v-alert v-else :value="true" color="info" icon="info">
              Estamos cargando los registros. <v-icon>sentiment_satisfied_alt</v-icon>
            </v-alert>
          </template>

          <template slot="footer">
            <td colspan="100%" class="text-xs-center">
              <v-pagination v-model="pagination.current_page" :total-visible="7" :length="pagination.last_page" @input="reloadPage"></v-pagination>
            </td>
          </template>
        </v-data-table>
      </v-container>
    </v-card>
    <confirmar ref="confirmo" :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaAnulacion" @aceptar="val => confirmaAnulacion(val)" />
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import Loading from '@/components/general/Loading'
  import Confirmar from '@/components/general/Confirmar2'

  export default {
    name: 'preferencia',
    components: {
      Loading,
      Confirmar,
      ToolbarList: () => import('@/components/general/ToolbarList')
    },
    data () {
      return {
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        preferencias: [],
        preferencia: {},
        search: '',
        dialog: false,
        tableLoading: false,
        localLoading: false,
        items_page: [
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
        pagination: {
          per_page: 15,
          current_page: 1
        },
        headers: [
          {
            text: 'Id',
            align: 'left',
            sortable: false,
            value: 'configuracion_salarial'
          },
          {
            text: 'Descripción',
            align: 'left',
            sortable: false,
            value: 'descripcion'
          },
          {
            text: 'Tipo Preferencia',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Valor',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Valor Texto',
            align: 'left',
            sortable: false,
            value: ''
          },
          {
            text: 'Opciones',
            align: 'left',
            sortable: false,
            value: ''
          }
        ],
        tiposPreferencias: [
          {
            tipo_preferencia: '1',
            nombre: 'Vigencia Autorización por Solicitud Hospitalaria'
          },
          {
            tipo_preferencia: '2',
            nombre: 'Vigencia Autorización por Solicitud Procedimientos Quirúrgicos'
          },
          {
            tipo_preferencia: '3',
            nombre: 'Vigencia Autorización por Solicitud Traslados'
          },
          {
            tipo_preferencia: '4',
            nombre: 'Vigencia Autorización por Solicitud Ambulatoria Quirúrgica'
          },
          {
            tipo_preferencia: '5',
            nombre: 'Vigencia Autorización por Solicitud Ambulatoria No Quirúrgica'
          },
          {
            tipo_preferencia: '6',
            nombre: 'Vigencia Autorización por Solicitud Medicamentos'
          },
          {
            tipo_preferencia: '7',
            nombre: 'Edad Máxima Afiliado'
          },
          {
            tipo_preferencia: '8',
            nombre: 'Puntaje Mínimo SISBEN'
          },
          {
            tipo_preferencia: '9',
            nombre: 'Puntaje Máximo SISBEN'
          },
          {
            tipo_preferencia: '10',
            nombre: 'Fecha Mínima Para Anular Autorización'
          },
          {
            tipo_preferencia: '11',
            nombre: 'Cantidad por defecto resultados asignar auditor'
          },
          {
            tipo_preferencia: '12',
            nombre: 'Máximo días hábiles para cierre auditoría factura'
          },
          {
            tipo_preferencia: '13',
            nombre: 'Máximo días hábiles para recibir respuesta glosa 1'
          },
          {
            tipo_preferencia: '14',
            nombre: 'Máximo días contestar respuesta glosa 1'
          },
          {
            tipo_preferencia: '15',
            nombre: 'Máximo días hábiles para recibir respuesta glosa 2'
          },
          {
            tipo_preferencia: '16',
            nombre: 'Máximo días contestar respuesta glosa 2'
          },
          {
            tipo_preferencia: '17',
            nombre: 'Máximos días calendario Respuesta Recobro'
          },
          {
            tipo_preferencia: '18',
            nombre: 'Máximo Días Acción Persuasiva Primera luego de firmeza título'
          },
          {
            tipo_preferencia: '19',
            nombre: 'Máximo Días Acción Persuasiva Segunda luego de firmeza título'
          },
          {
            tipo_preferencia: '20',
            nombre: 'Máximo Días Cobro Jurídico luego de firmeza título'
          },
          {
            tipo_preferencia: '21',
            nombre: 'Tarifario Servicio Fuera de Red'
          },
          {
            tipo_preferencia: '22',
            nombre: 'Tarifario Medicamento Fuera de Red'
          },
          {
            tipo_preferencia: '23',
            nombre: 'Tarifario Insumo Fuera de Red'
          },
          {
            tipo_preferencia: '24',
            nombre: 'Días que Aportante asume en incapacidad'
          },
          {
            tipo_preferencia: '25',
            nombre: 'Días máximo auxilio liquidación prestaciones económicas'
          },
          {
            tipo_preferencia: '26',
            nombre: 'Porcentaje Reconoce EPS Incapacidad Enfermedad General'
          },
          {
            tipo_preferencia: '27',
            nombre: 'Días Mínimo cotización reconocimiento Incapacidades'
          },
          {
            tipo_preferencia: '28',
            nombre: 'Días mínimo cotización reconocimiento Licencia independiente'
          },
          {
            tipo_preferencia: '29',
            nombre: 'Porcentaje adicional beneficio Independientes prestaciones económicas'
          },
          {
            tipo_preferencia: '30',
            nombre: 'Días Parámetro Licencia maternidad 98 hasta el 4 de enero 2017'
          },
          {
            tipo_preferencia: '31',
            nombre: 'Días Parámetro Licencia maternidad 126 desde 5 de enero 2017'
          },
          {
            tipo_preferencia: '32',
            nombre: 'Días máximo a reconocer Parto No viable'
          },
          {
            tipo_preferencia: '33',
            nombre: 'Días máximo a reconocer Licencia paternidad'
          },
          {
            tipo_preferencia: '34',
            nombre: 'Días adicionales a reconocer parto prematuro múltiple'
          },
          {
            tipo_preferencia: '35',
            nombre: 'Días adicionales a reconocer parto múltiple'
          },
          {
            tipo_preferencia: '36',
            nombre: 'Días adicionales a reconocer parto proporcional múltiple'
          },
          {
            tipo_preferencia: '37',
            nombre: 'Días adicionales a reconocer parto proporcional prematuro múltiple'
          },
          {
            tipo_preferencia: '38',
            nombre: 'Porcentaje provisión prestación económica'
          },
          {
            tipo_preferencia: '39',
            nombre: 'Periodos máximos en mora para suspensión de afiliado'
          },
          {
            tipo_preferencia: '40',
            nombre: 'Edad mínima para ser cónyuges o compañeros permanentes'
          },
          {
            tipo_preferencia: '41',
            nombre: 'Edad mínima para ser cotizantes'
          },
          {
            tipo_preferencia: '42',
            nombre: 'Edad mínima para permitir Ocupación y Profesión'
          },
          {
            tipo_preferencia: '43',
            nombre: 'URL PQR externo '
          },
          {
            tipo_preferencia: '44',
            nombre: 'Día hábil máximo para afiliación'
          },
          {
            tipo_preferencia: '45',
            nombre: 'Consecutivo Motivo Causal retiro afiliado PILA'
          },
          {
            tipo_preferencia: '46',
            nombre: 'Consecutivo Actividad económica por defecto para afiliación PILA'
          },
          {
            tipo_preferencia: '47',
            nombre: 'Consecutivo Soporte que define Pago Factura'
          },
          {
            tipo_preferencia: '48',
            nombre: 'Días calendario esperar respuesta radicación NO POS'
          },
          {
            tipo_preferencia: '49',
            nombre: 'Días calendario para presentar objeción NO POS  '
          },
          {
            tipo_preferencia: '50',
            nombre: 'Nombre del cargo de la persona que aparece en la firma de los certificado de Afiliación'
          },
          {
            tipo_preferencia: '51',
            nombre: 'Actividad económica que no se reporta en los archivos'
          },
          {
            tipo_preferencia: '52',
            nombre: 'Código del Tipo Cotizante Independiente que no valida que el Cotizante y el Aportante sean iguales '
          },
          {
            tipo_preferencia: '53',
            nombre: 'Código de los sub tipo contizante de pensionados que no se validan contra Actividad Económica'
          },
          {
            tipo_preferencia: '54',
            nombre: 'Código del sub tipo cotizante pensionado sustituto'
          },
          {
            tipo_preferencia: '55',
            nombre: 'Días máximo a reconocer Licencia Fallecimiento Madre'
          },
          {
            tipo_preferencia: '56',
            nombre: 'Porcentaje reconoce EPS Incapacidad Enfermedad General a partir de 91 días y menor o igual a 180 días'
          },
          {
            tipo_preferencia: '57',
            nombre: 'Recobro ARL Consecutivo Causal genCausal - Rechazado'
          },
          {
            tipo_preferencia: '58',
            nombre: 'Recobro ARL Consecutivo Causal genCausal - Conciliado'
          },
          {
            tipo_preferencia: '59',
            nombre: 'Recobro AC Consecutivo Causal genCausal - Glosado'
          },
          {
            tipo_preferencia: '60',
            nombre: 'Recobro AC Consecutivo Causal genCausal - Conciliado'
          },
          {
            tipo_preferencia: '61',
            nombre: 'Recobro NO POS Consecutivo Causal genCausal - Glosado MYT01/02'
          },
          {
            tipo_preferencia: '62',
            nombre: 'Recobro NO POS Consecutivo Causal genCausal - Glosado MYT04'
          },
          {
            tipo_preferencia: '63',
            nombre: 'Edad meses de nacimientos en estado Pendiente MC o MS, que se visualizan como activos'
          },
          {
            tipo_preferencia: '64',
            nombre: 'Porcentaje reconoce EPS Incapacidad Enfermedad General a partir de 181 días y menor o igual a 540 días'
          },
          {
            tipo_preferencia: '65',
            nombre: 'Porcentaje reconoce EPS Incapacidad Enfermedad General a partir de 541 días'
          },
          {
            tipo_preferencia: '66',
            nombre: 'Código de la Apropiación Orden de pago'
          },
          {
            tipo_preferencia: '67',
            nombre: 'Código de Fondo Orden de pago'
          },
          {
            tipo_preferencia: '68',
            nombre: 'Código Concepto Orden de pago para Copagos'
          },
          {
            tipo_preferencia: '69',
            nombre: 'Código Concepto Orden de pago para Cuota Moderadora'
          },
          {
            tipo_preferencia: '70',
            nombre: 'Código Centro de Costo Orden de pago'
          },
          {
            tipo_preferencia: '71',
            nombre: 'Contributivo PBS'
          },
          {
            tipo_preferencia: '72',
            nombre: 'Contributivo No PBS'
          },
          {
            tipo_preferencia: '73',
            nombre: 'Contrubutivo Tutelas (Por No PBS)'
          },
          {
            tipo_preferencia: '74',
            nombre: 'Subsidiado PBS'
          },
          {
            tipo_preferencia: '75',
            nombre: 'Subsidiado No PBS'
          },
          {
            tipo_preferencia: '76',
            nombre: 'Subsidiado Tutelas (Por No PBS)'
          },
          {
            tipo_preferencia: '77',
            nombre: 'URL para visualizar la carta de derechos y deberes'
          }
        ],
        filterTipo (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        }
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {

    },
    created () {
      this.reloadPage()
    },
    watch: {
      'pagination.per_page' () {
        this.pagination.current_page = 1
        this.reloadPage()
      }
    },
    computed: {
      infoComponent () {
        return this.$store.getters.getInfoComponent('preferencia')
      },
      modalTitulo () {
        return !this.preferencia.consecutivo_preferencia ? 'Nueva preferencia' : 'Editar preferencia: ' + this.preferencia.consecutivo_preferencia
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      }
    },
    methods: {
      reloadPage () {
        this.tableLoading = true
        this.localLoading = true
        this.axios.get('preferencia?per_page=' + this.pagination.per_page + '&page=' + this.pagination.current_page + '&search=%25' + this.search + '%25')
          .then((response) => {
            response.data.meta.per_page = this.items_page.find(page => page.value === parseInt(response.data.meta.per_page)) ? parseInt(response.data.meta.per_page) : -1
            this.pagination = response.data.meta
            this.preferencias = response.data.data
            this.tableLoading = false
            this.localLoading = false
          })
          .catch(e => {
            this.tableLoading = false
            this.localLoading = false
            return false
          })
      },
      buscar: window.lodash.debounce(function () {
        this.pagination.current_page = 1
        this.reloadPage()
      }, 500),
      formReset () {
        this.preferencia = {
          descripcion: null,
          tipo_preferencia: null,
          valor: null,
          valor_texto: null
        }
      },
      eliminar (item) {
        this.dialogA.content = '¿Está seguro de eliminar?'
        this.dialogA.registroID = item
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
        this.dialogA.registroID = null
      },
      confirmaAnulacion (motivo) {
        this.localLoading = true
        this.axios.delete('preferencia/eliminar/' + this.dialogA.registroID)
          .then(res => {
            this.$store.commit(SNACK_SHOW, {msg: 'La preferencia se eliminó correctamente', color: 'success'})
            this.reloadPage()
            this.cancelaAnulacion()
            this.localLoading = false
            this.$refs.confirmo.pararCarga()
          })
          .catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al eliminar el registro. ' + e.response, color: 'danger'})
          })
      },
      editar (item) {
        console.log('el item', item)
        this.dialog = true
        this.preferencia = Object.assign({}, item)
        this.preferencia.tipo_preferencia = item.tipo_preferencia.toString()
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async save () {
        if (await this.validador('formPago')) {
          this.dialog = false
          this.localLoading = true
          var send = !this.preferencia.consecutivo_preferencia ? this.axios.post('preferencia/crear', this.preferencia) : this.axios.put('preferencia/actualizar/' + this.preferencia.consecutivo_preferencia, this.preferencia)
          send.then(response => {
            this.localLoading = false
            if (this.preferencia.preferencia) {
              this.$store.commit(SNACK_SHOW, {msg: 'La preferencia se actualizó correctamente', color: 'success'})
              // this.traslados.splice(this.traslados.findIndex(traslados => traslados.consecutivo_vigencia === response.data.data.consecutivo_vigencia), 1, response.data.data)
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Registro exitoso', color: 'success'})
              this.reloadPage()
              // this.traslados.splice(0, 0, response.data.data)
            }
            this.dialog = false
          }).catch(e => {
            this.localLoading = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
        this.formReset()
      }
    },
    filters: {
      tipos: function (val, tipos) {
        for (let i = 0; i < tipos.length; i++) {
          const element = tipos[i]
          if (val.toString() === element.tipo_preferencia) {
            return element.nombre
          }
        }
      }
    }

  }
</script>

<style scoped>


</style>
