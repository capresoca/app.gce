<template>
  <div>
    <v-toolbar>
      <v-icon>{{!contratista.id ? 'person_add' : 'person'}}</v-icon>
      <v-toolbar-title>{{!contratista.id ? 'Nuevo contratista' : 'Edición de contratista' }}</v-toolbar-title>
    </v-toolbar>

    <v-stepper v-model="stepActual">
      <v-stepper-header>
        <v-stepper-step :complete="stepActual > 1" step="1" editable :rules="[() => validStepBasicos]">Datos básicos <small v-if="!validStepBasicos">Datos por diligenciar.</small></v-stepper-step>
        <v-divider/>
        <v-stepper-step :complete="stepActual > 2" step="2" editable :rules="[() => validStepComplementarios]">Datos complementarios <small v-if="!validStepComplementarios">Datos por diligenciar.</small></v-stepper-step>
        <v-divider/>
        <v-stepper-step :complete="stepActual > 3" step="3" editable :rules="[() => validStepFiscal]">Información fiscal <small v-if="!validStepFiscal">Datos por diligenciar.</small></v-stepper-step>
        <v-divider/>
        <v-stepper-step :complete="stepActual > 4" step="4" editable :rules="[() => validFormcontratista]">Información comercial <small v-if="!validFormcontratista">Datos por diligenciar.</small></v-stepper-step>
      </v-stepper-header>
      <v-stepper-items>
        <v-stepper-content step="1">
          <v-card class="mb-1">
            <v-container
              fluid
              grid-list-lg
            >
              <form-basicos
                ref="fBasicos"
                :verified="estadoTercero"
                @errors="val => validStepBasicos = val"
                @verified="val => estadoTercero = val"
                @update="val => complementar(val)"
              />
            </v-container>
          </v-card>
        </v-stepper-content>
        <v-stepper-content step="2">
          <v-card class="mb-1">
            <v-container
              fluid
              grid-list-lg
            >
              <form-complementarios
                ref="fComplementarios"
                :verified="estadoTercero"
                @errors="val => validStepComplementarios = val"
              />
            </v-container>
          </v-card>
        </v-stepper-content>
        <v-stepper-content step="3">
          <v-card class="mb-1">
            <v-container
              fluid
              grid-list-lg
            >
              <form-fiscal
                ref="fFiscal"
                manager="Contratista"
                :verified="estadoTercero"
                @errors="val => validStepFiscal = val"
              />
            </v-container>
          </v-card>
        </v-stepper-content>
        <v-stepper-content step="4">
          <v-card class="mb-1">
            <v-container
              fluid
              grid-list-lg
            >
              <v-form data-vv-scope="formcontratista">
                <v-layout row wrap>
                  <v-flex xs12 sm6 md3>
                    <v-text-field
                      label="Cámara de comercio"
                      v-model="contratista.ccomercio"
                      :disabled="!estadoTercero"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-menu
                      ref="fechaCcomercio"
                      :close-on-content-click="false"
                      v-model="menuDate"
                      :nudge-right="40"
                      :return-value.sync="contratista.fecha_ccomercio"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                      :disabled="!estadoTercero"
                    >
                      <v-text-field
                        slot="activator"
                        v-model="contratista.fecha_ccomercio"
                        label="Fecha expedición"
                        clearable
                        readonly
                        :disabled="!estadoTercero"
                      />
                      <v-date-picker
                        :disabled="!estadoTercero"
                        locale="es-co"
                        :max="maxDate"
                        :min="minDate"
                        autosave
                        v-model="contratista.fecha_ccomercio"
                        @input="$refs['fechaCcomercio'].save(contratista.fecha_ccomercio)"
                      />
                    </v-menu>
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-autocomplete
                      label="Municipio expedición"
                      v-model="contratista.gn_munccomercio_id"
                      :items="complementos.municipios"
                      item-value="id"
                      item-text="nombre"
                      :disabled="!estadoTercero"
                      :filter="filterMunicipios"
                      clearable
                    >
                      <template slot="item" slot-scope="data">
                        <template>
                          <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.nombre"/>
                            <v-list-tile-sub-title v-html="data.item.nombre_departamento"/>
                          </v-list-tile-content>
                        </template>
                      </template>
                    </v-autocomplete>
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-select
                      label="Naturaleza jurídica"
                      v-model="contratista.ce_natjuridica_id"
                      :items="complementos.natjuridicas"
                      item-value="id"
                      item-text="nombre"
                      :disabled="!estadoTercero"
                      clearable
                    />
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field
                      label="Representante legal"
                      v-model="contratista.rep_legal"
                      :disabled="!estadoTercero"
                    />
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
            <v-tooltip v-if="stepActual <  4" top>
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
  </div>
</template>

<script>
  import store from '@/store/complementos/index'
  import formBasicos from '@/components/administrativo/niif/terceros/FormBasicos'
  import FormComplementarios from '@/components/administrativo/niif/terceros/FormComplementarios'
  import FormFiscal from '@/components/administrativo/niif/terceros/FormFiscal'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {CONTRATISTA_RELOAD_ITEM} from '@/store/modules/general/tables'
  export default {
    name: 'RegistroContratista',
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
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10),
        menuDate: false,
        validStepBasicos: true,
        validStepComplementarios: true,
        validStepFiscal: true,
        validFormcontratista: true,
        estadoTercero: false,
        tercero: {},
        contratista: {},
        loadingSubmit: false,
        makecontratista: {
          id: null,
          ce_natjuridica_id: null,
          ccomercio: null,
          fecha_ccomercio: null,
          gn_munccomercio_id: null,
          gn_tercero_id: null,
          rep_legal: null,
          tercero: null
        },
        stepActual: 1,
        filterMunicipios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        }
      }
    },
    watch: {
      'estadoTercero' (val) {
        if (val && this.contratista.id === null && this.tercero.id !== null) {
          this.getRegistro(this.tercero.id)
        }
      },
      'errors.items' (val) {
        this.validFormcontratista = !(val.filter(item => item.scope === 'formcontratista').length > 0)
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosRegistroContratista
      }
    },
    mounted () {
      this.refresh()
      if (this.parametros.entidad !== null && this.parametros.entidad.gn_tercero_id !== null) this.getRegistro(this.parametros.entidad.gn_tercero_id)
    },
    methods: {
      getRegistro (id) {
        this.axios.get('ce_contratistas/tercero/' + id)
          .then(response => {
            if (response.data !== '') {
              this.contratista = response.data.data
              this.$refs.fBasicos.assign(this.contratista.tercero)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el tercero desde contratista. ', error: e})
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
        this.contratista = JSON.parse(JSON.stringify(this.makecontratista))
        this.$refs.fBasicos.assign(JSON.parse(JSON.stringify(this.$store.getters.newTercero)))
      },
      async submit () {
        if (await this.$refs.fBasicos.validate()) {
          if (await this.$refs.fComplementarios.validate()) {
            if (await this.$refs.fFiscal.validate()) {
              this.$validator.validateAll('formcontratista').then(result => {
                if (result) {
                  this.loadingSubmit = true
                  if (!this.tercero.tipo_tercero.find(x => x === 'Contratista')) this.tercero.tipo_tercero.push('Contratista')
                  this.contratista.tercero = this.tercero
                  this.contratista.gn_tercero_id = this.tercero.id
                  const typeRequest = this.contratista.id ? 'editar' : 'crear'
                  this.axios.post('ce_contratistas', this.contratista).then(response => {
                    this.$store.commit(SNACK_SHOW, {msg: 'El registro del contratista se ha ' + (typeRequest === 'editar' ? 'editado' : 'creado') + ' satisfactoriamente.', color: 'success'})
                    this.$store.commit(CONTRATISTA_RELOAD_ITEM, {item: response.data.contratista, estado: typeRequest, key: this.parametros.key})
                    this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                  }).catch(e => {
                    this.loadingSubmit = false
                    this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro del contratista. ', error: e})
                  })
                } else {
                  this.stepActual = 4
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
