<template>
  <v-container fluid grid-list-xl class="pa-0">
    <loading v-model="loading" />
    <v-flex v-if="item.tipo_afiliado.nombre === 'Cotizante' || item.tipo_afiliado.nombre === 'Cabeza de familia'" class="text-xs-right pr-0 pt-0 pb-1">
      <v-btn v-if="item" @click="cardDialog = !cardDialog" class="mr-0" small color="primary" dark>Agregar Beneficiario</v-btn>
    </v-flex>
    <v-slide-y-transition>
      <v-card v-show="cardDialog" key="seccion1_03">
        <v-form data-vv-scope="formFamiliar">
          <v-card-title class="grey lighten-3">
            <span class="title" v-text="'Beneficiario'"></span>
          </v-card-title>
          <v-container class="pa-3">
            <v-layout  row wrap key="seccion1_04">
              <v-flex xs12 sm8>
                <postulador-v2
                  no-data="Busqueda por nombre o número de documento."
                  hint="identificacion_completa"
                  item-text="nombre_completo"
                  data-title="identificacion_completa"
                  data-subtitle="nombre_completo"
                  label="Afiliado"
                  entidad="afiliados"
                  ref="buscarAfiliado"
                  preicon="person"
                  v-model="beneficiario"
                  @changeid="val => idBeneficiario = val"
                  name="Afiliado"
                  rules="required|afiliadoPermitido"
                  v-validate="'required|afiliadoPermitido'"
                  :error-messages="errors.collect('Afiliado')"
                  no-btn-edit
                  clearable
                />
              </v-flex>
              <v-flex xs12 sm4>
                <v-select
                  :items="complementosFormBeneficiario.parentescos"
                  v-model="parentesco"
                  @change="val => familiar.as_parentesco_id = val ? val.id : null"
                  item-text="nombre"
                  prepend-icon="supervised_user_circle"
                  label="Parentesco"
                  name="parentesco"
                  key="parentesco"
                  return-object
                  v-validate="'required'"
                  :error-messages="errors.collect('parentesco')">
                </v-select>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>
        <v-card-actions class="grey lighten-4">
          <v-spacer></v-spacer>
          <v-btn @click.prevent="close" flat small color="primary" dark v-text="'Cancelar'"></v-btn>
          <v-btn @click.prevent="agregar()" flat small color="primary" dark v-text="'Registrar'"></v-btn>
        </v-card-actions>
      </v-card>
    </v-slide-y-transition>
    <v-slide-y-transition group>
      <v-layout row wrap v-if="item && nucleo" key="seccion1_1">
        <v-flex xs12 sm12 md12>
          <v-subheader>Miembros</v-subheader>
          <v-expansion-panel focusable class="v-expansion-panel-pi">
            <v-expansion-panel-content
              v-for="(item, i) in nucleo"
              :key="i"
            >
              <v-list-tile slot="header" @click="">
                <v-list-tile-action>
                  <span style="font-size: 25px !important;" v-text="item.afiliado.emoticon"></span>
                </v-list-tile-action>
                <v-list-tile-content class="truncate-content">
                  <v-list-tile-title>{{item.afiliado.nombre_completo}}</v-list-tile-title>
                  <v-list-tile-sub-title
                    v-html="`<v-chip label small class='teal lighten-2 white--text pa-1 caption' style='border-radius: 4px'>${item.tipo}</v-chip>
                  - ${item.parentesco}`"></v-list-tile-sub-title>
                </v-list-tile-content>
                <v-list-tile-action>
                </v-list-tile-action>
                <v-list-tile-action>
                    <v-tooltip top>
                      <v-btn flat slot="activator" icon color="info" @click.stop="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: item.afiliado, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})">
                        <v-icon v-text="'info'"></v-icon>
                      </v-btn>
                        <span>Más información</span>
                    </v-tooltip>
                </v-list-tile-action>
                  <v-list-tile-action v-if="item.afiliado.as_tipafiliado_id === 'xxx'">
                    <v-tooltip top>
                      <v-btn flat icon slot="activator" color="accent" @click.prevent="deleteBeneficiario(item)">
                        <v-icon v-text="'delete'"></v-icon>
                      </v-btn>
                      <span>Eliminar</span>
                    </v-tooltip>
                  </v-list-tile-action>
              </v-list-tile>
              <v-container fluid grid-list-xl>
                <v-layout align-center>
                  <v-flex xs12>
                    <detalle-general-basico :afiliado="item.afiliado" :afiliado-id="item.afiliado.id" />
                  </v-flex>
                </v-layout>
              </v-container>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-flex>
      </v-layout>
      <v-layout v-else key="seccion1_2">
        <v-subheader>No hay miembros en el núcleo familiar </v-subheader>
      </v-layout>
    </v-slide-y-transition>
  </v-container>
</template>

<script>
  import store from '@/store/complementos/index'
  import Loading from '@/components/general/Loading'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import { Validator } from 'vee-validate'
  export default {
    name: 'DetalleGeneralNucleoFamiliar',
    props: {
      afiliado: {
        type: Object,
        default: null
      },
      afiliadoId: {
        type: Number,
        default: 0
      }
    },
    data () {
      return {
        cardDialog: false,
        loading: false,
        item: null,
        nucleo: [],
        familiar: {
          padre: null
        },
        parentesco: null,
        beneficiario: null,
        idBeneficiario: null
      }
    },
    components: {
      Loading,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      DetalleEntidad: () => import('@/components/misional/redservicios/entidades/DetalleEntidad'),
      DetalleGeneralBasico: () => import('@/components/misional/aseguramiento/afiliados/DetalleGeneralBasicos')
    },
    filters: {
      dateDMA (val) {
        if (val) {
          let date = val.split('-')
          return date[2] + '-' + date[1] + '-' + date[0]
        }
        return ''
      }
    },
    created () {
      this.formReset()
      this.afiliado && this.afiliado.id ? this.item = this.afiliado : this.getData()
      Validator.extend('afiliadoPermitido', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = (this.item.id === value.id)
              ? {valido: false, mensaje: 'El afiliado seleccionado debe ser diferente al afiliado Cabeza de familia.'}
              : (this.item.beneficiarios.find(x => x.id === value.id))
                ? {valido: false, mensaje: 'El afiliado seleccionado ya se encuentra en el listado de beneficiarios.'}
                : {valido: true, mensaje: null}
            return resolve({
              valid: response.valido,
              data: {
                message: response.mensaje
              }
            })
          }
        }),
        getMessage: (field, params, data) => data.message
      })
    },
    computed: {
      complementosFormBeneficiario () {
        return store.getters.complementosFormBeneficiario
      }
    },
    watch: {
      'item' (val) {
        this.nucleo = []
        if (val) {
          if (val.cabeza) {
            this.nucleo.push({
              tipo: 'Cabeza de familia',
              parentesco: (val.cabeza.as_parentesco_id === null || val.cabeza.as_parentesco_id === 3) && val.cabeza.as_regimene_id === 1 ? (val.cabeza.gn_sexo_id === 1 ? 'M' : 'P') + 'adre' : '',
              afiliado: val.cabeza
            })
          }
          if (val.beneficiarios.length) {
            val.beneficiarios.forEach(beneficiario => {
              this.nucleo.push({
                tipo: 'Beneficiario',
                parentesco: beneficiario.parentesco ? beneficiario.parentesco.nombre : '',
                afiliado: beneficiario
              })
            })
          }
        }
      },
      'beneficiario' (value) {
        console.log('value 1', value)
        if (value) {
          let oldFamily = value.cabfamilia_id
          // this.familiar = JSON.parse(JSON.stringify(value))
          this.familiar.id = value.id
          this.familiar.cabfamilianterior_id = value.cabfamilia_id ? oldFamily : null
          this.familiar.cabfamilia_id = this.item ? this.item.id : null
          this.familiar.cabeza = this.item
        } else {
          this.familiar.cabfamilianterior_id = null
          this.familiar.cabfamilia_id = null
          this.familiar.cabeza = null
        }
      },
      'cardDialog' (value) {
        if (value === false) {
          this.close()
        }
      }
    },
    methods: {
      formReset () {
        this.parentesco = null
        this.beneficiario = null
        this.idBeneficiario = null
        this.familiar = {
          id: null,
          cabeza: null,
          cabfamilia_id: null,
          cabfamilianterior_id: null,
          as_parentesco_id: null
        }
      },
      close () {
        this.$refs.buscarAfiliado.reset()
        this.$validator.reset()
        this.formReset()
        this.cardDialog = false
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      async agregar () {
        // let errorAfiliado = await this.$refs.buscarAfiliado.validate()
        if (await this.validador('formFamiliar')) {
          this.loading = true
          this.axios.post(`afiliados/beneficiario/${this.familiar.id}`, this.familiar)
            .then((response) => {
              if (response.data !== '') {
                this.$store.commit(SNACK_SHOW, {msg: 'El beneficiario se agrego correctamente. ', color: 'success'})
                console.log('sss', response.data)
                this.nucleo.push({
                  tipo: 'Beneficiario',
                  parentesco: response.data.data.parentesco ? response.data.data.parentesco.nombre : '',
                  afiliado: response.data.data
                })
                // this.getData()
                this.close()
                this.loading = false
              }
            })
            .catch(e => {
              this.loading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar datos del núcleo familiar. ', error: e})
            })
        }
      },
      deleteBeneficiario (value) {
        // let oldFamily = value.cabfamilia_id
        let oldFamilyAnterior = value.afiliado.cabfamilianterior_id
        // this.familiar = JSON.parse(JSON.stringify(value))
        this.familiar.id = value.afiliado.id
        this.familiar.cabfamilia_id = oldFamilyAnterior
        // this.familiar.cabeza = value.afiliado.cabeza
        this.familiar.cabfamilianterior_id = null
        this.nucleo.splice(this.item.beneficiarios.indexOf(value), 1)
        console.log('delet', value)
      },
      getData () {
        this.loading = true
        this.axios.get(`afiliados/${this.afiliadoId}`)
          .then((response) => {
            if (response.data !== '') {
              this.item = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los datos del núcleo familiar. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
