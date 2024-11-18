<template>
  <div>
    <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent max-width="400">
        <v-card>
          <v-card-text class="title font-weight-light">¿Esta seguro que desea confirmar el estado de la cuenta?</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn flat @click="confirmarEstado()">No</v-btn>
            <v-btn color="primary" @click.stop="confirmarEstado('yesConfirmar')">Si</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="dialog2" persistent max-width="400">
        <v-card>
          <v-card-title class="grey lighten-3">
            <span class="title">¿Qué desea hacer?</span>
          </v-card-title>
          <v-container fluid>
            <v-layout row wrap class="justify-center">
              <v-flex xs4>
                <v-checkbox color="teal" off-icon="done" on-icon="done_all" default v-model="confirmar" :value="salInicial.estado == 'Confirmado' ? true : false"
                            :disabled="salInicial.estado == 'Anulado' ? true : false" :label="salInicial.estado != 'Anulado' ? 'Confirmar' : 'Anulado'" hide-details class="shrink mr-2"></v-checkbox>
                <!--label="Confirmar"-->
              </v-flex>
              <v-flex xs4>
                <v-checkbox color="black" v-if="salInicial.id != null" off-icon="far fa-file-pdf" on-icon="far fa-file-pdf" v-model="imprime" hide-details class="shrink mr-2" label="Imprimir PDF"></v-checkbox>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" flat @click="enviarCambio()">Aceptar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="dialog3" persistent max-width="400">
        <v-card>
          <v-card-title class="grey lighten-3">
            <v-card-text class="title">¿Por qué anula esta cuenta?</v-card-text>
          </v-card-title>
          <v-container fluid>
            <v-layout row wrap>
              <v-flex xs12>
                <v-textarea
                  outline
                  v-model="detalle_anulacion"
                  color="primary"
                  name="Detalle de anulación"
                >
                  <div slot="label">
                    Detalle de anulación
                  </div>
                </v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <!--<v-btn flat @click.native="dialog = false">No</v-btn>-->
            <v-btn color="primary" flat @click="esAnulado(salInicial, detalle_anulacion)">Enviar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
    <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
      <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title> &nbsp;
      <!--<small v-if="salInicial.id != null" class="pt-1 pb-0"> Estado: {{ salInicial.estado }}</small>-->
      <v-spacer/>
      <h2 v-if="salInicial.id != null" class="title">Saldo Inicial </h2>
      <div v-if="salInicial.id != null">
        <v-chip label color="white" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="salInicial.consecutivo == null ? '0000' : str_pad(salInicial.consecutivo,6)"></span>
        </v-chip>
        <v-chip :color="colorEstado" text-color="white">
          <v-avatar>
            <v-icon v-text="iconoEstado"></v-icon>
          </v-avatar>
          {{ salInicial.estado }}
        </v-chip>
      </div>
    </v-toolbar>
    <registro-niif :value="dialogFormNiif" :cue="cuentaNiff" @cancelar="dialogFormNiif = false" @crear="val => goNiif(val)"></registro-niif>
    <v-form data-vv-scope="formPrincipal">
    <v-container fluid grid-list-md grid-list-xl>
      <v-layout row wrap>
        <v-flex xs12 sm12 md3 lg3 class="pb-1">
          <v-menu
            ref="menuDate"
            :close-on-content-click="false"
            v-model="menuDate"
            :nudge-right="40"
            lazy
            transition="scale-transition"
            offset-y
            full-width
            max-width="290px"
            min-width="290px"
          >
            <!--:return-value.sync="date"-->
            <v-text-field
              slot="activator"
              v-model="computedDateFormatted"
              label="Fecha"
              prepend-icon="event"
              readonly
              name="fecha"
              :disabled="salInicial.estado === 'Confirmado'"
              v-validate="'required'"
              :error-messages="errors.collect('fecha')"
            ></v-text-field>
            <v-date-picker
              v-model="salInicial.fecha"
              @input="menuDate = false"
              @change="() => {
                let index = $validator.errors.items.findIndex(x => x.field === 'fecha')
                $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
              }"
              locale='es'
              no-title
              :max="maxDate"
            ></v-date-picker>
          </v-menu>
        </v-flex>
        <v-flex xs12 sm12 md3 xl3 class="pb-1">
          <v-select
            :items="tipos"
            v-model="salInicial.tipo"
            label="Tipo"
            name="tipo"
            key="tipo"
            :error-messages="errors.collect('tipo')"
            :disabled="salInicial.estado === 'Confirmado'"
          ></v-select>
        </v-flex>
        <v-flex xs12 sm12 md12 lg12 class="pt-0">
          <v-card>
              <v-container fluid grid-list-md grid-list-xl>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md4 xl4>
                    <postulador
                      nodata="Busqueda por nombre o número de documento."
                      required
                      hint="tercero.nombre_completo"
                      itemtext="tercero.identificacion_completa"
                      datatitle="tercero.identificacion_completa"
                      datasubtitle="tercero.nombre_completo"
                      filter="tercero.identificacion_completa,tercero.nombre_completo"
                      :label="salInicial.pg_proveedore_id == null ? 'proveedor' : 'Proveedor'"
                      scope="formPrincipal"
                      lite
                      :disabled="salInicial.estado === 'Confirmado'"
                      ref="postulaProveedor"
                      entidad="pg_proveedores"
                      preicon="local_shipping"
                      @change="val => salInicial.pg_proveedore_id = val"
                      @objectReturn="val => salInicial.proveedor = val"
                      :value="salInicial.proveedor"
                      clearable
                    />
                  </v-flex>
                  <v-flex xs12 sm12 md5 xl5>
                    <postulador
                      nodata="Busqueda por código o nombre."
                      required
                      hint="nombre"
                      itemtext="codigo"
                      datatitle="codigo"
                      datasubtitle="nombre"
                      filter="codigo,nombre"
                      :label="!salInicial.nf_niif_id ? 'cuenta' : 'Cuenta'"
                      :disabled="salInicial.estado === 'Confirmado'"
                      scope="formPrincipal"
                      ref="buscarCuenta"
                      entidad="niifs"
                      preicon="list_alt"
                      routeparams="nivel:auxiliar=1"
                      @change="val => salInicial.nf_niif_id = val"
                      @objectReturn="val => salInicial.niif = val"
                      :value="salInicial.niif"
                      clearable
                      btnplustruncate
                      @click="openNuevaNiif(1)"
                    ></postulador>
                  </v-flex>
                  <v-flex xs12 sm12 md3 xl3 >
                    <input-number
                      v-model.number="salInicial.valor"
                      label="Valor"
                      name="valor"
                      :precision="0"
                      prepend-icon="attach_money"
                      v-validate="'required'"
                      :error-messages="errors.collect('valor')"
                      :disabled="salInicial.estado === 'Confirmado'"
                    ></input-number>

<!--                    <input-number-->
<!--                      v-model="salInicial.valor"-->
<!--                      :disabled="salInicial.estado === 'Confirmado'"-->
<!--                      :label="'Valor'"-->
<!--                      v-validate="'required'"-->
<!--                      :name="'valor'"-->
<!--                      :error-messages="errors.collect('valor')"-->
<!--                      icon="attach_money"-->
<!--                    ></input-number>-->
                  </v-flex>
<!--                  <v-flex xs12 sm12 md2 lg2 v-if="salInicial.id">-->
<!--                    <v-select-->
<!--                      :items="estados"-->
<!--                      v-model="salInicial.estado"-->
<!--                      :disabled="salInicial.estado === 'Confirmado'"-->
<!--                      label="Estado"-->
<!--                      name="estado"-->
<!--                    ></v-select>-->
<!--                  </v-flex>-->
                  <template>
                    <v-flex xs12 sm12 md12 xl12 class="pt-0" v-if="salInicial.tipo === 'CXP'">
                      <v-subheader class="title pl-0" v-text="'Cuenta por pagar'"></v-subheader>
                      <v-layout row wrap>
                        <v-flex xs12 sm12 md3 xl3>
                          <v-text-field :disabled="salInicial.estado === 'Confirmado'" :prefix="(salInicial.no_documento !== undefined || salInicial.no_documento != null ) ? 'N°' : ''" label="No. Documento" v-model="salInicial.no_documento" key="número documento"
                                        name="número documento" required v-validate="'required|max:20|not_in:' + numeroDocumentoResgistrado"
                                        :error-messages="errors.collect('número documento')"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12 md3 xl3>
                          <v-menu
                            ref="menuDate2"
                            :close-on-content-click="false"
                            v-model="menuDate2"
                            :nudge-right="40"
                            lazy
                            transition="scale-transition"
                            offset-y
                            full-width
                            max-width="290px"
                            min-width="290px"
                          >
                            <v-text-field
                              slot="activator"
                              v-model="computedDateFormatted2"
                              label="Fecha documento"
                              prepend-icon="event"
                              readonly
                              name="fecha documento"
                              :disabled="salInicial.estado === 'Confirmado'"
                              v-validate="'required'"
                              :error-messages="errors.collect('fecha documento')"
                            ></v-text-field>
                            <v-date-picker
                              v-model="salInicial.fecha_documento"
                              @input="menuDate2 = false"
                              @change="() => {
                                let index = $validator.errors.items.findIndex(x => x.field === 'fecha documento')
                                $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                              }"
                              locale='es'
                              no-title
                              :max="maxDate"
                            ></v-date-picker>
                          </v-menu>
                        </v-flex>
                        <v-flex xs12 sm12 md3 md3>
                          <v-text-field
                            label="Plazo" :step="0" type="number" v-model.number="plazo" required name="plazo" key="plazo"
                            v-validate="'required'" :error-messages="errors.collect('plazo')"
                            :disabled="salInicial.estado === 'Confirmado'"
                            :suffix="plazo ? ( plazo !== 1 ? 'días' : 'día' ) : ''">
                          </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12 md3 xl3>
                          <v-text-field
                            :value="computedDateFormatted3"
                            label="Fecha vencimiento"
                            prepend-icon="event"
                            disabled
                            ></v-text-field>
                        </v-flex>
                      </v-layout>
                    </v-flex>
                    <v-flex xs12 sm12 md12 xl12 class="pt-0" v-else-if="salInicial.tipo === 'Anticipo'">
                      <v-subheader class="title pl-0" v-text="'Anticipos'"></v-subheader>
                      <v-layout row wrap>
                        <v-flex xs12 sm12 md3 xl3>
                          <v-menu
                            ref="menuDate4"
                            :close-on-content-click="false"
                            v-model="menuDate4"
                            :nudge-right="40"
                            lazy
                            transition="scale-transition"
                            offset-y
                            full-width
                            max-width="290px"
                            min-width="290px"
                          >
                            <!--:return-value.sync="date"-->
                            <v-text-field
                              slot="activator"
                              v-model="computedDateFormatted4"
                              label="Fecha anticipo"
                              prepend-icon="event"
                              readonly
                              name="fecha anticipo"
                              required
                              :disabled="salInicial.estado === 'Confirmado'"
                              v-validate="'required'"
                              data-vv-delay="2000"
                              :error-messages="errors.collect('fecha anticipo')"
                            ></v-text-field>
                            <v-date-picker
                              v-model="salInicial.fecha_anticipo"
                              @input="menuDate4 = false"
                              locale='es'
                              no-title
                              :max="maxDate"
                            ></v-date-picker>
                          </v-menu>
                        </v-flex>
                      </v-layout>
                    </v-flex>

                  </template>
                  <!--<v-flex xs12 sm12 md12 xl12 class="pt-1">-->
                    <!--<v-textarea-->
                      <!--v-model="salInicial.observaciones"-->
                      <!--color="primary"-->
                      <!--name="observaciones"-->
                      <!--required-->
                      <!--v-validate="'required'"-->
                      <!--key="observaciones"-->
                      <!--:error-messages="errors.collect('observaciones')"-->
                    <!--&gt;-->
                      <!--<div slot="label">-->
                        <!--Observaciones-->
                      <!--</div>-->
                    <!--</v-textarea>-->
                  <!--</v-flex>-->
                  <!--<v-spacer></v-spacer>-->
                </v-layout>
              </v-container>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
    </v-form>
    <v-card>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="refresh(null)">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="confirmarSave">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {SALINICIAL_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import InputNumber from '../../../general/InputNumber'
  // import {mapState} from 'vuex'
  export default {
    name: 'RegistroSalInicial',
    components: {
      InputNumber,
      RegistroNiif: () => import('@/components/administrativo/niif/puc/RegistroNiif'),
      Postulador: () => import('@/components/general/Postulador')
    },
    props: ['parametros'],
    data () {
      return {
        salIniciales: [],
        salInicial: {},
        salInicialEdicion: {
          id: null,
          consecutivo: null,
          fecha: this.moment().format('YYYY-MM-DD'),
          pg_proveedore_id: null,
          no_documento: null,
          fecha_anticipo: null,
          fecha_documento: null,
          fecha_vencimiento: null,
          nf_niif_id: null,
          valor: null,
          cxp_plazo: null,
          observaciones: null,
          detalle_anulacion: null,
          tipo: null,
          estado: null,
          proveedor: null,
          niif: null
        },
        dialogFormNiif: false,
        cuentaNiff: null,
        niifNueva: null,
        plazo: null,
        maxDate: this.moment().format('YYYY-MM-DD'),
        pagination: {},
        tipos: ['CXP', 'Anticipo'],
        estados: ['Registrado', 'Confirmado', 'Anulado'],
        dialogEliminar: false,
        detalle_anulacion: null,
        registrar: false,
        confirmar: false,
        dialog: false,
        dialog2: false,
        dialog3: false,
        menuDate: false,
        menuDate2: false,
        menuDate3: false,
        menuDate4: false,
        numeroDocumentoResgistrado: null,
        imprime: false,
        tableLoading: false,
        descriptionLimit: 60
      }
    },
    watch: {
      'salInicial.proveedor' (val) {
        console.log('proveerdor', val)
        if (val) {
          this.plazo = val.plazo
          this.salInicial.niif = val ? val.niif : null
        }
      },
      'salInicial.tipo' (val) {
        if (val === 'CXP') {
          this.salInicial.fecha_anticipo = this.moment().format('YYYY-MM-DD')
          this.salInicial.fecha_documento = this.moment().format('YYYY-MM-DD')
          this.salInicial.fecha_anticipo = null
        } else if (val === 'Anticipo') {
          this.salInicial.fecha_anticipo = this.moment().format('YYYY-MM-DD')
        }
      },
      // 'salInicial.niif' (val) {
      //   console.log('niif', val)
      //   if (this.salInicial.niif !== null) {
      //     this.salInicial.niif = val
      //   } else {
      //     this.salInicial.niif = this.salInicial.proveedor ? this.salInicial.proveedor.niif : null
      //   }
      // },
      'plazo' (val) {
        this.salInicial.fecha_vencimiento = this.moment(this.salInicial.fecha_documento).add(val, 'd').format('YYYY-MM-DD')
        this.salInicial.cxp_plazo = val
      }
    },
    computed: {
      tapTitulo () {
        return !this.salInicial.id ? 'Nuevo Saldo Inicial' : (this.salInicial.estado !== 'Registrado' ? 'Visualizando' : 'En Edición')
      },
      pages () {
        if (this.pagination.rowsPerPage == null || this.pagination.totalItems == null) return 0
        return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
      },
      typeObject () {
        return typeof this.object === 'undefined'
      },
      computedDateFormatted () {
        return this.formDate(this.salInicial.fecha)
      },
      computedDateFormatted2 () {
        return this.formDate(this.salInicial.fecha_documento)
      },
      computedDateFormatted3 () {
        return this.formDate(this.salInicial.fecha_vencimiento)
      },
      computedDateFormatted4 () {
        return this.formDate(this.salInicial.fecha_anticipo)
      },
      colorEstado () {
        return this.statusComdiario().color
      },
      iconoEstado () {
        return this.statusComdiario().icono
      }
    },
    beforeCreate () {
      this.axios.get('pg_saliniciales').then(res => {
        this.salIniciales = res.data.data
        this.numeroDocumentoResgistrado = this.getNumDocumentoUsado()
      }).catch(e => {
        this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer los saldos iniciales. ' + e.response, color: 'danger'})
      })
    },
    beforeMount () {
      this.refresh()
      this.formReset()
    },
    mounted () {
      if (this.parametros.entidad !== null) {
        console.log(this.parametros)
        this.getRegisto(this.parametros.entidad.id)
      }
      const dict = {
        custom: {
          factura: {
            not_in: 'El número de documento ya se encuentra registrado.'
          }
        }
      }

      this.$validator.localize('es', dict)
    },
    methods: {
      statusComdiario () {
        let color = null
        let icono = null
        switch (this.salInicial.estado) {
          case 'Registrado':
            color = 'primary'
            icono = 'stars'
            break
          case 'Confirmado':
            color = 'teal'
            icono = 'check_circle'
            break
          case 'Anulado':
            color = 'red'
            icono = 'remove_circle'
            break
          default:
            color = 'primary'
            icono = 'stars'
        }
        return {color, icono}
      },
      openNuevaNiif (val) {
        this.niifNueva = val
        this.dialogFormNiif = true
      },
      goNiif (val) {
        // console.log('GoVal', val)
        // console.log('Niif', this.niifNueva)
        if (this.niifNueva === 1) {
          this.salInicial.niif = val
        }
        this.dialogFormNiif = false
      },
      formReset () {
        this.numeroDocumentoResgistrado = this.getNumDocumentoUsado()
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.cargar
        })
        this.axios.get('pg_saliniciales/' + id)
          .then((response) => {
            console.log('Saliniciale', response)
            if (response.data !== '') {
              this.salInicial = response.data.data
              this.numeroDocumentoResgistrado = this.getNumDocumentoUsado()
            }
            loader.hide()
          }).catch(e => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el saldo inicial. ' + e.response, color: 'danger'})
          })
      },
      getNumDocumentoUsado () {
        return this.salIniciales.map(cod => { if (!(this.salIniciales.no_documento != null && this.salIniciales.no_documento === cod.no_documento)) return cod.no_documento })
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      refresh () {
        this.$validator.reset()
        this.salInicial = JSON.parse(JSON.stringify(this.salInicialEdicion))
      },
      // Modales Confirmar y registrar
      imprimirCuenta () {
        this.dialog2 = false
        console.log('Enviando a imprimir')
        let url = this.axios.defaults.baseURL + 'salIniciales/pdf/' + this.salInicial.id
        window.open(url, '_blank')
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      // Guardar
      async confirmarSave () {
        let errorCuenta = await this.$refs.buscarCuenta.validate()
        let errorTercero = await this.$refs.postulaProveedor.validate()
        if (await this.validador('formPrincipal') && errorCuenta && errorTercero) {
          this.dialog2 = true
        } else {
          this.$store.commit(SNACK_SHOW, {
            msg: 'Se presentan errores al tratar de guardar el saldo.',
            color: 'danger'
          })
        }
      },
      confirmarEstado (val) {
        if (val === 'yesConfirmar') {
          if (this.confirmar && this.imprime) {
            this.salInicial.estado = 'Confirmado'
            this.imprimirCuenta()
          } else {
            this.salInicial.estado = 'Confirmado'
          }
          this.dialog = false
          this.dialog2 = false
          this.save()
          this.$store.commit(SNACK_SHOW, {msg: 'El estado de la cuenta ha sido Confirmado', color: 'success'})
        } else {
          if (this.imprime) {
            this.imprimirCuenta()
          }
          this.confirmar = false
          this.dialog = false
          this.dialog2 = false
          this.salInicial.estado = 'Registrado'
          this.save()
          this.$store.commit(SNACK_SHOW, {msg: 'El estado de la cuenta ha sido Registrado', color: 'success'})
        }
      },
      esAnulado (salInicial, detalleAnulacion) {
        this.dialog3 = false
        this.dialog2 = false
        salInicial.detalle_anulacion = detalleAnulacion
        this.save()
      },
      enviarCambio () {
        if (this.confirmar) {
          this.dialog = true
        } else if (this.confirmar && this.imprime) {
          this.dialog = true
        } else if (this.imprime) {
          this.salInicial.estado = 'Registrado'
          this.imprimirCuenta()
          this.save()
        } else if (this.salInicial.estado === 'Anulado') {
          this.dialog3 = true
        } else if (this.salInicial.estado === 'Anulado' && this.imprime) {
          this.dialog3 = true
        } else {
          this.salInicial.estado = 'Registrado'
          this.save()
          this.$store.commit(SNACK_SHOW, {msg: 'El estado de la cuenta  ha sido Registrado', color: 'success'})
        }
      },
      save () {
        const typeRequest = this.salInicial.id ? 'editar' : 'crear'
        this.axios.post('pg_saliniciales', this.salInicial).then(response => {
          if (this.salInicial.id) {
            this.$store.commit(SNACK_SHOW, {
              msg: 'El saldo se actualizó correctamente',
              color: 'success'
            })
          } else {
            this.$store.commit(SNACK_SHOW, {
              msg: 'El saldo inicial se creó correctamente',
              color: 'success'
            })
          }
          this.$store.commit(SALINICIAL_RELOAD_ITEM, {
            salInicial: response.data.data,
            estado: typeRequest,
            key: this.parametros.key
          })
          this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
        }).catch(e => {
          this.$store.commit(SNACK_SHOW, {
            msg: 'Hay un error al guardar el registro. ' + e.response,
            color: 'danger'
          })
        })
      }
    }
  }
</script>

<style scoped>
  /*#inputUsage .layout .flex div {*/
  /*border: 1px dashed rgba(0,0,0,0.4) !important;*/
  /*}*/
  .cal-item .btn {
    margin: 0 5px;
    height: 28px;
    width: 28px;
  }
  .cal-item .btn i {
    font-size: 22px;
  }

  .cal-item .card__title {
    padding: 16px 16px
  }

  .cal-item .list__tile {
    height: unset!important;
    padding: 10px 16px;
  }

  .cal-item .list__tile .list__tile__action {
    align-self: flex-start;
    min-width: unset;
    padding-right: 16px;
  }

  .cal-item .color-2 {
    background-color: #F44336;
  }
</style>
