<template>
  <v-card>
    <v-container fluid grid-list-md>
      <v-layout row wrap>
        <v-flex xs12 sm4 md4>
          <v-text-field
            label="Código"
            name="código"
            v-model="concepto.codigo"
            :disabled="!!concepto.id"
            v-validate="'required|max:10'"
            :error-messages="errors.collect('código')"
            @keyup.enter="findByCodigo"
            @blur="findByCodigo"
            :loading="buscandoCodigo"
          >
          </v-text-field>
        </v-flex>
        <v-flex xs12 sm4 md8>
          <v-text-field
            label="Nombre del concepto"
            name="nombre del concepto"
            v-model="concepto.nombre"
            v-validate="'required|max:255'"
            :error-messages="errors.collect('nombre del concepto')"
          >
          </v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md2>
          <v-switch
            label="Afecta bancos"
            v-model="concepto.bancos"
            :true-value="1"
            :false-value="0"
          >
          </v-switch>
        </v-flex>
        <v-flex xs12 sm12 md7>
          <postulador-v2
            v-if="!concepto.bancos"
            ref="validadorNiif"
            no-data="Busqueda por código o nombre."
            hint="codigo"
            item-text="nombre"
            data-title="codigo"
            data-subtitle="nombre"
            label="Cuenta contable"
            entidad="niifs"
            @changeid="val => concepto.nf_niif_id = val"
            v-model="concepto.niif"
            route-params="nivel:auxiliar=1&with=clascuenta"
            name="cuenta contable"
            rules="required"
            v-validate="'required'"
            :error-messages="errors.collect('cuenta contable')"
            no-btn-create
            clearable
          ></postulador-v2>
          <v-autocomplete
            v-else
            label="Cuenta bancaria"
            v-model="concepto.ts_cuenta_id"
            :items="cuentasBancarias"
            item-value="id"
            item-text="id"
            :filter="filterCuentasBancarias"
            placehoder="Busqueda por código, número de cuenta o nombre de banco."
            no-data-text="No hay resultados para mostrar"
            name="cuenta bancaria"
            v-validate="'required'"
            :loading="loadingCuentasBancarias"
            :error-messages="errors.collect('cuenta bancaria')"
            persistent-hint
            :hint="concepto.ts_cuenta_id && !!cuentasBancarias.find(x => x.id === concepto.ts_cuenta_id) ? 'Tipo: ' + cuentasBancarias.find(x => x.id === concepto.ts_cuenta_id).tipo_cuenta : ''"
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
        <v-flex xs12 sm12 md3>
          <span class="v-label caption theme--light">Naturaleza</span>
          <v-radio-group
            class="mt-0"
            v-model="concepto.naturaleza"
            row
          >
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
      </v-layout>
      <v-layout row wrap>
        <v-expansion-panel
          :value="[concepto.presupuesto]"
          readonly
          expand
        >
          <v-expansion-panel-content readonly hide-actions>
            <template slot="header">
              <v-checkbox
                class="checkbox-pt0"
                v-model="concepto.presupuesto"
                label="Afecta presupuesto"
                hide-details
                :true-value="1"
                :false-value="0"
              ></v-checkbox>
            </template>
            <v-card>
              <v-divider></v-divider>
              <v-container fluid grid-list-md class="pa-2">
                <v-layout row wrap>
                  <v-flex xs12 sm12 md5>
                    <v-select
                      label="Entidad resolución de egreso"
                      v-model="concepto.pr_entidad_resolucion_egr_id"
                      item-value="id"
                      item-text="nombre"
                      :items="entidadesResolucion"
                      name="entidad resolución de egreso"
                      v-validate="'required'"
                      :error-messages="errors.collect('entidad resolución de egreso')"
                      :disabled="!concepto.presupuesto"
                      persistent-hint
                      :loading="loadingEntidadesResolucion"
                      :hint="concepto.pr_entidad_resolucion_egr_id && !!entidadesResolucion.find(x => x.id === concepto.pr_entidad_resolucion_egr_id) ? 'Periodo: ' + entidadesResolucion.find(x => x.id === concepto.pr_entidad_resolucion_egr_id).periodo : ''"
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
                              <v-list-tile-sub-title class=caption>Código: {{data.item.codigo}} - Periodo: {{ data.item.periodo }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      </template>
                    </v-select>
                  </v-flex>
                  <v-flex xs12 sm12 md5>
                    <v-autocomplete
                      label="Rubro de egreso"
                      v-model="concepto.pr_detstrgasto_id"
                      :items="detStregresos"
                      item-value="id"
                      item-text="id"
                      :filter="filterStringresos"
                      placehoder="Busqueda por código o nombre."
                      no-data-text="No hay resultados para mostrar"
                      name="rubro de egreso"
                      v-validate="'required'"
                      :error-messages="errors.collect('rubro de egreso')"
                      :disabled="!concepto.presupuesto"
                      :loading="loadingDetStregresos"
                      persistent-hint
                      :hint="concepto.pr_detstrgasto_id && !!detStregresos.find(x => x.id === concepto.pr_detstrgasto_id) ? 'Tipo: ' + detStregresos.find(x => x.id === concepto.pr_detstrgasto_id).tipo_gasto.nombre : ''"
                    >
                      <template slot="selection" slot-scope="data">
                        <div style="width: 100% !important;">
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title>{{data.item.rubro.nombre}}</v-list-tile-title>
                              <v-list-tile-sub-title class=caption>Código: {{data.item.rubro.codigo}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      </template>
                      <template slot="item" slot-scope="data">
                        <div style="width: 100% !important;">
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title>{{data.item.rubro.nombre}}</v-list-tile-title>
                              <v-list-tile-sub-title class=caption>Código: {{data.item.rubro.codigo}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      </template>
                    </v-autocomplete>
                  </v-flex>
                  <v-flex xs12 sm6 md2>
                    <v-switch
                      label="Capturar orden de pago"
                      v-model="concepto.orden_pago"
                      :true-value="1"
                      :false-value="0"
                      :disabled="!concepto.presupuesto"
                    >
                    </v-switch>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card>
          </v-expansion-panel-content>
        </v-expansion-panel>
        <v-expansion-panel
          class="my-1"
          :value="[concepto.concepto_cajamenor]"
          readonly
          expand
        >
          <v-expansion-panel-content readonly hide-actions>
            <template slot="header">
              <v-checkbox
                class="checkbox-pt0"
                v-model="concepto.concepto_cajamenor"
                label="Concepto de caja menor"
                hide-details
                :true-value="1"
                :false-value="0"
              ></v-checkbox>
            </template>
            <v-card>
              <v-divider></v-divider>
              <v-container fluid grid-list-md class="pa-2">
                <v-layout row wrap>
                  <v-flex xs12 sm12 md6>
                    <v-autocomplete
                      label="Caja menor"
                      v-model="concepto.ts_caja_id"
                      :items="cajasMenores"
                      item-value="id"
                      item-text="id"
                      :filter="filterCajasMenores"
                      placehoder="Busqueda por código o nombre."
                      no-data-text="No hay resultados para mostrar"
                      :disabled="!concepto.concepto_cajamenor"
                      name="caja menor"
                      v-validate="'required'"
                      :error-messages="errors.collect('caja menor')"
                      persistent-hint
                      :loading="loadingCajasMenores"
                      :hint="concepto.ts_caja_id && !!cajasMenores.find(x => x.id === concepto.ts_caja_id) ? 'Tipo: ' + cajasMenores.find(x => x.id === concepto.ts_caja_id).tipo : ''"
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
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-switch
                      label="Reembolso caja menor"
                      v-model="concepto.reembolso_cajamenor"
                      :true-value="1"
                      :false-value="0"
                      :disabled="!concepto.concepto_cajamenor"
                    >
                    </v-switch>
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-switch
                      label="Descuentos de caja menor"
                      v-model="concepto.descuento_cajamenor"
                      :true-value="1"
                      :false-value="0"
                      :disabled="!concepto.concepto_cajamenor"
                    >
                    </v-switch>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card>
          </v-expansion-panel-content>
        </v-expansion-panel>
        <v-expansion-panel
          :value="[concepto.devolucion_paciente]"
          readonly
          expand
        >
          <v-expansion-panel-content readonly hide-actions>
            <template slot="header">
              <v-checkbox
                class="checkbox-pt0"
                v-model="concepto.devolucion_paciente"
                label="Devolución pacientes / afiliados"
                hide-details
                :true-value="1"
                :false-value="0"
              ></v-checkbox>
            </template>
            <v-card>
              <v-divider></v-divider>
              <v-container fluid grid-list-md class="pa-2">
                <v-layout row wrap>
                  <v-flex xs12 sm12 md6>
                    <v-select
                      label="Entidad resolución de ingreso"
                      v-model="concepto.pr_entidad_resolucion_ing_id"
                      item-value="id"
                      item-text="nombre"
                      :items="entidadesResolucion"
                      name="entidad resolución de ingreso"
                      v-validate="'required'"
                      :error-messages="errors.collect('entidad resolución de ingreso')"
                      :disabled="!concepto.devolucion_paciente"
                      persistent-hint
                      :loading="loadingEntidadesResolucion"
                      :hint="concepto.pr_entidad_resolucion_ing_id && !!entidadesResolucion.find(x => x.id === concepto.pr_entidad_resolucion_ing_id) ? 'Periodo: ' + entidadesResolucion.find(x => x.id === concepto.pr_entidad_resolucion_ing_id).periodo : ''"
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
                              <v-list-tile-sub-title class=caption>Código: {{data.item.codigo}} - Periodo: {{ data.item.periodo }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      </template>
                    </v-select>
                  </v-flex>
                  <v-flex xs12 sm12 md6>
                    <v-autocomplete
                      label="Rubro de ingreso"
                      v-model="concepto.pr_detstringreso_id"
                      :items="detStringresos"
                      item-value="id"
                      item-text="id"
                      :filter="filterStringresos"
                      placehoder="Busqueda por código o nombre."
                      no-data-text="No hay resultados para mostrar"
                      name="rubro de ingreso"
                      v-validate="'required'"
                      :error-messages="errors.collect('rubro de ingreso')"
                      :disabled="!concepto.devolucion_paciente"
                      persistent-hint
                      :loading="loadingDetStringresos"
                      :hint="concepto.pr_detstringreso_id && !!detStringresos.find(x => x.id === concepto.pr_detstringreso_id) ? 'Tipo: ' + detStringresos.find(x => x.id === concepto.pr_detstringreso_id).tipo_ingreso.nombre : ''"
                    >
                      <template slot="selection" slot-scope="data">
                        <div style="width: 100% !important;">
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title>{{data.item.rubro.nombre}}</v-list-tile-title>
                              <v-list-tile-sub-title class=caption>Código: {{data.item.rubro.codigo}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      </template>
                      <template slot="item" slot-scope="data">
                        <div style="width: 100% !important;">
                          <v-list-tile class="content-v-list-tile-p0">
                            <v-list-tile-content>
                              <v-list-tile-title>{{data.item.rubro.nombre}}</v-list-tile-title>
                              <v-list-tile-sub-title class=caption>Código: {{data.item.rubro.codigo}}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </div>
                      </template>
                    </v-autocomplete>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card>
          </v-expansion-panel-content>
        </v-expansion-panel>
        <v-flex xs12 sm6 md3>
          <v-switch
            label="Afecta facturación"
            v-model="concepto.facturacion"
            :true-value="1"
            :false-value="0"
          >
          </v-switch>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-switch
            label="Maneja anticipos"
            v-model="concepto.anticipos"
            :true-value="1"
            :false-value="0"
            :disabled="!concepto.facturacion"
          >
          </v-switch>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-switch
            label="Requiere contrato"
            v-model="concepto.maneja_contrato"
            :true-value="1"
            :false-value="0"
            :disabled="!concepto.facturacion || !concepto.anticipos"
          >
          </v-switch>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-switch
            label="Descuentos especiales"
            v-model="concepto.descuentos"
            :true-value="1"
            :false-value="0"
          >
          </v-switch>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-switch
            label="Afecta acreedores"
            v-model="concepto.acreedores"
            :true-value="1"
            :false-value="0"
          >
          </v-switch>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-switch
            label="Constitucion de CDT"
            v-model="concepto.cdt"
            :true-value="1"
            :false-value="0"
          >
          </v-switch>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-switch
            label="Incapacidades"
            v-model="concepto.maneja_incapacidad"
            :true-value="1"
            :false-value="0"
          >
          </v-switch>
        </v-flex>
        <v-flex xs12 sm6 md3>
          <v-switch
            label="Cuentas médicas"
            v-model="concepto.maneja_cuentamedica"
            :true-value="1"
            :false-value="0"
          >
          </v-switch>
        </v-flex>
      </v-layout>
      <template v-if="configuracion">
        <v-divider></v-divider>
        <v-card-actions>
          <v-tooltip top>
            <v-btn
              slot="activator"
              @click="formReset(true)"
            >
              <v-icon left>fas fa-eraser</v-icon>
              Limpiar
            </v-btn>
            <span>Limpiar formulario</span>
          </v-tooltip>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            @click="submit"
          >
            Registrar
          </v-btn>
        </v-card-actions>
      </template>
    </v-container>
    <loading v-model="loading" />
    <v-dialog v-model="dialogCodigo" persistent max-width="290">      
      <v-card>
        <v-card-title class="headline">Codigo del concepto de egreso</v-card-title>
        <v-card-text>El codigo del concepto de egreso ya esta siendo utilizado, desea cargar los datos para su actualización o utilizar otro código</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click="editarByCodigo">Cargar datos</v-btn>
          <v-btn color="green darken-1" flat @click="cerrarByCodigo">Otro código</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>
<script>
  import Loading from '@/components/general/Loading'
  import PostuladorV2 from '@/components/general/PostuladorV2'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CONCEPTO_EGRESO_TESORERIA_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'RegistroConceptoEgreso',
    components: {
      Loading,
      Postulador: () => import('@/components/general/Postulador'),
      PostuladorV2
    },
    props: ['parametros', 'configuracion'],
    data () {
      return {
        loading: true,
        dialogCodigo: false,
        loadingCuentasBancarias: false,
        loadingEntidadesResolucion: false,
        loadingDetStregresos: false,
        loadingCajasMenores: false,
        loadingDetStringresos: false,
        buscandoCodigo: false,
        temporal: null,
        cuentasBancarias: [],
        cajasMenores: [],
        entidadesResolucion: [],
        detStringresos: [],
        detStregresos: [],
        filterCuentasBancarias (item, queryText) {
          const hasValue = val => val != null ? val : ''
          return hasValue(item.codigo + ' ' + item.numero_cuenta + ' ' + item.banco.nombre).toString().toLowerCase().indexOf(hasValue(queryText).toString().toLowerCase()) > -1
        },
        filterCajasMenores (item, queryText) {
          const hasValue = val => val != null ? val : ''
          return hasValue(item.codigo + ' ' + item.nombre).toString().toLowerCase().indexOf(hasValue(queryText).toString().toLowerCase()) > -1
        },
        filterStringresos (item, queryText) {
          const hasValue = val => val != null ? val : ''
          return hasValue(item.rubro.codigo + ' ' + item.rubro.nombre).toString().toLowerCase().indexOf(hasValue(queryText).toString().toLowerCase()) > -1
        },
        concepto: {}
      }
    },
    watch: {
      'concepto.pr_entidad_resolucion_ing_id' (val) {
        if (val) {
          this.getDetStringresos(val)
        } else {
          this.concepto.pr_detstringreso_id = null
        }
      },
      'concepto.devolucion_paciente' (val, prev) {
        if (!val && typeof prev !== 'undefined') {
          this.concepto.pr_detstringreso_id = null
          this.concepto.pr_entidad_resolucion_ing_id = null
        }
      },
      'concepto.niif' (val) {
        if (val) this.concepto.naturaleza = val.clascuenta.naturaleza
      },
      'concepto.ts_cuenta_id' (val, prev) {
        if (val && typeof prev !== 'undefined' && this.cuentasBancarias && this.cuentasBancarias.length) {
          this.concepto.naturaleza = this.cuentasBancarias.find(x => x.id === val).niif.clascuenta.naturaleza
        }
      },
      'concepto.concepto_cajamenor' (val, prev) {
        if (val && this.cajasMenores && !this.cajasMenores.length) this.getCajasMenores()
        if (!val && typeof prev !== 'undefined') {
          this.concepto.ts_caja_id = null
          this.concepto.reembolso_cajamenor = 0
          this.concepto.descuento_cajamenor = 0
        }
      },
      'concepto.acreedores' (val, prev) {
        if (!val && typeof prev !== 'undefined') {
          this.concepto.pr_detstrgasto_id = null
        }
      },
      'concepto.pr_entidad_resolucion_egr_id' (val) {
        if (val) {
          this.getDetStregresos(val)
        } else {
          this.concepto.pr_detstrgasto_id = null
        }
      },
      'concepto.presupuesto' (val, prev) {
        if (!val && typeof prev !== 'undefined') {
          this.concepto.pr_detstrgasto_id = null
          this.concepto.pr_entidad_resolucion_egr_id = null
          this.concepto.orden_pago = 0
        }
      },
      'concepto.bancos' (val, prev) {
        if (val && this.cuentasBancarias && !this.cuentasBancarias.length) this.getCuentasBancarias()
        if (typeof prev !== 'undefined') {
          if (val) {
            this.concepto.niif = null
            this.concepto.nf_niif_id = null
          } else {
            this.concepto.ts_cuenta_id = null
          }
        }
      },
      'concepto.facturacion' (val, prev) {
        if (!val && typeof prev !== 'undefined') {
          this.concepto.anticipos = 0
          this.concepto.maneja_contrato = 0
        }
      },
      'concepto.anticipos' (val, prev) {
        if (!val && typeof prev !== 'undefined') {
          this.concepto.maneja_contrato = 0
        }
      }
    },
    created () {
      this.formReset()
      if (this.parametros.id) {
        this.getRegistro(this.parametros.id)
      } else {
        this.loading = false
      }
      this.getEntidadesResolucion()
    },
    methods: {
      submit () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.loading = true
            const typeRequest = this.concepto.id ? 'editar' : 'crear'
            const request = typeRequest === 'crear' ? this.axios.post(`ts_concepto_egresos`, this.concepto) : this.axios.put(`ts_concepto_egresos/${this.concepto.id}`, this.concepto)
            request
              .then(response => {
                this.$store.commit(SNACK_SHOW, {msg: `El concepto se ${typeRequest === 'editar' ? 'actualizó' : 'creó'} correctamente.`, color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit(CONCEPTO_EGRESO_TESORERIA_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
                this.loading = false
              }).catch(e => {
                this.loading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al registrar el concepto. ', error: e})
              })
          }
        })
      },
      getDetStringresos (id) {
        this.loadingDetStringresos = true
        this.axios.get(`pr_stringresos?pr_entidad_resolucion=${id}`)
          .then(response => {
            this.loadingDetStringresos = false
            if (response.data.data.length) this.detStringresos = response.data.data[0].detalles
          })
          .catch(e => {
            this.loadingDetStringresos = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los rubros de ingreso. ', error: e})
          })
      },
      getDetStregresos (id) {
        this.loadingDetStregresos = true
        this.axios.get(`pr_strgastos?pr_entidad_resolucion=${id}`)
          .then(response => {
            this.loadingDetStregresos = false
            if (response.data.data.length) this.detStregresos = response.data.data[0].detalles
          })
          .catch(e => {
            this.loadingDetStregresos = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los rubros de egreso. ', error: e})
          })
      },
      getCajasMenores () {
        this.loadingCajasMenores = true
        this.axios.get(`cajas?tipo=Menor`)
          .then(response => {
            this.cajasMenores = response.data.data
            this.loadingCajasMenores = false
          })
          .catch(e => {
            this.loadingCajasMenores = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer las cajas menores. ', error: e})
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
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer las cuentas bancarias. ', error: e})
          })
      },
      getRegistro (id) {
        this.loading = true
        this.axios.get(`ts_concepto_egresos/${id}`)
          .then(response => {
            this.concepto = response.data.data
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el concepto. ', error: e})
          })
      },
      getEntidadesResolucion () {
        this.loadingEntidadesResolucion = true
        this.axios.get(`pr_entidad_resolucion`)
          .then(response => {
            this.entidadesResolucion = response.data.data
            this.loadingEntidadesResolucion = false
          })
          .catch(e => {
            this.loadingEntidadesResolucion = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer las entidades resolución. ', error: e})
          })
      },
      findByCodigo () {
        if (this.concepto.codigo) {
          this.temporal = null
          this.buscandoCodigo = true
          this.axios.get(`ts_concepto_egresos/codigo/${this.concepto.codigo}`)
            .then(response => {
              if (response.data.data) {
                this.temporal = response.data.data
                this.dialogCodigo = true
              }
              this.buscandoCodigo = false
            }).catch(e => {
              this.buscandoCodigo = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al validar el código. ', error: e})
            })
        }
      },
      editarByCodigo () {
        this.concepto = JSON.parse(JSON.stringify(this.temporal))
        this.dialogCodigo = false
      },
      cerrarByCodigo () {
        this.concepto.codigo = null
        this.dialogCodigo = false
      },
      formReset (input = false) {
        this.concepto = {
          id: null,
          codigo: null,
          nombre: null,
          nf_niif_id: null,
          naturaleza: null,
          facturacion: 0,
          anticipos: 0,
          descuentos: 0,
          presupuesto: 0,
          acreedores: 0,
          concepto_cajamenor: 0,
          orden_pago: 0,
          reembolso_cajamenor: 0,
          descuento_cajamenor: 0,
          cdt: 0,
          devolucion_paciente: 0,
          bancos: 0,
          maneja_contrato: 0,
          maneja_incapacidad: 0,
          maneja_cuentamedica: 0,
          pr_detstrgasto_id: null,
          pr_detstringreso_id: null,
          ts_cuenta_id: null,
          ts_caja_id: null,
          pr_entidad_resolucion_ing_id: null,
          pr_entidad_resolucion_egr_id: null,
          // Objetos
          niif: null
        }
        this.dialogCodigo = false
        this.$validator.reset()
        if (input) this.$emit('input', this.concepto)
      }
    }
  }
</script>
