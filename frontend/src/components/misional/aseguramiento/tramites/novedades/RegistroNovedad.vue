<template>
  <v-card ref="formLoading">
    <v-toolbar dense>
      <v-icon>file_copy</v-icon>
      <v-toolbar-title v-html="!novtramite.id ? 'Nuevo trámite de novedad' : 'Edición de trámite de novedad'" />
    </v-toolbar>
    <v-container fluid grid-list-xl>
      <v-form data-vv-scope="formNovtramite">
        <v-layout row wrap>
          <v-flex xs12 sm12 md6>
            <postulador-v2
              no-data="Busqueda por nombre o número de documento."
              hint="identificacion_completa"
              item-text="nombre_completo"
              data-title="identificacion_completa"
              data-subtitle="nombre_completo"
              label="Afiliado"
              :detail="detalleAfiliado"
              entidad="afiliados"
              v-model="novtramite.afiliado"
              @changeid="val => novtramite.as_afiliado_id = val"
              name="Afiliado"
              rules="required|afiliadoValido|afiliadoHabilitado"
              v-validate="'required|afiliadoValido|afiliadoHabilitado'"
              :error-messages="errors.collect('Afiliado')"
              :slot-append='{
                      template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,
                      props: [`value`]
                     }'
              no-btn-edit
              no-btn-create
            />
            <p v-for="label in labels"><strong>{{label.title}}</strong>{{label.descripcion}}</p>
          </v-flex>
          <v-flex xs12 sm12 md6>
            <v-autocomplete
              label="Tipo novedad"
              v-model="novtramite.tipoNovedad"
              :items="novtramite.tiposNovedad"
              item-text="descripcion"
              :hint="novtramite.tipoNovedad ? novtramite.tipoNovedad.observaciones : ''"
              persistent-hint
              return-object
              name="Tipo novedad"
              required
              v-validate="'required'"
              :error-messages="errors.collect('Tipo novedad')"
              :disabled="!novtramite.tiposNovedad.length"
            >
              <template slot="item" slot-scope="data">
                <template>
                  <p class="my-5 subheading">{{data.item.codigo + ' - ' + data.item.descripcion}}</p>
                </template>
              </template>
              <template
                slot="selection"
                slot-scope="data"
              >
                <p class="text-xs-justify mb-0 mt-1">{{data.item.codigo + ' - ' + data.item.descripcion}}</p>
              </template>
            </v-autocomplete>
          </v-flex>
        </v-layout>
        <v-divider/>
        <v-layout row wrap v-if="novtramite.tipoNovedad && (novtramite.tipoNovedad.id !== 23)">
          <template v-for="(campo, i) in (novtramite.tipoNovedad && novtramite.tipoNovedad.campos ? novtramite.tipoNovedad.campos : null)">
            <v-flex :class="campo.flex_class">
              <v-select
                v-if="(campo.tipo === 'select' && novtramite.tipoNovedad.id !== 22) && getProperty((campo.param_first === 'internal' ? $this : complementos), campo.referencia_complemento.split('.'))"
                :label="campo.nombre"
                :key="campo.tipo + i + campo.nombre"
                v-model="novtramite['v' + (i + 1)]"
                :item-value="campo.value_select"
                :item-text="campo.text_select"
                :items="getProperty((campo.param_first === 'internal' ? $this : complementos), campo.referencia_complemento.split('.'))"
                :name="campo.nombre"
                :required="campo.validacion ? (campo.validacion.indexOf('required') > -1) : false"
                :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                v-validate="campo.validacion"
                :error-messages="errors.collect(campo.nombre)"
              />
              
              <v-select
                v-if="(campo.tipo === 'select' && novtramite.tipoNovedad.id === 22 && campo.id === 42) && getProperty((campo.param_first === 'internal' ? $this : complementos), campo.referencia_complemento.split('.'))"
                :label="campo.nombre"
                :key="campo.tipo + i + campo.nombre"
                v-model="novtramite['v' + (i + 1)]"
                :item-value="campo.value_select"
                :item-text="campo.text_select"
                :items="novtramite.v2 == 1 ? complementos.nomenclaturasUrbano : complementos.nomenclaturasRural"
                :name="campo.nombre"
                :required="campo.validacion ? (campo.validacion.indexOf('required') > -1) : false"
                :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                v-validate="campo.validacion"
                :error-messages="errors.collect(campo.nombre)"
              />
              
              <v-select
                v-if="(campo.tipo === 'select' && novtramite.tipoNovedad.id === 22 && campo.id !== 42) && getProperty((campo.param_first === 'internal' ? $this : complementos), campo.referencia_complemento.split('.'))"
                :label="campo.nombre"
                :key="campo.tipo + i + campo.nombre"
                v-model="novtramite['v' + (i + 1)]"
                :item-value="campo.value_select"
                :item-text="campo.text_select"
                :items="getProperty((campo.param_first === 'internal' ? $this : complementos), campo.referencia_complemento.split('.'))"
                :name="campo.nombre"
                :required="campo.validacion ? (campo.validacion.indexOf('required') > -1) : false"
                :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                v-validate="campo.validacion"
                :error-messages="errors.collect(campo.nombre)"
              />
              
              <v-autocomplete
                v-if="campo.tipo === 'autocomplete' && campo.id !== '45'"
                :label="campo.nombre"
                :key="campo.tipo + i + campo.nombre"
                v-model="novtramite['v' + (i + 1)]"
                :item-value="campo.value_select"
                :item-text="campo.text_select"
                :items="getProperty((campo.param_first === 'internal' ? $this : complementos), campo.referencia_complemento.split('.'))"
                :name="campo.nombre"
                :required="campo.validacion ? (campo.validacion.indexOf('required') > -1) : false"
                :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                v-validate="campo.validacion"
                :error-messages="errors.collect(campo.nombre)"
                :no-filter="!campo.referencia_filtro"
                :filter="campo.referencia_filtro ? $this[campo.referencia_filtro] : () => false"
              >
                <template :slot="(campo.title_item_select || campo.subtitle_item_select) ? 'item' : ''" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="getProperty(data.item, campo.title_item_select.split('.'))"/>
                      <v-list-tile-sub-title v-if="campo.subtitle_item_select" v-html="getProperty(data.item, campo.subtitle_item_select.split('.'))"/>
                    </v-list-tile-content>
                  </template>
                </template>
              </v-autocomplete>
              <v-text-field
                v-upper="'novtramite.v' + (i + 1)"
                v-if="campo.tipo === 'text'"
                :label="campo.nombre"
                :key="campo.tipo + i + campo.nombre"
                v-model="novtramite['v' + (i + 1)]"
                :name="campo.nombre"
                :required="campo.validacion ? (campo.validacion.indexOf('required') > -1) : false"
                v-validate="campo.validacion"
                :error-messages="errors.collect(campo.nombre)"
              />
              <v-text-field
                v-if="campo.tipo === 'number'"
                :label="campo.nombre"
                type="number" 
                :key="campo.tipo + i + campo.nombre"
                v-model.number="novtramite['v' + (i + 1)]"
                :min="campo.param_first"
                :max="campo.param_second"
                :name="campo.nombre"
                required
                v-validate="'required|decimal:2|max_value:100'"
                :error-messages="errors.collect(campo.nombre)"
              />
              <v-menu
                v-if="campo.tipo === 'date'"
                :ref="campo.nombre"
                :key="campo.tipo + i + campo.nombre + 'menu'"
                :close-on-content-click="false"
                v-model="menuDatex"
                :nudge-right="40"
                :return-value.sync="novtramite['v' + (i + 1)]"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
              >
                <v-text-field
                  slot="activator"
                  :key="campo.tipo + i + campo.nombre + 'vtext'"
                  v-model="novtramite['v' + (i + 1)]"
                  :label="campo.nombre"
                  :name="campo.nombre"
                  :required="campo.validacion ? (campo.validacion.indexOf('required') > -1) : false"
                  :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                  v-validate="campo.validacion"
                  :error-messages="errors.collect(campo.nombre)"
                  prepend-icon="event"
                  readonly
                />
                <v-date-picker
                	locale="es-co"
                  v-model="novtramite['v' + (i + 1)]"
                  :max="maxDateIniNovedad"
                  @input="$refs[campo.nombre][0].save(novtramite['v' + (i + 1)])"
                  @change="() => {
                        let index = $validator.errors.items.findIndex(x => x.field === campo.nombre)
                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                        }"
                />
              </v-menu>

              <!--IPS-->
              <postulador-v2
                v-if="campo.tipo === 'ipslite'"
                no-data="Busqueda por NIT, razon social u código de habilitación."
                hint="identificacion"
                item-text="nombre_razon_social"
                data-title="identificacion"
                data-subtitle="nombre_razon_social"
                :label="campo.nombre"
                entidad="entidades_contrato"
                preicon="location_city"
                v-model="novtramite.ips"
                @changeid="val => novtramite['v' + (i + 1)] = val"
                :name="campo.nombre"
                :key="campo.tipo + i + campo.nombre"
                :rules="campo.validacion && (campo.validacion.indexOf('required') > -1) ? 'required' : ''"
                v-validate="campo.validacion && (campo.validacion.indexOf('required') > -1) ? 'required' : ''"
                :error-messages="errors.collect(campo.nombre)"
                :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                no-btn-edit
                no-btn-create
                :route-params="`rs_tipentidade_id=1&afiliado=${novtramite.afiliado.id}`"
              />
              <!--Afiliado-->
              <postulador-v2
                v-if="campo.tipo === 'afiliado'"
                no-data="Busqueda por nombre o número de documento."
                hint="identificacion_completa"
                item-text="nombre_completo"
                data-title="identificacion_completa"
                data-subtitle="nombre_completo"
                :label="campo.nombre"
                :key="campo.tipo + i + campo.nombre"
                :detail="detalleAfiliado"
                entidad="afiliados"
                preicon="person"
                v-model="afiliado"
                @changeid="val => novtramite['v' + (i + 1)] = val"
                :name="campo.nombre"
                :route-params="campo.param_second ? campo.param_second : null"
                :clearable="campo.validacion ? (campo.validacion.indexOf('required') === -1) : true"
                :rules="campo.validacion && (campo.validacion.indexOf('required') > -1) ? 'required' : ''"
                v-validate="campo.validacion && (campo.validacion.indexOf('required') > -1) ? 'required' : ''"
                :error-messages="errors.collect(campo.nombre)"
                no-btn-edit
                :no-btn-create ="!(campo.param_first && campo.param_first === 'lite')"
              />
              <!--Pagador-->
              <postulador-v2
                v-if="campo.tipo === 'pagador'"
                no-data="Busqueda por nombre o número de documento."
                item-text="razon_social"
                data-title="identificacion"
                data-subtitle="razon_social"
                hint="identificacion"
                :label="campo.nombre"
                :key="campo.tipo + i + campo.nombre"
                :detail="detallePagador"
                entidad="pagadores"
                preicon="person"
                v-model="pagador"
                @changeid="val => novtramite['v' + (i + 1)] = val"
                :name="campo.nombre"
                rules="required"
                v-validate="'required'"
                :error-messages="errors.collect(campo.nombre)"
                no-btn-edit
              />
              <!--Identificación-->
              <v-text-field
                v-if="campo.tipo === 'identification'"
                :label="campo.nombre"
                :key="campo.tipo + i + campo.nombre"
                v-model="novtramite['v' + (i + 1)]"
                :name="campo.nombre"
                required
                v-validate="'uniqueAfiliado:' + novtramite.afiliado.id + ',' + novtramite.afiliado.identificacion + '|required|alpha_num|max:' + longitudDocumento"
                :error-messages="errors.collect(campo.nombre)"
                :maxlength="longitudDocumento"
                :counter="longitudDocumento"
              />
              <!--Fecha de nacimiento-->
              <v-menu
                v-if="campo.tipo === 'birthdate'"
                :key="campo.tipo + i + campo.nombre + 'bb'"
                :ref="campo.nombre"
                v-model="menuDate"
                :close-on-content-click="false"
                :nudge-right="40"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
              >
                <v-text-field
                  slot="activator"
                  :key="campo.tipo + i + campo.nombre + 'aa'"
                  :label="campo.nombre"
                  v-model="novtramite['v' + (i + 1)]"
                  :name="campo.nombre"
                  required
                  v-validate="'required|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
                  :error-messages="errors.collect(campo.nombre)"
                  return-masked-value
                  mask="####-##-##"
                  @click="menuDate = false"
                  readonly
                />
                <v-date-picker
                  locale="es-co"
                  :key="campo.tipo + i + campo.nombre + 'cc'"
                  ref="picker"
                  v-model="novtramite['v' + (i + 1)]"
                  :max="maxDate"
                  :min="minDate"
                  @input="$refs[campo.nombre][0].save(novtramite['v' + (i + 1)])"
                  @change="() => {
                        let index = $validator.errors.items.findIndex(x => x.field === campo.nombre)
                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                        menuDate = false
                        }"
                />
              </v-menu>
            </v-flex>
          </template>

          <!-- Fecha Inicio Novedad-->
          <v-flex xs12 ms6 md3>
            <v-menu
              ref="menuFechaIni"
              v-model="menuDateIni"
              :return-value.sync="novtramite.fecha_ini_novedad"
              :close-on-content-click="false"
              :nudge-right="40"
              lazy
              transition="scale-transition"
              offset-y
              full-width
              min-width="290px"
            >
              <v-text-field
                slot="activator"
                label="Fecha novedad"
                v-model="novtramite.fecha_ini_novedad"
                prepend-icon="event"
                name="Fecha novedad"
                v-validate="'required|date_format:YYYY-MM-DD|date_between:' + minDateIniNovedad + ',' + maxDateIniNovedad + ',true'"
                :error-messages="errors.collect('Fecha novedad')"
                readonly
              />
              <v-date-picker
                locale="es-co"
                v-model="novtramite.fecha_ini_novedad"
                :max="maxDateIniNovedad"
                :min="minDateIniNovedad"
                @input="$refs['menuFechaIni'].save(novtramite.fecha_ini_novedad)"
                @change="() => {
                        let index = $validator.errors.items.findIndex(x => x.field === 'Fecha novedad')
                        $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                        }"
              />
            </v-menu>
          </v-flex>
          <v-flex xs12>
            <v-textarea
              v-model="novtramite.observaciones"
              auto-grow
              label="Observaciones"
              rows="1"></v-textarea>
          </v-flex>
        </v-layout>
        <!--postula datos del afiliado principal-->
        <v-layout v-if="novtramite.tipoNovedad && (novtramite.tipoNovedad.id === 23)">
          <v-flex xs12>
            <v-card>
              <v-card-title class="grey lighten-3 py-1">
                <div class="headline">Cotizante principal actual</div>
              </v-card-title>
              <v-card-text class="px-0">
                <v-expansion-panel class="elevation-0">
                  <v-expansion-panel-content>
                    <div slot="header">
                      <template>
                        <v-list-tile-content>
                          <v-list-tile-title class="title" v-html="novtramite.afiliado.cabeza.nombre_completo"/>
                          <v-list-tile-sub-title v-html="novtramite.afiliado.cabeza.identificacion_completa"/>
                        </v-list-tile-content>
                        <v-divider/>
                      </template>
                    </div>
                    <v-card>
                      <v-card-text>
                        <component :is="component" :item="novtramite.afiliado.cabeza"/>
                      </v-card-text>
                    </v-card>
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-card-text>
            </v-card>
          </v-flex>
        </v-layout>
      </v-form>
    </v-container>
    <v-card-actions>
      <v-btn @click="refresh">Limpiar</v-btn>
      <v-spacer></v-spacer>
      <div style="text-align: end !important;">
        <v-switch
          class="mr-4"
          color="success"
          label="Enviar Adres"
          v-model="novtramite.fosyga"
        ></v-switch>
      </div>
      <v-btn color="primary" @click="submitNovtramite">Registrar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {NOVTRAMITE_RELOAD_ITEM} from '@/store/modules/general/tables'

  import { Validator } from 'vee-validate'
  export default {
    name: 'RegistroNovedad',
    props: ['parametros'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    filters: {
    },
    data () {
      return {
        detallePagador: () => import('@/components/misional/aseguramiento/pagadores/DetallePagador'),
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
        labels: [],
        causales: null,
        afiliado: null,
        ips: null,
        pagador: null,
        veredas: [],
        barrios: [],
        component: null,
        menuDatex: false,
        menuDate: false,
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10),
        menuDateIni: false,
        minDateIniNovedad: this.moment().subtract(365, 'days').format('YYYY-MM-DD'),
        maxDateIniNovedad: this.moment().format('YYYY-MM-DD'),
        novtramite: null,
        makeNovtramite: {
          id: null,
          as_tramite_id: null,
          as_tipnovedade_id: null,
          as_afiliado_id: null,
          ips: null,
          fecha_ini_novedad: this.moment().format('YYYY-MM-DD'),
          v1: null,
          v2: null,
          v3: null,
          v4: null,
          v5: null,
          v6: null,
          v7: null,
          afiliado: null,
          tipoNovedad: null,
          tiposNovedad: [],
          observaciones: null,
          fosyga: true
        },
        $this: null,
        filterPoblacionEspecial: (item, queryText) => this.getFilter(item, queryText, item.codigo + ' ' + item.nombre),
        filterAportantes: (item, queryText) => this.getFilter(item, queryText, item.tercero.nombre_completo + ' ' + item.tercero.identificacion_completa),
        filterMunicipios: (item, queryText) => this.getFilter(item, queryText, item.nombre + ' ' + item.nombre_departamento),
        filterTiposIdentidad: (item, queryText) => this.getFilter(item, queryText, item.abreviatura + ' ' + item.nombre),
        filterCiius: (item, queryText) => this.getFilter(item, queryText, item.codigo + ' ' + item.nombre)
      }
    },
    watch: {
      menuDate (val) {
        val && this.$nextTick(() => (this.$refs.picker[0].activePicker = 'YEAR'))
      },
      'tipo_id' (val) {
        if (val) {
          this.maxDate = (this.moment().subtract(val.edad_minima, 'months')).format('YYYY-MM-DD')
          this.minDate = (this.moment().subtract(val.edad_maxima, 'months')).subtract(val.plazo_cambio, 'days').format('YYYY-MM-DD')
        }
      },
      'novtramite.v1' (val) {
        if (val) {
          this.novtramite.v2 && this.novtramite.v2 === 2 ? this.getVeredas() : this.getBarrios()
        }
      },
      async 'novtramite.afiliado' (val) {
        if (val) {
          this.novtramite.tipoNovedad = null
          this.novtramite.tiposNovedad = []
          this.axios.get('tipnovedades/' + val.id)
            .then((response) => {
              if (response.data.data.length) {
                this.novtramite.tiposNovedad = response.data.data
              } else {
                this.$store.commit(SNACK_SHOW, {msg: 'No es posible crear un trámite de novedad para éste afiliado. ', color: 'warning'})
              }
            })
            .catch(e => {
              this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los tipos de novedad. ', error: e})
            })
        } else {
          this.refresh()
        }
      },
      'novtramite.tipoNovedad' (val) {
        this.novtramite.as_tipnovedade_id = val ? val.id : null
        this.getCampos()
        if (val && (val.id === 23)) this.component = () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado')
        if (val && (val.id === 32)) {
          this.showState()
          this.causales = JSON.parse(JSON.stringify(this.complementos.condterminaciones))
          // if (this.novtramite.afiliado.as_regimene_id === 2) this.causales.splice(this.complementos.condterminaciones.findIndex(x => x.codigo === '5'), 1)
        }
        if (val) {
          setTimeout(() => {
            if (val.id === 34) this.showState()
            if (val.id === 35) this.labels.push({title: 'Sexo actual del afiliado: ', descripcion: this.novtramite.afiliado.sexo.nombre})
            if (val.id === 36) this.labels.push({title: 'Zona actual del afiliado: ', descripcion: this.novtramite.afiliado.zona.nombre})
            if (val.id === 44) this.labels.push({title: 'Fecha actual de afiliación: ', descripcion: this.novtramite.afiliado.fecha_afiliacion + ' (Año-Mes-Día)'})
          }, 500)
        }
      }
    },
    computed: {
      complementos: () => store.getters.complementosFormNovtramite,
      tipo_id () {
        if (this.novtramite && this.novtramite.as_tipnovedade_id === 19) {
          return (this.complementos.tipdocidentidades && this.complementos.tipdocidentidades.length) ? this.complementos.tipdocidentidades.find(x => x.id === this.novtramite.v1) : null
        }
      },
      longitudDocumento () {
        if (this.novtramite && this.novtramite.as_tipnovedade_id === 19) {
          if (this.complementos.tipdocidentidades && this.complementos.tipdocidentidades.length) {
            return this.complementos.tipdocidentidades.find(x => x.id === this.novtramite.v1).longitud
          }
        }
      }
    },
    beforeMount () {
      this.$this = this
      this.refresh()
      Validator.extend('afiliadoHabilitado', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = (this.novtramite.afiliado.consecutivo_vigencia === '' || this.novtramite.afiliado.consecutivo_vigencia === null) ? {valido: true, mensaje: null} : {valido: false, mensaje: this.novtramite.afiliado.mensaje_error}
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
      Validator.extend('afiliadoValido', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = (this.novtramite.as_afiliado_id)
              ? (this.novtramite.afiliado && this.novtramite.afiliado.estado !== 1)
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
      Validator.extend('uniqueAfiliado', {
        validate: (value, id) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            this.axios.get(`afiliados?id=${id[0]}&identificacion=${value}`)
              .then(response => {
                return resolve({
                  valid: !(response.status === 200 && response.data && response.data.data && response.data.data.length && (value !== id[1])),
                  data: {
                    message: 'ya se encuentra registrado un afiliado con ésta identificación.'
                  }
                })
              }).catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al intentar validar la identificación del afiliado. ', error: e})
              })
          }
        }),
        getMessage: (field, params, data) => data.message
      })
    },
    mounted () {
      if (this.parametros.entidad !== null && this.parametros.entidad.id !== null) this.getRegistro(this.parametros.entidad.id)
    },
    methods: {
      showState () {
        this.labels.push({title: 'Estado actual de afiliación: ', descripcion: this.novtramite.afiliado.estado_afiliado.codigo + ' - ' + this.novtramite.afiliado.estado_afiliado.nombre})
      },
      getVeredas () {
        if (this.novtramite.v1 && this.novtramite.v2 === 2) {
          if (!(this.veredas.length && (this.novtramite.v1 === this.veredas[0].gn_municipio_id))) {
            this.axios.get('complementos/veredas/' + this.novtramite.v1)
              .then((response) => {
                this.veredas = response.data.data
              })
              .catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer las veredas. ', error: e})
              })
          }
        }
      },
      getBarrios () {
        if (this.novtramite.v1 && this.novtramite.v2 === 1) {
          if (!(this.barrios.length && (this.novtramite.v1 === this.barrios[0].gn_municipio_id))) {
            this.axios.get('complementos/barrios/' + this.novtramite.v1)
              .then((response) => {
                this.barrios = response.data.data
              })
              .catch(e => {
                this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los barrios. ', error: e})
              })
          }
        }
      },
      getFilter (item, queryText, compareString) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(compareString)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      getProperty: (item, arr) => window.toProperty(item, arr),
      getCampos () {
        this.labels = []
        this.causales = null
        this.afiliado = null
        this.pagador = null
        this.novtramite.v1 = null
        this.novtramite.v2 = null
        this.novtramite.v3 = null
        this.novtramite.v4 = null
        this.novtramite.v5 = null
        this.novtramite.v6 = null
        this.novtramite.v7 = null

        this.novtramite.tipoNovedad && this.novtramite.tipoNovedad.campos.forEach((campo, index) => {
          this.novtramite['v' + (index + 1)] = campo.propiedad ? this.getProperty(this.novtramite, campo.propiedad.split('.')) : null
        })
      },
      showLoader (target = 'formLoading') {
        this.loader = this.$loading.show({
          container: this.$refs[target].$el
        })
      },
      refresh () {
        this.$validator.reset()
        this.novtramite = JSON.parse(JSON.stringify(this.makeNovtramite))
      },
      async submitNovtramite () {
        if (await this.$validator.validateAll('formNovtramite')) {
          if (this.hayCambios(this.novtramite.as_tipnovedade_id)) {
            this.showLoader()
            let novtramiteCopy = JSON.parse(JSON.stringify(this.novtramite))
            delete novtramiteCopy.afiliado
            delete novtramiteCopy.tipoNovedad
            delete novtramiteCopy.tiposNovedad
            const typeRequest = novtramiteCopy.id ? 'editar' : 'crear'
            this.axios.post('novtramites', novtramiteCopy)
              .then((response) => {
                this.$store.commit(SNACK_SHOW, {msg: 'El trámite de novedad ha sido ' + (typeRequest === 'editar' ? 'editado.' : 'creado.'), color: 'success'})
                this.$store.commit(NOVTRAMITE_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                this.formLoading = false
              }).catch(e => {
                this.formLoading = false
                this.$store.commit('SNACK_ERROR_LIST', {expression: ' al guardar el trámite de novedad. ', error: e})
              })
              .finally(this.formLoading = false)
          } else {
            this.$store.commit(SNACK_SHOW, {msg: 'La novedad no se puede registrar, los valores referenciados no han sido modificados. ', color: 'warning'})
          }
        }
      },
      hayCambios (type) {
        if (type === 19) return (this.novtramite.v1 !== this.novtramite.afiliado.tipo_id.id || this.novtramite.v2 !== this.novtramite.afiliado.identificacion || this.novtramite.v3 !== this.novtramite.afiliado.fecha_nacimiento)
        if (type === 20) return (this.novtramite.v1 !== this.novtramite.afiliado.nombre1 || this.novtramite.v2 !== this.novtramite.afiliado.nombre2)
        if (type === 21) return (this.novtramite.v1 !== this.novtramite.afiliado.apellido1 || this.novtramite.v2 !== this.novtramite.afiliado.apellido2)
        if (type === 22) return (this.novtramite.v1 !== this.novtramite.afiliado.gn_municipio_id)
        if (type === 25) return (this.novtramite.v1 !== this.novtramite.afiliado.as_parentesco_id || this.novtramite.v2 !== this.novtramite.afiliado.as_condicion_discapacidades_id || this.novtramite.v3 !== this.novtramite.afiliado.cabfamilia_id)
        if (type === 30) return (this.novtramite.v1 !== this.novtramite.afiliado.as_condicion_discapacidades_id)
        if (type === 32) return (this.novtramite.v1 !== this.novtramite.afiliado.estado)
        if (type === 37) return (this.novtramite.v1 !== this.novtramite.afiliado.nivel_sisben || this.novtramite.v2 !== this.novtramite.afiliado.puntaje_sisben)
        if (type === 38) return (this.novtramite.v1 !== this.novtramite.afiliado.as_pobespeciale_id)
        if (type === 40) return (this.novtramite.v1 !== this.novtramite.afiliado.rs_entidade_id)
        if (type === 41) return (this.novtramite.v1 !== this.novtramite.afiliado.as_pobespeciale_id || this.novtramite.v2 !== this.novtramite.afiliado.nivel_sisben || this.novtramite.v3 !== this.novtramite.afiliado.puntaje_sisben)
        if (type === 42) return (this.novtramite.v1 !== this.novtramite.afiliado.as_parentesco_id || this.novtramite.v2 !== this.novtramite.afiliado.as_condicion_discapacidades_id)
        if (type === 44) return (this.novtramite.v1 !== this.novtramite.afiliado.fecha_afiliacion)
        if ((type === 23) || (type === 24) || (type === 26) || (type === 27) || (type === 28) || (type === 29) || (type === 34) || (type === 35) || (type === 36) || (type === 43)) return true
      }
    }
  }
</script>

<style scoped>
</style>
