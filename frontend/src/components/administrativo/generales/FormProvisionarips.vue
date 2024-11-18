<template>
  <v-form data-vv-scope="formTabProvisional">
    <registro-niif :value="dialogFormNiif" :cue="cuentaNiff" @cancelar="dialogFormNiif = false" @crear="val => goNiif(val)"></registro-niif>
    <v-card>
      <v-container fluid grid-list-xl>
        <v-layout row wrap>
          <v-flex xs12>
            <h3>Prestador con Contrato</h3>
          </v-flex>
          <v-flex xs12 sm4 md4>
            <postulador-v2
              no-data="Busqueda por código o nombre."
              hint="nombre"
              item-text="codigo"
              data-title="codigo"
              data-subtitle="nombre"
              scope="formTabProvisional"
              :label="!value.nf_niifdebitoprovisional_id ? 'cuenta débito provisional' : 'Cuenta Débito Provisional'"
              entidad="niifs"
              route-params="nivel:auxiliar=1"
              preicon="business_center"
              @changeid="val => value.nf_niifdebitoprovisional_id = val"
              v-model="value.cuenta_debitoprovisional"
              v-validate="'required'"
              name="cuenta débito provisional"
              :error-messages="errors.collect('cuenta débito provisional')"
              :disabled="!value.id ? false : !isEditing"
              btnCreateTruncate
              @create="openNuevaNiif(1)"
              :min-characters-search="2"
              clearable/>
<!--            <postulador-->
<!--              nodata="Busqueda por código o nombre."-->
<!--              required-->
<!--              hint="nombre"-->
<!--              itemtext="codigo"-->
<!--              datatitle="codigo"-->
<!--              datasubtitle="nombre"-->
<!--              filter="codigo,nombre"-->
<!--              :label="!value.nf_niifdebitoprovisional_id ? 'cuenta débito provisional' : 'Cuenta Débito Provisional'"-->
<!--              scope="formTabProvisional"-->
<!--              ref="buscarCuentadeficit"-->
<!--              entidad="niifs"-->
<!--              preicon="business_center"-->
<!--              routeparams="nivel:auxiliar=1"-->
<!--              @change="val => value.nf_niifdebitoprovisional_id = val"-->
<!--              @objectReturn="val => value.cuenta_debitoprovisional = val"-->
<!--              :value="value.cuenta_debitoprovisional"-->
<!--              clearable-->
<!--              btnplustruncate-->
<!--              :disabled="!value.id ? false : !isEditing"-->
<!--              @click="openNuevaNiif(1)"-->
<!--            ></postulador>-->
          </v-flex>
          <v-flex xs12 sm4 md4>
            <postulador-v2
              no-data="Busqueda por código o nombre."
              hint="nombre"
              item-text="codigo"
              data-title="codigo"
              data-subtitle="nombre"
              scope="formTabProvisional"
              :label="!value.nf_niifcreditoprovisional_id ? 'cuenta crédito provisional' : 'Cuenta Crédito Provisional'"
              entidad="niifs"
              route-params="nivel:auxiliar=1"
              preicon="business_center"
              @changeid="val => value.nf_niifcreditoprovisional_id = val"
              v-model="value.cuenta_creditoprovisional"
              v-validate="'required'"
              name="cuenta crédito provisional"
              :error-messages="errors.collect('cuenta crédito provisional')"
              :disabled="!value.id ? false : !isEditing"
              btnCreateTruncate
              @create="openNuevaNiif(2)"
              :min-characters-search="2"
              clearable/>
<!--            <postulador-->
<!--              nodata="Busqueda por código o nombre."-->
<!--              required-->
<!--              hint="nombre"-->
<!--              itemtext="codigo"-->
<!--              datatitle="codigo"-->
<!--              datasubtitle="nombre"-->
<!--              filter="codigo,nombre"-->
<!--              :label="!value.nf_niifcreditoprovisional_id ? 'cuenta crédito provisional' : 'Cuenta Crédito Provisional'"-->
<!--              scope="formTabProvisional"-->
<!--              ref="buscarCuentadeficit"-->
<!--              entidad="niifs"-->
<!--              preicon="business_center"-->
<!--              routeparams="nivel:auxiliar=1"-->
<!--              @change="val => value.nf_niifcreditoprovisional_id = val"-->
<!--              @objectReturn="val => value.cuenta_creditoprovisional = val"-->
<!--              :value="value.cuenta_creditoprovisional"-->
<!--              clearable-->
<!--              btnplustruncate-->
<!--              :disabled="!value.id ? false : !isEditing"-->
<!--              @click="openNuevaNiif(2)"-->
<!--            ></postulador>-->
          </v-flex>
          <v-flex xs12 sm4 md4>
            <v-autocomplete
              label="Tipo de Comprobante Contratado"
              v-model="value.nf_comdiarioprovisional_id"
              :items="complementos.tipcomdiarios"
              item-value="id"
              item-text="nombre"
              name="tipo de comprobante contratado"
              required
              v-validate="'required'"
              :disabled="!value.id ? false : !isEditing"
              :error-messages="errors.collect('tipo de comprobante contratado')"
              autocomplete>
              <template slot="item" slot-scope="data">
                <template>
                  <v-list-tile-content>
                    <v-list-tile-title v-html="data.item.nombre"/>
                  </v-list-tile-content>
                </template>
              </template>
            </v-autocomplete>
          </v-flex>
          <v-flex xs12>
            <v-divider></v-divider>
            <h3>Prestador sin Contrato</h3>
          </v-flex>
          <v-flex xs12 sm4 md4>
            <postulador-v2
              no-data="Busqueda por código o nombre."
              hint="nombre"
              item-text="codigo"
              data-title="codigo"
              data-subtitle="nombre"
              scope="formTabProvisional"
              :label="!value.nf_niifdebitoprosincontrato_id ? 'cuenta débito (sin contrato)' : 'Cuenta Débito (Sin Contrato)'"
              entidad="niifs"
              route-params="nivel:auxiliar=1"
              preicon="business_center"
              @changeid="val => value.nf_niifdebitoprosincontrato_id = val"
              v-model="value.cuentadebito_sincontrato"
              v-validate="'required'"
              name="cuenta débito (sin contrato)"
              :error-messages="errors.collect('cuenta débito (sin contrato)')"
              :disabled="!value.id ? false : !isEditing"
              btnCreateTruncate
              @create="openNuevaNiif(3)"
              :min-characters-search="2"
              clearable/>
<!--            <postulador-->
<!--              nodata="Busqueda por código o nombre."-->
<!--              required-->
<!--              hint="nombre"-->
<!--              itemtext="codigo"-->
<!--              datatitle="codigo"-->
<!--              datasubtitle="nombre"-->
<!--              filter="codigo,nombre"-->
<!--              :label="!value.nf_niifdebitoprosincontrato_id ? 'cuenta débito (sin contrato)' : 'Cuenta Débito (Sin Contrato)'"-->
<!--              scope="formTabProvisional"-->
<!--              ref="buscarCuentadebitosincontrato"-->
<!--              entidad="niifs"-->
<!--              preicon="business_center"-->
<!--              routeparams="nivel:auxiliar=1"-->
<!--              @change="val => value.nf_niifdebitoprosincontrato_id = val"-->
<!--              @objectReturn="val => value.cuentadebito_sincontrato = val"-->
<!--              :value="value.cuentadebito_sincontrato"-->
<!--              clearable-->
<!--              btnplustruncate-->
<!--              :disabled="!value.id ? false : !isEditing"-->
<!--              @click="openNuevaNiif(3)"-->
<!--            ></postulador>-->
          </v-flex>
          <v-flex xs12 sm4 md4>
            <postulador-v2
              no-data="Busqueda por código o nombre."
              hint="nombre"
              item-text="codigo"
              data-title="codigo"
              data-subtitle="nombre"
              scope="formTabProvisional"
              :label="!value.nf_niifcreditoprosincontrato_id ? 'cuenta crédito (sin contrato)' : 'Cuenta Crédito (Sin Contrato)'"
              entidad="niifs"
              route-params="nivel:auxiliar=1"
              preicon="business_center"
              @changeid="val => value.nf_niifcreditoprosincontrato_id = val"
              v-model="value.cuentacredito_sincontrato"
              v-validate="'required'"
              name="cuenta crédito (sin contrato)"
              :error-messages="errors.collect('cuenta crédito (sin contrato)')"
              :disabled="!value.id ? false : !isEditing"
              btnCreateTruncate
              @create="openNuevaNiif(4)"
              :min-characters-search="2"
              clearable/>
<!--            <postulador-->
<!--              nodata="Busqueda por código o nombre."-->
<!--              required-->
<!--              hint="nombre"-->
<!--              itemtext="codigo"-->
<!--              datatitle="codigo"-->
<!--              datasubtitle="nombre"-->
<!--              filter="codigo,nombre"-->
<!--              :label="!value.nf_niifcreditoprosincontrato_id ? 'cuenta crédito (sin contrato)' : 'Cuenta Crédito (Sin Contrato)'"-->
<!--              scope="formTabProvisional"-->
<!--              ref="buscarCuentacreditosincontrato"-->
<!--              entidad="niifs"-->
<!--              preicon="business_center"-->
<!--              routeparams="nivel:auxiliar=1"-->
<!--              @change="val => value.nf_niifcreditoprosincontrato_id = val"-->
<!--              @objectReturn="val => value.cuentacredito_sincontrato = val"-->
<!--              :value="value.cuentacredito_sincontrato"-->
<!--              clearable-->
<!--              btnplustruncate-->
<!--              :disabled="!value.id ? false : !isEditing"-->
<!--              @click="openNuevaNiif(4)"-->
<!--            ></postulador>-->
          </v-flex>
          <v-flex xs12 sm4 md4>
            <v-autocomplete
              label="Tipo de Comprobante (Sin Contrato)"
              v-model="value.nf_comdiarioprovisionalsincontrato_id"
              :items="complementos.tipcomdiarios"
              item-value="id"
              item-text="nombre"
              name="tipo de comprobante sin contrato"
              required
              v-validate="'required'"
              :disabled="!value.id ? false : !isEditing"
              :error-messages="errors.collect('tipo de comprobante sin contrato')"
              autocomplete>
              <template slot="item" slot-scope="data">
                <template>
                  <v-list-tile-content>
                    <v-list-tile-title v-html="data.item.nombre"/>
                  </v-list-tile-content>
                </template>
              </template>
            </v-autocomplete>
          </v-flex>
          <v-flex xs12>
            <v-divider></v-divider>
            <v-switch
              :disabled="!value.id ? false : !isEditing"
              v-model="cuentaAnterior"
              :label="`Provisión vigencias anteriores ${cuentaAnterior ? '(A' : '(Desa'}ctivada)`"
            ></v-switch>
          </v-flex>
          <v-expand-transition>
            <template v-if="cuentaAnterior">
              <v-layout row wrap>
                <v-flex xs12 sm6 md6>
                  <postulador-v2
                    no-data="Busqueda por código o nombre."
                    hint="nombre"
                    item-text="codigo"
                    data-title="codigo"
                    data-subtitle="nombre"
                    scope="formTabProvisional"
                    :label="!value.nf_niifdebitoanterior_id ? 'cuenta debito provisional anterior' : 'Cuenta Debito Provisional Anterior'"
                    entidad="niifs"
                    route-params="nivel:auxiliar=1"
                    preicon="business_center"
                    @changeid="val => value.nf_niifdebitoanterior_id = val"
                    v-model="value.debitopro_anterior"
                    v-validate="'required'"
                    name="cuenta debito provisional anterior"
                    :error-messages="errors.collect('cuenta debito provisional anterior')"
                    :disabled="!value.id ? false : !isEditing"
                    btnCreateTruncate
                    @create="openNuevaNiif(5)"
                    :min-characters-search="2"
                    clearable/>
                </v-flex>
                <v-flex xs12 sm6 md6>
                  <postulador-v2
                    no-data="Busqueda por código o nombre."
                    hint="nombre"
                    item-text="codigo"
                    data-title="codigo"
                    data-subtitle="nombre"
                    scope="formTabProvisional"
                    :label="!value.nf_niifcreditoanterior_id ? 'cuenta crédito provisional anterior' : 'Cuenta Crédito Provisional Anterior'"
                    entidad="niifs"
                    route-params="nivel:auxiliar=1"
                    preicon="business_center"
                    @changeid="val => value.nf_niifcreditoanterior_id = val"
                    v-model="value.creditopro_anterior"
                    v-validate="'required'"
                    name="cuenta crédito provisional anterior"
                    :error-messages="errors.collect('cuenta crédito provisional anterior')"
                    :disabled="!value.id ? false : !isEditing"
                    btnCreateTruncate
                    @create="openNuevaNiif(6)"
                    :min-characters-search="2"
                    clearable/>
                </v-flex>
              </v-layout>
            </template>
          </v-expand-transition>
        </v-layout>
      </v-container>
    </v-card>
  </v-form>
</template>

<script>
  import store from '@/store/complementos/index'
  export default {
    name: 'FormProvisionarips',
    props: ['value', 'isEditing'],
    inject: {
      $validator: '$validator'
    },
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      Postulador: () => import('@/components/general/Postulador'),
      RegistroNiif: () => import('@/components/administrativo/niif/puc/RegistroNiif')
    },
    data () {
      return {
        cuentaAnterior: false,
        niifNueva: null,
        menuDate: false,
        dialogFormNiif: null,
        cuentaNiff: null,
        item: null
      }
    },
    created () {
      this.item = this.value
    },
    watch: {
      'errors.items' (val) {
        this.$emit('errors', !(val.filter(item => item.scope === 'formTabProvisional').length > 0))
      },
      'item' (val) {
        if ((val.nf_niifcreditoanterior_id !== null) || (val.nf_niifdebitoanterior_id !== null)) this.cuentaAnterior = true
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosEmpresa
      }
    },
    methods: {
      goNiif (val) {
        console.log('GoVal', val)
        console.log('Niif', this.niifNueva)
        if (this.niifNueva === 1) {
          this.value.cuenta_debitoprovisional = val
        } else if (this.niifNueva === 2) {
          this.value.cuenta_creditoprovisional = val
        } else if (this.niifNueva === 3) {
          this.value.cuentadebito_sincontrato = val
        } else if (this.niifNueva === 4) {
          this.value.cuentacredito_sincontrato = val
        } else if (this.niifNueva === 5) {
          this.value.debitopro_anterior = val
        } else if (this.niifNueva === 6) {
          this.value.creditopro_anterior = val
        }
        this.dialogFormNiif = false
      },
      openNuevaNiif (val) {
        this.niifNueva = val
        this.dialogFormNiif = true
      },
      validate () {
        return this.$validator.validateAll('formTabProvisional').then(result => { return result })
      }
    }
  }
</script>

<style scoped>

</style>
