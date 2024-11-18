<template>
  <v-card>
    <v-toolbar dense>
      <v-icon>{{!pagador.id ? 'person_add' : 'person'}}</v-icon>
      <v-toolbar-title>{{!pagador.id ? 'Nuevo aportante' : 'Edición de aportante' }}</v-toolbar-title>
      <v-spacer/>
      <v-chip label color="white" text-color="red" dark absolute right top v-if="parametros && parametros.entidad && parametros.entidad.identificacion">
        <span class="subheading">Identificación: </span>&nbsp;
        <span class="subheading" v-text="parametros.entidad.identificacion"></span>
      </v-chip>
    </v-toolbar>
    <v-stepper v-model="stepActual">
      <v-stepper-header>
        <v-stepper-step :complete="stepActual > 1" step="1" editable :rules="[() => validStepBasicos]">Datos básicos <small v-if="!validStepBasicos">Hay datos por diligenciar.</small></v-stepper-step>
        <v-divider/>
        <v-stepper-step :complete="stepActual > 2" step="2" editable :rules="[() => validStepComplementarios]">Datos complementarios <small v-if="!validStepComplementarios">Hay datos por diligenciar.</small></v-stepper-step>
        <v-divider/>
        <v-stepper-step :complete="stepActual > 3" step="3" editable :rules="[() => (validStepFiscal && validFormPagador)]">Información adicional <small v-if="!(validStepFiscal && validFormPagador)">Hay datos por diligenciar.</small></v-stepper-step>
      </v-stepper-header>
      <v-stepper-items>
        <v-stepper-content step="1">
          <v-card class="mb-1">
            <v-container
              fluid
              grid-list-lg
            >
              <form-basicos ref="fBasicos" :verified="estadoTercero" @errors="val => validStepBasicos = val" @verified="val => estadoTercero = val" @update="val => complementar(val)"/>
            </v-container>
          </v-card>
        </v-stepper-content>
        <v-stepper-content step="2">
          <v-card class="mb-1">
            <v-container
              fluid
              grid-list-lg
            >
              <form-complementarios ref="fComplementarios" :verified="estadoTercero" @errors="val => validStepComplementarios = val"/>
            </v-container>
          </v-card>
        </v-stepper-content>
        <v-stepper-content step="3">
          <v-card class="mb-1">
            <v-container
              fluid
              grid-list-lg
            >
              <form-fiscal ref="fFiscal" manager="Pagador" :verified="estadoTercero" @errors="val => validStepFiscal = val"/>
              <v-form data-vv-scope="formpagadores">
                <v-layout row wrap>
                  <v-flex xs12 sm6 md6>
                    <v-select
                      label="Tipo aportante"
                      v-model="pagador.as_tipaportante_id"
                      :items="complementos.tipaportantes"
                      item-value="id"
                      item-text="nombre"
                      name="Tipo aportante"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Tipo aportante')"
                      :disabled="!estadoTercero"
                    ></v-select>
                  </v-flex>
                </v-layout>
              </v-form>
            </v-container>
          </v-card>
        </v-stepper-content>
        <v-layout row wrap>
          <v-flex xs12 sm4 class="text-xs-center text-sm-left">
            <v-tooltip v-if="stepActual > 1" top>
              <v-btn slot="activator" fab small color="primary" @click.native="stepActual--">
                <v-icon>arrow_back_ios</v-icon>
              </v-btn>
              <span>Anterior</span>
            </v-tooltip>
            <v-tooltip v-if="stepActual <  3" top>
              <v-btn slot="activator" fab small color="primary" @click.native="stepActual++">
                <v-icon>arrow_forward_ios</v-icon>
              </v-btn>
              <span>Siguiente</span>
            </v-tooltip>
          </v-flex>
        </v-layout>
      </v-stepper-items>
    </v-stepper>
    <v-layout row wrap>
      <v-flex xs6 class="text-xs-left">
        <v-btn @click="refresh(null)" :loading="loadingSubmit">Limpiar</v-btn>
      </v-flex>
      <v-flex xs6 class="text-xs-right">
        <v-btn @click="submit" color="primary" :loading="loadingSubmit">Guardar</v-btn>
      </v-flex>
    </v-layout>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import formBasicos from '@/components/administrativo/niif/terceros/FormBasicos'
  import FormComplementarios from '@/components/administrativo/niif/terceros/FormComplementarios'
  import FormFiscal from '@/components/administrativo/niif/terceros/FormFiscal'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {PAGADOR_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'RegistroPagador',
    props: ['parametros'],
    components: {
      FormComplementarios,
      formBasicos,
      FormFiscal
    },
    inject: {
      $validator: '$validator'
    },
    data () {
      return {
        validStepBasicos: true,
        validStepComplementarios: true,
        validStepFiscal: true,
        validFormPagador: true,
        estadoTercero: false,
        tercero: {},
        pagador: {},
        loadingSubmit: false,
        makepagador: {
          id: null,
          identificacion: null,
          razon_social: null,
          gn_tercero_id: null,
          as_tipaportante_id: null,
          tercero: null
        },
        stepActual: 1
      }
    },
    watch: {
      'estadoTercero' (val) {
        if (val && this.pagador.id === null && this.tercero.id !== null) {
          this.getRegistro(this.tercero.id)
        }
      },
      'errors.items' (val) {
        this.validFormPagador = !(val.filter(item => item.scope === 'formpagadores').length > 0)
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosFormPagadores
      }
    },
    mounted () {
      this.refresh()
      if (this.parametros.entidad !== null) {
        if (this.parametros.entidad.gn_tercero_id !== null && this.parametros.entidad.gn_tercero_id !== '') {
          this.getRegistro(this.parametros.entidad.gn_tercero_id)
        } else {
          this.pagador.id = this.parametros.entidad.id
          this.pagador.tercero.gn_tipdocidentidad_id = 12
          if (this.parametros.entidad.identificacion !== null && this.parametros.entidad.identificacion !== '') {
            this.pagador.identificacion = this.parametros.entidad.identificacion
            this.pagador.razon_social = this.parametros.entidad.nombre_razon_social
            let entidadTemporal = JSON.parse(JSON.stringify(this.parametros.entidad))
            this.pagador.tercero.identificacion = entidadTemporal.identificacion
            this.pagador.tercero.razon_social = entidadTemporal.nombre_razon_social
          } else {
            this.pagador.identificacion = null
            this.pagador.razon_social = null
          }
        }
      }
    },
    methods: {
      getRegistro (id) {
        this.axios.get('pagadores/tercero/' + id)
          .then(response => {
            if (response.data !== '') {
              this.pagador = response.data.data
              this.$refs.fBasicos.assign(this.pagador.tercero)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el tercero desde pagador. ', error: e})
          })
      },
      complementar (val) {
        this.tercero = val
        this.$refs.fComplementarios.assign(this.tercero)
        this.$refs.fFiscal.assign(this.tercero)
      },
      refresh () {
        this.stepActual = 1
        this.$validator.reset()
        this.pagador = JSON.parse(JSON.stringify(this.makepagador))
        this.pagador.tercero = JSON.parse(JSON.stringify(this.$store.getters.newTercero))
        this.$refs.fBasicos.assign(this.pagador.tercero)
      },
      async submit () {
        if (await this.$refs.fBasicos.validate()) {
          if (await this.$refs.fComplementarios.validate()) {
            if (await this.$refs.fFiscal.validate()) {
              this.$validator.validateAll('formpagadores').then(result => {
                if (result) {
                  this.loadingSubmit = true
                  if (!this.tercero.tipo_tercero.find(x => x === 'Pagador')) this.tercero.tipo_tercero.push('Pagador')
                  this.pagador.tercero = this.tercero
                  this.pagador.gn_tercero_id = this.tercero.id
                  if (!this.pagador.identificacion) this.pagador.identificacion = this.pagador.tercero.identificacion
                  this.pagador.razon_social = this.pagador.tercero.razon_social
                  const typeRequest = this.pagador.id ? 'editar' : 'crear'
                  this.axios.post('pagadores', this.pagador)
                    .then((response) => {
                      this.$store.commit(SNACK_SHOW, {msg: response.data.message, color: 'success'})
                      this.$store.commit(PAGADOR_RELOAD_ITEM, {item: response.data.pagador, estado: typeRequest, key: this.parametros.key})
                      this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                    }).catch(e => {
                      this.loadingSubmit = false
                      this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro del aportante. ', error: e})
                    })
                } else {
                  this.stepActual = 3
                }
              })
            } else {
              this.stepActual = 3
            }
          } else {
            this.stepActual = 2
          }
        } else {
          this.stepActual = 1
        }
      }

    }
  }
</script>

<style scoped>

</style>
