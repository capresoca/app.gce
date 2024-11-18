<template>
  <div>
    <v-card>
      <v-card-title class="headline grey lighten-2" primary-title>
        Registro Recibos de caja
      </v-card-title>
      <v-card-text>
        <v-form data-vv-scope="formRegistroRecibosCaja">
          <v-container fluid grid-list-xl >
            <v-layout row wrap>
              <v-flex xs12 sm12 md9 lg9>
                <postulador
                  nodata="Busqueda por nombre o codigo."
                  :required="true"
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="Cliente"
                  scope="formRegistroRecibosCaja"
                  ref="postulaCliente"
                  entidad="clientes"
                  preicon="person"
                  lite
                  @change="val => recibocaja.cr_cliente_id = val"
                  @objectReturn="val => recibocaja.cliente = val"
                  :value="recibocaja.cliente"
                  clearable
                />
              </v-flex>
              <v-flex xs12 sm12 md3 lg3>
                <v-text-field label="Numero"
                  v-model="recibocaja.numero"
                  key="numero"
                  v-validate="'required'"
                  name="Numero"
                  :error-messages="errors.collect('Numero')"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4 lg2>
                Bancos
                <v-radio-group
                  v-model="recibocaja.bancos" :column="false">
                  <v-radio
                  key="bancosSi"
                  label="Si"
                  value="Si"
                  ></v-radio>
                  <v-radio
                  key="bancosNo"
                  label="No"
                  value="No"
                  ></v-radio>
                </v-radio-group>
              </v-flex>
              <v-flex xs12 sm12 md8 lg10>
                <v-autocomplete
                  label="Caja"
                  v-model="recibocaja.ts_cajas_id"
                  :items="complementosFormRecibosCaja.cajas"
                  required
                  key="cajaContableTs"
                  name="Caja"
                  v-validate="'required'"
                  :error-messages="errors.collect('Caja')"
                  item-value="id"
                  item-text="nombre"
                  autocomplete>
                  <template slot="item" slot-scope="data">
                    <template>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.nombre"/>
                        <v-list-tile-sub-title v-html="data.item.codigo"></v-list-tile-sub-title>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm12 md4 lg2>
                <v-dialog
                  ref="pickerFechaRecibo"
                  v-model="pickerFechaRecibo"
                  :return-value.sync="recibocaja.fecha_recibo"
                  persistent
                  lazy
                  full-width
                  width="290px">
                  <v-text-field
                    slot="activator"
                    v-model="recibocaja.fecha_recibo"
                    label="Fecha"
                    prepend-icon="event"
                    key="fecha"
                    v-validate="'required'"
                    name="fecha"
                    :error-messages="errors.collect('fecha')"
                    readonly>                      
                  </v-text-field>
                  <v-date-picker v-model="recibocaja.fecha_recibo" scrollable>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="pickerFechaRecibo = false">Cancelar</v-btn>
                    <v-btn flat color="primary" @click="$refs.pickerFechaRecibo.save(recibocaja.fecha_recibo)">OK</v-btn>
                  </v-date-picker>
                </v-dialog>
              </v-flex>
              <v-flex xs12 sm12 md8 lg10>
                <postulador
                  nodata="Busqueda por código o nombre."
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="cuenta auxiliar"
                  lite
                  scope="formConceptoEgreso"
                  ref="buscarCuentaBanco"
                  entidad="niifs"
                  routeparams="nivel:auxiliar=1"
                  preicon="person"
                  @change="val => recibocaja.nf_niif_banco_id = val"
                  @objectReturn="val => recibocaja.banco = val"
                  :value="recibocaja.banco"
                  clearable
                ></postulador>
              </v-flex>
              <v-flex xs12 sm12 md10 lg10>
                <v-text-field label="Centro"
                  v-model="recibocaja.centro"
                  key="centro"
                  v-validate="'required'"
                  name="Centro"
                  :error-messages="errors.collect('Centro')"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md1 lg1>
                <v-tooltip top>
                  <v-btn
                    slot="activator"
                    fab
                    right
                    color="red">
                    <v-icon color="white">clear</v-icon>
                  </v-btn>
                  <span>Anular</span>
                </v-tooltip>
              </v-flex>
              <v-flex xs12 sm12 md1 lg1>
                <v-tooltip top>
                  <v-btn
                    slot="activator"
                    fab
                    right
                    color="amber">
                    <v-icon color="white">library_books</v-icon>
                  </v-btn>
                  <span>Ver facturas pendientes</span>
                </v-tooltip>
              </v-flex>
              <v-flex xs12 sm12 md9 lg9>
                <v-textarea
                  v-model="recibocaja.detalle"
                  label="Detalle"
                ></v-textarea>
              </v-flex>
              <v-flex xs12 sm12 md3 lg3>
                <v-text-field label="Total del recibo"
                  v-model="recibocaja.total_recibo"
                  key="totalRecibo"
                  v-validate="'required'"
                  name="Total del recibo"
                  :error-messages="errors.collect('Total del recibo')"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md12 lg12>
                <v-data-table
                :headers="conceptosHeader"
                :items="conceptosReciboCaja"
                class="elevation-1"
                >
                  <template slot="items" slot-scope="props">
                    <td class="text-xs-left">{{ props.item.concepto.codigo }}</td>
                    <td class="text-xs-left">{{ props.item.concepto.nombre }}</td>
                    <td class="text-xs-left">{{ props.item.valor }}</td>
                    <td>
                    </td>
                  </template>
                </v-data-table>
              </v-flex>
              <v-flex xs12 sm12 md4 lg4>
                <v-text-field label="Debitos" background-color="orange" v-model="recibocaja.debitos" outline readonly required></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4 lg4>
                <v-text-field label="Creditos" background-color="primary" v-model="recibocaja.creditos" outline readonly required></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4 lg4>
                <v-text-field label="Diferencia" background-color="success" v-model="recibocaja.valor" outline readonly required></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md6 lg6>
                <v-tooltip top>
                  <v-btn
                    slot="activator"
                    fab
                    right
                    color="grey">
                    <v-icon color="yellow">monetization_on</v-icon>
                  </v-btn>
                  <span>Forma de pago</span>
                </v-tooltip>
              </v-flex>
              <v-flex xs12 sm12 md6 lg6>
                <v-tooltip top>
                  <v-btn
                    slot="activator"
                    fab
                    right
                    color="amber">
                    <v-icon color="white">send</v-icon>
                  </v-btn>
                  <span>Grabar concepto</span>
                </v-tooltip>
              </v-flex>
            </v-layout>
            <v-layout row wrap>
              <v-flex xs12 sm12 md10 lg10>
                <v-text-field label="Conceptos de Pago" v-model="recibocaja.concepto"></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md2 lg2>
                Iva
                <v-radio-group
                  v-model="recibocaja.iva" :column="false">
                  <v-radio
                  key="ivaSi"
                  label="Si"
                  value="Si"
                  ></v-radio>
                  <v-radio
                  key="ivaNo"
                  label="No"
                  value="No"
                  ></v-radio>
                </v-radio-group>
              </v-flex>
              <v-flex xs12 sm6>
                <postulador
                  nodata="Busqueda por nombre o número de documento."
                  required
                  hint="nombre_completo"
                  itemtext="identificacion"
                  datatitle="identificacion_completa"
                  datasubtitle="nombre_completo"
                  filter="identificacion_completa,nombre_completo"
                  label="Tercero"
                  scope="formRegistroRecibosCaja"
                  ref="postulaTercero"
                  entidad="terceros"
                  preicon="person"
                  @change="val => recibocaja.gn_tercero_id = val"
                  @objectReturn="val => recibocaja.tercero = val"
                  :value="recibocaja.tercero"
                  clearable
                />
              </v-flex>
              <v-flex xs12 sm6>
                <postulador
                  nodata="Busqueda por nombre o número de documento."
                  required
                  hint="nombre_completo"
                  itemtext="identificacion"
                  datatitle="identificacion_completa"
                  datasubtitle="nombre_completo"
                  filter="identificacion_completa,nombre_completo"
                  label="Centro"
                  scope="formRegistroRecibosCaja"
                  ref="postulaTercero"
                  entidad="terceros"
                  preicon="person"
                  @change="val => recibocaja.gn_tercero_id = val"
                  @objectReturn="val => recibocaja.tercero = val"
                  :value="recibocaja.tercero"
                  clearable
                />
              </v-flex>
              <v-flex xs12 sm12 md4>
                <v-text-field label="Numero de cita" v-model="recibocaja.numero_cita"></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4>
                <v-text-field label="Retencion" v-model="recibocaja.retencion"></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4>
                <v-text-field label="Base de liquidacion" v-model="recibocaja.base_liquidacion"></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4>
                <v-text-field label="Valor facturado" v-model="recibocaja.valor_facturado"></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4>
                <v-text-field label="Valor de movimiento" v-model="recibocaja.valor_movimiento"></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4>
                <v-text-field label="Base" v-model="recibocaja.base"></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4>
                <v-text-field label="Descuento" v-model="recibocaja.descuento"></v-text-field>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>
  export default {
    name: 'RegistroRecibosCaja',
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    data () {
      return {
        pickerFechaRecibo: false,
        ordenEdit: false,
        recibocaja: {},
        conceptosHeader: [
          { text: 'Concepto', value: 'concepto.codigo', align: 'left', sortable: false },
          { text: 'Nombre', value: 'concepto.nombre', align: 'left', sortable: false },
          { text: 'Valor', value: 'valor', align: 'left', sortable: false },
          { text: 'Opciones', value: 'id', align: 'left', sortable: false }
        ],
        conceptosReciboCaja: [],
        complementosFormRecibosCaja: []
      }
    },
    mounted () {
      this.axios.get('cajas')
        .then((res) => {
          this.complementosFormRecibosCaja.cajas = res.data.data
        })
        .catch(e => {
          return false
        })
    }
  }
</script>
