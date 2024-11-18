<template>
  <v-form data-vv-scope="formTabContable">
    <v-container fluid grid-list-xl>
      <v-layout row wrap>
        <v-flex xs12 sm12 md12 lg12 class="pl-3 pr-3">
          <v-autocomplete
            label="Caja"
            v-model="value.ts_cajas_id"
            :items="cajas"
            required
            name="caja"
            prepend-inner-icon="inbox"
            v-validate="'required'"
            :error-messages="errors.collect('caja')"
            item-value="id"
            :disabled="!value.id ? false : !isEditing"
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
            <template slot="no-data" class="error">
              <div class="pa-1 pl-2 text--white">
                Lo sentimos, el campo no presenta registros. <v-icon>sentiment_very_dissatisfied</v-icon>
              </div>
            </template>
          </v-autocomplete>
        </v-flex>
        <v-flex xs12>
          <v-card>
            <v-card-title class="grey lighten-4 subheading">
              Comprobantes
            </v-card-title>
            <v-container fluid grid-list-xl>
              <v-layout row wrap>
                <v-flex xs12 sm12 md4 lg4>
                  <v-autocomplete
                    label="Diario Caja"
                    v-model="value.tipocaja"
                    :items="complementosFormComDiario.tipcomdiarios"
                    :hint="value.tipocaja ? 'Descripción: ' + value.tipocaja.nombre : null"
                    persistent-hint
                    name="diario caja"
                    key="diario caja"
                    required
                    return-object
                    :disabled="!value.id ? false : !isEditing"
                    item-value="id"
                    item-text="codigo"
                    @change="val => value.nf_comdiario_caja_id = val ? val.id : null"
                    v-validate="'required'"
                    :error-messages="errors.collect('diario caja')"
                    autocomplete clearable>
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.nombre"/>
                          <v-list-tile-sub-title v-html="data.item.codigo"></v-list-tile-sub-title>
                        </v-list-tile-content>
                      </template>
                    </template>
                    <template slot="no-data" class="error">
                      <div class="pa-1 pl-2 text--white">
                        Lo sentimos, el campo no presenta registros. <v-icon>sentiment_very_dissatisfied</v-icon>
                      </div>
                    </template>
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 sm12 md4 lg4>
                  <v-autocomplete
                    label="Egreso"
                    v-model="value.tipoegreso"
                    :items="complementosFormComDiario.tipcomdiarios"
                    name="egreso"
                    :disabled="!value.id ? false : !isEditing"
                    :hint="value.tipoegreso ? 'Descripción: ' + value.tipoegreso.nombre : null"
                    persistent-hint
                    required
                    item-value="id"
                    item-text="codigo"
                    return-object
                    @change="val => value.nf_comdiario_egreso_id = val ? val.id : null"
                    v-validate="'required'"
                    :error-messages="errors.collect('egreso')"
                    autocomplete clearable>
                    <template slot="item" slot-scope="data">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="data.item.nombre"/>
                          <v-list-tile-sub-title v-html="data.item.codigo"></v-list-tile-sub-title>
                        </v-list-tile-content>
                      </template>
                    </template>
                    <template slot="no-data" class="error">
                      <div class="pa-1 pl-2 text--white">
                        Lo sentimos, el campo no presenta registros. <v-icon>sentiment_very_dissatisfied</v-icon>
                      </div>
                    </template>
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 sm12 md4 lg4>
                  <v-autocomplete
                    label="Consignación"
                    v-model="value.tipoconsignacion"
                    :items="complementosFormComDiario.tipcomdiarios"
                    :hint="value.tipoconsignacion ? 'Descripción: ' + value.tipoconsignacion.nombre : null"
                    return-object
                    :disabled="!value.id ? false : !isEditing"
                    persistent-hint
                    name="consignación"
                    key="consignación"
                    required
                    item-value="id"
                    item-text="codigo"
                    @change="val => value.nf_comdiario_consignacion_id = val ? val.id : null"
                    v-validate="'required'"
                    :error-messages="errors.collect('consignación')"
                    autocomplete clearable>
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
                <v-flex xs12 sm12 md4 lg4>
                  <v-autocomplete
                    label="Notas"
                    v-model="value.tiponotas"
                    :items="complementosFormComDiario.tipcomdiarios"
                    :hint="value.tiponotas ? 'Descripción: ' + value.tiponotas.nombre : null"
                    return-object
                    persistent-hint
                    name="notas"
                    key="notas"
                    :disabled="!value.id ? false : !isEditing"
                    required
                    item-value="id"
                    item-text="codigo"
                    @change="val => value.nf_comdiario_notas_id = val ? val.id : null"
                    v-validate="'required'"
                    :error-messages="errors.collect('notas')"
                    autocomplete clearable>
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
                <v-flex xs12 sm12 md4 lg4>
                  <v-autocomplete
                    label="Inversiones"
                    v-model="value.tipoinversiones"
                    :items="complementosFormComDiario.tipcomdiarios"
                    :hint="value.tipoinversiones ? 'Descripción: ' + value.tipoinversiones.nombre : null"
                    return-object
                    persistent-hint
                    :disabled="!value.id ? false : !isEditing"
                    name="inversiones"
                    key="inversiones"
                    required
                    item-value="id"
                    item-text="codigo"
                    @change="val => value.nf_comdiario_inversiones_id = val ? val.id : null"
                    v-validate="'required'"
                    :error-messages="errors.collect('inversiones')"
                    autocomplete clearable>
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
                <v-flex xs12 sm12 md4 lg4>
                  <v-autocomplete
                    label="Aprovecha"
                    v-model="value.tipoaprovecha"
                    :items="complementosFormComDiario.tipcomdiarios"
                    :hint="value.tipoaprovecha ? 'Descripción: ' + value.tipoaprovecha.nombre : null"
                    return-object
                    persistent-hint
                    :disabled="!value.id ? false : !isEditing"
                    name="aprovecha"
                    key="aprovecha"
                    required
                    item-value="id"
                    item-text="codigo"
                    @change="val => value.nf_comdiario_aprovecha_id = val ? val.id : null"
                    v-validate="'required'"
                    :error-messages="errors.collect('aprovecha')"
                    autocomplete clearable>
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
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
        <v-flex xs12>
          <v-card>
            <v-card-title class="grey lighten-4 subheading">
              Inversiones
            </v-card-title>
            <v-container fluid grid-list-xl>
              <v-layout row wrap>
                <v-flex xs12 sm12 md6 lg6 xl6>
                  <postulador
                    nodata="Busqueda por código o nombre."
                    required
                    hint="codigo"
                    itemtext="nombre"
                    datatitle="codigo"
                    datasubtitle="nombre"
                    filter="codigo,nombre"
                    label="Cuenta de Inversiones"
                    :disabled="!value.id ? false : !isEditing"
                    lite
                    scope="formTabContable"
                    ref="buscarCuentaInversiones"
                    entidad="niifs"
                    routeparams="nivel:auxiliar=1"
                    preicon="local_atm"
                    @change="val => value.nf_niif_inversiones_id = val"
                    @objectReturn="val => value.niifinversiones = val"
                    :value="value.niifinversiones"
                    clearable>
                  </postulador>
                </v-flex>
                <v-flex xs12 sm12 md6 lg6 xl6>
                  <postulador
                    nodata="Busqueda por código o nombre."
                    required
                    hint="codigo"
                    itemtext="nombre"
                    datatitle="codigo"
                    datasubtitle="nombre"
                    filter="codigo,nombre"
                    label="Cuenta de Inversiones ING"
                    :disabled="!value.id ? false : !isEditing"
                    lite
                    scope="formTabContable"
                    ref="buscarCuentaInversionesIng"
                    entidad="niifs"
                    routeparams="nivel:auxiliar=1"
                    preicon="local_atm"
                    @change="val => value.nf_niif_inversionesing_id = val"
                    @objectReturn="val => value.niifinversionesing = val"
                    :value="value.niifinversionesing"
                    clearable>
                  </postulador>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
        <v-flex xs12>
          <v-card>
            <v-card-title class="grey lighten-4 subheading">
              Impuestos
            </v-card-title>
            <v-container fluid grid-list-xl>
              <v-layout row wrap>
                <v-flex xs12 sm12 md12 lg12 class="mb-0">
                  <v-subheader class="pa-0 ma-0">Contabilizacion Tasa X 1000</v-subheader>
                  <v-radio-group class="pt-0 mt-0" v-model="value.contabiliacion_tasa"
                                 :column="false" :disabled="!value.id ? false : !isEditing">
                    <v-radio key="contabilizacionTasaSi" label="Específica" value="Especifica"></v-radio>
                    <v-radio key="contabilizacionTasaNo" label="Por Cada Banco" value="Por Cada Banco"></v-radio>
                  </v-radio-group>
                </v-flex>
                <v-flex xs12 sm12 md6 lg6 class="mt-0 pt-0">
                  <postulador
                    nodata="Busqueda por código o nombre."
                    required
                    hint="codigo"
                    itemtext="nombre"
                    datatitle="codigo"
                    datasubtitle="nombre"
                    filter="codigo,nombre"
                    label="Cuenta CXP X 1000"
                    lite
                    :disabled="!value.id ? false : !isEditing"
                    scope="formTabContable"
                    ref="buscarCuentaCxp"
                    entidad="niifs"
                    routeparams="nivel:auxiliar=1"
                    preicon="local_atm"
                    @change="val => value.nf_niif_cxp_id = val"
                    @objectReturn="val => value.niifcxp = val"
                    :value="value.niifcxp"
                    clearable>
                  </postulador>
                </v-flex>
                <v-flex xs12 sm12 md6 lg6 class="mt-0 pt-0">
                  <postulador
                    nodata="Busqueda por código o nombre."
                    required
                    hint="codigo"
                    itemtext="nombre"
                    datatitle="codigo"
                    datasubtitle="nombre"
                    filter="codigo,nombre"
                    label="Cuenta Gasto X 1000"
                    lite
                    :disabled="!value.id ? false : !isEditing"
                    scope="formTabContable"
                    ref="buscarCuentaGasto"
                    entidad="niifs"
                    routeparams="nivel:auxiliar=1"
                    preicon="local_atm"
                    @change="val => value.nf_niif_gasto_id = val"
                    @objectReturn="val => value.niifgasto = val"
                    :value="value.niifgasto"
                    clearable>
                  </postulador>
                </v-flex>
                <v-flex xs12 sm12 md4 lg4 class="mb-0">
                  <v-subheader class="pa-0 ma-0">Tipo de Tasa X 1000</v-subheader>
                  <v-radio-group
                    :disabled="!value.id ? false : !isEditing"
                    class="pt-0 mt-0" v-model="value.tipo_tasa" :column="false">
                    <v-radio key="tipoTasaSi" label="General" value="General"></v-radio>
                    <v-radio key="tipoTasaNo" label="Especifica" value="Especifica"></v-radio>
                  </v-radio-group>
                </v-flex>
                <v-flex xs12 sm12 md8 lg8>
                  <v-text-field label="Tasa X 1000" v-model="value.tasa"></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
        <v-flex xs12>
          <v-card>
            <v-card-title class="grey lighten-4 subheading">
              Aprovechamiento
            </v-card-title>
            <v-container fluid grid-list-xl>
              <v-layout row wrap>
                <v-flex xs12 sm12 md6 lg6>
                  <postulador
                    nodata="Busqueda por código o nombre."
                    required
                    hint="codigo"
                    itemtext="nombre"
                    datatitle="codigo"
                    datasubtitle="nombre"
                    filter="codigo,nombre"
                    label="Cuenta de Aprovechamiento Débito"
                    lite
                    scope="formTabContable"
                    ref="buscarCuentaDebito"
                    entidad="niifs"
                    routeparams="nivel:auxiliar=1"
                    preicon="local_atm"
                    @change="val => value.nf_niif_debito_id = val"
                    @objectReturn="val => value.niifdebito = val"
                    :value="value.niifdebito"
                    clearable
                    :disabled="!value.id ? false : !isEditing"
                  >
                  </postulador>
                </v-flex>
                <v-flex xs12 sm12 md6 lg6>
                  <postulador
                    nodata="Busqueda por código o nombre."
                    required
                    hint="codigo"
                    itemtext="nombre"
                    datatitle="codigo"
                    datasubtitle="nombre"
                    filter="codigo,nombre"
                    label="Cuenta de Aprovechamiento Crédito"
                    lite
                    scope="formTabContable"
                    ref="buscarCuentaCredito"
                    entidad="niifs"
                    routeparams="nivel:auxiliar=1"
                    preicon="local_atm"
                    @change="val => value.nf_niif_credito_id = val"
                    @objectReturn="val => value.niifcredito = val"
                    :value="value.niifcredito"
                    clearable
                    :disabled="!value.id ? false : !isEditing"
                  >
                  </postulador>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-form>
</template>

<script>
  import store from '../../../store/complementos/index'
  export default {
    name: 'TabParametrosContables',
    props: ['value', 'isEditing'],
    inject: {
      $validator: '$validator'
    },
    components: {
      Postulador: () => import('@/components/general/Postulador')
    },
    created () {
      this.axios.get('cajas')
        .then((res) => {
          this.cajas = res.data.data
          // this.complementosFormParametros.cajas = res.data.data
        })
        .catch(e => {
          return false
        })
    },
    data () {
      return {
        cajas: []
        // complementosFormParametros: {}
      }
    },
    computed: {
      complementosFormComDiario () {
        return store.getters.complementosFormComDiario
      }
    },
    watch: {
      'errors.items' (val) {
        this.$emit('errors', !(val.filter(item => item.scope === 'formTabContable').length > 0))
      }
    },
    methods: {
      validate () {
        return this.$validator.validateAll('formTabContable').then(result => { return result })
      }
    }
  }
</script>

<style scoped>

</style>
