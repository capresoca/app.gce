<template>
  <v-card>
    <loading v-model="loading"/>
    <v-toolbar dense>
      <v-icon>{{ 'assignment_late' }}</v-icon>
      <v-toolbar-title class="text-capitalize">{{ (!item.id ? 'Nuevo ' : 'Edición de ') + 'reporte' }}</v-toolbar-title>
    </v-toolbar>
    <v-container fluid grid-list-sm>
      <v-layout row wrap align-center>
        <v-flex xs12 sm12 md7>
          <v-text-field
            label="Nombre"
            v-model="item.nombre"
            name="Nombre"
            v-validate="'required'"
            :error-messages="errors.collect('Nombre')"
          />
        </v-flex>
        <v-flex xs12 sm12 md5>
          <v-autocomplete
            v-model="item.gs_modulo_id"
            :items="listaModulos"
            item-text="nombre"
            item-value="id"
            label="Módulo"
            name="Módulo"
            v-validate="'required'"
            :error-messages="errors.collect('Módulo')"
          />
        </v-flex>
        <v-flex xs12 sm12 md12>
          <v-textarea
            v-model="item.descripcion"
            auto-grow
            label="Descripción"
            rows="1"
          />
        </v-flex>
        <v-flex xs12 sm12 md12>
          <span :class="focusCOdemirror ? 'primary--text' : ''" class="caption v-label v-label--active theme--light">Sentencia SQL</span>
          <codemirror
            v-model="item.sentencia"
            :options="cmOptions"
            @focus="focusCOdemirror = true"
            @blur="focusCOdemirror = false"
          />
          <span class="red--text caption">{{ errors.collect('Sentencia SQL')[0] }}</span>
          <span class="caption grey--text text--darken-1"> {{ errors.collect('Sentencia SQL')[0] ? '...' : '' }} Las variables se escriben con el prefijo ':' y sin espacios Ej. => :variable_uno</span>
          <v-textarea
            v-show="false"
            v-model="item.sentencia"
            auto-grow
            name="Sentencia SQL"
            v-validate="'required|integridadSQL'"
            :error-messages="errors.collect('Sentencia SQL')"
          />
        </v-flex>
      </v-layout>
      <v-card class="my-3">
        <v-toolbar dense>
          <v-toolbar-title>
            <v-icon>settings_input_component</v-icon>
            Configuración de variables
          </v-toolbar-title>
        </v-toolbar>
        <v-form @submit.prevent="" data-vv-scope="tablaVariables">
          <v-data-table
            :items="item.variables"
            :headers="headers"
            class="elevation-1"
            hide-actions
            no-data-text="No hay variables generadas para configurar"
          >
            <template slot="items" slot-scope="props">
              <td>{{ props.item.ref }}</td>
              <td>
                <v-text-field
                  class="mb-2"
                  :key="'label' + props.index"
                  :name="'label' + props.index"
                  :placeholder="'Label control de la referencia ' + props.item.ref"
                  data-vv-as="Label control"
                  v-validate="'required'"
                  v-model="props.item.label"
                  :error-messages="errors.collect('label' + props.index)"
                />
              </td>
              <td>
                <v-select
                  :items="tiposControl"
                  item-text="nombre"
                  item-value="id"
                  single-line
                  class="mb-2"
                  :key="'type' + props.index"
                  :name="'type' + props.index"
                  :placeholder="'Tipo control de la referencia ' + props.item.ref"
                  data-vv-as="Tipo control"
                  v-validate="'required'"
                  v-model="props.item.type"
                  :error-messages="errors.collect('type' + props.index)"
                />
              </td>
              <td>
                <v-select
                  :disabled="props.item.type !== 'postulador'"
                  :items="getApis"
                  item-text="nombre"
                  item-value="api"
                  single-line
                  class="mb-2"
                  :key="'api' + props.index"
                  :name="'api' + props.index"
                  :placeholder="'API de la referencia ' + props.item.ref"
                  data-vv-as="API"
                  v-validate="'required'"
                  v-model="props.item.entidades"
                  :error-messages="errors.collect('api' + props.index)"
                  @change="() => (props.item.type !== 'postulador' ? (props.item.entidades = null) : props.item.entidades)"
                />
              </td>
            </template>
          </v-data-table>
        </v-form>
      </v-card>
      <v-layout row wrap>
        <v-flex xs12 sm12 md12>
          <v-autocomplete
            color="accent"
            class="v-chips"
            v-model="item.roles"
            :items="listaRoles"
            item-text="nombre"
            item-value="id"
            label="Roles Autorizados"
            chips
            multiple
            deletable-chips
            small-chips
            name="Roles Autorizados"
            v-validate="'required'"
            :error-messages="errors.collect('Roles Autorizados')"
          />
        </v-flex>
      </v-layout>
      <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent
                  :max-width="consulta && consulta.variables && consulta.variables.length <= 2 ? '600px' : '900px'">
          <v-card>
            <loading v-model="loadingPrueba"/>
            <v-toolbar dense>
              <v-toolbar-title>
                <v-icon>settings_applications</v-icon>
                Prueba de consulta SQL
              </v-toolbar-title>
            </v-toolbar>
            <v-card-text>
              <v-form data-vv-scope="formPrueba" @submit.prevent="ejecutarConsulta">
                <v-container grid-list-md v-if="consulta && consulta.variables && consulta.variables.length">
                  <v-layout wrap>
                    <v-flex v-for="(input, index) in consulta.variables" :key="'input' + index" xs12 sm6
                            :md4="consulta.variables.length > 2" :md6="consulta.variables.length === 2"
                            :md12="consulta.variables.length === 1">
                      <template v-if="input.type && input.label">
                        <v-text-field
                          v-if="input.type === 'text'"
                          :key="'input-text' + index"
                          :name="'input-text' + index"
                          :label="input.label"
                          :data-vv-as="input.label"
                          v-validate="'required'"
                          v-model="input[input.ref]"
                          :error-messages="errors.collect('input-text' + index)"
                          @input="reloadTextConsulta"
                        />
                        <v-text-field
                          v-if="input.type === 'number'"
                          :key="'input-number' + index"
                          :name="'input-number' + index"
                          :label="input.label"
                          type="number"
                          :data-vv-as="input.label"
                          v-validate="'decimal|required'"
                          v-model.number="input[input.ref]"
                          :error-messages="errors.collect('input-number' + index)"
                          @input="reloadTextConsulta"
                        />
                        <v-menu
                          v-if="input.type === 'date'"
                          :key="'menu-date' + index"
                          :ref="'menuDate' + index"
                          v-model="input.menu"
                          :return-value.sync="input[input.ref]"
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
                            :label="input.label"
                            v-model="input[input.ref]"
                            prepend-icon="event"
                            :name="'input-menuDate' + index"
                            :data-vv-as="input.label"
                            v-validate="'required|date_format:YYYY-MM-DD|date_between:' + minDate + ',' + maxDate + ',true'"
                            :error-messages="errors.collect('input-menuDate' + index)"
                            readonly
                          />
                          <v-date-picker
                            locale="es-co"
                            v-model="input[input.ref]"
                            :max="maxDate"
                            :min="minDate"
                            @input="$refs['menuDate' + index][0].save(input[input.ref])"
                            @change="() => {
                            let indexy = $validator.errors.items.findIndex(x => x.field === ('input-menuDate' + index))
                            $validator.errors.items.splice((indexy !== -1) ? indexy : 0, (indexy !== -1) ? 1 : 0)
                            reloadTextConsulta()
                        }"
                          />
                        </v-menu>
                        <v-datetime-picker
                          v-if="dialog && input.type === 'dateTime'"
                          :key="'dateTime' + index"
                          :label="input.label"
                          :datetime="moment().format('YYYY-MM-DD HH:mm')"
                          @input="val => {
                            input[input.ref] = val
                            reloadTextConsulta()
                          }"
                          :timeData="input[input.ref]"
                        />
                        <v-menu
                          v-if="input.type === 'period'"
                          :key="'menu-period' + index"
                          :ref="'menuPeriod' + index"
                          v-model="input.menu"
                          :return-value.sync="input[input.ref]"
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
                            :label="input.label"
                            v-model="input[input.ref]"
                            prepend-icon="event"
                            :name="'input-menuPeriod' + index"
                            :data-vv-as="input.label"
                            v-validate="'required|date_format:YYYY-MM|date_between:' + moment(minDate).format('YYYY-MM') + ',' + moment(maxDate).format('YYYY-MM') + ',true'"
                            :error-messages="errors.collect('input-menuPeriod' + index)"
                            readonly
                          />
                          <v-date-picker
                            locale="es-co"
                            v-model="input[input.ref]"
                            :max="maxDate"
                            :min="minDate"
                            type="month"
                            @input="$refs['menuPeriod' + index][0].save(input[input.ref])"
                            @change="() => {
                            let indexy = $validator.errors.items.findIndex(x => x.field === ('input-menuPeriod' + index))
                            $validator.errors.items.splice((indexy !== -1) ? indexy : 0, (indexy !== -1) ? 1 : 0)
                            reloadTextConsulta()
                        }"
                          />
                        </v-menu>
                        <postulador-v3
                          v-if="input.type === 'postulador' && input.entidades === 'entidades'"
                          no-data-text="Búsqueda por razon social o código de habilitación."
                          :hint="`${input.objEnt ? ('Código habilitación: ' + input.objEnt.cod_habilitacion) : ''}`"
                          item-text="nombre"
                          :label="input.label"
                          route="entidades"
                          model="entidades"
                          filter="nombre,cod_habilitacion"
                          prepend-icon="fas fa-clinic-medical"
                          v-model="input.objEnt"
                          :name="input.label"
                          v-validate="'required'"
                          :error-messages="errors.collect('prestador autorizado')"
                          :slot-data='{
                              template: `
                                <v-list-tile class="content-v-list-tile-p0">
                                  <v-list-tile-content>
                                    <v-list-tile-title>{{value.nombre}}</v-list-tile-title>
                                    <v-list-tile-sub-title>Código habilitación: {{value.cod_habilitacion}}</v-list-tile-sub-title>
                                  </v-list-tile-content>
                                </v-list-tile>
                              `,
                              props: [`value`]
                            }'
                          @input="val => {
                            input[input.ref] = val ? val.id : ''
                            reloadTextConsulta()
                          }"
                        ></postulador-v3>
                        <postulador-v2
                          v-if="input.type === 'postulador' && input.entidades === 'afiliados'"
                          :no-data="`Búsqueda de ${input.label}.`"
                          hint="identificacion_completa"
                          item-text="nombre_completo"
                          data-title="identificacion_completa"
                          data-subtitle="nombre_completo"
                          :label="input.label"
                          preicon="person"
                          :entidad="input.entidades"
                          v-model="input.objEnt"
                          @changeid="val => input[input.ref] = val"
                          :name="input.label"
                          rules="required"
                          v-validate="'required'"
                          :error-messages="errors.collect(input.label)"
                          no-btn-edit
                          no-btn-create
                          @input="reloadTextConsulta"
                        ></postulador-v2>
                        <v-autocomplete
                          v-if="(input.type === 'postulador') && (input.entidades === 'municipios')"
                          :label="input.label"
                          item-value="id"
                          item-text="nombre"
                          v-model="input.objEnt"
                          :items="municipios"
                          :hint="input.objEnt ? ('Departamento: ' + input.objEnt.nombre_departamento) : null"
                          persistent-hint
                          v-validate="'required'"
                          :error-messages="errors.collect(input.label)"
                          :filter="filterMunicipios"
                          @change="val => postuladorInput(val, input)"
                          return-object
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
                      </template>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-form>
              <span>Consulta:</span>
              <p class="caption grey--text text--darken-2" v-if="consulta && consulta.sentenciaGo">
                {{ consulta.sentenciaGo }}</p>
            </v-card-text>
            <v-divider/>
            <v-card-actions>
              <v-btn flat @click="dialog = false">Cerrar</v-btn>
              <v-spacer/>
              <v-btn color="blue darken-1" flat @click="ejecutarConsulta">Ejecutar prueba</v-btn>
              <v-btn color="blue darken-1" flat @click="ejecutarReporte">Ejecutar reporte</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout>
      <form method="post" :action="urlReporte" id="formReporte" target="_blank" hidden>
        <textarea name="sentencia" style="width: 1000px !important;">
          {{ consulta && consulta.sentenciaGo ? consulta.sentenciaGo : '' }}
        </textarea>
      </form>
    </v-container>
    <v-divider/>
    <v-card-actions>
      <v-btn @click="refresh">Limpiar</v-btn>
      <v-spacer/>
      <v-btn @click="pruebaConsulta" color="primary">Realizar prueba</v-btn>
      <v-btn @click="submit" color="primary">Guardar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import Loading from '@/components/general/Loading'
import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
import {REPORTE_RELOAD_ITEM} from '@/store/modules/general/tables'
import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
import {Validator} from 'vee-validate'
import store from '@/store/complementos/index'

export default {
  name: 'RegistroReporte',
  props: ['parametros'],
  components: {
    PostuladorV2: () => import('@/components/general/PostuladorV2'),
    InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
    InputFile: () => import('@/components/general/InputFile'),
    VDatetimePicker: () => import('@/components/general/VDatetimePicker'),
    Loading
  },
  filters: {},
  data () {
    return {
      focusCOdemirror: false,
      cmOptions: {
        tabSize: 4,
        lineWrapping: true,
        styleActiveLine: true,
        lineNumbers: true,
        line: true,
        mode: 'text/x-mysql',
        theme: 'solarized light'
      },
      dialog: false,
      minDate: '1900-01-01',
      maxDate: new Date().toISOString().substr(0, 10),
      loadingPrueba: false,
      loading: false,
      patron: /:(\w+)(?!\w)/g,
      item: null,
      consulta: null,
      makeItem: {
        id: null,
        nombre: null,
        descripcion: null,
        gs_modulo_id: null,
        sentencia: '',
        variables: [],
        roles: [],
        entidades: null,
        objEnt: null
      },
      listaRoles: [],
      listaModulos: [],
      tiposControl: [
        {id: 'text', nombre: 'texto'},
        {id: 'number', nombre: 'Número'},
        {id: 'date', nombre: 'Fecha'},
        {id: 'dateTime', nombre: 'Fecha y hora'},
        {id: 'period', nombre: 'Periodo'},
        {id: 'postulador', nombre: 'Postulador'}
      ],
      headers: [
        {
          text: 'Referencia',
          align: 'left',
          sortable: false
        },
        {
          text: 'Label control',
          align: 'left',
          sortable: false
        },
        {
          text: 'Tipo control',
          align: 'left',
          sortable: false
        },
        {
          text: 'Api',
          align: 'left',
          sortable: false
        }
      ],
      urlReporte: null,
      filterMunicipios (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      }
    }
  },
  watch: {
    'item.sentencia' () {
      this.reloadVariables()
    }
  },
  computed: {
    getApis () {
      return [
        {api: 'municipios', nombre: 'Municipios'},
        {api: 'entidades', nombre: 'Entidades'},
        {api: 'afiliados', nombre: 'Afiliados'}
      ]
    },
    municipios () {
      return store.getters.complementosFormAfiliados.municipios
    }
  },
  created () {
    this.getRoles()
    this.refresh()
    this.parametros.entidad && this.parametros.entidad.id && this.getData()
  },
  beforeMount () {
    Validator.extend('integridadSQL', {
      validate: (value) => new Promise((resolve) => {
        if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
          const exprecion = /(DELETE |CREATE |ALTER |DROP |RENAME |TRUNCATE |CALL |IMPORT |INSERT |REPLACE |LOAD |UPDARE |GRANT)/i
          // const exprecion2 = /^(?=.*(SELECT).*(FROM)).*$/i
          let response = exprecion.test(value)
            // let response = exprecion.test(value) || !exprecion2.test(value)
            ? {
              valido: false,
              mensaje: exprecion.test(value) ? `La consulta no está permitida.` : `La consulta no es valida.`
            }
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
  methods: {
    postuladorInput (val, input) {
      input[input.ref] = val ? val.id : null
      this.reloadTextConsulta()
    },
    getRoles () {
      this.axios.get('gs_roles')
        .then((response) => {
          if (response.data !== '') {
            this.listaRoles = response.data.data.roles
            this.listaModulos = response.data.data.modulos
          }
        })
        .catch(e => {
          this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer los roles. `, error: e})
        })
    },
    ejecutarConsulta () {
      this.$validator.validateAll('formPrueba').then(result => {
        if (result) {
          this.loadingPrueba = true
          this.axios.post(`reporte/probar`, {consulta: this.consulta.sentenciaGo})
            .then((response) => {
              if (response.data !== '') {
                this.$store.commit(SNACK_SHOW, {msg: 'La consulta se está ejecutando correctamente.', color: 'success'})
                this.loadingPrueba = false
              }
            })
            .catch(e => {
              this.loadingPrueba = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ` al ejecutar la consulta. `, error: e})
            })
        }
      })
    },
    ejecutarReporte () {
      this.loadingPrueba = true
      this.axios.get('firmar-ruta?nombre_ruta=ejecutarReporte')
        .then((response) => {
          if (response.data !== '') {
            this.urlReporte = response.data
            setTimeout(() => {
              document.getElementById('formReporte').submit()
            }, 500)
            this.loadingPrueba = false
          }
        })
        .catch(e => {
          this.loadingPrueba = false
          this.$store.commit(SNACK_ERROR_LIST, {expression: ` al ejecutar el reporte. `, error: e})
        })
    },
    reloadTextConsulta () {
      let sentenciaTemporal = JSON.parse(JSON.stringify(this.consulta.sentencia))
      this.consulta.variables.forEach(x => {
        sentenciaTemporal = sentenciaTemporal.replace(new RegExp(x.ref, 'g'), x.type !== 'number' ? ('"' + x[x.ref] + '"') : x[x.ref])
      })
      this.consulta.sentenciaGo = sentenciaTemporal
    },
    pruebaConsulta () {
      this.$validator.validateAll('tablaVariables').then(result => {
        if (result && this.item && this.item.sentencia && !this.$validator.errors.items.find(x => x.field === 'Sentencia SQL')) {
          this.consulta = JSON.parse(JSON.stringify(this.item))
          this.$set(this.consulta, 'sentenciaGo', JSON.parse(JSON.stringify(this.item.sentencia)))
          this.consulta.variables.forEach(x => {
            x[x.ref] = null
            if (x.type === 'date' || x.type === 'period') this.$set(x, 'menu', false)
          })
          this.dialog = true
        }
      })
    },
    validaVariables () {
      return ((!!this.item.sentencia.match(this.patron) && this.item.variables.length) || (!this.item.sentencia.match(this.patron) && !this.item.variables.length))
    },
    async submit () {
      let errors = await this.$validator.validateAll()
      let errorVariables = this.validaVariables()
      let errorVariablesTabla = await this.$validator.validateAll('tablaVariables')
      if (errors && errorVariables && errorVariablesTabla) {
        this.loading = true
        let typeRequest = this.item.id ? 'editar' : 'crear'
        let request = typeRequest === 'crear' ? this.axios.post(`reportes`, this.item) : this.axios.put(`reportes/${this.item.id}`, this.item)
        request
          .then(response => {
            this.$store.commit(REPORTE_RELOAD_ITEM, {item: response.data, estado: 'reload', key: this.parametros.key})
            this.$store.commit(SNACK_SHOW, {msg: 'El reporte se ha guardado correctamente.', color: 'success'})
            this.loading = false
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, {contexto: this, itemId: this.parametros.tabOrigin})
          }).catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: `al guardar la configuración del reporte.`, error: e})
          })
      }
    },
    reloadVariables () {
      let campos = window.lodash.uniq(this.item.sentencia.match(this.patron))
      if (campos) {
        let variablesGeneradas = []
        campos.forEach(x => {
          variablesGeneradas.push({ref: x, type: null, label: null})
        })
        let variablesNuevas = this.getVariablesNuevas(variablesGeneradas)
        if (variablesNuevas.length) this.item.variables.length ? this.item.variables = this.item.variables.concat(variablesNuevas) : this.item.variables = variablesNuevas
        this.removeVariables(variablesGeneradas)
      } else {
        this.item.variables = []
      }
    },
    removeVariables (variablesGeneradas) {
      if (this.item.variables.length) {
        this.item.variables = this.item.variables.filter(x => {
          return window.lodash.includes(variablesGeneradas.map(z => z.ref), x.ref)
        })
      }
    },
    getVariablesNuevas (variablesGeneradas) {
      if (this.item.variables.length) {
        let retorno = variablesGeneradas.filter(z => {
          return !window.lodash.includes(this.item.variables.map(x => x.ref), z.ref)
        })
        return !retorno ? [] : retorno
      } else {
        return variablesGeneradas
      }
    },
    async getData () {
      this.loading = true
      this.axios.get(`reportes/${this.parametros.entidad.id}`)
        .then((response) => {
          if (response.data !== '') {
            response.data.data.roles = response.data.data.roles.map(x => {
              return x.id
            })
            this.item = response.data.data
            setTimeout(() => {
              this.$validator.validateAll()
            }, 200)
            this.loading = false
          }
        })
        .catch(e => {
          this.loading = false
          this.$store.commit(SNACK_ERROR_LIST, {
            expression: ` al traer los datos de la configuración del reporte. `,
            error: e
          })
        })
    },
    refresh () {
      this.item = JSON.parse(JSON.stringify(this.makeItem))
      this.$validator.reset()
    }
  }
}
</script>

<style>
.table-detalle-solicitud table tbody td input {
  text-align: center !important;
}
</style>
