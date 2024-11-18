<template>
  <div>
    <v-card ref="formPQRS">
      <v-toolbar class="grey lighten-3 elevation-0 toolbar">
        <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
      </v-toolbar>
      <v-container grid-list-md style="max-width: inherit">
        <v-layout row wrap >
          <v-flex xs12>
            <v-form data-vv-scope="formPQRSS">
              <v-container fluid grid-list-md>
                <v-layout row wrap>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>people</v-icon> Actores</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="tipoPeticion"
                              v-model="pqrs.actores"
                              label="Seleccionar..."
                              name="actores"
                              :error-messages="errors.collect('actores')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="tiposFuente"
                              v-model="pqrs.fuente"
                              label="Fuente"
                              name="fuente"
                              :error-messages="errors.collect('fuente')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>person</v-icon> Datos afiliado</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm8 v-if="pqrs.tipo_usuario === 'Afiliado'">
                            <v-subheader class="pa-0 ma-0">Afiliado</v-subheader>
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
                              v-model="pqrs.afiliado"
                              @changeid="val => pqrs.as_afiliado_id = val"
                              name="Afiliado"
                              :rules="'required'"
                              v-validate="'required'"
                              :error-messages="errors.collect('Afiliado')"
                              :slot-append='{
                                template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,
                                props: [`value`]
                               }'
                              :slot-prepend='pqrs.afiliado ? {
                                template:`<mini-card-detail :data="value.mini_afiliado" alone-icon/>`,
                                props: [`value`]
                               } : null'
                              no-btn-edit
                            />
                          </v-flex>
                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>person</v-icon> Entidad afectada</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm6>
                            <postulador-v2
                              no-data="Busqueda por NIT, razon social u código de habilitación."
                              hint="nombre"
                              item-text="nombre"
                              data-title="nombre"
                              data-subtitle="nombre"
                              label="Entidad"
                              entidad="entidades"
                              preicon="location_city"
                              @changeid="val => pqrs.rs_entidad_id = val"
                              v-model="pqrs.entidad"
                              name="entidad"
                              rules="required"
                              v-validate="'required'"
                              :error-messages="errors.collect('entidad')"
                              no-btn-create
                              :min-characters-search="3"
                            />
                          </v-flex>


                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_ind</v-icon>Remitente</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4>
                            <v-autocomplete
                              label="Tipo Documento"
                              v-model="pqrs.gn_tipdocidentidad_id"
                              :items="complementos.tipdocidentidades"
                              item-value="id"
                              item-text="nombre"
                              name="tipo de documento"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('tipo de documento')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="pqrs.identificacion"
                                          label="No. Identificación" key="identificacion"
                                          name="identificacion" v-validate="'required'" required
                                          :error-messages="errors.collect('identificacion')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-autocomplete
                              label="Municipio"
                              v-model="pqrs.gn_municipio_id"
                              :items="complementosMunicipios.municipios"
                              item-value="id"
                              item-text="nombre"
                              name="municipio"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('municipio')"
                              :filter="filterMunicipios">

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

                          <v-flex xs12 sm4>
                            <v-text-field v-model="pqrs.nombres"
                                          label="Nombres" key="nombres"
                                          name="nombres" v-validate="'required'" required
                                          :error-messages="errors.collect('nombres')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="pqrs.apellidos"
                                          label="Apellidos" key="apellidos" :disabled="pqrs.gn_tipdocidentidad_id == 12"
                                          name="apellidos" v-validate="'required'" required
                                          :error-messages="errors.collect('apellidos')" ></v-text-field>
                          </v-flex>


                          <v-flex xs12 sm4>
                            <v-text-field v-model="pqrs.direccion"
                                          label="Dirección" key="direccion" required
                                          name="direccion" v-validate="'required'" required
                                          :error-messages="errors.collect('direccion')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="pqrs.telefono"
                                          label="Teléfono" key="telefono"
                                          name="telefono" v-validate="'required'" required
                                          :error-messages="errors.collect('telefono')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="pqrs.email"
                                          label="Correo electrónico" key="correo electronico"
                                          name="email"
                                          v-validate="'email'"
                                          :error-messages="errors.collect('email')"></v-text-field>
                          </v-flex>
                        </v-layout>

                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment</v-icon> Detalle </v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="pqrs.consecutivo_externo"
                                          label="Consecutivo externo" key="consecutivo"
                                          name="consecutivo" v-validate="'required'" required
                                          :error-messages="errors.collect('consecutivo')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="tiposPqrs"
                              v-model="pqrs.au_tipopqrsd_id"
                              item-value="id"
                              item-text="tipo"
                              label="Tipo"
                              name="tipo"
                              :error-messages="errors.collect('tipo')"
                              v-validate="'required'" required
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.tipo"/>
                                    <v-list-tile-sub-title v-html="data.item.descripcion"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-text-field v-model="pqrs.plazo"
                                          label="Plazo (Días hábiles)" key="plazo"
                                          name="plazo" v-validate="'required'" required disabled
                                          :error-messages="errors.collect('plazo')" ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-select
                              :items="mediosDeRecepcion"
                              v-model="pqrs.medio_recepcion"
                              label="Medio de recepción"
                              name="medio de recepcion"
                              :error-messages="errors.collect('medio de recepcion')"
                              v-validate="'required'" required
                            ></v-select>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-menu
                              ref="menuDate"
                              :close-on-content-click="false"
                              v-model="menuDate"
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
                                v-model="computedDateFormatted"
                                label="Fecha recepción"
                                prepend-icon="event"
                                readonly
                                name="fecha recepción"
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha recepción')"
                              ></v-text-field>
                              <v-date-picker
                                v-model="fechaRecepcion"
                                @input="menuDate = false"
                                @change="() => {
                                  let index = $validator.errors.items.findIndex(x => x.field === 'fecha recepción')
                                  $validator.errors.items.splice((index !== -1) ? index : 0, (index !== -1) ? 1 : 0)
                                }"
                                locale='es'
                                no-title
                                :max="maxDate"
                              ></v-date-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4>
                            <v-menu
                              ref="menuTime"
                              :close-on-content-click="false"
                              v-model="menuTime"
                              :nudge-right="40"
                              :return-value.sync="horaRecepcion"
                              lazy
                              transition="scale-transition"
                              offset-y
                              full-width
                              max-width="290px"
                              min-width="290px"
                            >
                              <v-text-field
                                slot="activator"
                                v-model="horaRecepcion"
                                label="Hora de recepción"
                                prepend-icon="access_time"
                                readonly
                                required
                                name="hora recepcion"
                                v-validate="'required'"
                                :error-messages="errors.collect('hora recepcion')"
                              ></v-text-field>
                              <v-time-picker
                                v-if="menuTime"
                                v-model="horaRecepcion"
                                full-width
                                @change="$refs.menuTime.save(horaRecepcion)"
                              ></v-time-picker>
                            </v-menu>
                          </v-flex>

                          <v-flex xs12 sm4>
                              <v-text-field
                                slot="activator"
                                v-model="pqrs.fecha_limite"
                                label="Fecha límite respuesta"
                                prepend-icon="event"
                                readonly
                                name="fecha limite"
                                required
                                disabled
                                v-validate="'required|date_format:YYYY/MM/DD'"
                                :error-messages="errors.collect('fecha limite')"
                              ></v-text-field>
                          </v-flex>

                          <v-flex xs12 sm1>
                            <v-subheader class="pa-0 ma-0">Prioritaria</v-subheader>
                            <v-switch class="ma-0 pa-0"  color="accent" :label="pqrs.es_prioritaria"
                                      v-model="pqrs.es_prioritaria"  true-value="Si" false-value="No"></v-switch>
                          </v-flex>
                        </v-layout>

                        <v-layout>
                          <v-flex xs12>
                            <treeselect
                              v-model="pqrs.au_motivo_id"
                              :options="options"
                              :disable-branch-nodes="true"
                              placeholder="Motivo..."
                              required
                              name="motivo"
                              v-validate="'required'"
                              :error-messages="errors.collect('motivo')"
                              />
                          </v-flex>
                        </v-layout>

                        <v-layout>
                          <v-flex xs12>
                            <v-textarea v-model="pqrs.relato"
                                        label="Relato de los hechos" key="relado de los hechos"
                                        name="relado de los hechos" v-validate="'required'" required
                                        :error-messages="errors.collect('relado de los hechos')" ></v-textarea>
                          </v-flex>
                        </v-layout>
                        <v-layout>
                          <v-flex xs12>
                            <v-select
                              :items="modalidaservicios"
                              v-model="pqrs.au_mod_servicios_pqrs_id"
                              item-value="id"
                              item-text="nombre"
                              label="Modalidad de Servicio"
                              name="modalidad de servicio"
                              clearable
                            >
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.nombre"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-select>
                          </v-flex>
                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>assignment_return</v-icon> Asignación</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>

                          <v-flex xs12 sm4>
                            <v-autocomplete
                              label="Funcionario"
                              v-model="pqrs.funcionario_id"
                              :items="funcionarios"
                              item-value="id"
                              item-text="name"
                              name="funcionario"
                              required
                              v-validate="'required'"
                              :error-messages="errors.collect('funcionario')"
                              clearable>
                              <template slot="item" slot-scope="data">
                                <template>
                                  <v-list-tile-content>
                                    <v-list-tile-title v-html="data.item.name"/>
                                  </v-list-tile-content>
                                </template>
                              </template>
                            </v-autocomplete>
                          </v-flex>

                        </v-layout>
                      </v-card-text>
                    </v-card>
                  </v-flex>

                  <v-flex xs12 class="pb-4">
                    <v-card>
                      <v-toolbar dense class="grey lighten-4 elevation-0">
                        <v-toolbar-title class="subheading"><v-icon>attachment</v-icon> Archivos adjuntos</v-toolbar-title>
                      </v-toolbar>
                      <v-card-text>
                        <v-layout row wrap>
                          <v-flex xs12 sm4 v-if="pqrs.anexos.length > 0" v-for="(archivo, index) in pqrs.anexos" :key="index">
                            <v-layout align-center fill-height>
                              <v-flex d-flex xs10 sm8>
                                <v-text-field v-model="archivo.nombre"
                                              label="Archivo" key="archivo"
                                              name="archivo" disabled prepend-icon="description"
                                              :error-messages="errors.collect('archivo')" ></v-text-field>

                              </v-flex>
                              <v-flex d-flex xs2 sm4>
                                <v-tooltip top>
                                  <v-btn
                                    fab
                                    color="success"
                                    small
                                    :href="archivo.url_signed"
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

                          <v-flex xs12 sm4 v-for="(item, index) in archivosNuevos" :key="index">
                            <input-file
                              ref="archivoAdjunto"
                              label="Adjuntar archivo"
                              v-model="pqrs.files[index]"
                              :file-name="pqrs.files[index] ? pqrs.files[index].nombre : null"
                              accept="application/pdf"
                              :hint="'Extenciones aceptadas: pdf'"
                              class="mb-3"
                              prepend-icon="description"
                              clearable
                            />
                          </v-flex>
                          <v-tooltip top>
                            <v-btn fab dark small color="primary" slot="activator" @click="archivosNuevos.push({})">
                              <v-icon dark>add</v-icon>
                            </v-btn>
                            <span>Nuevo archivo</span>
                          </v-tooltip>

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
  import {PQRS_RELOAD_ITEM} from '../../../../store/modules/general/tables'
  import store from '../../../../store/complementos/index'
  import Treeselect from '@riophae/vue-treeselect'
  import '@riophae/vue-treeselect/dist/vue-treeselect.css'

  export default {
    name: 'RegistroRecepcionPqrs',
    props: ['parametros'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputFile: () => import('@/components/general/InputFile'),
      Treeselect
    },
    data () {
      return {
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
        // define default value
        value: null,
        // define options
        options: [],
        pqrs: {},
        user: null,
        payload: null,
        tiposPqrs: [],
        mediosDeRecepcion: [],
        menuDate: false,
        maxDate: this.moment().format('YYYY-MM-DD'),
        menuDateFechaLimite: false,
        menuTime: false,
        fechaRecepcion: null,
        horaRecepcion: null,
        funcionarios: [],
        loadingSubmit: false,
        archivosNuevos: [{}],
        tipoPeticion: ['Afiliado contra Capresoca', 'Afiliado contra IPS', 'Capresoca contra afiliado', 'IPS contra afiliado'],
        tiposFuente: [],
        modalidaservicios: [],
        motivosquejapqrs: []
      }
    },
    created () {
      this.axios.get('complementos-pqrs')
        .then((res) => {
          this.tiposPqrs = res.data.tipos
          this.mediosDeRecepcion = res.data.medios
          this.funcionarios = res.data.funcionarios
          this.options = res.data.macromotivos
          this.tiposFuente = res.data.fuentes
          this.modalidaservicios = res.data.modalidaservicios
          this.motivosquejapqrs = res.data.motivosquejapqrs
        })
        .catch((e) => {
          console.log('Hay un error. ' + e)
        })
    },
    beforeMount () {
      this.formReset()
    },
    watch: {
      'pqrs.afiliado' (value) {
        this.llenarAfiliado(value)
      },
      'pqrs.au_tipopqrsd_id' (value) {
        this.tiposPqrs.map(tipo => { if (value === tipo.id) this.pqrs.plazo = tipo.plazo })
        if (this.fechaRecepcion !== null) {
          this.axios.get(`fecha-limite?fecha_inicial=${this.fechaRecepcion}&plazo=${this.pqrs.plazo}`)
            .then(res => {
              this.pqrs.fecha_limite = res.data
            })
            .catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer fecha limite. ' + e.response, color: 'danger'})
            })
        }
      },
      'fechaRecepcion' (value) {
        if (this.pqrs.au_tipopqrsd_id !== null) {
          this.axios.get(`fecha-limite?fecha_inicial=${value}&plazo=${this.pqrs.plazo}`)
            .then(res => {
              this.pqrs.fecha_limite = res.data
            })
            .catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer fecha limite. ' + e.response, color: 'danger'})
            })
        }
      },
      'pqrs.plazo' (value) {
        this.pqrs.plazo = value
        if (this.fechaRecepcion !== null) {
          this.axios.get(`fecha-limite?fecha_inicial=${this.fechaRecepcion}&plazo=${this.pqrs.plazo}`)
            .then(res => {
              this.pqrs.fecha_limite = res.data
            })
            .catch(e => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer fecha limite. ' + e.response, color: 'danger'})
            })
        }
      },
      'pqrs.gn_tipdocidentidad_id' (value) {
        if (value === 12 && !this.pqrs.id) {
          this.formResetRemitente()
        }
      }
    },
    mounted () {
      if (this.parametros.entidad !== null) {
        this.getRegisto(this.parametros.entidad.id)
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosTutelas
      },
      complementosMunicipios () {
        let beforeComplement = JSON.parse(JSON.stringify(store.getters.complementosTercerosFormBasicos))

        return beforeComplement
      },
      computedDateFormatted () {
        return this.formDate(this.fechaRecepcion)
      },
      tapTitulo () {
        return !this.pqrs.id ? 'Nueva PQRS' : 'Edición de : ' + this.pqrs.consecutivo_externo
      }
    },
    methods: {
      getRegisto (id) {
        let loader = this.$loading.show({
          container: this.$refs.formPQRS.$el
        })
        this.axios.get('pqrsds/' + id)
          .then((response) => {
            console.log('responsePQRS', response)
            if (response.data !== '') {
              this.pqrs = response.data.data
              this.pqrs.files = []
              this.splitFechaRecepcionYHora()
              if (this.pqrs.email === 'null') this.pqrs.email = ''
              if (this.pqrs.apellidos === 'null') this.pqrs.apellidos = ''
            }
            loader.hide()
          })
          .catch(e => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer la PQRS. ' + e.response, color: 'danger'})
          })
      },
      formReset () {
        if (this.pqrs.archivo) this.$refs['archivoAdjunto'].reset()
        this.pqrs = {
          id: null,
          consecutivo_externo: null,
          tipo_usuario: 'Afiliado',
          afiliado: null,
          as_afiliado_id: '',
          au_tipopqrsd_id: null,
          funcionario_id: null,
          user_id: null,
          nombres: null,
          apellidos: null,
          direccion: null,
          identificacion: null,
          gn_tipdocidentidad_id: null,
          telefono: null,
          gn_municipio_id: null,
          email: '',
          medio_recepcion: null,
          relato: null,
          es_prioritaria: 'No',
          fecha_recepcion: null,
          fecha_limite: null,
          plazo: null,
          au_motivo_id: null,
          rs_entidad_id: null,
          actores: null,
          au_mod_servicios_pqrs_id: null,
          files: []
        }
        this.fechaRecepcion = null
        this.horaRecepcion = null
        this.$validator.reset()
      },
      formResetRemitente () {
        this.pqrs.identificacion = null
        this.pqrs.gn_municipio_id = null
        this.pqrs.nombres = null
        this.pqrs.apellidos = null
        this.pqrs.direccion = null
        this.pqrs.telefono = null
        this.pqrs.email = null
      },
      llenarAfiliado (afiliado) {
        if (afiliado !== null && !this.pqrs.id) {
          this.pqrs.gn_tipdocidentidad_id = afiliado.gn_tipdocidentidad_id
          this.pqrs.identificacion = afiliado.identificacion
          this.pqrs.nombres = `${afiliado.nombre1} ${afiliado.nombre2}`
          this.pqrs.apellidos = `${afiliado.apellido1} ${afiliado.apellido2}`
          this.pqrs.direccion = afiliado.direccion
          this.pqrs.gn_municipio_id = afiliado.gn_municipio_id
          this.pqrs.telefono = afiliado.celular
          this.pqrs.email = afiliado.correo_electronico == null ? '' : afiliado.correo_electronico
        }
      },
      splitFechaRecepcionYHora () {
        let fechaHoraDivididos = this.pqrs.fecha_recepcion.split(' ')
        this.fechaRecepcion = fechaHoraDivididos[0]
        this.horaRecepcion = fechaHoraDivididos[1]
      },
      filterMunicipios (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      },
      formDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${year}/${month}/${day}`
      },
      close () {
        this.formReset()
        this.$validator.reset()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      addFormData () {
        let data = new FormData()
        data.append('actores', this.pqrs.actores)
        data.append('fuente', this.pqrs.fuente)
        data.append('consecutivo_externo', this.pqrs.consecutivo_externo)
        data.append('tipo_usuario', this.pqrs.tipo_usuario)
        data.append('as_afiliado_id', this.pqrs.as_afiliado_id)
        data.append('au_tipopqrsd_id', this.pqrs.au_tipopqrsd_id)
        data.append('funcionario_id', this.pqrs.funcionario_id)
        data.append('user_id', this.pqrs.user_id)
        data.append('nombres', this.pqrs.nombres)
        data.append('apellidos', this.pqrs.apellidos)
        data.append('apellidos', this.pqrs.apellidos)
        data.append('direccion', this.pqrs.direccion)
        data.append('identificacion', this.pqrs.identificacion)
        data.append('gn_tipdocidentidad_id', this.pqrs.gn_tipdocidentidad_id)
        data.append('telefono', this.pqrs.telefono)
        data.append('gn_municipio_id', this.pqrs.gn_municipio_id)
        data.append('email', this.pqrs.email)
        data.append('medio_recepcion', this.pqrs.medio_recepcion)
        data.append('relato', this.pqrs.relato)
        data.append('es_prioritaria', this.pqrs.es_prioritaria)
        data.append('fecha_recepcion', this.pqrs.fecha_recepcion)
        data.append('fecha_limite', this.pqrs.fecha_limite)
        data.append('plazo', this.pqrs.plazo)
        data.append('au_motivo_id', this.pqrs.au_motivo_id)
        data.append('rs_entidad_id', this.pqrs.rs_entidad_id)
        data.append('au_mod_servicios_pqrs_id', this.pqrs.au_mod_servicios_pqrs_id)
        for (let file of this.pqrs.files) {
          data.append('files[]', file)
        }

        this.payload = data
      },
      async submit () {
        if (await this.validador('formPQRSS')) {
          this.loadingSubmit = true
          this.pqrs.fecha_recepcion = `${this.fechaRecepcion} ${this.horaRecepcion}`
          this.addFormData()

          const typeRequest = this.pqrs.id ? 'editar' : 'crear'
          let send = !this.pqrs.id ? this.axios.post('pqrsds', this.payload) : this.axios.post('pqrsds/' + this.pqrs.id, this.payload)
          send.then(response => {
            this.$store.commit(SNACK_SHOW, {
              msg: `Se ${this.pqrs.id ? 'actualizaron los datos' : 'realizó el registro'} correctamente.`,
              color: 'success'
            })
            this.$store.commit(PQRS_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          }).catch(e => {
            this.loadingSubmit = false
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ' + e.response, color: 'danger'})
          })
        } else {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay uno o más campos vacios.', color: 'danger'})
        }
      }
    }
  }
</script>

<style scoped>

</style>

