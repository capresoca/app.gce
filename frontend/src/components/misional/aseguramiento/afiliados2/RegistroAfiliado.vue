<template>
  <div>
    <v-toolbar dense>
      <v-icon>{{!afiliado.id ? 'person_add' : 'person'}}</v-icon>
      <v-toolbar-title>{{!afiliado.id ? 'Nuevo afiliado' : 'Edición de afiliado' }}</v-toolbar-title>
    </v-toolbar>
    <v-stepper v-model="stepActual">
      <loading v-model="loadingSubmit" />
      <v-stepper-header>
        <v-stepper-step :complete="stepActual > 1" step="1" editable :rules="[() => !errorsStep1]">Datos básicos <small v-if="errorsStep1">Hay datos por diligenciar.</small></v-stepper-step>
        <v-divider/>
        <v-stepper-step :complete="stepActual > 2" step="2" editable :rules="[() => !errorsStep2]">Datos complementarios <small v-if="errorsStep2">Hay datos por diligenciar.</small></v-stepper-step>
        <v-divider/>
        <v-stepper-step :complete="stepActual > 3" step="3" editable :rules="[() => !errorsStep3]">Información adicional <small v-if="errorsStep3">Hay datos por diligenciar.</small></v-stepper-step>
      </v-stepper-header>
      <v-stepper-items>
        <v-stepper-content step="1">
          <v-card class="mb-1">
            <v-container
              fluid
              grid-list-lg
            >
              <form-basicos ref="fBasicos" manager="Afiliado" :edicion-total="edicionTotal" :verified="estadoTercero" @errors="val => validStepBasicos = val" @verified="val => estadoTercero = val" @update="val => complementar(val)"/>
              <v-form data-vv-scope="formAfiliadoStep1">
                <v-layout row wrap>
                  <v-flex xs12 sm6 md3>
                    <input-date
                      v-model="afiliado.fecha_nacimiento"
                      label="Fecha nacimiento (Día/Mes/Año)"
                      format="DD/MM/YYYY"
                      name="Fecha nacimiento"
                      :min="minDate"
                      :max="maxDate"
                      v-validate="'required|fechaValida:DD/MM/YYYY|date_format:DD/MM/YYYY' + (edicionTotal ? '' : '|date_between:' + minDateI + ',' + maxDateI + ',true')"
                      :error-messages="errors.collect('Fecha nacimiento')"
                      :disabled="!estadoTercero || requiereNovedad"
                    ></input-date>
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-select
                    label="Sexo"
                    v-model="afiliado.gn_sexo_id"
                    :items="complementos.sexos"
                    item-value="id"
                    item-text="nombre"
                    name="Sexo"
                    required
                    v-validate="'required'"
                    :error-messages="errors.collect('Sexo')"
                    :disabled="!estadoTercero || requiereNovedad"
                    ></v-select>
                  </v-flex>
                  <v-flex xs12 sm6 md3 v-if="afiliado.id">
                    <v-select
                      label="Régimen"
                      v-model="afiliado.as_regimene_id"
                      :items="complementos.regimenes"
                      item-value="id"
                      item-text="nombre"
                      name="régimen"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('régimen')"
                      :disabled="!estadoTercero || requiereNovedad"
                    ></v-select>
                  </v-flex>
                  <v-flex xs12 sm6 md3 v-if="afiliado.id">
                    <v-text-field
                      v-if="requiereRolLider"
                      label="Estado"
                      :value="afiliado.estado_afiliado.nombre"
                      disabled
                    ></v-text-field>
                    <v-select
                      v-else
                      label="Estado"
                      v-model="afiliado.estado"
                      :items="complementos.estadosAfiliado"
                      item-value="id"
                      item-text="nombre"
                      name="estado"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('estado')"
                      :disabled="!estadoTercero"
                    ></v-select>
                  </v-flex>
                  <template>
                    <v-flex xs12 sm6 md3>
                      <input-date
                        v-model="afiliado.fecha_afiliacion"
                        label="Fecha afiliación (Día/Mes/Año)"
                        format="DD/MM/YYYY"
                        :min="minDateAfiliacion2"
                        :max="maxFechaActual"
                        name="fecha afiliación"
                        v-validate="'required|fechaValida:DD/MM/YYYY|date_format:DD/MM/YYYY' + (false ? '' : '|date_between:' + minDateAfiliacionI + ',' + maxFechaActualI + ',true')"
                        :error-messages="errors.collect('fecha afiliación')"
                        :disabled="!estadoTercero || requiereNovedad"
                      ></input-date>
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                      <v-select
                        label="Tipo afiliado"
                        v-model="afiliado.as_tipafiliado_id"
                        :items="complementos.tipafiliados"
                        item-value="id"
                        item-text="nombre"
                        name="tipo afiliado"
                        required
                        v-validate="'required'"
                        :error-messages="errors.collect('tipo afiliado')"
                        :disabled="!estadoTercero || requiereNovedad"
                      ></v-select>
                    </v-flex>
                  </template>
                </v-layout>
              </v-form>
            </v-container>
          </v-card>
        </v-stepper-content>
        <v-stepper-content step="2">
          <v-card class="mb-1">
            <v-container
              fluid
              grid-list-lg
            >
              <form-complementarios ref="fComplementarios" manager="Afiliado" :edicion-total="edicionTotal" :verified="estadoTercero" @errors="val => validStepComplementarios = val"/>
              <v-form data-vv-scope="formAfiliadoStep2">
                <v-layout row wrap>
                  
                  <v-flex xs12 sm6 md3 v-if="afiliado.gn_zona_id === 2">
                    <v-autocomplete
                      key="autocomple-vereda"
                      label="Vereda"
                      v-model="afiliado.gn_vereda_id"
                      :items="veredas"
                      item-value="id"
                      item-text="nombre"
                      name="Vereda"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Vereda')"
                      :disabled="!estadoTercero"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3 v-if="afiliado.gn_zona_id === 1">
                    <v-autocomplete
                      key="autocomple-barrio"
                      label="Barrio"
                      v-model="afiliado.gn_barrio_id"
                      :items="barrios"
                      item-value="id"
                      item-text="nombre"
                      name="Barrio"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Barrio')"
                      :disabled="!estadoTercero"
                    ></v-autocomplete>
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-select
                      label="Etnia"
                      v-model="afiliado.as_etnia_id"
                      :items="complementos.etnias"
                      item-value="id"
                      item-text="nombre"
                      :disabled="!estadoTercero"
                      clearable
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3>
                    <v-autocomplete
                      label="Pais procedencia"
                      v-model="afiliado.gn_paise_id"
                      :items="complementos.paises"
                      item-value="id"
                      item-text="nombre"
                      name="Pais procedencia"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Pais procedencia')"
                      :disabled="!estadoTercero"
                    >
                      <template slot="item" slot-scope="data">
                        <template>
                          <v-list-tile-action>
                            <flag :iso="data.item.iso_dos"/>
                          </v-list-tile-action>
                          <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.nombre"/>
                          </v-list-tile-content>
                        </template>
                      </template>
                      <template slot="selection" slot-scope="data">
                        <flag :iso="data.item.iso_dos"/> &nbsp; {{data.item.nombre}}
                      </template>
                    </v-autocomplete>
                  </v-flex>
                </v-layout>
              </v-form>
            </v-container>
          </v-card>
        </v-stepper-content>
        <v-stepper-content step="3">
          <v-card class="mb-1">
            <v-container
              fluid
              grid-list-lg
            >
              <v-form data-vv-scope="formAfiliadoStep3">
                <v-layout row wrap>
                  <v-flex xs12 sm12 md6>
                    <v-select
                      label="Condición de Discapacidad"
                      v-model="afiliado.as_condicion_discapacidades_id"
                      :items="complementos.condicionesdiscapacidad"
                      item-value="id"
                      item-text="nombre"
                      :disabled="!estadoTercero || requiereNovedad"
                      clearable
                    />
                  </v-flex>
                  <v-flex xs12 sm12 md6 v-if="afiliado && afiliado.as_regimene_id === 2">
                    <v-autocomplete
                      key="autocompletePoblacionEspecial"
                      label="Población Especial"
                      v-model="afiliado.as_pobespeciale_id"
                      :items="complementos.pobespeciales"
                      item-value="id"
                      item-text="nombre"
                      :disabled="!estadoTercero || requiereNovedad"
                      name="Población Especial"
                      v-validate="'required'"
                      :error-messages="errors.collect('Población Especial')"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3 v-if="afiliado && afiliado.as_regimene_id === 2 && afiliado.as_pobespeciale_id === 4">
                    <v-select
                      key="selectZonaDNP"
                      label="Zona DNP"
                      v-model="afiliado.zona_dnp"
                      :items="complementos.zonas"
                      item-value="id"
                      item-text="nombre"
                      name="Zona DNP"
                      v-validate="'required'"
                      :error-messages="errors.collect('Zona DNP')"
                      :disabled="!estadoTercero || requiereNovedad"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3 v-if="afiliado && afiliado.as_regimene_id === 2 && afiliado.as_pobespeciale_id === 4">
                    <v-text-field
                      key="textFichaSISBEN"
                      label="Ficha SISBEN"
                      v-model="afiliado.ficha_sisben"
                      name="Ficha SISBEN"
                      maxlength="8"
                      required
                      v-validate="(afiliado && afiliado.as_pobespeciale_id === 4) ? 'required|max:8' : ''"
                      :error-messages="errors.collect('Ficha SISBEN')"
                      :disabled="!estadoTercero || requiereNovedad"
                    />
                  </v-flex>
                  <v-flex xs12 sm6 md3 v-if="afiliado && afiliado.as_regimene_id === 2 && afiliado.as_pobespeciale_id === 4">
                    <v-text-field
                      key="textPuntajeSISBEN"
                      label="Puntaje Sisben"
                      type="number"
                      :step="0.01"
                      :min="zonaDNP ? zonaDNP.rn1.ini : 0"
                      :max="zonaDNP ? zonaDNP.rn2.fin : 0"
                      v-model:number="afiliado.puntaje_sisben"
                      name="Puntaje Sisben"
                      data-vv-validate-on="input|change|custom|blur"
                      v-validate="'puntajeSisben:' + afiliado.zona_dnp + 'decimal:2|max_value:' + (zonaDNP ? zonaDNP.rn2.fin : 0) + '|min_value:' + (zonaDNP ? zonaDNP.rn1.ini : 0) + '|required'"
                      :error-messages="errors.collect('Puntaje Sisben')"
                      :disabled="!estadoTercero || (afiliado && !afiliado.zona_dnp) || requiereNovedad"
                    />
                  </v-flex>
                  <v-slide-x-reverse-transition
                    mode="out-in"
                  >
                    <v-flex
                      xs12
                      sm6
                      md3
                      v-if="estadoTercero && afiliado && afiliado.as_regimene_id === 2 && afiliado.as_pobespeciale_id === 4 && afiliado.puntaje_sisben !== null && afiliado.puntaje_sisben !== '' && afiliado.puntaje_sisben > -1"
                    >
                      <v-text-field
                        label="Nivel SISBEN"
                        v-model:number="afiliado.nivel_sisben"
                        :value="afiliado.nivel_sisben"
                        readonly
                        :disabled="!estadoTercero || (afiliado && afiliado.as_pobespeciale_id !== 4) || requiereNovedad"
                      />
                    </v-flex>
                  </v-slide-x-reverse-transition>
                  <v-flex xs12 sm12 md6 v-if="afiliado.as_regimene_id === 1">
                    <v-autocomplete
                      key="autocompleteCajaCompensacion"
                      label="Caja de compensación"
                      v-model="afiliado.as_ccf_id"
                      :items="complementos.ccfs"
                      item-value="id"
                      item-text="nombre"
                      :disabled="!estadoTercero"
                      clearable
                    />
                  </v-flex>
                  <v-flex xs12 sm12 md6 v-if="afiliado.as_regimene_id === 1">
                    <v-autocomplete
                      key="autocompleteAFP"
                      label="AFP"
                      v-model="afiliado.as_afp_id"
                      :items="complementos.afps"
                      item-value="id"
                      item-text="nombre"
                      :disabled="!estadoTercero"
                      clearable
                    />
                  </v-flex>
                  <v-flex xs12 sm12 md6 v-if="afiliado.as_regimene_id === 1">
                    <v-autocomplete
                      key="autocompleteActividadEconomica"
                      label="Actividad económica"
                      v-model="afiliado.nf_ciiu_id"
                      :items="complementos.ciius"
                      item-value="id"
                      item-text="nombre"
                      name="Actividad económica"
                      required
                      v-validate="'required'"
                      :error-messages="errors.collect('Actividad económica')"
                      :disabled="!estadoTercero"
                      :filter="filterCiius"
                    >
                      <template slot="item" slot-scope="data">
                        <template>
                          <v-list-tile-content>
                            <v-list-tile-title v-html="data.item.codigo"/>
                            <v-list-tile-sub-title v-html="data.item.nombre"/>
                          </v-list-tile-content>
                        </template>
                      </template>
                    </v-autocomplete>
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
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import InputDate from '@/components/general/InputDate'
  import store from '@/store/complementos/index'
  import formBasicos from '@/components/administrativo/niif/terceros/FormBasicos'
  import FormComplementarios from '@/components/administrativo/niif/terceros/FormComplementarios'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {AFILIADO_RELOAD_ITEM} from '@/store/modules/general/tables'
  import { Validator } from 'vee-validate'
  import {mapGetters} from 'vuex'

  export default {
    name: 'RegistroAfiliado',
    props: ['parametros'],
    components: {
      Loading,
      InputDate,
      FormComplementarios,
      formBasicos
    },
    data () {
      return {
        flagMounted: false,
        validStepBasicos: true,
        validStepComplementarios: true,
        estadoTercero: false,
        afiliado: {},
        loadingSubmit: false,
        edad_minima_valida: null,
        makeAfiliado: {
          id: null,
          nomenclatura: null,
          tipo_identificacion: null,
          as_etnia_id: null,
          ficha_sisben: null,
          puntaje_sisben: null,
          nivel_sisben: null,
          as_pobespeciale_id: null,
          as_regimene_id: 2,
          as_condicion_discapacidades_id: null,
          as_afp_id: null,
          as_ccf_id: null,
          gn_zona_id: null,
          gn_vereda_id: null,
          gn_barrio_id: null,
          fecha_nacimiento: null,
          estado: 1,
          gn_sexo_id: null,
          zona_dnp: null,
          apellido1: null,
          apellido2: null,
          celular: null,
          gn_paise_id: 45,
          fecha_expedicion: null,
          correo_electronico: null,
          direccion: null,
          gn_municipio_id: null,
          gn_tipdocentidad_id: null,
          identificacion: null,
          nf_ciiu_id: null,
          nombre1: null,
          nombre2: null,
          telefono_fijo: null,
          nombre_completo: null,
          fecha_afiliacion: null,
          as_tipafiliado_id: null
        },
        stepActual: 1,
        veredas: [],
        barrios: [],
        menuDate: false,
        minDate: '01/01/1900',
        minDateI: '1900-01-01',
        maxDateI: '',
        maxDate: '',
        maxFechaActual: this.moment().format('YYYY-MM-DD'),
        maxDateAfiliacion: '',
        minDateAfiliacion: '01/01/1910',
        minDateAfiliacion2: '01/01/1910',
        maxFechaActualI: this.moment().format('DD/MM/YYYY'),
        minDateAfiliacionI: '01/01/1910',
        filterMunicipios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterCiius (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codigo + ' ' + item.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        }
      }
    },
    watch: {
      'afiliado.as_pobespeciale_id' (val) {
        if (typeof val === 'undefined') this.afiliado.as_pobespeciale_id = null
      },
      menuDate (val) {
        val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
      },
      'tipo_id' (val) {
        if (val) {
          this.edad_minima_valida = val.edad_minima
          console.log(val.edad_minima)
          if ((!this.afiliado.fecha_expedicion && (typeof this.afiliado.fecha_expedicion === 'undefined')) && (this.afiliado.fecha_expedicion === '0000-00-00') && (this.afiliado.fecha_expedicion === null)) {
            this.maxDate = (this.moment().subtract(val.edad_minima, 'months')).format('YYYY-MM-DD')
          }
          this.minDate = (this.moment().subtract(val.edad_maxima, 'months')).subtract(val.plazo_cambio, 'days').format('YYYY-MM-DD')
          this.minDateI = (this.moment().subtract(val.edad_maxima, 'months')).subtract(val.plazo_cambio, 'days').format('DD/MM/YYYY')
          this.afiliado.tipo_identificacion = val.abreviatura
        }
      },
      'afiliado.fecha_nacimiento' (val) {
        if (val !== null && val !== undefined) {
          var vars = val
          var arrVars = vars.split('/')
          this.minDateAfiliacionI = val
          this.minDateAfiliacion2 = arrVars[2] + '-' + arrVars[1] + '-' + arrVars[0]
        }
      },
      'afiliado.fecha_expedicion' (val) {
        if (val !== undefined && val !== null) {
          console.log(this.edad_minima_valida)
          var vars = val
          var arrVars = vars.split('/')
          this.maxDate = arrVars[2] + '-' + arrVars[1] + '-' + arrVars[0]
          var temporal = this.maxDate
          this.maxDate = (this.moment(temporal).subtract(this.edad_minima_valida, 'months')).format('YYYY-MM-DD')
          this.maxDateI = (this.moment(temporal).subtract(this.edad_minima_valida, 'months')).format('DD/MM/YYYY')
        }
      },
      'afiliado.gn_municipio_id' (val) {
        if (val) {
          this.afiliado.gn_zona_id && this.afiliado.gn_zona_id === 2 ? this.getVeredas() : this.getBarrios()
          if (this.flagMounted) {
            this.afiliado.gn_barrio_id = null
            this.afiliado.gn_vereda_id = null
          }
        }
      },
      'afiliado.gn_zona_id' (val) {
        if (val) {
          this.afiliado.gn_municipio_id && val === 2 ? this.getVeredas() : this.getBarrios()
          if (this.flagMounted) {
            this.afiliado.gn_barrio_id = null
            this.afiliado.gn_vereda_id = null
          }
        }
      }
    },
    created () {
      this.maxDate = this.formDate(new Date().toISOString().substr(0, 10))
      this.maxDateAfiliacion = this.formDate(new Date().toISOString().substr(0, 10))
    },
    computed: {
      ...mapGetters(['getProfile']),
      edicionTotal () {
        return (this.getProfile && this.getProfile.roles && this.getProfile.roles.find(x => x.id === 13))
      },
      requiereRolLider () {
        return this.afiliado && this.afiliado.id && !this.edicionTotal
      },
      requiereNovedad () {
        return false
        // return this.afiliado && this.afiliado.id && this.afiliado.estado !== 1 && !this.edicionTotal
      },
      errorsStep1 () {
        return (!this.validStepBasicos || this.errors.items.filter(item => item.scope === 'formAfiliadoStep1').length > 0)
      },
      errorsStep2 () {
        return (!this.validStepComplementarios || this.errors.items.filter(item => item.scope === 'formAfiliadoStep2').length > 0)
      },
      errorsStep3 () {
        return this.errors.items.filter(item => item.scope === 'formAfiliadoStep3').length > 0
      },
      complementos () {
        return store.getters.complementosFormAfiliados
      },
      tipo_id () {
        return (
          this.complementos.tipdocidentidades && this.complementos.tipdocidentidades.length && this.afiliado.gn_tipdocidentidad_id) ? this.complementos.tipdocidentidades.find(tipo => tipo.id === this.afiliado.gn_tipdocidentidad_id) : null
      },
      zonaDNP () {
        if (this.complementos.zonas && this.afiliado && this.afiliado.zona_dnp) {
          // console.log('Afiliado ', this.afiliado)
          let zonaActual = this.complementos.zonas.find(x => x.id === (this.afiliado.zona_dnp.id === null || this.afiliado.zona_dnp.id === undefined ? this.afiliado.zona_dnp : this.afiliado.zona_dnp.id))
          // console.log('Zona Actual: ', zonaActual)
          // let rn1 = zonaActual.rango_nivel1.split('-')
          // let rn2 = zonaActual.rango_nivel2.split('-')
          return {
            zona: zonaActual,
            rn1: {
              ini: parseFloat(0),
              fin: parseFloat(44.79)
            },
            rn2: {
              ini: parseFloat(44.80),
              fin: parseFloat(51.57)
            }
          }
        } else {
          return null
        }
      }
    },
    beforeMount () {
      Validator.extend('puntajeSisben', {
        validate: (value, prop) => new Promise(resolve => {
          // console.log('objeto prop ', )
          // let zonaId = parseInt(prop[0][23])
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '') && (this.complementos.zonas)) {
            // console.log('Zona dnp ', zonaId)
            // let zona = this.complementos.zonas.find(x => x.id === zonaId)
            // let rn1 = zona.rango_nivel1.split('-')
            // let rn2 = zona.rango_nivel2.split('-')
            let response = value >= parseFloat(0) && value <= parseFloat(44.79)
              ? {valido: true, nivel: 1, mensaje: null}
              : value >= parseFloat(44.80) && value <= parseFloat(51.57)
                ? {valido: true, nivel: 2, mensaje: null}
                : {valido: false, nivel: 'NO APLICA', mensaje: 'el puntaje no aplica para los niveles de SISBEN permitidos.'}
            this.afiliado.nivel_sisben = response.nivel
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
      this.refresh()
      if (this.parametros.entidad !== null && this.parametros.entidad.id !== null) {
        if (typeof this.parametros.entidad.prop_dato !== 'undefined' && (this.parametros.entidad.prop_dato)) {
          let afiliadoPadre = this.parametros.entidad.prop_dato
          let afiliadoHijo = this.clone(this.afiliado)
          if (typeof this.afiliado.cabfamilia_id !== 'undefined' && this.afiliado.cabfamilia_id) {
            this.afiliado.cabfamilianterior_id = this.afiliado.cabfamilia_id
          }
          this.afiliado.cabfamilia_id = afiliadoPadre.id
          this.afiliado.telefono_fijo = afiliadoPadre.telefono_fijo
          this.afiliado.celular = afiliadoPadre.celular
          this.afiliado.direccion = this.clone(afiliadoPadre.direccion)
          this.afiliado.correo_electronico = afiliadoPadre.correo_electronico
          this.afiliado.gn_municipio_id = afiliadoPadre.gn_municipio_id
          this.afiliado.municipio = afiliadoPadre.municipio
          this.afiliado.gn_zona_id = afiliadoPadre.gn_zona_id
          this.afiliado.gn_barrio_id = afiliadoPadre.gn_barrio_id
          this.afiliado.as_etnia_id = afiliadoPadre.as_etnia_id
          afiliadoHijo.gn_paise_id = afiliadoPadre.gn_paise_id
          this.afiliado.as_pobespeciale_id = afiliadoPadre.as_pobespeciale_id
          this.afiliado.ficha_sisben = afiliadoPadre.ficha_sisben
          this.afiliado.puntaje_sisben = afiliadoPadre.puntaje_sisben
          this.afiliado.nivel_sisben = afiliadoPadre.nivel_sisben
          this.afiliado.zona_dnp = afiliadoPadre.zona_dnp
          this.afiliado.as_etnia_id = afiliadoPadre.as_etnia_id
          var numEspacio = this.afiliado.direccion.indexOf(' ')
          var inicio = this.afiliado.direccion.substr(0, numEspacio)
          console.log(inicio)
          var nom1 = inicio === 'CR' ? 1 : ''
          nom1 = inicio === 'CL' ? 2 : nom1
          nom1 = inicio === 'CORREGIMIENTO' ? 3 : nom1
          nom1 = inicio === 'VEREDA' ? 4 : nom1
          nom1 = inicio === 'TRANSVERSAL' ? 5 : nom1
          this.afiliado.direccion = this.afiliado.direccion.substr(numEspacio + 1)
          this.afiliado.nomenclatura = nom1
          this.nomenclatura = nom1
        } else {
          this.getRegistro(this.parametros.entidad.id)
        }
      } else {
        this.flagMounted = true
      }
    },
    methods: {
      getRegistro (id) {
        this.loadingSubmit = true
        this.axios.get('afiliados/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.afiliado = response.data.data
              this.afiliado.fecha_nacimiento = this.moment(this.afiliado.fecha_nacimiento).format('DD/MM/YYYY')
              this.afiliado.fecha_afiliacion = this.moment(this.afiliado.fecha_afiliacion).format('DD/MM/YYYY')
              console.log(this.afiliado.fecha_afiliacion)
              this.afiliado.fecha_expedicion = this.moment(this.afiliado.fecha_expedicion).format('DD/MM/YYYY')
              this.$refs.fBasicos.assign(this.afiliado)
              this.afiliado.gn_zona_id && this.afiliado.gn_zona_id === 2 ? this.getVeredas() : this.getBarrios()
              this.loadingSubmit = false
              setTimeout(() => { this.flagMounted = true }, 500)
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el afiliado. ', error: e})
          })
      },
      complementar (val) {
        this.flagMounted = false
        this.afiliado = val
        this.$refs.fComplementarios.assign(this.afiliado)
        setTimeout(() => { this.flagMounted = true }, 500)
      },
      refresh () {
        this.stepActual = 1
        this.afiliado = JSON.parse(JSON.stringify(this.makeAfiliado))
        // this.minDateAfiliacion2 = this.formDate(this.afiliado.fecha_nacimiento)
        // this.maxDateAfiliacion = this.moment(this.maxDateAfiliacion).format('DD/MM/YYYY')
        // let max = new Date().toISOString().substr(0, 10)
        // this.maxDate = this.formDate(max)
        // this.maxDateAfiliacion = this.formDate(max)
        this.$refs.fBasicos.assign(this.afiliado)
        this.$validator.reset()
      },
      getVeredas () {
        if (this.afiliado.gn_municipio_id && this.afiliado.gn_zona_id === 2) {
          if (!(this.veredas.length && (this.afiliado.gn_municipio_id === this.veredas[0].gn_municipio_id))) {
            this.axios.get('complementos/veredas/' + this.afiliado.gn_municipio_id)
              .then((response) => {
                this.veredas = response.data.data
              })
              .catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer las veredas. ', error: e})
              })
          }
        }
      },
      formDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${day}/${month}/${year}`
      },
      getBarrios () {
        if (this.afiliado.gn_municipio_id && this.afiliado.gn_zona_id === 1) {
          if (!(this.barrios.length && (this.afiliado.gn_municipio_id === this.barrios[0].gn_municipio_id))) {
            this.axios.get('complementos/barrios/' + this.afiliado.gn_municipio_id)
              .then((response) => {
                this.barrios = response.data.data
              })
              .catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los barrios. ', error: e})
              })
          }
        }
      },
      async submit () {
        if (await this.$refs.fBasicos.validate() && await this.$validator.validateAll('formAfiliadoStep1')) {
          if (await this.$refs.fComplementarios.validate() && await this.$validator.validateAll('formAfiliadoStep2')) {
            if (await this.$validator.validateAll('formAfiliadoStep3')) {
              this.loadingSubmit = true
              let copyAfiliado = JSON.parse(JSON.stringify(this.afiliado))
              if (copyAfiliado.as_pobespeciale_id !== 4 || copyAfiliado.as_regimene_id === 1) {
                copyAfiliado.ficha_sisben = null
                copyAfiliado.puntaje_sisben = null
                copyAfiliado.nivel_sisben = 'N'
                copyAfiliado.zona_dnp = null
                if (copyAfiliado.as_pobespeciale_id === 4) {
                  copyAfiliado.as_pobespeciale_id = null
                }
              }
              copyAfiliado.fecha_expedicion = this.afiliado.fecha_expedicion
              const typeRequest = copyAfiliado.id ? 'editar' : 'crear'
              copyAfiliado.zona_dnp = copyAfiliado.zona_dnp !== null && copyAfiliado.zona_dnp !== undefined && copyAfiliado.zona_dnp.id !== null && copyAfiliado.zona_dnp.id !== undefined ? copyAfiliado.zona_dnp.id : copyAfiliado.zona_dnp
              console.log('copia afiliado ', copyAfiliado)
              const request = typeRequest === 'crear' ? this.axios.post('afiliados', copyAfiliado) : this.axios.put(`afiliados/${copyAfiliado.id}`, copyAfiliado)
              request.then((response) => {
                this.$store.commit(SNACK_SHOW, {msg: `El afiliado fue ${typeRequest === 'editar' ? 'editado' : 'creado'} correctamente.`, color: 'success'})
                this.$store.commit(AFILIADO_RELOAD_ITEM, {item: response.data, estado: typeRequest, key: this.parametros.key})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.$store.commit('reloadTable', 'tableItemAffiliate')
              }).catch(e => {
                this.loadingSubmit = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar guardar el registro. ', error: e})
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
