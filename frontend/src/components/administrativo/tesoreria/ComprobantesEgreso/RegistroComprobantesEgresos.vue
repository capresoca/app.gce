<template>
  <v-card>
    <v-toolbar dense>
      <v-icon>fas fa-file-invoice-dollar</v-icon>
      <v-toolbar-title>{{comprobante.id ? 'Edición de' : 'Nuevo'}} comprobante de egreso</v-toolbar-title>
      <template v-if="comprobante.id">
        <v-spacer></v-spacer>
        <v-divider vertical></v-divider>
        <span class="pt-1 pl-2 text-xs-right">
        <span class="title text-capitalize">Comprobante</span>
        <p class="mb-0 caption">{{comprobante.estado}}</p>
      </span>
        <v-chip label color="white" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="comprobante.consecutivo"></span>
        </v-chip>
      </template>
    </v-toolbar>
    <v-container fluid grid-list-sm>
      <v-layout row wrap>
        <v-flex xs12 sm12 md9>
          <postulador-v2
            no-data="Busqueda por nombre o número de documento."
            hint="identificacion_completa"
            item-text="nombre_completo"
            data-title="identificacion_completa"
            data-subtitle="nombre_completo"
            label="Tercero"
            :detail="detalleTercero"
            entidad="terceros"
            @changeid="val => comprobante.gn_tercero_id = val"
            v-model="comprobante.tercero"
            name="Tercero"
            preicon="person"
            :rules="'required'"
            v-validate="'required'"
            :error-messages="errors.collect('Tercero')"
          ></postulador-v2>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <input-date
            v-model="comprobante.fecha"
            label="Fecha comprobante"
            hint="Formato: (Año-Mes-Día)"
            name="fecha comprobante"
            v-validate="'required|fechaValida|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
            :error-messages="errors.collect('fecha comprobante')"
            :min="minDate"
            :max="maxDate"
          ></input-date>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-select
            label="Forma de pago"
            :items="complementos.formasPagoEgreso"
            v-model="comprobante.forma_pago"
            name="forma de pago"
            v-validate="'required'"
            :error-messages="errors.collect('forma de pago')"
          ></v-select>
        </v-flex>
        <v-flex xs12 ms12 md9>
          <v-autocomplete
            key="cuentaCaja"
            v-if="comprobante.forma_pago === 'Efectivo'"
            label="Caja"
            v-model="comprobante.ts_caja_id"
            :items="cajas"
            item-value="id"
            item-text="id"
            :filter="filterCajas"
            placehoder="Busqueda por código o nombre."
            no-data-text="No hay resultados para mostrar"
            :disabled="!comprobante.forma_pago"
            name="caja"
            v-validate="'required'"
            :error-messages="errors.collect('caja')"
            persistent-hint
            :loading="loadingCajas"
            :hint="comprobante.ts_caja_id && !!cajas.find(x => x.id === comprobante.ts_caja_id) ? 'Tipo: ' + cajas.find(x => x.id === comprobante.ts_caja_id).tipo : ''"
          >
            <template slot="selection" slot-scope="data">
              <div style="width: 100% !important;">
                <v-list-tile class="content-v-list-tile-p0">
                  <v-list-tile-content>
                    <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                    <v-list-tile-sub-title class=caption>Código: {{data.item.codigo}}</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
              </div>
            </template>
            <template slot="item" slot-scope="data">
              <div style="width: 100% !important;">
                <v-list-tile class="content-v-list-tile-p0">
                  <v-list-tile-content>
                    <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                    <v-list-tile-sub-title class=caption>Código: {{data.item.codigo}}</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
              </div>
            </template>
          </v-autocomplete>
          <v-autocomplete
            v-else
            key="cuentaBancaria"
            label="Cuenta bancaria"
            v-model="comprobante.ts_cuenta_id"
            :items="cuentasBancarias"
            item-value="id"
            item-text="id"
            :filter="filterCuentasBancarias"
            placehoder="Busqueda por código, número de cuenta o nombre de banco."
            no-data-text="No hay resultados para mostrar"
            name="cuenta bancaria"
            :disabled="!comprobante.forma_pago"
            v-validate="'required'"
            :loading="loadingCuentasBancarias"
            :error-messages="errors.collect('cuenta bancaria')"
            persistent-hint
            :hint="comprobante.ts_cuenta_id && !!cuentasBancarias.find(x => x.id === comprobante.ts_cuenta_id) ? 'Tipo: ' + cuentasBancarias.find(x => x.id === comprobante.ts_cuenta_id).tipo_cuenta : ''"
          >
            <template slot="selection" slot-scope="data">
              <div style="width: 100% !important;">
                <v-list-tile class="content-v-list-tile-p0">
                  <v-list-tile-content>
                    <v-list-tile-title>{{data.item.numero_cuenta}}</v-list-tile-title>
                    <v-list-tile-sub-title class=caption>Código: {{data.item.codigo}} - Banco: {{data.item.banco.nombre}}</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
              </div>
            </template>
            <template slot="item" slot-scope="data">
              <div style="width: 100% !important;">
                <v-list-tile class="content-v-list-tile-p0">
                  <v-list-tile-content>
                    <v-list-tile-title>{{data.item.numero_cuenta}}</v-list-tile-title>
                    <v-list-tile-sub-title class=caption>Código: {{data.item.codigo}} - Banco: {{data.item.banco.nombre}}</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
              </div>
            </template>
          </v-autocomplete>
        </v-flex>
        <v-flex xs12 sm12 md12 v-if="showCentroCosto">
          <v-autocomplete
            label="Centro de costo"
            v-model="comprobante.nf_cencosto_id"
            :items="complementos.cencostos"
            item-value="id"
            item-text="id"
            :filter="filterCentrosCostos"
            placehoder="Busqueda por código, nombre o tipo."
            no-data-text="No hay resultados para mostrar"
            name="centro de costo"
            :disabled="!comprobante.forma_pago"
            v-validate="'required'"
            :loading="loadingCentrosCostos"
            :error-messages="errors.collect('centro de costo')"
            persistent-hint
            :hint="comprobante.nf_cencosto_id && complementos.cencostos && !!complementos.cencostos.find(x => x.id === comprobante.nf_cencosto_id) ? 'Tipo: ' + complementos.cencostos.find(x => x.id === comprobante.nf_cencosto_id).tipo : ''"
          >
            <template slot="selection" slot-scope="data">
              <div style="width: 100% !important;">
                <v-list-tile class="content-v-list-tile-p0">
                  <v-list-tile-content>
                    <v-list-tile-title>{{data.item.codigo}} - {{data.item.nombre}}</v-list-tile-title>
                    <v-list-tile-sub-title class=caption>Tipo: {{data.item.tipo}}</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
              </div>
            </template>
            <template slot="item" slot-scope="data">
              <div style="width: 100% !important;">
                <v-list-tile class="content-v-list-tile-p0">
                  <v-list-tile-content>
                    <v-list-tile-title>{{data.item.codigo}} - {{data.item.nombre}}</v-list-tile-title>
                    <v-list-tile-sub-title class=caption>Tipo: {{data.item.tipo}}</v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
              </div>
            </template>
          </v-autocomplete>
        </v-flex>
<!--        <v-flex xs12 sm12 md2 lg2>-->
<!--          <v-text-field label="Cheque"-->
<!--                        v-model="comprobante.cheque"-->
<!--                        key="chequeCampo"-->
<!--                        v-validate="'required'"-->
<!--                        name="Cheque"-->
<!--                        :error-messages="errors.collect('Cheque')"-->
<!--                        required>-->
<!--          </v-text-field>-->
<!--        </v-flex>-->
<!--        <v-flex xs12 sm12 md2 lg2>-->
<!--          X Mil-->
<!--          <v-radio-group-->
<!--            v-model="comprobante.xmil" :column="false">-->
<!--            <v-radio-->
<!--              key="xMilSi"-->
<!--              label="Si"-->
<!--              value="Si"-->
<!--            ></v-radio>-->
<!--            <v-radio-->
<!--              key="xMilNo"-->
<!--              label="No"-->
<!--              value="No"-->
<!--            ></v-radio>-->
<!--          </v-radio-group>-->
<!--        </v-flex>-->
<!--        <v-flex xs12 sm12 md2 lg2>-->
<!--          <v-text-field v-model="comprobante.centro" placeholder="valor x mil">-->
<!--          </v-text-field>-->
<!--        </v-flex>-->
        <v-flex xs12 sm12 md12>
          <v-textarea
            v-model="comprobante.descripcion"
            label="Detalle"
            rows="2"
            auto-grow
          ></v-textarea>
        </v-flex>
      </v-layout>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card>
            <v-toolbar dense>
              <v-icon>fas fa-list</v-icon>
              <v-toolbar-title>Conceptos del comprobante</v-toolbar-title>
              <v-spacer></v-spacer>
              <registro-concepto v-if="comprobante.tercero" :comprobante="comprobante" @returnConcepto="concepto => comprobante.conceptos.push(concepto)"></registro-concepto>
            </v-toolbar>
            <v-data-table
              item-key="index"
              :headers="comprobantesHeader"
              :items="comprobante.conceptos"
              hide-actions
              no-data-text="No hay conceptos registrados"
            >
              <template slot="items" slot-scope="props">
                <tr @click="props.expanded = !props.expanded">
                  <td class="text-xs-left">{{props.item.concepto.codigo}}</td>
                  <td class="text-xs-left">{{props.item.concepto.nombre}}</td>
                  <td class="text-xs-right">{{ currency(window ? window.lodash.sumBy(props.item.detalles, 'valor') : 0) }}</td>
                  <td class="text-xs-center">
                    <v-tooltip right>
                      <v-btn flat icon color="red" class="darken-3" slot="activator" @click.stop="comprobante.conceptos.splice(props.index, 1)">
                        <v-icon>delete</v-icon>
                      </v-btn>
                      <span>Borrar</span>
                    </v-tooltip>
                  </td>
                </tr>
              </template>
              <template slot="expand" slot-scope="props">
                <v-container fluid grid-list-md class="blue-grey lighten-4">
                  <v-layout row wrap>
                    <v-flex xs12>
                      <template v-if="props.item.detalles.length">
                        <template v-if="props.item.detalles.filter(x => x.cxp).length">
                          <v-system-bar status color="primary" dark>
                            <v-spacer></v-spacer>
                            <span>Registros de cuentas por pagar</span>
                          </v-system-bar>
                          <v-data-table
                            :items="props.item.detalles.filter(x => x.cxp)"
                            hide-actions
                            no-data-text="No hay detalles registrados."
                          >
                            <template v-slot:headers="props">
                              <tr style="height: 10px !important;">
                                <th class="text-xs-center">CxP</th>
                                <th class="text-xs-left">Causación CxP</th>
                                <th class="text-xs-left">Cuenta CxP</th>
                                <th class="text-xs-center">Módulo</th>
                                <th class="text-xs-center">Factura</th>
                                <th class="text-xs-right">Valor</th>
                                <th class="text-xs-right">Saldo</th>
                                <th class="text-xs-right">Valor a pagar</th>
                              </tr>
                            </template>
                            <template v-slot:items="propsx">
                              <td class="text-xs-center">{{ propsx.item.cxp.consecutivo }}</td>
                              <td class="text-xs-left">{{propsx.item.cxp.fecha_causacion}}</td>
                              <td class="text-xs-left">{{propsx.item.cxp.niif.codigo}} - {{propsx.item.cxp.niif.nombre}}</td>
                              <td class="text-xs-center">{{ propsx.item.cxp.modulo }}</td>
                              <td class="text-xs-center">{{ propsx.item.cxp.no_factura }}</td>
                              <td class="text-xs-right">{{ currency(propsx.item.cxp.valor_sin_formato) }}</td>
                              <td class="text-xs-right">{{ currency(propsx.item.cxp.saldo) }}</td>
                              <td class="text-xs-right">{{ currency(propsx.item.cxp.valor) }}</td>
                            </template>
                          </v-data-table>
                        </template>
                        <template v-if="props.item.detalles.filter(x => !x.cxp).length">
                          <v-system-bar status color="primary" dark>
                            <v-spacer></v-spacer>
                            <span>Registros de anticipos</span>
                          </v-system-bar>
                          <v-data-table
                            :items="props.item.detalles.filter(x => !x.cxp)"
                            hide-actions
                            no-data-text="No hay detalles registrados."
                          >
                            <template v-slot:headers="props">
                              <tr style="height: 10px !important;">
                                <th class="text-xs-left">Contrato</th>
                                <th class="text-xs-left">Plan cobertura</th>
                                <th class="text-xs-right">Valor</th>
                              </tr>
                            </template>
                            <template v-slot:items="propsx">
                              <td class="text-xs-left">
                                <v-list-tile class="content-v-list-tile-p0" v-if="propsx.item.contrato">
                                  <v-list-tile-content>
                                    <v-list-tile-title>No. {{propsx.item.contrato.numero_contrato}}</v-list-tile-title>
                                    <v-list-tile-sub-title>{{propsx.item.contrato.entidad.nombre}}</v-list-tile-sub-title>
                                  </v-list-tile-content>
                                </v-list-tile>
                                <span v-else>No aplica</span>
                              </td>
                              <td class="text-xs-left">
                                <v-list-tile class="content-v-list-tile-p0" v-if="propsx.item.plan_cobertura">
                                  <v-list-tile-content>
                                    <v-list-tile-title>{{propsx.item.plan_cobertura.nombre}}</v-list-tile-title>
                                    <v-list-tile-sub-title>Estado: {{propsx.item.plan_cobertura.estado}}</v-list-tile-sub-title>
                                  </v-list-tile-content>
                                </v-list-tile>
                                <span v-else>No aplica</span>
                              </td>
                              <td class="text-xs-right">{{ currency(propsx.item.valor) }}</td>
                            </template>
                          </v-data-table>
                        </template>
                      </template>
                    </v-flex>
                  </v-layout>
                </v-container>
<!--                <v-data-table-->
<!--                  class="px-4 py-1 cyan darken-2 table-detalle-conceptos"-->
<!--                  :items="props.item.detalles"-->
<!--                  :headers="conceptoCXPHeader"-->
<!--                  hide-actions-->
<!--                >-->
<!--                  <template slot="items" slot-scope="propsx">-->
<!--                    <td class="text-xs-left">{{propsx.item.cxp.consecutivo}}</td>-->
<!--                    <td class="text-xs-left">{{propsx.item.cxp.fecha_causacion}}</td>-->
<!--                    <td class="text-xs-left">{{propsx.item.cxp.niif.codigo}} - {{propsx.item.cxp.niif.nombre}}</td>-->
<!--                    <td class="text-xs-right">{{currency(propsx.item.cxp.valor_sin_formato)}}</td>-->
<!--                    <td class="text-xs-right">saldo de la cxp</td>-->
<!--                    <td class="text-xs-right">-->
<!--                      {{currency(propsx.item.valor)}}-->
<!--                    </td>-->
<!--                  </template>-->
<!--                </v-data-table>-->
              </template>
              <template slot="footer">
                <td colspan="3" class="font-weight-bold text-xs-right">
                  Total Comprobante
                </td>
                <td class="font-weight-bold text-xs-right">
                  {{currency(totalComprobante)}}
                </td>
              </template>
            </v-data-table>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
    <v-divider></v-divider>
    <v-card-actions>
      <v-tooltip top>
        <v-btn
          slot="activator"
          @click="formReset"
        >
          <v-icon left>fas fa-eraser</v-icon>
          Limpiar
        </v-btn>
        <span>Limpiar formulario</span>
      </v-tooltip>
      <v-spacer></v-spacer>
      <span class="caption error--text mr-3"><strong>{{errorValidate}}</strong></span>
      <v-btn
        color="primary"
        @click="confirmaComprobante"
      >
        Guardar y confirmar
      </v-btn>
      <v-btn
        color="primary"
        @click="submit"
      >
        Guardar
      </v-btn>
    </v-card-actions>
    <confirmar
      :value="dialogA.visible"
      :content="dialogA.content"
      @cancelar="dialogA.visible = false"
      :requiere-motivo="false"
      @aceptar="aceptaConfirmacion"
    />
    <loading v-model="loading"></loading>
  </v-card>
</template>
<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'RegistroComprobantesEgresos',
    props: ['parametros'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputDate: () => import('@/components/general/InputDate'),
      Confirmar: () => import('@/components/general/Confirmar'),
      RegistroConcepto: () => import('@/components/administrativo/tesoreria/ComprobantesEgreso/RegistroConcepto')
    },
    data () {
      return {
        window: null,
        loading: true,
        comprobante: null,
        detalleTercero: () => import('@/components/administrativo/niif/terceros/DetalleTercero'),
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10),
        cuentasBancarias: [],
        cajas: [],
        loadingCuentasBancarias: false,
        loadingCajas: false,
        loadingCentrosCostos: false,
        filterCuentasBancarias (item, queryText) {
          const hasValue = val => val != null ? val : ''
          return hasValue(item.codigo + ' ' + item.numero_cuenta + ' ' + item.banco.nombre).toString().toLowerCase().indexOf(hasValue(queryText).toString().toLowerCase()) > -1
        },
        filterCajas (item, queryText) {
          const hasValue = val => val != null ? val : ''
          return hasValue(item.codigo + ' ' + item.nombre).toString().toLowerCase().indexOf(hasValue(queryText).toString().toLowerCase()) > -1
        },
        filterCentrosCostos (item, queryText) {
          const hasValue = val => val != null ? val : ''
          return hasValue(item.codigo + ' ' + item.nombre + ' ' + item.tipo).toString().toLowerCase().indexOf(hasValue(queryText).toString().toLowerCase()) > -1
        },
        comprobantesHeader: [
          { text: 'Código', align: 'left', sortable: false },
          { text: 'Nombre', align: 'left', sortable: false },
          { text: 'Valor', align: 'right', sortable: false },
          { text: '', align: 'center', sortable: false }
        ],
        conceptoCXPHeader: [
          { text: 'Num. CxP', align: 'left', sortable: false },
          { text: 'Causación CxP', align: 'left', sortable: false },
          { text: 'Cuenta CxP', align: 'left', sortable: false },
          { text: 'Valor CxP', align: 'right', sortable: false },
          { text: 'Saldo CxP', align: 'right', sortable: false },
          { text: 'Valor a pagar', align: 'right', sortable: false }
        ],
        dialogA: {
          visible: false,
          content: `El comprobante de egreso serán confirmados, si decide continuar, los datos no se podran editar nuevamente.<br/> <strong>¿Está seguro de confirmar el comprobante?</strong>`
        },
        errorValidate: ''
      }
    },
    watch: {
      'comprobante.conceptos.length' () {
        this.comprobante.conceptos.map((x, i) => { x.index = i })
      },
      'comprobante.forma_pago' (val, prev) {
        if (val && val === 'Efectivo' && this.cajas && !this.cajas.length) this.getCajas()
        if (val && val !== 'Efectivo' && this.cuentasBancarias && !this.cuentasBancarias.length) this.getCuentasBancarias()
        if (typeof prev !== 'undefined') {
          if (val && val === 'Efectivo') {
            this.comprobante.ts_cuenta_id = null
          } else {
            this.comprobante.ts_caja_id = null
          }
        }
      }
    },
    computed: {
      showCentroCosto () {
        return this.comprobante && (this.cuentasBancarias.length || this.cajas.length) && ((this.comprobante.ts_cuenta_id && this.cuentasBancarias.find(x => x.id === this.comprobante.ts_cuenta_id).niif.maneja_ccosto) || (this.comprobante.ts_caja_id && this.cajas.find(x => x.id === this.comprobante.ts_caja_id).niif.maneja_ccosto))
      },
      totalComprobante () {
        let sumTotal = 0
        this.comprobante.conceptos.forEach(x => {
          sumTotal = sumTotal + window.lodash.sumBy(x.detalles, 'valor')
        })
        return sumTotal
      },
      complementos () {
        return store.getters.complementosFormComprobantesEgresos
      }
    },
    created () {
      this.window = window
      this.formReset()
      if (this.parametros && this.parametros.entidad && this.parametros.entidad.id) {
        this.getRegistro(this.parametros.entidad.id)
      } else {
        this.loading = false
      }
    },
    methods: {
      aceptaConfirmacion () {
        this.comprobante.estado = 'Confirmado'
        this.submit()
        this.dialogA.visible = false
      },
      async confirmaComprobante () {
        if (await this.validate()) this.dialogA.visible = true
      },
      submit () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            if (!this.showCentroCosto) this.comprobante.nf_cencosto_id = null
            const typeRequest = this.comprobante.id ? 'editar' : 'crear'
            const request = typeRequest === 'crear' ? this.axios.post(`compegresos`, this.comprobante) : this.axios.put(`compegresos/${this.comprobante.id}`, this.comprobante)
            request
              .then(response => {
                this.$store.commit('SNACK_SHOW', {msg: `El comprobante se ${typeRequest === 'editar' ? 'actualizó' : 'creó'} correctamente.`, color: 'success'})
                this.$store.commit('COMPROBANTE_EGRESO_TESORERIA_RELOAD_ITEM', {item: response.data.data, estado: typeRequest, key: this.parametros.key})
                this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: this.parametros.tabOrigin })
                this.loading = false
              }).catch(e => {
                this.loading = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: ' al registrar el comprobante. ', error: e})
              })
          } else {
          }
        })
      },
      getRegistro (id) {
        this.loading = true
        this.axios.get(`compegresos/${id}`)
          .then(response => {
            response.data.data.fecha = this.moment(response.data.data.fecha).format('YYYY-MM-DD')
            this.comprobante = response.data.data
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer el comprobante. ', error: e})
          })
      },
      getCajas () {
        this.loadingCajas = true
        this.axios.get(`cajas`)
          .then(response => {
            this.cajas = response.data.data
            this.loadingCajas = false
          })
          .catch(e => {
            this.loadingCajas = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer las cajas. ', error: e})
          })
      },
      getCuentasBancarias () {
        this.loadingCuentasBancarias = true
        this.axios.get(`cuentas`)
          .then(response => {
            this.cuentasBancarias = response.data.data
            this.loadingCuentasBancarias = false
          })
          .catch(e => {
            this.loadingCuentasBancarias = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer las cuentas bancarias. ', error: e})
          })
      },
      formReset () {
        this.comprobante = {
          id: null,
          fecha: null,
          consecutivo: null,
          gn_tercero_id: null,
          forma_pago: null,
          ts_cuenta_id: null,
          ts_caja_id: null,
          nf_cencosto_id: null,
          descripcion: null,
          rs_planescobertura_id: null,
          gs_usuario_id: null,
          gs_usuanula_id: null,
          fecha_anula: null,
          motivo_anula: null,
          anticipo: 0,
          estado: 'Registrado',
          conceptos: [],
          // Objetos
          niif: null,
          tercero: null
        }
        this.$validator.reset()
      },
      async validate () {
        let validacionFormulario = await this.$validator.validateAll()
        if (validacionFormulario) {
          return true
        } else {
          this.errorValidate = 'Hay datos por diligenciar en el formulario.'
          setTimeout(() => {
            this.errorValidate = ''
          }, 8000)
          return false
        }
      }
    }
  }
</script>
<style>
  .table-detalle-conceptos>div>table>thead>tr:first-child{
    height: 30px !important;
  }
  .table-detalle-conceptos>div>table>thead>tr:first-child>th{
    height: 30px !important;
    font-weight: bold !important;
  }
  .table-detalle-conceptos>div>table>tbody>tr>td{
    height: 30px !important;
  }
</style>
