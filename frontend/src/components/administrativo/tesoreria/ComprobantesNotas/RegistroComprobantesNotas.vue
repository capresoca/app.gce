<template>
  <div>
    <v-card>
      <v-card-title class="headline grey lighten-2" primary-title>
        Registro comprobantes de egresos
      </v-card-title>
      <v-card-text>
        <v-form data-vv-scope="formRegistroRecibosCaja">
          <v-container fluid grid-list-xl >
            <v-layout row wrap>
              <v-flex xs12 sm12 md3 lg3>
                <v-text-field label="Numero"
                  v-model="comprobante.numero"
                  key="numero"
                  v-validate="'required'"
                  name="Numero"
                  :error-messages="errors.collect('Numero')"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md3 lg3>
                Naturaleza
                <v-radio-group
                  v-model="comprobante.naturaleza" :column="false">
                  <v-radio
                  key="naturalezaDebito"
                  label="Débito"
                  value="Débito"
                  ></v-radio>
                  <v-radio
                  key="naturalezaCredito"
                  label="Crédito"
                  value="Crédito"
                  ></v-radio>
                </v-radio-group>
              </v-flex>
              <v-flex xs12 sm12 md6 lg6>
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
                  @change="val => comprobante.nf_niif_id = val"
                  @objectReturn="val => comprobante.niif = val"
                  :value="comprobante.niif"
                  clearable
                ></postulador>
              </v-flex>
              <v-flex xs12 sm12 md8 lg8>
                <v-autocomplete
                  label="Caja"
                  v-model="comprobante.ts_cajas_id"
                  :items="complementosFormComprobantesNotas.cajas"
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
              <v-flex xs12 sm12 md2 lg2>
                X Mil
                <v-radio-group
                  v-model="comprobante.xmil" :column="false">
                  <v-radio
                  key="xMilSi"
                  label="Si"
                  value="Si"
                  ></v-radio>
                  <v-radio
                  key="xMilNo"
                  label="No"
                  value="No"
                  ></v-radio>
                </v-radio-group>
              </v-flex>
              <v-flex xs12 sm12 md2 lg2>
                <v-text-field v-model="comprobante.centro" placeholder="valor x mil">
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md9 lg9>
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
                  @change="val => comprobante.nf_niif_cuenta_id = val"
                  @objectReturn="val => comprobante.niifcuenta = val"
                  :value="comprobante.niifcuenta"
                  clearable
                ></postulador>
              </v-flex>
              <v-flex xs12 sm12 md3 lg3>
                <v-dialog
                  ref="pickerFechaRecibo"
                  v-model="pickerFechaRecibo"
                  :return-value.sync="comprobante.fecha"
                  persistent
                  lazy
                  full-width
                  width="290px">
                  <v-text-field
                    slot="activator"
                    v-model="comprobante.fecha"
                    label="Fecha"
                    prepend-icon="event"
                    key="fecha"
                    v-validate="'required'"
                    name="fecha"
                    :error-messages="errors.collect('fecha')"
                    readonly>                      
                  </v-text-field>
                  <v-date-picker v-model="comprobante.fecha" scrollable>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="pickerFechaRecibo = false">Cancelar</v-btn>
                    <v-btn flat color="primary" @click="$refs.pickerFechaRecibo.save(comprobante.fecha)">OK</v-btn>
                  </v-date-picker>
                </v-dialog>
              </v-flex>
              <v-flex xs12 sm12 md12 lg12>
                <v-text-field label="Centro"
                  v-model="comprobante.centro"
                  key="centroCampo"
                  v-validate="'required'"
                  name="Centro"
                  :error-messages="errors.collect('Centro')"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md6 lg10>
                <v-textarea
                  v-model="comprobante.detalle"
                  label="Detalle"
                ></v-textarea>
              </v-flex>
              <v-flex xs12 sm12 md4 lg2>
                Estado
                <v-radio-group
                  v-model="comprobante.xmil">
                  <v-radio
                  key="sinConfirmar"
                  label="Sin Confirmar"
                  value="Sin Confirmar"
                  ></v-radio>
                  <v-radio
                  key="confirmado"
                  label="Confirmado"
                  value="Confirmado"
                  ></v-radio>
                  <v-radio
                  key="anulado"
                  label="Anulado"
                  value="Anulado"
                  ></v-radio>
                </v-radio-group>
              </v-flex>
            </v-layout>
            <v-layout row wrap>
              <v-flex xs12 sm12 md12 lg12>
                <v-data-table
                :headers="conceptosHeader"
                :items="conceptosComprobantesEgreso"
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
              <v-flex xs12 sm12 md12 lg12>
                <v-text-field label="Total de comprobante" background-color="purple" v-model="comprobante.total_comprobante" outline readonly required></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4 lg4>
                <v-text-field label="Debitos" background-color="orange" v-model="comprobante.debitos" outline readonly required></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4 lg4>
                <v-text-field label="Creditos" background-color="primary" v-model="comprobante.creditos" outline readonly required></v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md4 lg4>
                <v-text-field label="Diferencia" background-color="success" v-model="comprobante.valor" outline readonly required></v-text-field>
              </v-flex>
            </v-layout>
            <v-layout row wrap>
              <v-flex xs12 sm12 md9 lg9>
                <postulador
                  nodata="Busqueda por código o nombre."
                  hint="codigo"
                  itemtext="nombre"
                  datatitle="codigo"
                  datasubtitle="nombre"
                  filter="codigo,nombre"
                  label="Concepto"
                  lite
                  scope="formConceptoEgreso"
                  ref="buscarCuentaBanco"
                  entidad="ts_concepto_egresos"
                  preicon="person"
                  @change="val => comprobante.ts_concepto_egresos = val"
                  @objectReturn="val => comprobante.conceptoegresos = val"
                  :value="comprobante.conceptoegresos"
                  clearable
                ></postulador>
              </v-flex>
              <v-flex xs12 sm12 md3 lg3>
                Anulacion
                <v-radio-group
                  v-model="comprobante.xmil">
                  <v-radio
                  key="anulacionSi"
                  label="Si"
                  value="Si"
                  ></v-radio>
                  <v-radio
                  key="anulacionNo"
                  label="No"
                  value="No"
                  ></v-radio>
                </v-radio-group>
              </v-flex>
              <v-flex xs12 sm12 md12 lg12>
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
                  @change="val => comprobante.nf_niif_concepto_id = val"
                  @objectReturn="val => comprobante.niifconcepto = val"
                  :value="comprobante.niifconcepto"
                  clearable
                ></postulador>
              </v-flex>
              <v-flex xs12 sm12 md3 lg3>
                Tipo de nota
                <v-radio-group
                  v-model="comprobante.tipo_nota" :column="false">
                  <v-radio
                  key="naturalezaDebito"
                  label="Débito"
                  value="Débito"
                  ></v-radio>
                  <v-radio
                  key="naturalezaCredito"
                  label="Crédito"
                  value="Crédito"
                  ></v-radio>
                </v-radio-group>
              </v-flex>
              <v-flex xs12 sm12 md12 lg12>
                <postulador
                  nodata="Busqueda por nombre o número de documento."
                  required
                  hint="nombre_completo"
                  itemtext="identificacion"
                  datatitle="identificacion_completa"
                  datasubtitle="nombre_completo"
                  filter="identificacion_completa,nombre_completo"
                  label="Tercero"
                  scope="formPrincipal"
                  ref="postulaTercero"
                  entidad="terceros"
                  lite
                  preicon="person"
                  @change="val => comprobante.gn_concepto_tercero_id = val"
                  @objectReturn="val => comprobante.terceroconcepto = val"
                  :value="comprobante.terceroconcepto"
                  clearable
                />
              </v-flex>
              <v-flex xs12 sm12 md12 lg12>
                <v-text-field label="Centro"
                  v-model="comprobante.centro_concepto"
                  key="centroConcepto"
                  v-validate="'required'"
                  name="Centro"
                  :error-messages="errors.collect('Centro')"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md9 lg9>
                <v-text-field label="Codigo de retencion"
                  v-model="comprobante.codigo_retencion"
                  key="cdigoRetencion"
                  v-validate="'required'"
                  name="Codigo de retencion"
                  :error-messages="errors.collect('Codigo de retencion')"
                  required>
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md3 lg3>
                <v-text-field label="Valor movimiento"
                  v-model="comprobante.valor_movimiento"
                  key="cdigoRetencion"
                  v-validate="'required'"
                  name="Valor movimiento"
                  :error-messages="errors.collect('Valor movimiento')"
                  required>
                </v-text-field>
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
    name: 'RegistroComprobantesNotas',
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    data () {
      return {
        pickerFechaRecibo: false,
        pickerFechaCheque: false,
        comprobante: {},
        conceptosHeader: [
          { text: 'Concepto', value: 'concepto.codigo', align: 'left', sortable: false },
          { text: 'Nombre', value: 'concepto.nombre', align: 'left', sortable: false },
          { text: 'Valor', value: 'valor', align: 'left', sortable: false },
          { text: 'Opciones', value: 'id', align: 'left', sortable: false }
        ],
        conceptosComprobantesEgreso: [],
        complementosFormComprobantesNotas: []
      }
    },
    mounted () {
      this.axios.get('cajas')
        .then((res) => {
          this.complementosFormComprobantesNotas.cajas = res.data.data
        })
        .catch(e => {
          return false
        })
    }
  }
</script>
