<template>
  <div>
    <v-card class="pa-0">
      <v-form data-vv-scope="formReferencia">
        <v-container fluid grid-list-xl>
          <v-layout row wrap class="refSpace">
            <v-flex xs12 sm12 md5 lg5 xl5 class="grey lighten-4">
              <v-toolbar class="grey lighten-4 elevation-0 toolbar--dense">
                <v-layout row wrap>
                  <v-flex xs12 sm6 md12 class="pa-0 pt-2 head1">
                    <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
                  </v-flex>
                  <v-flex xs12 sm6 md12 class="pa-0 head2">
                    <h2 v-if="referencia.id !== null" class="d-inline-block subheading">Referencia</h2>
                    <div v-if="referencia.id !== null" class="d-inline-block">
                      <v-chip label color="white" text-color="red" dark absolute right top>
                        <span class="subheading">N°</span>&nbsp;
                        <span class="subheading" v-text="referencia.consecutivo == null ? '0000' : referencia.consecutivo"></span>
                      </v-chip>
                    </div>
                  </v-flex>
                </v-layout>
                <!--&nbsp;-->
              </v-toolbar>
            </v-flex>
            <v-spacer></v-spacer>
            <v-divider vertical class="dNone pl-1"></v-divider>
            <v-flex xs12 sm6 md3 lg3 xl3>
              <v-datetime-picker
                label="Fecha Orden de Remisión"
                :datetime="referencia.fecha_orden"
                @input="updateDatetime"
                :timeData="referencia.fecha_orden"
                :max="moment().format('YYYY-MM-DD HH:mm:ss')"
              ></v-datetime-picker>
            </v-flex>
            <v-flex xs12 sm6 md3 lg3 xl3>
              <v-datetime-picker
                label="Fecha Solicitud de Remisión"
                :datetime="referencia.fecha_solicitud"
                @input="updateDatetime2"
                :timeData="referencia.fecha_solicitud"
                :max="moment().format('YYYY-MM-DD HH:mm:ss')"
              ></v-datetime-picker>
            </v-flex>
            <v-flex xs12 class="pa-1">
              <v-divider class="dividerTwo"></v-divider>
            </v-flex>
            <v-flex xs12 sm12 md8 lg8 xl8 class="pt-0">
              <postulador-v2
                v-if="!referencia.id || referencia.estado === 'Iniciada'"
                no-data="Busqueda por nombre o número de documento."
                hint="identificacion_completa"
                item-text="nombre_completo"
                data-title="identificacion_completa"
                data-subtitle="nombre_completo"
                label="Afiliado"
                entidad="afiliados"
                preicon="person"
                @changeid="val => referencia.as_afiliado_id = val"
                v-model="referencia.afiliado"
                name="afiliado"
                rules="required"
                v-validate="'required'"
                :error-messages="errors.collect('afiliado')"
                clearable
                :slot-prepend='referencia.afiliado ? {
                                template:`<mini-card-detail :data="value.mini_afiliado" alone-icon/>`,
                                props: [`value`]
                               } : null'
              />
              <input-detail-flex
                v-else
                class="pt-0 pl-0"
                flex-class="xs12"
                label="Afiliado"
                :text="`${referencia.afiliado ? referencia.afiliado.nombre_completo : ''}`"
                :hint="`${referencia.afiliado ? referencia.afiliado.identificacion_completa : 'No Registra.'}`"
                :prepend-icon="referencia.afiliado ? referencia.afiliado.emoticon : 'person'"
                :append-button="{tooltip: !!referencia.as_afiliado_id ? 'Ir al afiliado' : 'No es afilaido', icon: !!referencia.as_afiliado_id ? 'link' : 'warning', color: !!referencia.as_afiliado_id ? 'success' : 'error'}"
                @clickAppend="!!referencia.as_afiliado_id ? $store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: {id: referencia.as_afiliado_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}}) : ''"
              ></input-detail-flex>
              <!--:slot-append='{-->
              <!--:detail="detalleAfiliado"-->
              <!--template:`<span class="pt-2 caption">Estado: {{value.estado_afiliado.codigo}} - {{value.estado_afiliado.nombre}}</span>`,-->
              <!--props: [`value`]-->
              <!--}'-->
            </v-flex>
            <v-flex xs12 sm6 md2 lg2 xl2 class="pt-0">
              <v-text-field label="Régimen Afiliado" :value="regimenName" readonly></v-text-field>
            </v-flex>
            <v-flex xs12 sm6 md2 lg2 xl2 class="pt-0">
              <v-select
                :items="complementosReferencias.estadosReferencias"
                disabled
                v-model="referencia.estado"
                label="Estado"
                name="estado"
                key="estado"
                v-validate="'required'"
                :error-messages="errors.collect('estado')">
              </v-select>
            </v-flex>
            <v-flex xs12 sm6 md6 lg6 xl6 class="pt-0">
              <postulador-v2
                no-data="Busqueda por razon social u código de habilitación."
                hint="tercero.identificacion_completa"
                item-text="nombre"
                data-title="tercero.identificacion_completa"
                data-subtitle="nombre"
                label="IPS Origen"
                entidad="entidades"
                preicon="location_city"
                @changeid="val => referencia.entidad_origen_id = val"
                v-model="referencia.entidad_uno"
                name="IPS Origen"
                route-params="rs_tipentidade_id=1"
                rules="required"
                v-validate="'required'"
                :error-messages="errors.collect('IPS Origen')"
                no-btn-create
                :min-characters-search="3"
                clearable/>
            </v-flex>
            <v-flex xs12 sm6 md6 lg6 xl6 class="pt-0">
              <postulador-v2
                no-data="Busqueda por código, descripcion y genero"
                hint="codigo"
                item-text="descripcion"
                data-title="codigo"
                data-subtitle="descripcion"
                label="Diagnóstico"
                entidad="rs_cie10s"
                preicon="reorder"
                @changeid="val => referencia.cie10_ingreso_id = val"
                v-model="referencia.cie10_uno"
                name="diagnóstico"
                rules="required"
                v-validate="'required'"
                :error-messages="errors.collect('diagnóstico')"
                no-btn-create
                :min-characters-search="3" clearable/>
            </v-flex>
            <v-flex xs12 sm6 md2 lg2 xl2>
              <v-select
                :items="complementosReferencias.tipoOrigenRefs"
                v-model="referencia.tipo_origen"
                disabled
                label="Tipo Origen"
                name="tipo origen"
                key="tipo origen"
                v-validate="'required'"
                :error-messages="errors.collect('tipo origen')">
              </v-select>
            </v-flex>
            <v-flex xs12 sm6 md4 lg4 xl4>
              <v-autocomplete
                label="Modalidad de Servicio"
                v-model="referencia.au_modservicio_id"
                :items="complementosReferencias.modalidadesServicio"
                item-value="id"
                item-text="tipo"
                name="modalidad de servicio"
                v-validate="'required'"
                :error-messages="errors.collect('modalidad de servicio')"
                :filter="filterModalidades"
                clearable
              >
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="data.item.codigo + ' - ' + data.item.tipo"/>
                      <v-list-tile-sub-title v-html="data.item.modalidad"/>
                    </v-list-tile-content>
                  </template>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm12 md6 lg6 xl6>
              <v-autocomplete
                label="Servicio"
                v-model="referencia.servicio"
                @change="val => referencia.rs_servicio_id = val ? val.id : null"
                :items="complementosReferencias.rsServicios"
                :hint="referencia.rs_servicio_id ? 'Código: ' + referencia.servicio.codigo : ''"
                item-value="id"
                item-text="nombre"
                name="servicio"
                return-object
                prepend-icon="record_voice_over"
                v-validate="'required'"
                :error-messages="errors.collect('servicio')" clearable>
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title v-html="data.item.codigo + ' - ' + data.item.nombre"></v-list-tile-title>
                    </v-list-tile-content>
                  </template>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12>
              <postulador-v2
                ref="postuladorProcedimiento"
                no-data="Busqueda por código o nombre."
                item-text="descripcion"
                label="Procedimiento"
                entidad="cups"
                hint="codigo"
                persistent-hint
                v-model="referencia.cups"
                @changeid="val => referencia.rs_cup_id = val"
                no-btn-create
                no-btn-edit
                name="procedimiento"
                route-params="cupscategoria:subgrupo:rs_cupsgrupo_id=109"
                rules="required"
                v-validate="'required'"
                :error-messages="errors.collect('procedimiento')"
                :min-characters-search="4"
                :slot-data='{
                      template:`
                      <v-list-tile>
                      <v-list-tile-action>
                        <v-chip
                        color="indigo lighten-2"
                        label
                        small
                        >
                        {{ value.codigo }}
                        </v-chip>
                        </v-list-tile-action>
                        <v-list-tile-content>
                          <v-list-tile-title>{{value.descripcion}}</v-list-tile-title>
                          <v-list-tile-sub-title class=caption>{{ value.cobertura }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                      `,
                      props: [`value`]
                     }'
              />
            </v-flex>
            <v-flex xs12 sm6 md6 lg6 xl6 v-if="referencia.oj_tutela_id">
              <postulador-v2
                no-data="Busqueda por N° tutela, nombre y estado"
                hint="nombre"
                item-text="no_tutela"
                data-title="no_tutela"
                data-subtitle="nombre"
                label="Tutela"
                entidad="oj_tutelas"
                preicon="description"
                @changeid="val => referencia.oj_tutela_id = val"
                v-model="referencia.tutela"
                no-btn-create
                :min-characters-search="3" clearable/>
            </v-flex>
            <v-flex xs12 sm6 md5 lg5 xl5>
              <v-layout row wrap>
                <v-flex xs10>
                  <input-file
                    ref="archivoAdjunto"
                    label="Formato de Remisión"
                    v-model="referencia.archivo_uno"
                    :file-name="referencia.archivo_uno ? referencia.archivo_uno.nombre : null"
                    accept="application/pdf"
                    :hint="'Extenciones aceptadas: pdf'"
                    class="mb-3"
                    prepend-icon="attach_file"
                    :error-messages="errors.collect('formato de remisión')"
                  />
                </v-flex>
                <v-flex xs2 class="pt-4 pl-0">
                  <v-tooltip top>
                    <v-btn
                      :loading="loadingButton"
                      :disabled="loadingButton"
                      slot="activator"
                      color="accent"
                      @click="loader = 'loadingButton'"
                      fab small :href="referencia.archivo_uno ? referencia.archivo_uno.url_signed : '' "
                    >
                      <v-icon v-text="'archive'"></v-icon>
                      <span slot="loader" class="custom-loader">
                        <v-icon light>cached</v-icon>
                      </span>
                    </v-btn>
                    <span v-text="'Descargar Formato de Remisión'"></span>
                  </v-tooltip>
                </v-flex>
                <!--<v-spacer></v-spacer>-->
              </v-layout>
              <!--@openArchive=""-->
            </v-flex>
            <v-flex xs12 sm6 md5 lg5 xl5>
              <v-layout row wrap>
                <v-flex xs10>
                  <input-file
                    ref="archivoAdjuntoTwo"
                    label="Evolución Médica"
                    v-model="referencia.archivo_two"
                    :file-name="referencia.archivo_two ? referencia.archivo_two.nombre : null"
                    accept="application/pdf"
                    :hint="'Extenciones aceptadas: pdf'"
                    class="mb-3"
                    prepend-icon="attach_file"
                  />
                </v-flex>
                <v-flex xs2 class="pt-4 pl-0">
                  <v-tooltip top>
                    <v-btn
                      :loading="loadingButton"
                      :disabled="loadingButton"
                      slot="activator"
                      color="accent"
                      @click="loader = 'loadingButton'"
                      fab small :href="referencia.archivo_two ? referencia.archivo_two.url_signed : '' "
                    >
                      <v-icon v-text="'archive'"></v-icon>
                      <span slot="loader" class="custom-loader">
                        <v-icon light>cached</v-icon>
                      </span>
                    </v-btn>
                    <span v-text="'Descargar Evolución Médica'"></span>
                  </v-tooltip>
                </v-flex>
              </v-layout>
            </v-flex>
            <v-flex xs12 sm12 md2 lg2 xl2>
              <v-switch  color="success" :label="'Alto Costo'"
                         v-model="referencia.alto_costo"  true-value="Si" false-value="No"></v-switch>
            </v-flex>
            <v-flex xs12 v-if="msgTutela">
              <v-alert
                :value="true"
                color="orange darken-2"
                icon="priority_high"
              >
                {{ msgTutela }}
              </v-alert>
            </v-flex>
            <v-flex xs12 sm12 md12 xl12 class="pt-0">
              <v-textarea
                v-model="referencia.observaciones"
                color="primary"
                name="observaciones"
                required
                v-validate="'required'"
                key="observaciones"
                :error-messages="errors.collect('observaciones')">
                <div slot="label">
                  Observaciones
                </div>
              </v-textarea>
            </v-flex>
          </v-layout>
        </v-container>
      </v-form>
      <v-divider></v-divider>
      <v-card-actions class="grey lighten-4">
        <v-layout row wrap>
          <v-flex xs6 class="text-xs-left">
            <v-btn @click="refresh(null)">Limpiar</v-btn>
          </v-flex>
          <v-flex xs6 class="text-xs-right">
            <v-btn @click="close()">Cancelar</v-btn>
            <v-btn
              :loading="saveLoadingButton"
              :disabled="saveLoadingButton"
              color="primary"
              @click.prevent="save" @input="loader = 'saveLoadingButton'">
              Guardar
              <span slot="loader" class="custom-loader">
                <v-icon light>cached</v-icon>
              </span>
            </v-btn>
          </v-flex>
        </v-layout>
      </v-card-actions>
    </v-card>
  </div>

</template>

<script>
  import store from '../../../../store/complementos/index'
  import VDatetimePicker from '../../../general/VDatetimePicker'
  import InputFile from '../../../general/InputFile'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import {REFERENCIA_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroReferencia',
    components: {
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      InputFile,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      VDatetimePicker
    },
    props: ['parametros'],
    data () {
      return {
        referencia: {},
        datetime: this.moment().format('YYYY-MM-DD HH:mm:ss'),
        datetime2: this.moment().format('YYYY-MM-DD HH:mm:ss'),
        datetime3: '2018-01-01 11:05:00',
        tiposRegimenEps: ['Subsidiado', 'Contributivo'],
        regimenName: null,
        payData: null,
        loader: null,
        loadingButton: false,
        saveLoadingButton: false,
        msgTutela: '',
        filterModalidades (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codigo + ' ' + item.tipo + ' ' + item.modalidad)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        }
      }
    },
    computed: {
      tapTitulo () {
        return this.referencia.id ? 'En Edición' : 'Nuevo Centro Regulador'
      },
      complementosReferencias () {
        return store.getters.complementosReferencias
      }
    },
    watch: {
      'referencia.afiliado' (val) {
        // console.log('val', val)
        this.referencia.as_regimen_id = val ? val.as_regimene_id : null
        this.regimenName = val ? val.regimen.nombre : null
        this.getSiAfiliadoTutela(val)
      },
      'referencia.id' (val) {
        if (val) {
          // this.datetime = this.referencia.fecha_orden
          this.datetime = this.referencia.fecha_orden.toString()
          this.datetime2 = this.referencia.fecha_solicitud.toString()
        }
      }
    },
    beforeMount () {
      this.formReset()
    },
    mounted () {
      if (this.parametros.entidad !== null) this.getRegistro(this.parametros.entidad.id)
      // console.log('Que trae', this.parametros)
      // this.datetime = this.moment().format('YYYY-MM-DD h:mm:ss')
    },
    methods: {
      getSiAfiliadoTutela (item) {
        if (item) {
          this.axios.get('referencias/situtela/' + item.id)
            .then(res => {
              this.msgTutela = res.data.msg
            }).catch(() => {
              this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al buscar si tiene tutela.', color: 'danger'})
            })
        }
      },
      getRegistro (id) {
        // console.log('registros', id)
        this.axios.get('au_referencias/' + id)
          .then(res => {
            // console.log('res', res)
            this.referencia = res.data.data
            // this.datetime = res.data.data.fecha_orden
            // this.datetime2 = res.data.data.fecha_solicitud
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro.', color: 'danger'})
          })
      },
      updateDatetime (datetime) {
        this.datetime = datetime
        this.referencia.fecha_orden = datetime
      },
      updateDatetime2 (datetime2) {
        this.datetime2 = datetime2
        this.referencia.fecha_solicitud = datetime2
      },
      updateDatetime3 (datetime3) {
        this.datetime3 = datetime3
        this.referencia.fecha_egreso = this.datetime3
      },
      formReset () {
        this.referencia = {
          id: null,
          consecutivo: null,
          fecha_orden: this.moment().format('YYYY-MM-DD HH:mm:ss'),
          fecha_solicitud: this.moment().format('YYYY-MM-DD HH:mm:ss'),
          as_afiliado_id: null,
          entidad_origen_id: null,
          as_regimen_id: null,
          tipo_origen: 'Hospitalario',
          cie10_ingreso_id: null,
          au_modservicio_id: null,
          rs_servicio_id: null,
          oj_tutela_id: null,
          rs_cup_id: null,
          alto_costo: 'No',
          orden_medica_id: null,
          observaciones: null,
          historia_clinica_id: null,
          estado: 'Iniciada',
          cie10_egreso_id: null,
          entidad_egreso_id: null,
          estado_egreso: null,
          fecha_egreso: null,
          // object
          afiliado: null,
          entidad_uno: null,
          entidad_two: null,
          regimen: null,
          cie10_uno: null,
          cie10_two: null,
          servicio: null,
          tutela: null,
          archivo_uno: null,
          archivo_two: null,
          cups: null
        }
      },
      refresh () {
        this.$validator.reset()
        this.formReset()
      },
      close () {
        this.refresh()
        this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
      },
      addFormData () {
        let formData = new FormData()
        formData.append('id', this.referencia.id)
        formData.append('consecutivo', this.referencia.consecutivo)
        formData.append('fecha_orden', this.referencia.fecha_orden)
        formData.append('fecha_solicitud', this.referencia.fecha_solicitud)
        formData.append('as_afiliado_id', this.referencia.as_afiliado_id)
        formData.append('entidad_origen_id', this.referencia.entidad_origen_id)
        formData.append('as_regimen_id', this.referencia.as_regimen_id)
        formData.append('tipo_origen', this.referencia.tipo_origen)
        formData.append('cie10_ingreso_id', this.referencia.cie10_ingreso_id)
        formData.append('au_modservicio_id', this.referencia.au_modservicio_id)
        formData.append('rs_servicio_id', this.referencia.rs_servicio_id)
        if (this.referencia.oj_tutela_id) formData.append('oj_tutela_id', this.referencia.oj_tutela_id)
        formData.append('alto_costo', this.referencia.alto_costo)
        if (this.referencia.archivo_two) formData.append('archivo_two', typeof this.referencia.archivo_two === 'undefined' ? '' : this.referencia.archivo_two)
        formData.append('observaciones', this.referencia.observaciones)
        formData.append('archivo_uno', typeof this.referencia.archivo_uno === 'undefined' ? '' : this.referencia.archivo_uno)
        formData.append('estado', this.referencia.estado)
        formData.append('rs_cup_id', this.referencia.rs_cup_id)

        if (this.referencia.historia_clinica_id) formData.append('historia_clinica_id', this.referencia.historia_clinica_id)
        // if (this.referencia.orden_medica_id) formData.append('orden_medica_id', this.referencia.orden_medica_id)
        this.payData = formData
      },
      async save () {
        // let errorArchivo = await this.$refs.archivoAdjunto.validate()
        if (await this.validador('formReferencia') && (this.referencia.archivo_uno !== null)) {
          let loader = this.$loading.show({
            container: this.$refs.cargar
          })
          this.addFormData()
          const typeRequest = this.referencia.id ? 'editar' : 'crear'
          let send = !this.referencia.id ? this.axios.post('au_referencias', this.payData) : this.axios.post('referencias/' + this.referencia.id, this.payData)
          send.then(response => {
            loader.hide()
            this.$store.commit(SNACK_SHOW, {
              msg: `Se ${this.referencia.id
                ? 'actualizaron los datos' : 'realizó el registro'} correctamente de la referencia.`,
              color: 'success'
            })
            this.$store.commit(REFERENCIA_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
            this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ', color: 'danger'})
          })
        } else {
          if (await this.validador('formReferencia') && (this.referencia.archivo_uno === null)) {
            this.$store.commit(SNACK_SHOW, {msg: 'No se ha cargado el formato de remisión.', color: 'danger'})
          } else {
            this.$store.commit(SNACK_SHOW, {msg: 'No se puede registrar, existen campos vacíos. ', color: 'danger'})
          }
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      }
    }
  }
</script>

<style scoped>
  .dividerTwo {
    padding: 0.1mm;
    background-color: #f5f5f5 !important;
    border-color: #f5f5f5 !important;
  }
  .refSpace .spacer {
    background-color: #f5f5f5 !important;
    border-color: #f5f5f5 !important;
  }
  @media (max-width: 601px) {
    .head2 {
      text-align: right !important;
    }
  }
  @media (min-width: 602px) and (max-width: 959px){
    .head1 {
      padding-top: 4px !important;
    }
    .head2 {
      text-align: right;
    }
    .head2 h2 {
      font-size: 20px !important;
      font-weight: 500;
      line-height: 1 !important;
      letter-spacing: 0.02em !important;
      font-family: 'Roboto', sans-serif !important;
    }
  }

  @media (max-width: 959px) {
    .dNone {
      display: none !important;
    }
    .refSpace .spacer {
      display: none !important;
    }
  }
</style>
