<template>
  <v-card>
    <v-card-title class="headline grey lighten-2" primary-title v-if="ordenEdit">
      Editar saldo inicial
    </v-card-title>
    <v-card-title class="headline grey lighten-2" primary-title v-else>
      Registrar saldos iniciales
    </v-card-title>
    <v-card-text>
      <v-container fluid grid-list-xl >
        <v-form data-vv-scope="formSaldosIniciales">
          <v-layout row wrap>
            <v-flex xs12 sm12 md6 lg6>
              <v-select
                :items="tipoSaldo"
                v-model="saldoinicial.tipo_saldo"
                key="tipoSaldo"
                v-validate="'required'"
                name="tipoSaldo"
                :error-messages="errors.collect('tipoSaldo')"
                required
                label="Tipo de saldo inicial"
              ></v-select>
            </v-flex>
            <v-flex xs12 sm12 md6 lg6>
              <v-layout align-center class="pt-2 px-2">
                <v-text-field label="Consecutivo"
                  v-model="saldoinicial.consecutivo"
                  :disabled="ordenEdit"
                  key="consecutivo"
                  v-validate="'required'"
                  name="consecutivo"
                  :error-messages="errors.collect('consecutivo')"
                  readonly
                  required></v-text-field>
                <v-tooltip top v-show="!ordenEdit">
                  <v-btn
                    slot="activator"
                    flat
                    icon
                    color="accent"
                    @click="getConsecutivo"
                  >
                    <v-icon>done</v-icon>
                  </v-btn>
                  <span>Obtener consecutivo</span>
                </v-tooltip>
              </v-layout>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs12 sm12 md4 lg4>
              <postulador
                nodata="Busqueda por nombre o codigo."
                :required="true"
                hint="codigo"
                itemtext="nombre"
                datatitle="codigo"
                datasubtitle="nombre"
                filter="codigo,nombre"
                label="Cliente"
                scope="formSaldosIniciales"
                ref="postulaCliente"
                entidad="clientes"
                preicon="person"
                @change="val => saldoinicial.cr_cliente_id = val"
                @objectReturn="val => clienteSeleccionado(val)"
                :value="saldoinicial.cliente"
                clearable
              />
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
              <v-text-field label="Identificacion cliente" :value="saldoinicial.cliente.tercero.identificacion_completa" readonly></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
              <v-text-field label="Nombre cliente"  :value="saldoinicial.cliente.tercero.nombre_completo" readonly></v-text-field>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs12 sm12 md4 lg4>
              <postulador
                nodata="Busqueda por nombre o número de documento."
                required
                hint="nombre_completo"
                itemtext="identificacion"
                datatitle="identificacion_completa"
                datasubtitle="nombre_completo"
                filter="identificacion_completa,nombre_completo"
                label="Tercero"
                scope="formSaldosIniciales"
                ref="postulaTercero"
                entidad="terceros"
                preicon="person"
                @change="val => saldoinicial.gn_tercero_id = val"
                @objectReturn="val => terceroSeleccionado(val)"
                :value="saldoinicial.tercero"
                clearable
              />
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
              <v-text-field label="Identificacion del tercero" :value="saldoinicial.tercero.identificacion_completa" readonly></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
              <v-text-field label="Nombre del tercero"  :value="saldoinicial.tercero.nombre_completo" readonly></v-text-field>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs12 sm12 md4 lg4>
              <v-layout align-center class="pt-2 px-2">
                <postulador
                  nodata="Busqueda por codigo o nombre"
                  required
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="Vendedor"
                  scope="formSaldosIniciales"
                  lite
                  ref="postulaVendedor"
                  entidad="vendedor"
                  preicon="person"
                  @change="val => saldoinicial.cr_vendedor_id = val"
                  @objectReturn="val => saldoinicial.vendedor = val"
                  :value="saldoinicial.vendedor"
                  clearable
                />
                <v-tooltip top>
                  <v-btn
                    slot="activator"
                    flat
                    icon
                    @click="dialogSave = true">
                    <v-icon color="accent">add</v-icon>
                  </v-btn>
                  <span>Nuevo vendedor</span>
                </v-tooltip>
              </v-layout>
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
              <v-text-field label="Identificacion del vendedor" :value="saldoinicial.vendedor.tercero.identificacion_completa" readonly></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md4 lg4>
              <v-text-field label="Nombre del vendedor"  :value="saldoinicial.vendedor.tercero.nombre_completo" readonly></v-text-field>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs12 sm12 md12 lg12>
              <postulador
                nodata="Busqueda por código o nombre"
                required
                hint="codigo"
                itemtext="nombre"
                datatitle="codigo"
                datasubtitle="nombre"
                filter="codigo,nombre"
                label="Cuenta contable"
                lite
                scope="formSaldosIniciales"
                ref="postulaCuenta"
                entidad="niifs"
                routeparams="nivel:auxiliar=1"
                preicon="score"
                @change="val => saldoinicial.nf_niif_id = val"
                @objectReturn="val => saldoinicial.niif = val"
                :value="saldoinicial.niif"
                clearable
              ></postulador>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs12 sm12 md3 lg3>
              <v-text-field label="Numero de factura"
                v-model="saldoinicial.numero_factura"
                key="numeroFactura"
                v-validate="'required'"
                name="numeroFactura"
                :error-messages="errors.collect('numeroFactura')"
                required></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md3 lg3>
              <v-dialog
                ref="pickerFechaFactura"
                v-model="pickerFechaFactura"
                :return-value.sync="saldoinicial.fecha_factura"
                persistent
                lazy
                full-width
                width="290px">
                <v-text-field
                  slot="activator"
                  v-model="saldoinicial.fecha_factura"
                  label="Fecha de factura"
                  prepend-icon="event"
                  key="fechaFactura"
                  v-validate="'required'"
                  name="fechaFactura"
                  :error-messages="errors.collect('fechaFactura')"
                  readonly>                      
                </v-text-field>
                <v-date-picker v-model="saldoinicial.fecha_factura" :min="fechaActual" scrollable>
                  <v-spacer></v-spacer>
                  <v-btn flat color="primary" @click="pickerFechaFactura = false">Cancelar</v-btn>
                  <v-btn flat color="primary" @click="$refs.pickerFechaFactura.save(saldoinicial.fecha_factura)">OK</v-btn>
                </v-date-picker>
              </v-dialog>
            </v-flex>
            <v-flex xs12 sm12 md3 lg3>
              <v-dialog
                ref="pickerFechaVencimiento"
                v-model="pickerFechaVencimiento"
                :return-value.sync="saldoinicial.fecha_vencimiento"
                persistent
                lazy
                full-width
                width="290px">
                <v-text-field
                  slot="activator"
                  v-model="saldoinicial.fecha_vencimiento"
                  label="Fecha de vencimiento"
                  prepend-icon="event"
                  key="fechaVencimiento"
                  v-validate="'required'"
                  name="fechaVencimiento"
                  :error-messages="errors.collect('fechaVencimiento')"
                  readonly>                      
                </v-text-field>
                <v-date-picker v-model="saldoinicial.fecha_vencimiento" :min="fechaActual" scrollable>
                  <v-spacer></v-spacer>
                  <v-btn flat color="primary" @click="pickerFechaVencimiento = false">Cancelar</v-btn>
                  <v-btn flat color="primary" @click="$refs.pickerFechaVencimiento.save(saldoinicial.fecha_vencimiento)">OK</v-btn>
                </v-date-picker>
              </v-dialog>
            </v-flex>
            <v-flex xs12 sm12 md3 lg3>
              <v-text-field label="Saldo de factura"
                v-model="saldoinicial.saldo_factura"
                key="saldoFactura"
                v-validate="'required'"
                name="saldoFactura"
                :error-messages="errors.collect('saldoFactura')"
                required></v-text-field>
            </v-flex>
          </v-layout>
          <v-layout row wrap>
            <v-flex xs12 sm12 md12 lg12>
              <v-btn 
                color="success"
                @click="submit">{{ ordenEdit ? 'Editar' : 'Guardar' }}</v-btn>
            </v-flex>
          </v-layout>
        </v-form>
      </v-container>
    </v-card-text>    
    <div class="text-xs-center">
      <registro-vendedores :open="dialogSave" :parametros="param" @dialog="val => dialogSave = val" @objectReturn="val => returnDialogo(val)"></registro-vendedores>
    </div>
  </v-card>
</template>

<script>
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CARTERASALINICIAL_RELOAD_ITEM} from '@/store/modules/general/tables'
  import RegistroVendedores from '@/components/administrativo/cartera/vendedores/RegistroVendedores'
  import moment from 'moment'
  export default {
    name: 'RegistroSaldosIniciales',
    props: ['parametros'],
    components: {
      Postulador: () => import('@/components/general/Postulador'),
      RegistroVendedores
    },
    data () {
      return {
        dialogSave: false,
        pickerFechaFactura: false,
        pickerFechaVencimiento: false,
        ordenEdit: false,
        fechaActual: '2018-01-01',
        param: {},
        tipoSaldo: ['Cuenta por Cobrar', 'Anticipo'],
        saldoinicial: {
          consecutivo: '',
          cliente: {
            codigo: '',
            nombre: '',
            tercero: {}
          },
          tercero: {
            identificacion: '',
            nombre_completo: ''
          },
          vendedor: {
            nombre: '',
            codigo: '',
            tercero: {}
          },
          niif: {
            codigo: '',
            nombre: ''
          }
        }
      }
    },
    watch: {
      'saldoinicial.fecha_factura' (val) {
        var convert1 = this.parseDate(val)
        if (convert1 != null) this.saldoinicial.fecha_factura = convert1
      },
      'saldoinicial.fecha_vencimiento' (val) {
        var convert2 = this.parseDate(val)
        if (convert2 != null) this.saldoinicial.fecha_vencimiento = convert2
      }
    },
    methods: {
      clienteSeleccionado (val) {
        if (val !== null) {
          if (val.id !== null && val.id !== '' && val.id !== undefined) {
            if (val.id !== this.saldoinicial.cliente.id) {
              this.axios.get('clientes/' + val.id)
                .then((response) => {
                  if (response.data.exists) {
                    this.saldoinicial.cliente = response.data.data
                    this.saldoinicial.tercero = response.data.data.tercero
                    this.saldoinicial.vendedor = response.data.data.vendedor
                    this.saldoinicial.niif = response.data.data.niif
                    console.log(response.data.data)
                  } else {
                    this.$store.commit(SNACK_SHOW, {msg: 'No podemos recuperar los datos del cliente. Intente de nuevo', color: 'danger'})
                  }
                }).catch(e => {
                  this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
                })
            }
          }
        } else {
          if (val !== null) {
            this.saldoinicial.cliente = val
          } else {
            this.saldoinicial.cliente = {
              codigo: '',
              nombre: '',
              tercero: {}
            }
          }
        }
      },
      terceroSeleccionado (val) {
        if (val !== null) {
          if (val.id !== null && val.id !== '' && val.id !== undefined) {
            if (val.id !== this.saldoinicial.tercero.id) {
              this.axios.get('clientes/tercero/' + val.id)
                .then((response) => {
                  if (response.data.exists) {
                    this.saldoinicial.tercero = response.data.tercero
                  } else {
                    this.$store.commit(SNACK_SHOW, {msg: 'No podemos recuperar los datos del tercero. Intente de nuevo', color: 'danger'})
                  }
                }).catch(e => {
                  this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al intentar consultar codigo. ', error: e})
                })
            }
          }
        } else {
          if (val !== null) {
            this.saldoinicial.tercero = val
          } else {
            this.saldoinicial.tercero = {
              identificacion: '',
              nombre_completo: ''
            }
          }
        }
      },
      returnDialogo (val) {
        this.saldoinicial.vendedor = val.data
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async submit () {
        if (await this.validador('formSaldosIniciales')) {
          if (this.saldoinicial.id) {
            this.axios.put('saliniciales/' + this.saldoinicial.id, this.saldoinicial)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'Saldo inicial actualizado correctamente', color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(CARTERASALINICIAL_RELOAD_ITEM, {item: response.data.data, estado: 'editar', key: this.parametros.key})
                this.formReset()
              }).catch(e => {
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          } else {
            this.axios.post('saliniciales', this.saldoinicial)
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: 'Saldo inicial creada con exito se creo correctamente', color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(CARTERASALINICIAL_RELOAD_ITEM, {item: response.data.data, estado: 'crear', key: this.parametros.key})
                this.formReset()
              }).catch(e => {
                console.log(e.response)
                this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
              })
          }
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay datos obligatorios verifique el formulario. ', color: 'danger'})
        }
      },
      getConsecutivo () {
        this.axios.get('saliniciales/consecutivo')
          .then((res) => {
            this.saldoinicial.consecutivo = res.data.data
          })
          .catch(e => {
            return false
          })
      },
      getRegistro (id) {
        this.axios.get('saliniciales/' + id)
          .then((response) => {
            if (response.data.exists) {
              console.log(response.data.data)
              this.saldoinicial = response.data.data
              this.ordenEdit = true
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el cliente. ', error: e})
          })
      },
      parseDate (date) {
        if (!date) return null
        if (date.split('/').length !== 3) return null
        const [month, day, year] = date.split('/')
        return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
      },
      formReset () {
        this.saldoinicial = {
          cliente: {
            codigo: '',
            nombre: '',
            tercero: {}
          },
          tercero: {
            identificacion: '',
            nombre_completo: ''
          },
          vendedor: {
            nombre: '',
            codigo: '',
            tercero: {}
          },
          niif: {
            codigo: '',
            nombre: ''
          }
        }
        this.dialogSave = false
        this.pickerFechaFactura = false
        this.pickerFechaVencimiento = false
        this.fechaActual = moment().format('YYYY-MM-DD')
        this.saldoinicial.fecha_vencimiento = moment().format('YYYY-MM-DD')
        this.saldoinicial.fecha_factura = moment().format('YYYY-MM-DD')
        this.ordenEdit = false
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      this.$validator.localize('es')
    },
    beforeMount () {
      this.fechaActual = moment().format('YYYY-MM-DD')
      this.saldoinicial.fecha_vencimiento = moment().format('YYYY-MM-DD')
      this.saldoinicial.fecha_factura = moment().format('YYYY-MM-DD')
    }
  }
</script>

<style scoped>

</style>
