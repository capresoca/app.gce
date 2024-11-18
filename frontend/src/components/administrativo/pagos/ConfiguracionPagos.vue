<template>
    <v-card>
      <v-toolbar class="grey lighten-3 elevation-0">
        <v-toolbar-title class="title">CONFIGURACIÓN DE PAGOS</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon color="white" v-if="configuracionPago.id" flat @click="isEditing = !isEditing">
          <v-icon color="accent" v-text="'edit'"></v-icon>
        </v-btn>
      </v-toolbar>
      <v-container fluid  grid-list-xs grid-list-xl class="pt-3">
        <v-layout row wrap>
          <v-flex v-if="!configuracionPago.id" xs12 md12 class="pt-3">
            <v-alert v-show="!configuracionPago.id" :value="true" color="error" icon="warning" width="500px">
              {{ message }}
            </v-alert>
          </v-flex>
          <v-form style="max-width: 100%" data-vv-scope="formPrincipal">
              <v-container fluid grid-list-xl>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md5 xl5  class="pa-2" ref="cargar">
                    <v-card>
                      <v-card-title class="grey lighten-3">
                        <span class="title">Cuentas</span>
                        <v-spacer></v-spacer>
                        <small>{{ isEditing ? 'En edición' : '' }}</small>
                      </v-card-title>
                      <v-container fluid>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 xl12>
                            <postulador
                              nodata="Busqueda por código o nombre."
                              required
                              hint="nombre"
                              itemtext="codigo"
                              datatitle="codigo"
                              datasubtitle="nombre"
                              filter="codigo,nombre"
                              :label="configuracionPago.nf_niifaprovechamiento_id ? 'Aprovechamiento ' : 'cuenta de aprovechamiento'"
                              lite
                              scope="formPrincipal"
                              ref="cuentaAprovechamiento"
                              entidad="niifs"
                              routeparams="nivel:auxiliar=1"
                              preicon="person"
                              :disabled="!configuracionPago.id ? false : !isEditing"
                              @change="val => configuracionPago.nf_niifaprovechamiento_id = val"
                              @objectReturn="val => configuracionPago.niif_aprovechamiento = val"
                              :value="configuracionPago.niif_aprovechamiento"
                              clearable
                            ></postulador>
                            <!--<postulador label="cuenta de aprovechamiento"  lite></postulador>-->
                          </v-flex>
                          <v-flex xs12 sm12 md12 xl12>
                            <postulador
                              nodata="Busqueda por código o nombre."
                              required
                              hint="nombre"
                              itemtext="codigo"
                              datatitle="nombre"
                              datasubtitle="codigo"
                              filter="codigo,nombre"
                              :label="configuracionPago.nf_niifdescuento_id ? 'Descuento' : 'cuenta de descuento'"
                              lite
                              scope="formPrincipal"
                              ref="cuentaDescuento"
                              entidad="niifs"
                              routeparams="nivel:auxiliar=1"
                              preicon="person"
                              :disabled="!configuracionPago.id ? false : !isEditing"
                              @change="val => configuracionPago.nf_niifdescuento_id = val"
                              @objectReturn="val => configuracionPago.niif_descuento = val"
                              :value="configuracionPago.niif_descuento"
                              clearable
                            ></postulador>
                            <!--<postulador label="cuenta de descuento" lite></postulador>-->
                          </v-flex>
                        </v-layout>
                      </v-container>
                    </v-card>
                  </v-flex>
                  <v-flex xs12 sm12 md7 xl7 class="pa-2">
                    <v-card>
                      <v-card-title class="grey lighten-3">
                        <span class="title">Tipo de comprobante</span>
                        <v-spacer></v-spacer>
                        <small>{{ isEditing ? 'En edición' : '' }}</small>
                      </v-card-title>
                      <v-container fluid>
                        <v-layout row wrap >
                          <v-flex xs12 sm6 md6 xl6>
                            <v-autocomplete
                              label="CXP"
                              v-model="configuracionPago.tipcomp_cxp"
                              :items="complementos.tipcomdiarios"
                              @change="configuracionPago.nf_tipcomcxp_id = configuracionPago.tipcomp_cxp ? configuracionPago.tipcomp_cxp.id : null"
                              item-value="id"
                              item-text="codigo"
                              persistent-hint
                              :hint="configuracionPago.tipcomp_cxp ? configuracionPago.tipcomp_cxp.nombre : null"
                              name="cxp"
                              :disabled="!configuracionPago.id ? false : !isEditing"
                              return-object
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('cxp')"
                              clearable
                            >
                              <!--autocomplete-->
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.codigo"/>
                                    <v-list-tile-sub-title v-html="data.item.nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                              <template slot="no-data">
                                <div class="pa-2">
                                  <span>No se encuentra el comprobante.</span>
                                </div>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm6 md6 xl6>
                            <v-autocomplete
                              label="Traslado"
                              v-model="configuracionPago.tipcomp_traslado"
                              :items="complementos.tipcomdiarios"
                              @change="configuracionPago.nf_tipcomtraslado_id = configuracionPago.tipcomp_traslado ? configuracionPago.tipcomp_traslado.id : null"
                              item-value="id"
                              item-text="codigo"
                              persistent-hint
                              :hint="configuracionPago.tipcomp_traslado ? configuracionPago.tipcomp_traslado.nombre : null"
                              name="traslado"
                              :disabled="!configuracionPago.id ? false : !isEditing"
                              return-object
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('traslado')"
                              clearable
                            >
                              <!--autocomplete-->
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.codigo"/>
                                    <v-list-tile-sub-title v-html="data.item.nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                              <template slot="no-data">
                                <div class="pa-2">
                                  <span>No se encuentra el comprobante.</span>
                                </div>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm6 md6 xl6>
                            <v-autocomplete
                              label="Nota débito"
                              v-model="configuracionPago.tipcomp_ndebito"
                              :items="complementos.tipcomdiarios"
                              @change="configuracionPago.nf_tipcomnotadebito_id = configuracionPago.tipcomp_ndebito ? configuracionPago.tipcomp_ndebito.id : null"
                              item-value="id"
                              item-text="codigo"
                              persistent-hint
                              :hint="configuracionPago.tipcomp_ndebito ? configuracionPago.tipcomp_ndebito.nombre : null"
                              name="nota débito"
                              :disabled="!configuracionPago.id ? false : !isEditing"
                              return-object
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('nota débito')"
                              clearable
                            >
                              <!--autocomplete-->
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.codigo"/>
                                    <v-list-tile-sub-title v-html="data.item.nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                              <template slot="no-data">
                                <div class="pa-2">
                                  <span>No se encuentra el comprobante.</span>
                                </div>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                          <v-flex xs12 sm6 md6 xl6>
                            <v-autocomplete
                              label="Nota crédito"
                              v-model="configuracionPago.tipcomp_ncredito"
                              :items="complementos.tipcomdiarios"
                              @change="configuracionPago.nf_tipcomnotacredito_id = configuracionPago.tipcomp_ncredito ? configuracionPago.tipcomp_ncredito.id : null"
                              item-value="id"
                              item-text="codigo"
                              persistent-hint
                              :hint="configuracionPago.tipcomp_ncredito ? configuracionPago.tipcomp_ncredito.nombre : null"
                              name="nota crédito"
                              :disabled="!configuracionPago.id ? false : !isEditing"
                              return-object
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('nota crédito')"
                              clearable
                            >
                              <!--autocomplete-->
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.codigo"/>
                                    <v-list-tile-sub-title v-html="data.item.nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                              <template slot="no-data">
                                <div class="pa-2">
                                  <span>No se encuentra el comprobante.</span>
                                </div>
                              </template>
                            </v-autocomplete>
                          </v-flex>
                        </v-layout>
                      </v-container>
                    </v-card>
                  </v-flex>
                </v-layout>
                <v-layout row wrap v-if="infoComponent ? infoComponent.permisos.agregar : false">
                  <v-flex xs12 class="text-xs-right pr-0">
                    <v-spacer></v-spacer>
                    <v-slide-y-transition>
                      <!--<v-btn v-if="isEditing == false">Cancelar</v-btn>-->
                      <v-btn color="primary" v-if="isEditing == false && !configuracionPago.id ? true : (isEditing == true && configuracionPago.id ? true : false) " @click.prevent="saveContable" v-text="!configuracionPago.id ? 'Registrar' : 'Actualizar'"></v-btn>
                    </v-slide-y-transition>
                  </v-flex>
                </v-layout>
              </v-container>
          </v-form>
          <v-dialog v-model="dialogEliminar" width="300px">
            <v-card>
              <v-card-title>
                <span class="title">¿Está seguro de eliminar el rango?</span>
              </v-card-title>
              <v-card-actions class="grey lighten-4">
                <v-spacer></v-spacer>
                <v-btn small @click.prevent="eliminarRango()">No</v-btn>
                <v-btn class="primary" small @click.prevent="eliminarRango(rango)">Si</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-layout class="pt-0 pb-1 pl-3 pr-3">
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" width="500px" v-if="infoComponent ? infoComponent.permisos.agregar : false">
              <!--<v-icon>add</v-icon>-->
              <!--<v-spacer></v-spacer>-->
              <v-btn slot="activator" color="primary" v-show="configuracionPago.id" dark>Agregar Rangos</v-btn>
              <v-card>
                <v-card-title class="grey lighten-3">
                  <span class="title">Agregar Rango </span>
                </v-card-title>
                <v-form data-vv-scope="formRango">
                  <v-container fluid grid-list-lg>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-text-field v-model="rango.codigo" key="código"
                                      name="código" required v-validate="'required|max:5'"
                                      :error-messages="errors.collect('código')" label="Código" ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-text-field v-model="rango.nombre" key="nombre"
                                      name="nombre" required v-validate="'required'"
                                      :error-messages="errors.collect('nombre')" label="Nombre" ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-text-field :step="0.1" ref="vIncial" type="number" v-model.number="rango.valor_inicial" key="valor inicial"
                                      name="valor inicial" required v-validate="'required'"
                                      :error-messages="valor_value != '' ? valor_value : errors.collect('valor inicial')" label="Valor Inicial" @input="validaMensaje" :min="1" :max="rango.valor_final"></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-text-field type="number" v-model.number="rango.valor_final" key="valor final"
                                      name="valor final" required v-validate="'required'"
                                      :error-messages="valor_value2 != '' ? valor_value2 : errors.collect('valor final')" label="Valor Final" @input="validaMensaje" :min="rango.valor_inicial"></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md12 lg12 class="text-sm-right pt-2">
                        <div sm10>
                          <v-btn @click.prevent="close()">Cancelar</v-btn>
                          <v-btn color="primary" @click.prevent="agregarRango(rango)">Agregar</v-btn>
                        </div>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-form>
              </v-card>
            </v-dialog>
          </v-layout>
          <v-flex xs12 sm12 md12 v-if="configuracionPago.rangos.length > 0">
            <template>
              <v-card>
                <v-card-title class="grey lighten-3">
                  <h3>Rangos Registrados</h3>
                </v-card-title>
                <v-divider></v-divider>
                <!--<v-alert class="py-1" v-if="valorEnd !== ''" v-model="valorEnd" color="error" type="error" @input="validaMensaje">-->
                  <!--{{ validarDetalles(detalle).mensaje_error_rango }}-->
                <!--</v-alert>-->
                <v-divider></v-divider>
                <v-data-table
                              :headers="headers"
                              :items="configuracionPago.rangos"
                              :loading="tableLoading"
                              :pagination.sync="pagination"
                              hide-actions rows-per-page-text="Registros por página"
                              :rows-per-page-items='[10,25,50,{"text":"Todos","value":-1}]'>
                  <template slot="items" slot-scope="props">
                    <td>{{ props.item.codigo }}</td>
                    <td>{{ props.item.nombre }}</td>
                    <td>{{ props.item.valor_inicial }}</td>
                    <td>{{ props.item.valor_final }}</td>
                    <td class="text-xs-center">
                      <v-btn icon class="mx-0" fab small @click="preguntarEliminarRango(props.item)"> <v-icon color="accent">delete</v-icon> </v-btn>
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
                  <template slot="pageText" slot-scope="props">
                    {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                  </template>
                </v-data-table>
              </v-card>
            </template>
          </v-flex>
        </v-layout>
      </v-container>
    </v-card>
</template>

<script>
  import store from '../../../store/complementos/index'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'ConfiguracionPagos',
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    data () {
      return {
        configuracionesPagos: [],
        configuracionPago: {
          id: '',
          nf_niifaprovechamiento_id: null,
          nf_niifdescuento_id: null,
          nf_tipcomcxp_id: null,
          nf_tipcomnotadebito_id: null,
          nf_tipcomtraslado_id: null,
          nf_tipcomnotacredito_id: null,
          tipcomp_ndebito: null,
          tipcomp_traslado: null,
          tipcomp_ncredito: null,
          tipcomp_cxp: null,
          niif_aprovechamiento: null,
          niif_descuento: null,
          rangos: []
        },
        snackbarStatic: true,
        isEditing: false,
        message: null,
        rango: {},
        dialog: false,
        dialogEliminar: false,
        valor_value: '',
        valor_value2: '',
        valorEnd: '',
        tableLoading: false,
        pagination: {},
        headers: [
          {
            text: 'Código',
            align: 'left',
            sortable: false,
            value: 'codigo'
          },
          {
            text: 'Nombre',
            align: 'left',
            sortable: false,
            value: 'nombre'
          },
          {
            text: 'Valor Inicial',
            align: 'left',
            sortable: false,
            value: 'valor_inicial'
          },
          {
            text: 'Valor Final',
            align: 'left',
            sortable: false,
            value: 'valor_final'
          },
          {
            text: 'Opciones',
            align: 'center',
            sortable: false,
            value: 'id'
          }
        ]
      }
    },
    watch: {
      'configuracionPago.tipcomp_traslado' (val) {
        if (val === null || val === undefined) {
          this.configuracionPago.tipcomp_traslado = null
          this.$validator.reset()
        }
      },
      'configuracionPago.tipcomp_cxp' (val) {
        if (val === null || val === undefined) {
          this.configuracionPago.tipcomp_cxp = null
          this.$validator.reset()
        }
      },
      'configuracionPago.tipcomp_ndebito' (val) {
        if (val === null || val === undefined) {
          this.configuracionPago.tipcomp_ndebito = null
          this.$validator.reset()
        }
      },
      'configuracionPago.tipcomp_ncredito' (val) {
        if (val === null || val === undefined) {
          this.configuracionPago.tipcomp_ncredito = null
          this.$validator.reset()
        }
      },
      'dialog' (val) {
        if (val === false) {
          this.close()
        }
      }
    },
    beforeMount () {
      this.formReset()
      this.getConfiguracioPagos()
    },
    mounted () {
    },
    computed: {
      complementos () {
        return store.getters.complementosFormComDiario
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('configuracionPagos')
      }
    },
    methods: {
      formReset () {
        this.rango = {
          id: null,
          codigo: null,
          nombre: null,
          valor_inicial: '',
          valor_final: ''
        }
      },
      getConfiguracioPagos () {
        let loader = this.$loading.show({
          container: this.$refs.cargar
        })
        this.axios.get('pg_configuraciones').then((response) => {
          // console.log('response')
          if (response.data.data) {
            this.configuracionPago = response.data.data
            loader.hide()
          } else {
            this.message = response.data.message
            loader.hide()
          }
        })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: e.data.message, color: 'danger'})
          })
      },
      validaMensaje () {
        let mensinicial = this.validarDetalles(this.rango).mensaje_inicial
        let mensfinal = this.validarDetalles(this.rango).mensaje_final
        if (mensinicial && this.rango.valor_inicial !== '') {
          this.valor_value = mensinicial
        } else {
          this.$validator.reset()
          this.valor_value = ''
        }
        if (mensfinal && this.rango.valor_final !== '') {
          this.valor_value2 = mensfinal
        } else {
          this.$validator.reset()
          this.valor_value2 = ''
        }
        if (this.rango.valor_inicial !== '' && this.rango.valor_final !== '') {
          this.valorEnd = this.validarDetalles(this.rango).mensaje_error_rango
        } else {
          this.$validator.reset()
          this.valorEnd = ''
        }
      },
      ordenarRangos () {
        this.configuracionPago.rangos.sort(function (a, b) {
          return (a.valor_inicial - b.valor_final)
        })
      },
      validarDetalles (rangoVerificar) {
        var respuesta = {
          libre: true,
          mensaje_inicial: '',
          mensaje_final: '',
          mensaje_error_rango: ''
        }
        if (this.configuracionPago.rangos.length) {
          if (rangoVerificar.valor_final <= rangoVerificar.valor_inicial) {
            respuesta.mensaje_error_rango = 'El valor final debe ser mayor al valor inicial.'
            respuesta.libre = false
          } else if (rangoVerificar.valor_inicial >= rangoVerificar.valor_final) {
            respuesta.mensaje_error_rango = 'El valor inicial debe ser menor al valor inicial.'
            respuesta.libre = false
          } else {
            this.configuracionPago.rangos.forEach((rango) => {
              let vInicial = ((rangoVerificar.valor_inicial >= rango.valor_inicial) && (rangoVerificar.valor_inicial <= rango.valor_final))
              let vFinal = ((rangoVerificar.valor_final >= rango.valor_inicial) && (rangoVerificar.valor_final <= rango.valor_final))
              let val = vInicial || vFinal
              if (val) {
                respuesta.libre = false
                respuesta.mensaje_inicial = vInicial ? 'El valor inicial: ' + rangoVerificar.valor_inicial + ' esta entre el rango: ' + rango.valor_inicial + ' - ' + rango.valor_final + '.' : ''
                respuesta.mensaje_final = vFinal ? 'El valor final: ' + rangoVerificar.valor_final + ' esta entre el rango: ' + rango.valor_inicial + ' - ' + rango.valor_final + '.' : ''
                respuesta.mensaje_error_rango = val ? 'Se presenta conflicto con otro(s) rango(s).' : ''
              } else {
                let vIniInverse = (rango.valor_inicial >= rangoVerificar.valor_inicial && rango.valor_inicial <= rangoVerificar.valor_final)
                let vFinInverse = (rango.valor_final <= rangoVerificar.valor_final && rango.valor_final >= rangoVerificar.valor_inicial)
                let varInFin = vIniInverse || vFinInverse
                if (varInFin) {
                  respuesta.libre = false
                  respuesta.mensaje_inicial = vIniInverse ? 'El valor inicial: ' + rango.valor_inicial + ' esta entre el rango: ' + rangoVerificar.valor_inicial + ' - ' + rango.valor_final + '.' : ''
                  respuesta.mensaje_final = vFinInverse ? 'El valor final: ' + rango.valor_final + ' esta entre el rango: ' + rangoVerificar.valor_inicial + ' - ' + rango.valor_final + '.' : ''
                  respuesta.mensaje_error_rango = varInFin ? 'Se presenta conflicto con otro(s) rango(s).' : ''
                }
              }
            })
          }
        }
        return respuesta
      },
      close () {
        this.dialog = false
        this.formReset()
        this.$validator.reset()
      },
      preguntarEliminarRango (item) {
        this.dialogEliminar = true
        this.rango = item
      },
      eliminarRango (rango) {
        if (rango) {
          this.axios.delete('pg_configuraciones/' + this.configuracionPago.id + '/rangos/' + rango.id)
            .then((res) => {
              this.configuracionPago.rangos.splice(this.configuracionPago.rangos.indexOf(rango), 1)
              this.$store.commit(SNACK_SHOW, {msg: res.data.message, color: 'success'})
            })
        } else {
        }
        this.dialogEliminar = false
        this.formReset()
      },
      async agregarRango (rango) {
        this.tableLoading = true
        if (await this.validador('formRango')) {
          var send = !this.configuracionPago.id ? console.log('Aun no ejecuto') : this.axios.post('pg_configuraciones/' + this.configuracionPago.id + '/rangos', this.rango)
          send.then(response => {
            if (this.configuracionPago.id) {
              this.configuracionPago.rangos.push(response.data.data)
              // this.configuracionPago.rangos.push(rango)
              // this.configuracionPago.rangos = response.data.data
            } else {
            }
            this.tableLoading = false
            this.$validator.reset()
            this.ordenarRangos()
            this.formReset()
            this.dialog = false
          })
        } else {
        }
      },
      async saveContable () {
        let errorCuenAprovechamiento = await this.$refs.cuentaAprovechamiento.validate()
        let errorCuenDescuento = await this.$refs.cuentaDescuento.validate()
        if (await this.validador('formPrincipal') && errorCuenAprovechamiento && errorCuenDescuento) {
          // const typeRequest = this.comDiario.id ? 'editar' : 'crear'
          delete this.configuracionPago.tipcomp_ncredito
          delete this.configuracionPago.niif_descuento
          delete this.configuracionPago.tipcomp_ndebito
          delete this.configuracionPago.tipcomp_cxp
          delete this.configuracionPago.niif_aprovechamiento
          delete this.configuracionPago.tipcomp_traslado
          delete this.configuracionPago.rangos
          // if (this.configuracionPago.rangos.length <= 0) {
          // }
          var send = !this.configuracionPago.id ? this.axios.post('pg_configuraciones', this.configuracionPago) : this.axios.put('pg_configuraciones/' + this.configuracionPago.id, this.configuracionPago)
          send.then(response => {
            if (this.configuracionPago.id) {
              this.$store.commit(SNACK_SHOW, {
                msg: 'Los datos contables se actualizaron correctamente',
                color: 'success'
              })
              this.isEditing = false
            } else {
              this.$store.commit(SNACK_SHOW, {
                msg: 'Los datos contables se registraron correctamente',
                color: 'success'
              })
            }
            // this.getConfiguracioPagos()
            this.configuracionPago = response.data.data
            // this.configuracionPago.rangos = response.data.data.rangos
            // this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {
              msg: 'Hay un error al guardar el registro. ' + e.response,
              color: 'danger'
            })
          })
        } else {
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      }
    }
  }
</script>

<style scoped>

</style>
