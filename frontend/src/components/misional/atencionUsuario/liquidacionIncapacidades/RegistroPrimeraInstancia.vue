<template>
  <div>
    <v-card ref="formLiquidacion">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar--dense">
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
        <v-spacer></v-spacer>
        <div v-if="liquidacion.id != null">
          <v-chip label color="white" text-color="red" dark absolute right top>
            <span class="subheading">N°</span>&nbsp;
            <span class="subheading" v-text="liquidacion.numeroDocumento"></span>
          </v-chip>
        </div>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap>
          <v-flex xs12>
            <v-form data-vv-scope="formLiquidaciones">
              <v-container fluid grid-list-md>
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon> General</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm8>
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
                              v-model="liquidacion.afiliado"
                              @changeid="val => obtenerRelacionesLaborales(val)"
                              name="Afiliado"
                              :rules="'required'"
                              v-validate="'required'"
                              :error-messages="errors.collect('Afiliado')"
                              :slot-append='{
                                template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,
                                props: [`value`]
                               }'
                              :slot-prepend='liquidacion.afiliado ? {
                                template:`<mini-card-detail :data="value.mini_afiliado" alone-icon/>`,
                                props: [`value`]
                               } : null'
                              no-btn-edit
                            />
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="relacionesLaborales"
                              v-model="liquidacion.as_afiliado_pagador_id"
                              item-value="id"
                              item-text="pagador.razon_social"
                              label="Relación laboral"
                              name="relacion laboral"
                              :no-data-text="textoRelacionLaboral"
                              :error-messages="errors.collect('relacion laboral')"
                              v-validate="'required'" required
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.pagador.razon_social"/>
                                    <v-list-tile-sub-title v-html="data.item.fecha_vinculacion"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-select>
                          </v-flex>

                          <v-flex xs12 sm8>
                            <v-select
                              :items="incapacidades"
                              v-model="prestacion"
                              item-text="tipo"
                              label="Tipo de prestación"
                              name="tipoPrestacion"
                              prepend-icon="assignment"
                              return-object
                              :error-messages="errors.collect('tipoPrestacion')"
                              v-validate="'required'" required
                              :disabled="!liquidacion.as_afiliados_id"
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="prestacion.tipos"
                              v-model="tipoIncapacidad"
                              item-value="id"
                              item-text="tipo"
                              @change="asignarTipincapacidadesid"
                              label="Tipo de incapacidad"
                              name="tipo incapacidad"
                              return-object
                              :error-messages="errors.collect('tipo incapacidad')"
                              v-validate="'required'" required
                              :disabled="!liquidacion.as_afiliados_id"
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4 v-if="esIncapacidadPrematuro">
                            <v-text-field v-model="liquidacion.dias_prematuro" prepend-icon="calendar_today"
                                          label="Días prematuro" key="dias prematuro"
                                          name="dias prematuro" v-validate="'required|numeric'" required
                                          :error-messages="errors.collect('dias prematuro')">

                            </v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4 v-if="esProrroga">
                            <v-autocomplete
                              label="Incapacidad anterior"
                              v-model="liquidacion.incapacidad_id"
                              :items="incapacidadesAnteriores"
                              item-text="tipo"
                              item-value="id"
                              name="incapacidad anterior"
                              no-data-text="No existen datos"
                              prepend-icon="restore"
                              required
                              v-validate="'required'" required
                              :error-messages="errors.collect('incapacidad anterior')">

                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="'Periodo: ' + data.item.tipo"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="liquidacion.dias_incapacidad" prepend-icon="calendar_today"
                                          label="Días incapacidad" key="dias incapacidad" :disabled="!!tipoIncapacidad.dias"
                                          name="dias incapacidad" v-validate="'required|numeric|between:' + tipoIncapacidad.min_dias + ',' + tipoIncapacidad.max_dias" required
                                          :error-messages="errors.collect('dias incapacidad')">

                            </v-text-field>
                          </v-flex>

                        </v-layout>

                        <v-layout row wrap v-if="prestacion.codigo === licenciaDeMaternidad">

                          <v-flex xs12 sm4>
                            <v-select
                              :items="medidasGestacion"
                              v-model="medidaGestacion"
                              label="Gestación"
                              name="medida"
                              prepend-icon="event_note"
                              :error-messages="errors.collect('medida')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model.number="diasGestacion"
                                          :label="medidaGestacion" key="dias valor"
                                          name="dias valor" v-validate="'required|numeric|intervaloDiasValidos'"
                                          required
                                          :error-messages="errors.collect('dias valor')">

                            </v-text-field>
                          </v-flex>

                        </v-layout>

                        <v-layout row wrap>

                          <v-flex xs12 sm8>
                            <postulador-v2
                              no-data="Busqueda por código, descripcion y genero"
                              hint="codigo"
                              item-text="descripcion"
                              data-title="codigo"
                              data-subtitle="descripcion"
                              label="Diagnóstico"
                              entidad="rs_cie10s"
                              preicon="reorder"
                              @changeid="val => liquidacion.rs_cie10_id = val"
                              v-model="liquidacion.cie10"
                              name="diagnóstico"
                              rules="required"
                              v-validate="'required'"
                              :error-messages="errors.collect('diagnóstico')"
                              no-btn-create
                              :min-characters-search="3" clearable
                            />
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-menu
                              ref="menuFechaInicio"
                              :close-on-content-click="false"
                              v-model="menuFechaInicio"
                              :nudge-right="40"
                              lazy
                              transition="scale-transition"
                              offset-y
                              full-width
                              max-width="290px"
                              min-width="290px"
                            >
                              <v-text-field
                                slot="activator"
                                v-model="computedDateFormattedFechaInicio"
                                label="Inicio incapacidad"
                                prepend-icon="event"
                                readonly
                                name="inicio incapacidad"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('inicio incapacidad')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="liquidacion.fecha_inicio"
                                @input="menuFechaInicio = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'inicio incapacidad')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                }"
                                locale='es'
                                no-title
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="tiposPagar_a"
                              v-model="liquidacion.pagar_a"
                              label="Pagar a"
                              name="pagar a"
                              prepend-icon="account_balance"
                              :error-messages="errors.collect('pagar a')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>
                        </v-layout>



                        <v-layout row wrap>

                          <v-flex xs12 sm4>
                            <v-layout align-center fill-height>
                              <v-flex d-flex xs10 sm8>
                                <input-file
                                  ref="historiaClinica"
                                  label="Historia clínica"
                                  v-model="liquidacion.histclinica"
                                  :file-name="liquidacion.histclinica ? liquidacion.histclinica.nombre : null"
                                  required
                                  accept="application/pdf"
                                  :hint="'Extenciones aceptadas: pdf'"
                                  class="mb-3"
                                  prepend-icon="attach_file"
                                />
                              </v-flex>
                              <v-flex d-flex xs2 sm4 v-if="liquidacion.histclinica && liquidacion.histclinica.url_signed">
                                <v-tooltip bottom>
                                  <v-btn
                                    fab
                                    color="success"
                                    small
                                    :href="liquidacion.histclinica ? liquidacion.histclinica.url_signed : ''"
                                    target="_blank"
                                    slot="activator"
                                  >
                                    <v-icon>remove_red_eye</v-icon>
                                  </v-btn>
                                  <span>Ver archivo</span>
                                </v-tooltip>
                              </v-flex>
                            </v-layout>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-layout align-center fill-height>
                              <v-flex d-flex xs10 sm8>
                                <input-file
                                  ref="incapacidad"
                                  label="Incapacidad"
                                  v-model="liquidacion.incapacidad"
                                  :file-name="liquidacion.incapacidad ? liquidacion.incapacidad.nombre : null"
                                  required
                                  accept="application/pdf"
                                  :hint="'Extenciones aceptadas: pdf'"
                                  class="mb-3"
                                  prepend-icon="attach_file"
                                />
                              </v-flex>
                              <v-flex d-flex xs2 sm4 v-if="liquidacion.incapacidad && liquidacion.incapacidad.url_signed">
                                <v-tooltip bottom>
                                  <v-btn
                                    fab
                                    color="success"
                                    small
                                    :href="liquidacion.incapacidad ? liquidacion.incapacidad.url_signed : ''"
                                    target="_blank"
                                    slot="activator"
                                  >
                                    <v-icon>remove_red_eye</v-icon>
                                  </v-btn>
                                  <span>Ver archivo</span>
                                </v-tooltip>
                              </v-flex>
                            </v-layout>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-layout align-center fill-height>
                              <v-flex d-flex xs10 sm8>
                                <input-file
                                  ref="certificacionBancaria"
                                  label="Certificación bancaria"
                                  v-model="liquidacion.certbanco"
                                  :file-name="liquidacion.certbanco ? liquidacion.certbanco.nombre : null"
                                  required
                                  accept="application/pdf"
                                  :hint="'Extenciones aceptadas: pdf'"
                                  class="mb-3"
                                  prepend-icon="attach_file"
                                />
                              </v-flex>
                              <v-flex d-flex xs2 sm4 v-if="liquidacion.certbanco && liquidacion.certbanco.url_signed">
                                <v-tooltip bottom>
                                  <v-btn
                                    fab
                                    color="success"
                                    small
                                    :href="liquidacion.certbanco ? liquidacion.certbanco.url_signed : ''"
                                    target="_blank"
                                    slot="activator"
                                  >
                                    <v-icon>remove_red_eye</v-icon>
                                  </v-btn>
                                  <span>Ver archivo</span>
                                </v-tooltip>
                              </v-flex>
                            </v-layout>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-layout align-center fill-height>
                              <v-flex d-flex xs10 sm8>
                                <input-file
                                  ref="archivo_identificacion"
                                  label="Identificación"
                                  v-model="liquidacion.archivo_identificacion"
                                  :file-name="liquidacion.archivo_identificacion ? liquidacion.archivo_identificacion.nombre : null"
                                  required
                                  accept="application/pdf"
                                  :hint="'Extenciones aceptadas: pdf'"
                                  class="mb-3"
                                  prepend-icon="attach_file"
                                />
                              </v-flex>
                              <v-flex d-flex xs2 sm4>
                                <v-tooltip bottom v-if="liquidacion.archivo_identificacion && liquidacion.archivo_identificacion.url_signed">
                                  <v-btn
                                    fab
                                    color="success"
                                    small
                                    :href="liquidacion.archivo_identificacion ? liquidacion.archivo_identificacion.url_signed : ''"
                                    target="_blank"
                                    slot="activator"
                                  >
                                    <v-icon>remove_red_eye</v-icon>
                                  </v-btn>
                                  <span>Ver archivo</span>
                                </v-tooltip>
                              </v-flex>
                            </v-layout>
                          </v-flex>

                        </v-layout>

                      </v-card-text>
                    </v-card>
                  </v-flex>

                </v-layout>
              </v-container>

            </v-form>
          </v-flex>

        </v-layout>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="formReset" :loading="loadingSubmit">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn color="primary" @click.prevent="submit" :loading="loadingSubmit" :disabled="errors.any()">Guardar</v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {LIQUIDACION_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import {SNACK_ERROR_LIST} from '../../../../store/modules/general/snackbar'
  import { Validator } from 'vee-validate'

  export default {
    name: 'RegistroPrimeraInstancia',
    props: ['parametros'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputFile: () => import('@/components/general/InputFile')
    },
    data () {
      return {
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
        liquidacion: {},
        prestacion: {},
        tiposPagar_a: ['Afiliado', 'Aportante'],
        tiposEstado: ['Liquidada', 'Aprobada', 'Negada', 'Pagada'],
        medidasGestacion: ['Días', 'Semanas'],
        idsProrrogas: [4, 6, 7, 16],
        esProrroga: false,
        relacionesLaborales: [],
        incapacidadesAnteriores: [],
        incapacidades: [],
        tiposIncapacidadMaternidad: [],
        menuFechaInicio: false,
        menuFechaFin: false,
        loadingSubmit: false,
        payload: null,
        licenciaDeMaternidad: 1,
        tipoIncapacidad: {},
        medidaGestacion: 'Días',
        diasGestacion: 0,
        mostrarIncapacidadesAnteriores: false,
        opcionesMasculino: 2,
        totalOpcionesIncapacidad: 10,
        esIncapacidadPrematuro: false,
        textoRelacionLaboral: 'No ha seleccionado un afiliado'
      }
    },
    created () {
      this.axios.get('tipincapacidades')
        .then((res) => {
          this.incapacidades = res.data
          this.tiposIncapacidadMaternidad = this.incapacidades[0].tipos
        })
        .catch((e) => {
          console.log('Hay un error. ' + e)
        })
    },
    beforeMount () {
      this.formReset()
      this.validadorDiasGestacion()
    },
    mounted () {
      if (this.parametros.entidad !== null) {
        this.getRegisto(this.parametros.entidad.id)
      }
    },
    watch: {
      'medidaGestacion' () {
        this.diasGestacion = null
        this.liquidacion.dias_gestacion = null
      },
      'diasGestacion' () {
        if (this.medidaGestacion === 'Semanas') this.liquidacion.dias_gestacion = this.diasGestacion * 7
        if (this.medidaGestacion === 'Días') this.liquidacion.dias_gestacion = this.diasGestacion
      },
      'liquidacion.au_tipincapacidades_id' (value) {
        this.esProrroga = this.idsProrrogas.includes(value)
        if (this.esProrroga) {
          this.mostrarIncapacidadesAnteriores = true
          this.obtenerIncapacidadesAnteriores()
        }
      },
      'liquidacion.afiliado' (value) {
        if (value) {
          if (this.liquidacion.afiliado.gn_sexo_id === 2) {
            this.incapacidades[0].tipos = this.tiposIncapacidadMaternidad.filter(tipo => {
              return tipo.id === 2 || tipo.id === 15
            })
          }
          if (this.liquidacion.afiliado.gn_sexo_id === 1) {
            this.incapacidades[0].tipos = this.tiposIncapacidadMaternidad.filter(tipo => {
              return tipo.id !== 2 && tipo.id !== 15
            })
          }
        }
      },
      'tipoIncapacidad' () {
        const idsIncapacidadesPrematuro = [9, 11, 13, 14]
        this.esIncapacidadPrematuro = idsIncapacidadesPrematuro.includes(this.tipoIncapacidad.id)
      }
    },
    computed: {
      tapTitulo () {
        return !this.liquidacion.id ? 'Nuevo Item' : 'Edición de : ' + this.str_pad(this.liquidacion.consecutivo, 6)
      },
      computedDateFormattedFechaInicio () {
        return this.formDate(this.liquidacion.fecha_inicio)
      }
    },
    methods: {
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.formLiquidacion.$el
        })
        this.axios.get('incapacidades/' + id)
          .then((response) => {
            if (response.data !== '') {
              this.liquidacion = response.data.incapacidad
              this.tipoIncapacidad = response.data.incapacidad.tipo_incapacidad
              this.prestacion = this.tipoIncapacidad.prestacion
              this.diasGestacion = this.liquidacion.dias_gestacion
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la incapacidad. ' + e, color: 'danger'})
          })
      },
      formReset () {
        this.liquidacion = {
          id: '',
          as_afiliados_id: null,
          as_afiliado_pagador_id: null,
          au_tipincapacidades_id: null,
          fecha_inicio: null,
          pagar_a: null,
          incapacidad: null,
          histclinica: null,
          certbanco: null,
          archivo_identificacion: null,
          dias_gestacion: null,
          dias_incapacidad: '',
          cie10_ingreso_id: null,
          dias_prematuro: 0
        }
        this.$validator.reset()
      },
      obtenerRelacionesLaborales (id) {
        this.liquidacion.as_afiliados_id = id
        this.textoRelacionLaboral = 'Cargando...'
        this.axios.get(`afiliadoaportantes?as_afiliado_id=${id}`)
          .then((res) => {
            this.relacionesLaborales = res.data.data
            this.textoRelacionLaboral = 'No ha seleccionado un afiliado'
          })
          .catch((e) => {
            console.log('Hay un error. ' + e)
            this.textoRelacionLaboral = 'No existen'
          })
      },
      obtenerIncapacidadesAnteriores () {
        this.axios.get(`incapacidades?as_afiliados_id=${this.liquidacion.as_afiliados_id}`)
          .then(response => {
            this.incapacidadesAnteriores = response.data.data
          })
          .catch(e => {
            console.log(`Hay un error ${e}`)
          })
      },
      asignarTipincapacidadesid () {
        if (this.tipoIncapacidad) {
          this.liquidacion.au_tipincapacidades_id = this.tipoIncapacidad.id
          this.liquidacion.dias_incapacidad = this.tipoIncapacidad.dias
        }
      },
      addFormData () {
        let data = new FormData()
        data.append('id', this.liquidacion.id)
        data.append('as_afiliados_id', this.liquidacion.as_afiliados_id)
        data.append('as_afiliado_pagador_id', this.liquidacion.as_afiliado_pagador_id)
        data.append('au_tipincapacidades_id', this.liquidacion.au_tipincapacidades_id)
        data.append('fecha_inicio', this.liquidacion.fecha_inicio)
        data.append('pagar_a', this.liquidacion.pagar_a)
        data.append('dias_gestacion', this.liquidacion.dias_gestacion)
        data.append('dias_incapacidad', this.liquidacion.dias_incapacidad)
        data.append('dias_prematuro', this.liquidacion.dias_prematuro)
        data.append('rs_cie10_id', this.liquidacion.rs_cie10_id)

        if (this.liquidacion.incapacidad_id) {
          data.append('incapacidad_id', this.liquidacion.incapacidad_id)
        } else {
          data.append('incapacidad_id', null)
        }

        data.append('incapacidad', typeof this.liquidacion.incapacidad === 'undefined' ? '' : this.liquidacion.incapacidad)
        data.append('histclinica', typeof this.liquidacion.histclinica === 'undefined' ? '' : this.liquidacion.histclinica)
        data.append('certbanco', typeof this.liquidacion.certbanco === 'undefined' ? '' : this.liquidacion.certbanco)
        data.append('archivo_identificacion', typeof this.liquidacion.archivo_identificacion === 'undefined' ? '' : this.liquidacion.archivo_identificacion)

        data.append('archivo_incapacidad_id', this.liquidacion.archivo_incapacidad_id)
        data.append('histclinica_id', this.liquidacion.histclinica_id)
        data.append('certbanco_id', this.liquidacion.certbanco_id)
        data.append('archivo_identificacion_id', this.liquidacion.archivo_identificacion_id)

        this.payload = data
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      validadorDiasGestacion () {
        Validator.extend('intervaloDiasValidos', {
          validate: (value, prop) => new Promise((resolve) => {
            let response = {}
            if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
              if (this.medidaGestacion === 'Días') {
                if (this.diasGestacion >= this.tipoIncapacidad.min_dias_gestacion && this.diasGestacion <= this.tipoIncapacidad.max_dias_gestacion) {
                  response = {valido: true, mensaje: null}
                } else {
                  response = {valido: false, mensaje: `El valor debe encontrarse entre ${this.tipoIncapacidad.min_dias_gestacion} y ${this.tipoIncapacidad.max_dias_gestacion} días`}
                }
              }

              if (this.medidaGestacion === 'Semanas') {
                let semanasADias = this.diasGestacion * 7
                if (semanasADias >= this.tipoIncapacidad.min_dias_gestacion && semanasADias <= this.tipoIncapacidad.max_dias_gestacion) {
                  response = {valido: true, mensaje: null}
                } else {
                  response = {valido: false, mensaje: `El valor debe encontrarse entre ${this.tipoIncapacidad.min_dias_gestacion} y ${this.tipoIncapacidad.max_dias_gestacion} días`}
                }
              }

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
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      async submit () {
        if (await this.validador('formLiquidaciones')) {
          this.loadingSubmit = true
          this.addFormData()

          const typeRequest = this.liquidacion.id ? 'editar' : 'crear'
          let send = !this.liquidacion.id ? this.axios.post('incapacidades', this.payload) : this.axios.post('incapacidades/' + this.liquidacion.id, this.payload)
          send.then(response => {
            if (this.liquidacion.id) {
              this.$store.commit(SNACK_SHOW, {msg: 'Item actualizado satisfactoriamente', color: 'success'})
              this.$store.commit(LIQUIDACION_RELOAD_ITEM, {item: response.data.incapacidad_o, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            } else {
              this.$store.commit(SNACK_SHOW, {msg: 'Item creado satisfactoriamente', color: 'success'})
              this.$store.commit(LIQUIDACION_RELOAD_ITEM, {item: response.data.incapacidad_o, estado: typeRequest, key: this.parametros.key})
              this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            }
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: `:`, error: e})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Los campos no pueden estar vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>
</style>
