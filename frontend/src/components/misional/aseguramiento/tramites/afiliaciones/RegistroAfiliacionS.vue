<template>
  <v-stepper v-if="value" v-model="stepActual" vertical>
    <loading v-model="loadingSubmit" />
    <v-stepper-step :editable="isEditable" :complete="stepActual > 1" step="1" :rules="[() => validStep.One]">
      Datos básicos
      <small v-if="!validStep.One" >Hay datos por diligenciar.</small>
      <small v-else >Trámite y afiliado</small>
    </v-stepper-step>
    <v-stepper-content :step="1">
      <v-card color="grey lighten-1">
        <v-container fluid grid-list-xl>
          <v-form data-vv-scope="formStep1">
            <v-layout row wrap>
              <v-flex xs12>
                <postulador-v2
                  no-data="Busqueda por nombre o número de documento."
                  hint="identificacion_completa"
                  item-text="nombre_completo"
                  data-title="identificacion_completa"
                  data-subtitle="nombre_completo"
                  label="Afiliado"
                  :detail="detalleAfiliado"
                  entidad="afiliados"
                  preicon="person"
                  @changeid="val => value.as_afiliado_id = val"
                  v-model="value.afiliado"
                  name="Afiliado"
                  no-btn-edit
                  :rules="'required|afiliadoValidoAfiliacionS:' + value.recien_nacido"
                  v-validate="'required|afiliadoValidoAfiliacionS:' + value.recien_nacido"
                  :error-messages="errors.collect('Afiliado')"
                  :slot-append='{
                      template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,
                      props: [`value`]
                     }'
                  :slot-prepend='value.afiliado ? {
                                template:`<mini-card-detail :data="value.mini_afiliado" alone-icon/>`,
                                props: [`value`]
                               } : null'
                />
              </v-flex>
              <v-flex xs12 sm6>
               
                <v-select
                      label="Régimen"
                      v-model="value.as_regimene_id"
                      :items="complementos.regimenes"
                      item-value="id"
                      item-text="nombre"
                      name="régimen"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('régimen')"
                    ></v-select>
                
              </v-flex>
              <v-flex xs12 sm6>
                <v-text-field
                  label="Tipo afiliado"
                  :value="complementos.tipafiliados && value.as_tipafiliado_id && complementos.tipafiliados.find(x => x.id === value.as_tipafiliado_id).nombre"
                  disabled
                />
              </v-flex>
              <v-flex xs12 sm6>
                <v-autocomplete
                  label="Municipio afiliación"
                  v-model="value.gn_municipio_id"
                  :items="complementos.municipios"
                  item-value="id"
                  item-text="nombre"
                  name="Municipio afiliación"
                  required
                  v-validate="'required'"
                  :error-messages="errors.collect('Municipio afiliación')"
                  :filter="filterMunicipios"
                  persistent-hint
                  :hint="value.gn_municipio_id && complementos.municipios ? complementos.municipios.find(x => x.id === value.gn_municipio_id).nombre_departamento : ''"
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
              <v-flex xs12 sm6>
                <postulador-v2
                  :disabled="!value.gn_municipio_id"
                  no-data="Busqueda por NIT, razon social u código de habilitación."
                  hint="identificacion"
                  item-text="nombre_razon_social"
                  data-title="identificacion"
                  data-subtitle="nombre_razon_social"
                  label="IPS"
                  entidad="entidades_contrato"
                  preicon="location_city"
                  @changeid="val => value.rs_ips_id = val"
                  v-model="ips"
                  name="ips"
                  rules="required"
                  v-validate="'required'"
                  :error-messages="errors.collect('ips')"
                  :route-params="`rs_tipentidade_id=1&afiliado=${value.as_afiliado_id}&municipio=${value.gn_municipio_id}`"
                  no-btn-create
                  :min-characters-search="3"
                />
              </v-flex>
              
              <v-flex xs12 sm12 md6 v-show="value.as_regimene_id === 1">
	              <v-autocomplete
	                label="Tipo cotizante"
	                v-model="value.as_clasecotizante_id"
	                :items="complementos.clasecotizantes"
	                item-value="id"
	                item-text="descripcion"
	                name="Tipo cotizante"
	                required
	                v-validate="'required'"
	                :error-messages="errors.collect('Tipo cotizante')"
	                :disabled="value.as_regimene_id !== 1"
	                clearable
	              >
	                <template slot="item" slot-scope="data">
	                  <template>
	                    <v-list-tile-content class="pl-1 pt-0">
	                      <v-list-tile-title v-html="data.item.descripcion"/>
	                    </v-list-tile-content>
	                  </template>
	                </template>
	              </v-autocomplete>
	            </v-flex>
	            <v-flex xs12 sm12 md6 v-show="value.as_regimene_id === 1">
	              <v-autocomplete
	                label="Actividad económica"
	                v-model="value.nf_ciiu_id"
	                :items="complementos.ciius"
	                item-value="id"
	                item-text="nombre"
	                name="Actividad económica"
	                required
	                v-validate="'required'"
	                :error-messages="errors.collect('Actividad económica')"
	                :disabled="value.as_regimene_id !== 1"
	                :filter="filterCiius"
	              >
	                <template slot="item" slot-scope="data">
	                  <template>
	                    <v-list-tile-content class="pl-1 pt-0">
	                      <v-list-tile-title v-html="data.item.codigo"/>
	                      <v-list-tile-sub-title v-html="data.item.nombre"/>
	                    </v-list-tile-content>
	                  </template>
	                </template>
	              </v-autocomplete>
	            </v-flex>
              
            </v-layout>
            	<v-toolbar dense v-show="value.as_regimene_id === 1">
                  <v-icon>supervisor_account</v-icon>
                  <v-toolbar-title v-html="'Relación con el aportante'" />
                </v-toolbar>
                <v-layout rpw wrap v-show="value.as_regimene_id === 1">
                  <v-flex xs12 sm12 md6>
                    <v-autocomplete
                      prepend-icon="location_city"
                      label="ARL"
                      v-model="value.afiliado_pagador.as_arl_id"
                      :items="complementos.arls"
                      item-value="id"
                      item-text="nombre"
                      name="ARL"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('ARL')"
                      :disabled="value.as_regimene_id !== 1"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <input-date
                      v-model="value.afiliado_pagador.fecha_vinculacion"
                      label="Fecha vinvulación (Año-Mes-Día)"
                      format="YYYY-MM-DD"
                      name="Fecha vinvulación"
                      v-validate="'required|fechaValida'"
                      :error-messages="errors.collect('Fecha vinvulación')"
                      :disabled="value.as_regimene_id !== 1"
                    ></input-date>
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <input-number
                      label="IBC"
                      icon="attach_money"
                      :precision="0"
                      v-model="value.afiliado_pagador.ibc"
                      name="IBC"
                      v-validate="'required'"
                      :error-messages="errors.collect('IBC')"
                      :disabled="value.as_regimene_id !== 1"
                    />
                  </v-flex>
                  <v-flex xs12>
                    <postulador-v2
                      no-data="Busqueda por nombre o número de documento."
                      hint="identificacion"
                      item-text="razon_social"
                      data-title="identificacion"
                      data-subtitle="razon_social"
                      label="Aportante"
                      :detail="detallePagador"
                      entidad="pagadores"
                      preicon="person"
                      @changeid="val => value.afiliado_pagador.as_pagadore_id = val"
                      v-model="value.afiliado_pagador.pagador"
                      name="Aportante"
                      rules="required"
                      v-validate="'required'"
                      :error-messages="errors.collect('Aportante')"
                      :disabled="value.as_regimene_id !== 1"
                    />
                  </v-flex>
                </v-layout>
          </v-form>
        </v-container>
      </v-card>
      <v-btn color="primary" @click.native="next">Siguiente</v-btn>
    </v-stepper-content>
    
      <div id="form-beneficiario"></div>
    <v-stepper-step :editable="isEditable" :complete="stepActual > 2" step="2" :rules="[() => validStep.Two]">
      Beneficiarios
      <small v-if="!validStep.Two">Hay datos por diligenciar.</small>
      <small v-else>Datos de los nucleo_familiar</small>
    </v-stepper-step>
    <v-stepper-content :step="2">
      <registro-beneficiarios root-path="formafiliaciones" v-model="value"/>
      <v-btn flat @click.native="stepActual--">Anterior</v-btn>
      <v-btn color="primary" @click.native="next">Siguiente</v-btn>
    </v-stepper-content>
    <div id="form-anexo"></div>
    <v-stepper-step :editable="isEditable" :complete="stepActual > 3" step="3" :rules="[() => validStep.Three]">
      Declaraciones y autorizaciones
      <small v-if="!validStep.Three">Hay datos por diligenciar.</small>
      <small v-else>Lista de chequeo</small>
    </v-stepper-step>
    <v-stepper-content :step="3">
      <registro-decautramites :ownerid="value.id" v-model="decauttramites" root-path="formafiliaciones"/>
      <v-btn flat @click.native="stepActual--">Anterior</v-btn>
      <v-btn color="primary" @click.native="next">Siguiente</v-btn>
    </v-stepper-content>
    <v-stepper-step :editable="isEditable" :complete="stepActual > 4" step="4" :rules="[() => validStep.Four]">
      Anexos
      <small v-if="!validStep.Four">Hay datos por diligenciar.</small>
      <small v-else>Lista de chequeo</small>
    </v-stepper-step>
    <v-stepper-content :step="4">
      <registro-anetramites v-model="value" root-path="formafiliaciones"/>
      <v-btn flat @click.native="stepActual--">Anterior</v-btn>
      <v-btn color="primary" @click.native="goFinalizar">Finalizar</v-btn>
    </v-stepper-content>
  </v-stepper>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import FormInfoBasicaAfiliado from '@/components/misional/aseguramiento/tramites/FormInfoBasicaAfiliado'
  import store from '@/store/complementos/index'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {AFITRAMITE_RELOAD_ITEM} from '@/store/modules/general/tables'
  import { Validator } from 'vee-validate'
  export default {
    name: 'RegistroAfiliacionSubsidiado',
    props: ['afitramite', 'parametros'],
    components: {
      Loading,
      FormInfoBasicaAfiliado,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      RegistroDecautramites: () => import('@/components/misional/aseguramiento/tramites/RegistroDecautramites'),
      RegistroAnetramites: () => import('@/components/misional/aseguramiento/tramites/RegistroAnetramites'),
      RegistroBeneficiarios: () => import('@/components/misional/aseguramiento/tramites/RegistroBeneficiarios')
    },
    data () {
      return {
        detallePagador: () => import('@/components/misional/aseguramiento/pagadores/DetallePagador'),
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
        filterMunicipios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        afiliadoId: null,
        value: null,
        traslado: null,
        ips: {
          nombre_razon_social: null,
          identificacion: null
        },
        afiliado_pagador: {
          gn_municipio_id: null,
          arl: null,
          as_afiliado_id: null,
          as_arl_id: null,
          as_formtrasmov_id: null,
          as_pagadore_id: null,
          fecha_vinculacion: null,
          ibc: null,
          id: null,
          pagador: null
        },
        validStep: {
          One: true,
          Two: true,
          Three: true,
          Four: true
        },
        loadingSubmit: false,
        stepActual: 1,
        filterCiius (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codigo + ' ' + item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        decauttramites: []
      }
    },
    watch: {
      'afitramite' (val) {
        this.value = val
      },
      'value.afiliado' (val) {
        if (val) {
          this.value.ficha_sisben = val.ficha_sisben
          this.value.puntaje_sisben = val.puntaje_sisben
          this.value.afiliado.as_regimen_id = this.value.as_regimen_id
          this.value.afiliado.as_tipafiliado_id = this.value.as_tipafiliado_id
        }
        if (val === null) {
          this.value.as_afiliado_id = null
        }
      },
      'value.nucleo_familiar.length' (val) {
        if (val > -1 && this.value.id > 0) {
          this.$store.commit(AFITRAMITE_RELOAD_ITEM,
            {
              item:
              {
                afiliado: this.value.afiliado.nombre_completo,
                beneficiarios: this.value.nucleo_familiar.length,
                estado: this.value.estado,
                id: this.value.id,
                identificacion: this.value.afiliado.identificacion_completa,
                recien_nacido: this.value.recien_nacido
              },
              estado: 'editar',
              key: null
            })
        }
      }
    },
    beforeMount () {
      Validator.extend('afiliadoValidoAfiliacionS', {
        validate: (value, prop) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = (this.value.as_afiliado_id)
              ? (this.value.afiliado &&
                ((this.value.afiliado.estado === 1 && this.value.afiliado.id !== this.value.idAfiliadoValido) ||
                  (this.value.afiliado.estado !== 1 && this.value.afiliado.id === this.value.idAfiliadoValido)))
                ? {valido: true, mensaje: null}
                : {valido: false, mensaje: `El afiliado seleccionado no es valido para este trámite, su estado debe ser: ${this.complementos.estadosAfiliado.find(x => x.id === 1).nombre}.`}
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
    mounted () {
      this.value = this.afitramite
    },
    computed: {
      complementos () {
        return store.getters.complementosFormAfiliacion
      },
      isEditable () {
        return this.value && this.value.id !== null
      }
    },
    methods: {
      async goFinalizar () {
        if (await this.$validator.validateAll('formStep1')) {
          let valueCopy = await this.valueClean()
          this.$emit('endsubmit', valueCopy)
        } else {
          this.stepActual = '1'
          this.next()
        }
      },
      resetAF () {
        // this.value.afiliado_pagador.as_afiliado_id = this.value.as_afiliado_id
        // this.value.afiliado_pagador.arl = null
        // this.value.afiliado_pagador.as_arl_id = null
        // this.value.afiliado_pagador.as_pagadore_id = null
        // this.value.afiliado_pagador.fecha_vinculacion = null
        // this.value.afiliado_pagador.ibc = null
        // this.value.afiliado_pagador.pagador = null
        // this.$validator.reset()
        return this.value.afiliado_pagador
      },
      valueClean () {
        let valueCopy = JSON.parse(JSON.stringify(this.value))
        delete valueCopy.afiliado
        delete valueCopy.anexos
        delete valueCopy.ips
        delete valueCopy.declaraciones
        delete valueCopy.nucleo_familiar
        delete valueCopy.pagador
        delete valueCopy.clase_cotizante
        delete valueCopy.fecha_radicacion_array
        return valueCopy
      },
      async submitAfitramite () {
        this.loadingSubmit = true
        const typeRequest = this.value.id ? 'editar' : 'crear'
        let valueCopy = await this.valueClean()
        return new Promise((resolve, reject) => {
          let request = typeRequest === 'editar' ? this.axios.put(`formafiliaciones/${valueCopy.id}`, valueCopy) : this.axios.post('formafiliaciones', valueCopy)
          request
            .then((response) => {
              // console.log('aFILIADO PAGADOR: ', this.resetAF())
              var nfciiuid = this.value.nf_ciiu_id
              if (response.data.formafiliacion.afiliado_pagador === null || response.data.formafiliacion.afiliado_pagador === undefined) response.data.formafiliacion.afiliado_pagador = JSON.parse(JSON.stringify(this.resetAF()))
              // console.log(response.data.formafiliacion.afiliado_pagador)
              response.data.formafiliacion.idAfiliadoValido = response.data.formafiliacion.as_afiliado_id
              this.value = response.data.formafiliacion
              this.value.nf_ciiu_id = nfciiuid
              // this.value.afiliado_pagador = this.resetAF()
              this.$emit('change', this.value)
              this.$store.commit(AFITRAMITE_RELOAD_ITEM, {item: response.data.formafiliacion_o, estado: typeRequest, key: this.parametros.key})
              resolve(true)
            }).catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro del trámite de afiliación. ' + e, color: 'danger'})
              this.loadingSubmit = false
              reject(e)
            })
        })
      },
      async next () {
        this.stepActual = parseInt(this.stepActual)
        this.loadingSubmit = true
        switch (this.stepActual) {
          case 1: {
            if (await this.$validator.validateAll('formStep1')) {
              this.validStep.One = true
              if (await this.submitAfitramite()) {
                this.stepActual++
                this.loadingSubmit = false
              }
            } else {
              this.validStep.One = false
              this.loadingSubmit = false
            }
            break
          }
          case 2: {
            this.stepActual++
            this.loadingSubmit = false
            break
          }
          case 3: {
            this.stepActual++
            this.loadingSubmit = false
            break
          }
        }
      }
    }
  }
</script>

<style scoped>
</style>
