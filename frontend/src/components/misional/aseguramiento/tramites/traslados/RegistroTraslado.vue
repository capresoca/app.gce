<template>
  <v-card>
    <v-toolbar dense>
      <v-icon>file_copy</v-icon>
      <v-toolbar-title v-html="(!traslado.id ? 'Nuevo trámite de ' : 'Edición de trámite de ') + (traslado && traslado.tipo ? traslado.tipo : '...') " />
    </v-toolbar>
    <v-stepper v-model="stepActual" vertical ref="stepLoading">
      <v-stepper-step :editable="isEditable" :complete="stepActual > 1" step="1" :rules="[() => validStep.One]">
        Datos básicos
        <small v-if="!validStep.One" >Hay datos por diligenciar.</small>
        <small v-else >Trámite</small>
      </v-stepper-step>
      <v-stepper-content :step="1">
        <v-card color="grey lighten-1">
          <v-container fluid grid-list-xl>
            <v-form data-vv-scope="formStep1">
              <v-layout row wrap>
                <v-flex xs12 sm4 md2>
                  <v-radio-group v-model="traslado.tipo" label="Trámite" class="ma-0 pa-0">
                    <v-radio
                      label="Traslado"
                      value="Traslado"
                    />
                    <v-radio
                      label="Movilidad a contributivo"
                      value="Movilidad"
                    />
                    <v-radio
                      label="Movilidad a subsidiado"
                      value="Subsidiado"
                    />
                  </v-radio-group>
                </v-flex>
                <v-flex xs12 sm8 md10>
                  <postulador-v2
                    ref="postuladorAfiliadoPrincipal"
                    no-data="Busqueda por nombre o número de documento."
                    hint="identificacion_completa"
                    item-text="nombre_completo"
                    data-title="identificacion_completa"
                    data-subtitle="nombre_completo"
                    label="Afiliado"
                    :detail="detalleAfiliado"
                    entidad="afiliados"
                    preicon="person"
                    v-model="traslado.afiliado"
                    name="Afiliado"
                    :no-btn-create="(traslado && traslado.tipo === 'Movilidad')"
                    :rules="'required|afiliadoValidoTraslado:' + traslado.tipo"
                    v-validate="'required|afiliadoValidoTraslado:' + traslado.tipo"
                    :error-messages="errors.collect('Afiliado')"
                    :slot-append='{
                      template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,
                      props: [`value`]
                     }'
                    :slot-prepend='traslado.afiliado ? {
                                template:`<mini-card-detail :data="value.mini_afiliado" alone-icon/>`,
                                props: [`value`]
                               } : null'
                    no-btn-edit
                  />
                </v-flex>
                <v-flex xs12 sm12 md6>
                  <v-autocomplete
                    label="EPS a solicitar"
                    v-model="traslado.as_eps_id"
                    :items="complementos.epss"
                    item-value="id"
                    item-text="nombre"
                    name="EPS"
                    required
                    v-validate="'required'"
                    :error-messages="errors.collect('EPS')"
                  />
                </v-flex>
                <v-flex xs12 sm12 md6 v-show="traslado.tipo === 'Movilidad'">
                  <v-autocomplete
                    label="Tipo cotizante"
                    v-model="traslado.as_clasecotizante_id"
                    :items="complementos.clasecotizantes"
                    item-value="id"
                    item-text="descripcion"
                    name="Tipo cotizante"
                    required
                    v-validate="'required'"
                    :error-messages="errors.collect('Tipo cotizante')"
                    :disabled="traslado.tipo === 'Traslado'"
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
                <v-flex xs12 sm12 md12 v-show="traslado.tipo === 'Movilidad'">
                  <v-autocomplete
                    label="Actividad económica"
                    v-model="traslado.nf_ciiu_id"
                    :items="complementos.ciius"
                    item-value="id"
                    item-text="nombre"
                    name="Actividad económica"
                    required
                    v-validate="'required'"
                    :error-messages="errors.collect('Actividad económica')"
                    :disabled="traslado.tipo === 'Traslado'"
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

                <v-flex xs12 sm4>
                  <v-select
                    label="Tipo traslado"
                    v-model="traslado.tipo_traslado"
                    v-on:change="valor_traslado = traslado.tipo_traslado"
                    :items="tiposTraslado"
                  />
                </v-flex>

              </v-layout>
            </v-form>
          </v-container>
        </v-card>
        <v-btn color="primary" @click.native="next">Siguiente</v-btn>
      </v-stepper-content>
      <v-stepper-step :editable="isEditable" :complete="stepActual > 2" step="2" :rules="[() => validStep.Two]">
        Actualización afiliado
        <small v-if="!validStep.Two" >Hay datos por diligenciar.</small>
        <small v-else >Datos básicos</small>
      </v-stepper-step>
      <v-stepper-content :step="2">
        <v-card color="grey lighten-1">
          <v-container fluid grid-list-xl>
            <form-info-basica-afiliado v-model="traslado" ref="infobasicoafiliado" :tipos-identidad="complementos.tipdocidentidades"/>
            <v-slide-y-transition>
              <v-form data-vv-scope="formRelacionAportante" v-show="traslado.tipo === 'Movilidad'">
                <v-toolbar dense>
                  <v-icon>supervisor_account</v-icon>
                  <v-toolbar-title v-html="'Relación con el aportante'" />
                </v-toolbar>
                <v-layout rpw wrap>
                  <v-flex xs12 sm12 md6>
                    <v-autocomplete
                      prepend-icon="location_city"
                      label="ARL"
                      v-model="traslado.afiliado_pagador.as_arl_id"
                      :items="complementos.arls"
                      item-value="id"
                      item-text="nombre"
                      name="ARL"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('ARL')"
                      :disabled="traslado.tipo === 'Traslado'"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <input-date
                      v-model="traslado.afiliado_pagador.fecha_vinculacion"
                      label="Fecha vinvulación (Año-Mes-Día)"
                      format="YYYY-MM-DD"
                      name="Fecha vinvulación"
                      v-validate="'required|fechaValida'"
                      :error-messages="errors.collect('Fecha vinvulación')"
                      :disabled="traslado.tipo === 'Traslado'"
                    ></input-date>
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <input-number
                      label="IBC"
                      icon="attach_money"
                      :precision="0"
                      v-model="traslado.afiliado_pagador.ibc"
                      name="IBC"
                      v-validate="'required'"
                      :error-messages="errors.collect('IBC')"
                      :disabled="traslado.tipo === 'Traslado'"
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
                      @changeid="val => traslado.afiliado_pagador.as_pagadore_id = val"
                      v-model="traslado.afiliado_pagador.pagador"
                      name="Aportante"
                      rules="required"
                      v-validate="'required'"
                      :error-messages="errors.collect('Aportante')"
                      :disabled="traslado.tipo === 'Traslado'"
                    />
                  </v-flex>
                </v-layout>
              </v-form>
            </v-slide-y-transition>
          </v-container>
        </v-card>
        <v-btn flat @click.native="stepActual--">Anterior</v-btn>
        <v-btn color="primary" @click.native="next">Siguiente</v-btn>
      </v-stepper-content>
      <div id="form-beneficiario"></div>
      <v-stepper-step :editable="isEditable" :complete="stepActual > 3" step="3" :rules="[() => validStep.Three]">
        Beneficiarios
        <small v-if="!validStep.Three">Hay datos por diligenciar.</small>
        <small v-else>Datos de los beneficiarios</small>
      </v-stepper-step>
      <v-stepper-content :step="3">
        <registro-beneficiarios root-path="formtrasmovs" v-model="traslado"/>
        <v-btn flat @click.native="stepActual--">Anterior</v-btn>
        <v-btn color="primary" @click.native="next">Siguiente</v-btn>
      </v-stepper-content>
      <div id="form-anexo"></div>
      <v-stepper-step :editable="isEditable" :complete="stepActual > 4" step="4" :rules="[() => validStep.Four]">
        Declaraciones y autorizaciones
        <small v-if="!validStep.Four">Hay datos por diligenciar.</small>
        <small v-else>Lista de chequeo</small>
      </v-stepper-step>
      <v-stepper-content :step="4">
        <registro-decautramites :ownerid="traslado && traslado.id ? traslado.id : null" v-model="decauttramites" root-path="formtrasmovs"/>
        <v-btn flat @click.native="stepActual--">Anterior</v-btn>
        <v-btn color="primary" @click.native="next">Siguiente</v-btn>
      </v-stepper-content>
      <v-stepper-step :editable="isEditable" :complete="stepActual > 5" step="5" :rules="[() => validStep.Five]">
        Anexos
        <small v-if="!validStep.Five">Hay datos por diligenciar.</small>
        <small v-else>Lista de chequeo</small>
      </v-stepper-step>
      <v-stepper-content :step="5">
        <registro-anetramites v-model="traslado" root-path="formtrasmovs"/>
        <v-btn flat @click.native="stepActual--">Anterior</v-btn>
        <v-btn color="primary" @click.native="goFinalizar">Finalizar</v-btn>
      </v-stepper-content>
    </v-stepper>
    <v-dialog v-model="dialogFinalizar.show" persistent max-width="400">
      <v-card>
        <v-card-title class="grey lighten-3">
          <span class="title">¿Qué desea hacer?</span>
        </v-card-title>
        <v-card-text>
          <v-container fluid class="pa-0">
            <v-layout row wrap>
              <v-flex xs6>
                <v-layout align-center>
                  <v-checkbox :class="dialogFinalizar.radicar ? 'pb-4' : ''" :label="dialogFinalizar.radicar ? '': 'Radicar'" v-model="dialogFinalizar.radicar" hide-details class="shrink mr-2"/>
                  <input-date
                    key="fechaRadicacionmenu"
                    v-if="dialogFinalizar.radicar"
                    v-model="dialogFinalizar.fechaRadicacion"
                    label="Fecha radicación (Año-Mes-Día)"
                    format="YYYY-MM-DD"
                    :max="maxFecha"
                    name="Fecha radicación"
                    v-validate="'required|date_format:YYYY-MM-DD' + (false ? '' : '|date_between:' + minFecha + ',' + maxFecha + ',true')"
                    :error-messages="errors.collect('Fecha radicación')"
                  ></input-date>
                </v-layout>
              </v-flex>
              <v-flex xs6>
                <v-checkbox v-model="dialogFinalizar.imprimir" label="Imprimir" class="ml-2"/>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-divider/>
        <v-card-actions>
          <v-spacer/>
          <v-btn flat @click="dialogFinalizar.show = false" :loading="loadingEndBtn">Cancelar</v-btn>
          <v-btn color="primary" flat @click="finalizar" :loading="loadingEndBtn">Aceptar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import InputNumber from '@/components/general/InputNumber'
  import InputDate from '@/components/general/InputDate'
  import RegistroDecautramites from '@/components/misional/aseguramiento/tramites/RegistroDecautramites'
  import RegistroAnetramites from '@/components/misional/aseguramiento/tramites/RegistroAnetramites'
  import RegistroBeneficiarios from '@/components/misional/aseguramiento/tramites/RegistroBeneficiarios'
  import FormInfoBasicaAfiliado from '@/components/misional/aseguramiento/tramites/FormInfoBasicaAfiliado'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {TRASTRAMITE_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import { Validator } from 'vee-validate'
  export default {
    name: 'RegistroTraslado',
    props: ['parametros'],
    components: {
      InputDate,
      InputNumber,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      FormInfoBasicaAfiliado,
      RegistroBeneficiarios,
      RegistroDecautramites,
      RegistroAnetramites
    },
    data () {
      return {
        detallePagador: () => import('@/components/misional/aseguramiento/pagadores/DetallePagador'),
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
        idAfiliadoValido: null,
        loadingEndBtn: false,
        dialogFinalizar: {
          show: false,
          imprimir: false,
          radicar: false,
          fechaRadicacion: this.moment().format('YYYY-MM-DD')
        },
        maxFecha: this.moment().format('YYYY-MM-DD'),
        minFecha: '1990-01-01',
        afitramiteGo: null,
        traslado: null,
        valor_traslado: null,
        makeTraslado: {
          id: null,
          consecutivo: null,
          tipo: 'Traslado',
          tipo_traslado: null,
          seleccion_tipo_traslado: null,
          as_afiliado_id: null,
          ips: null,
          afiliado: null,
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
          as_padre_id: null,
          as_parentesco_id: null,
          rs_entidade_id: null,
          as_pagadore_id: null,
          nf_ciiu_id: null,
          as_clasecotizante_id: null,
          as_eps_id: null,
          eps: null,
          gn_tipdocidentidad_id: null,
          identificacion: null,
          nombre1: null,
          nombre2: null,
          fecha_nacimiento: null,
          gn_sexo_id: null,
          apellido1: null,
          apellido2: null,
          fecha_expedicion: null,
          estado: 'Registrado',
          fecha_traslado: this.moment().format('YYYY-MM-DD')
        },
        value: null,
        loader: null,
        validStep: {
          One: true,
          Two: true,
          Three: true,
          Four: true,
          Five: true
        },
        loadingSubmit: false,
        stepActual: 1,
        decauttramites: [],
        filterCiius (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codigo + ' ' + item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        tiposTraslado: [0, 1, 2, 3, 4, 5]
      }
    },
    watch: {
      'traslado.afiliado.id' (val) {
        if (val) {
          if (this.traslado.nf_ciiu_id === null) this.traslado.nf_ciiu_id = this.traslado.afiliado.nf_ciiu_id
          if (this.traslado.as_clasecotizante_id === null) this.traslado.as_clasecotizante_id = this.traslado.afiliado.as_clasecotizante_id
          this.traslado.as_afiliado_id = this.traslado.afiliado.id
          this.traslado.afiliado_pagador.as_afiliado_id = this.traslado.afiliado.id
          this.traslado.gn_tipdocidentidad_id = this.traslado.afiliado.gn_tipdocidentidad_id
          this.traslado.identificacion = this.traslado.afiliado.identificacion
          this.traslado.nombre1 = this.traslado.afiliado.nombre1
          this.traslado.nombre2 = this.traslado.afiliado.nombre2
          this.traslado.fecha_nacimiento = this.traslado.afiliado.fecha_nacimiento
          this.traslado.gn_sexo_id = this.traslado.afiliado.gn_sexo_id
          this.traslado.rs_entidade_id = this.traslado.afiliado.rs_entidade_id
          this.traslado.gn_municipio_id = this.traslado.afiliado.gn_municipio_id
          this.traslado.apellido1 = this.traslado.afiliado.apellido1
          this.traslado.apellido2 = this.traslado.afiliado.apellido2
          this.traslado.fecha_expedicion = this.traslado.afiliado.fecha_expedicion
        }
      },
      'traslado.tipo' (val) {
        if (this.$refs.postuladorAfiliadoPrincipal) this.$refs.postuladorAfiliadoPrincipal.focus()
        val === 'Traslado' && this.resetAF()
      }
    },
    created () {
      this.refresh()
    },
    beforeMount () {
      Validator.extend('afiliadoValidoTraslado', {
        validate: (value, prop) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = (this.traslado.as_afiliado_id)
              ? (this.traslado.afiliado &&
                (
                  this.traslado.tipo === 'Traslado'
                    ? (((this.traslado.afiliado.estado === 1) || (this.traslado.afiliado.estado === 2) || (this.traslado.afiliado.estado === 4)) || ((this.traslado.afiliado.estado !== 3) && this.traslado.afiliado.id === this.idAfiliadoValido))
                    : (this.traslado.tipo && ((this.traslado.afiliado.estado === 3)))
                )
              )
                ? {valido: true, mensaje: null}
                : {valido: false, mensaje: `El afiliado seleccionado no es valido para este trámite, su estado debe ser: ${this.complementos.estadosAfiliado.find(x => x.id === 1).nombre}, ${this.complementos.estadosAfiliado.find(x => x.id === 2).nombre}, ${this.complementos.estadosAfiliado.find(x => x.id === 4).nombre} o ${this.complementos.estadosAfiliado.find(x => x.id === 3).nombre} si el trámite esta marcado como movilidad a contributivo.`}
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
      if (this.parametros.entidad !== null && this.parametros.entidad.id !== null) this.getRegistro(this.parametros.entidad.id)
    },
    computed: {
      complementos () {
        return store.getters.complementosFormTraslado
      },
      isEditable () {
        return this.traslado && this.traslado.id !== null
      }
    },
    methods: {
      resetAF () {
        this.traslado.afiliado_pagador.as_afiliado_id = this.traslado.as_afiliado_id
        this.traslado.afiliado_pagador.arl = null
        this.traslado.afiliado_pagador.as_arl_id = null
        this.traslado.afiliado_pagador.as_pagadore_id = null
        this.traslado.afiliado_pagador.fecha_vinculacion = null
        this.traslado.afiliado_pagador.ibc = null
        this.traslado.afiliado_pagador.pagador = null
        this.$validator.reset()
        return this.traslado.afiliado_pagador
      },
      refresh () {
        this.afitramiteGo = null
        var tipoTraslado = null
        if (this.traslado !== null) {
          tipoTraslado = this.traslado.tipo_traslado
        }
        this.traslado = JSON.parse(JSON.stringify(this.makeTraslado))
        this.traslado.tipo_traslado = tipoTraslado
        this.$validator.reset()
        // this.traslado.tipo_traslado = this.valor_traslado
      },
      getRegistro (id) {
        this.axios.get('formtrasmov/' + id)
          .then(response => {
            if (response.data !== '') {
              if (response.data.data.afiliado_pagador === null) response.data.data.afiliado_pagador = JSON.parse(JSON.stringify(this.resetAF()))
              this.idAfiliadoValido = response.data.data.as_afiliado_id
              delete response.data.data.fecha_nacimiento_slash
              this.traslado = response.data.data
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el trámite de traslado. ', error: e})
          })
      },
      async goFinalizar () {
        if (await this.$validator.validateAll('formStep1')) {
          if (await this.$refs['infobasicoafiliado'].validate()) {
            let valueCopy = await this.valueClean()
            this.afitramiteGo = valueCopy
            this.dialogFinalizar.show = true
          } else {
            this.stepActual = '2'
            this.next()
          }
        } else {
          this.stepActual = '1'
          this.next()
        }
      },
      async finalizar () {
        this.loadingEndBtn = true
        if (this.dialogFinalizar.radicar) {
          this.afitramiteGo.estado = 'Radicado'
          this.afitramiteGo.fecha_radicacion = this.dialogFinalizar.fechaRadicacion
        }
        if (await this.$validator.validateAll()) {
          if (await this.submitTramite(this.afitramiteGo)) {
            this.$store.commit(SNACK_SHOW, {msg: 'El trámite de traslado se ha ' + (this.afitramiteGo.estado === 'Radicado' ? 'radicado' : 'guardado') + ' satisfactoriamente.', color: 'success'})
          }
          this.dialogFinalizar.show = false
          this.loadingEndBtn = false
          this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          if (this.dialogFinalizar.imprimir) this.filePrint(`formulario-tramite-traslado&id=${this.traslado.id}`)
        } else {
          this.loadingEndBtn = false
        }
      },
      valueClean () {
        // this.traslado.tipo_traslado = this.valor_traslado
        let valueCopy = JSON.parse(JSON.stringify(this.traslado))
        // console.log(this.traslado)
        delete valueCopy.afiliado
        delete valueCopy.afiliado_pagador.arl
        delete valueCopy.afiliado_pagador.pagador
        delete valueCopy.anexos
        delete valueCopy.beneficiarios
        delete valueCopy.declaraciones
        delete valueCopy.eps
        delete valueCopy.nucleo_familiar
        delete valueCopy.pagadores
        delete valueCopy.parentesco
        delete valueCopy.tipo_identidad
        delete valueCopy.fecha_nacimiento_slash
        return valueCopy
      },
      async submitTramite (afitramiteGo) {
        console.log('Entra primero', afitramiteGo)
        const typeRequest = this.traslado.id ? 'editar' : 'crear'
        let valueCopy = (typeof afitramiteGo !== 'undefined') ? afitramiteGo : await this.valueClean()
        // console.log('consulta: ' + this.valor_traslado)
        console.log('copy objeto', valueCopy)
        // valueCopy.tipo_traslado = this.valor_traslado
        return new Promise((resolve) => {
          let request = typeRequest === 'editar' ? this.axios.put(`formtrasmov/${valueCopy.id}`, valueCopy) : this.axios.post('formtrasmov', valueCopy)
          request
            .then(response => {
              if (response.data.formtrasmov.afiliado_pagador === null) response.data.formtrasmov.afiliado_pagador = JSON.parse(JSON.stringify(this.resetAF()))
              this.idAfiliadoValido = response.data.formtrasmov.as_afiliado_id
              this.traslado = response.data.formtrasmov
              this.$store.commit(TRASTRAMITE_RELOAD_ITEM, {item: response.data.formtrasmov_o, estado: typeRequest, key: this.parametros.key})
              this.$store.commit('reloadTable', 'tableItemTrasladosAfiliados')
              resolve(true)
            }).catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al guardar el registro del trámite de traslado. ', error: e})
              if (this.loader) this.loader.hide()
              resolve(false)
            })
        })
      },
      showLoaderStep (target = 'stepLoading') {
        this.loader = this.$loading.show({
          container: this.$refs[target].$el
        })
      },
      async next () {
        this.stepActual = parseInt(this.stepActual)
        this.showLoaderStep()
        switch (this.stepActual) {
          case 1: {
            if (await this.$validator.validateAll('formStep1')) {
              this.validStep.One = true
              if (await this.submitTramite()) {
                this.stepActual++
                this.loader.hide()
              }
            } else {
              this.validStep.One = false
              this.loader.hide()
            }
            break
          }
          case 2: {
            let infoBasica = await this.$refs['infobasicoafiliado'].validate()
            let form = await this.$validator.validateAll('formRelacionAportante')
            if (infoBasica && form) {
              this.validStep.Two = true
              var tipoTraslado = this.traslado.tipo_traslado
              if (await this.submitTramite()) {
                this.stepActual++
                this.loader.hide()
              }
              this.traslado.tipo_traslado = tipoTraslado
            } else {
              this.validStep.Two = false
              this.loader.hide()
            }
            break
          }
          case 3: {
            this.stepActual++
            this.loader.hide()
            break
          }
          case 4: {
            this.stepActual++
            this.loader.hide()
            break
          }
        }
      }
    }
  }
</script>

<style scoped>
</style>
