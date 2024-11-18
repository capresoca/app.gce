<template>
    <v-slide-y-transition>
      <v-form data-vv-scope="formReferencia" v-if="value">
          <v-card>
        <v-container fluid grid-list-xl>
          <v-layout row wrap class="refSpace">
            <v-flex xs12 sm12 md5 lg5 xl5 class="grey lighten-4">
              <v-toolbar class="grey lighten-4 elevation-0 toolbar--dense">
                <v-layout row wrap>
                  <v-flex xs12 sm6 md12 class="pa-0 pt-2 head1">
                    <v-toolbar-title> {{ tapTitulo }} </v-toolbar-title>
                  </v-flex>
                  <!--<v-flex xs12 sm6 md12 class="pa-0 head2">-->
                    <!--<h2 v-if="referenciaItem.id !== null" class="d-inline-block subheading">Afiliado: </h2>-->
                    <!--<div v-if="referenciaItem.id !== null" class="d-inline-block">-->
                      <!--<span class="subheading" v-text="referenciaItem.afiliado ? referenciaItem.afiliado.nombre_completo : null"></span>&nbsp;-->
                      <!--&lt;!&ndash;<span class="subheading" v-text="referenciaItem.consecutivo == null ? '0000' : referenciaItem.consecutivo"></span>&ndash;&gt;-->
                    <!--</div>-->
                  <!--</v-flex>-->
                </v-layout>
                <!--&nbsp;-->
              </v-toolbar>
            </v-flex>
            <v-spacer></v-spacer>
            <v-divider vertical class="dNone pl-1"></v-divider>
            <v-flex xs12 sm6 md2 lg2 xl2>
              <v-datetime-picker
                label="Fecha Egreso"
                :datetime="datetime3"
                @input="updateDatetime"
                :timeData="referenciaItem.fecha_egreso"
                :min="fecha_llegada"
              ></v-datetime-picker>
            </v-flex>
            <v-flex xs12 sm6 md2 lg2 xl2>
              <v-select
                :items="complementosReferencias.estadoEgresoRefs"
                v-model="referenciaItem.estado_egreso"
                label="Estado Egreso"
                name="estado egreso"
                key="estado egreso"
                v-validate="'required'"
                :error-messages="errors.collect('estado egreso')">
              </v-select>
            </v-flex>
            <v-flex xs12 class="pa-1">
              <v-divider class="dividerTwo"></v-divider>
            </v-flex>
            <!--<v-flex xs12 sm12 md6 lg6 xl6>-->
              <!--<postulador-v2-->
                <!--no-data="Busqueda por razon social u código de habilitación."-->
                <!--hint="tercero.identificacion_completa"-->
                <!--item-text="nombre"-->
                <!--data-title="tercero.identificacion_completa"-->
                <!--data-subtitle="nombre"-->
                <!--filter="tercero.identificacion_completa,nombre,cod_habilitacion"-->
                <!--label="IPS DESTINO"-->
                <!--entidad="entidades"-->
                <!--preicon="location_city"-->
                <!--@changeid="val => referenciaItem.entidad_egreso_id = val"-->
                <!--v-model="referenciaItem.entidad_two"-->
                <!--name="IPS Egreso"-->
                <!--route-params="rs_tipentidade_id=1"-->
                <!--rules="required|entidadReferencia"-->
                <!--v-validate="'required|entidadReferencia'"-->
                <!--:error-messages="errors.collect('IPS Egreso')"-->
                <!--no-btn-create-->
                <!--:min-characters-search="3"-->
                <!--clearable-->
              <!--/>-->
            <!--</v-flex>-->
            <v-flex xs12 sm12 md12 lg12 xl12>
              <postulador-v2
                no-data="Busqueda por código, descripcion y genero"
                hint="codigo"
                item-text="descripcion"
                data-title="codigo"
                data-subtitle="descripcion"
                label="CIE10 Egreso"
                entidad="rs_cie10s"
                preicon="reorder"
                @changeid="val => referenciaItem.cie10_egreso_id = val"
                v-model="referenciaItem.cie10_two"
                name="cie10 egreso"
                rules="required"
                v-validate="'required'"
                :error-messages="errors.collect('cie10 egreso')"
                no-btn-create
                :min-characters-search="3" clearable/>
            </v-flex>
          </v-layout>
        </v-container>
        <v-divider></v-divider>
        <v-card-actions class="grey lighten-4">
          <v-layout row wrap>
            <v-flex xs12 class="text-xs-right">
              <v-btn @click.stop="$emit('close')">Cancelar</v-btn>
              <v-btn
                color="primary"
                @click.prevent="save"
                v-text="'Registrar'"
              ></v-btn>
            </v-flex>
          </v-layout>
        </v-card-actions>
      </v-card>
      </v-form>
    </v-slide-y-transition>
</template>

<script>
  // import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  // import {REFERENCIA_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import VDatetimePicker from '../../../general/VDatetimePicker'
  import store from '../../../../store/complementos/index'
  import { Validator } from 'vee-validate'
  export default {
    name: 'FormReferencia',
    components: {
      VDatetimePicker,
      PostuladorV2: () => import('@/components/general/PostuladorV2')
    },
    props: {
      value: {
        type: Boolean,
        default: false
      },
      referId: {
        type: Number,
        default: null
      },
      origenId: {
        type: Number,
        default: null
      },
      centro: {
        type: Object,
        default: null
      }
    },
    created () {
      this.idReferencia = this.referId
    },
    mounted () {
      this.formReset()
      Validator.extend('entidadReferencia', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = {valido: true, mensaje: null}
            if (value.id === this.origenId) {
              response = {valido: false, mensaje: `La IPS origen no debe ser igual a la IPS destino.`}
            } else {
              response = {valido: true, mensaje: null}
            }
            return resolve({valid: response.valido, data: {message: response.mensaje}})
          }
        }),
        getMessage: (field, params, data) => data.message
      })
    },
    // beforeMount () {
    //   this.datetime3 = this.moment().format('YYYY-MM-DD HH:mm:ss')
    // },
    watch: {
      'value' (val) {
        if (val) {
          this.$validator.reset()
          this.getRegistro(this.idReferencia)
        } else {
          this.$validator.reset()
          this.formReset()
        }
      }
    },
    data () {
      return {
        datetime3: this.moment().format('YYYY-MM-DD HH:mm:ss'),
        payData: null,
        referenciaItem: {},
        idReferencia: null,
        fecha_llegada: null
      }
    },
    methods: {
      getRegistro (id) {
        let idCopy = parseInt(id)
        this.axios.get(`au_referencias/${idCopy}`)
          .then((res) => {
            res.data.data.bitacoras.forEach(x => {
              if (x.traslado !== null && x.au_tipaccion_id === 9) {
                if (x.traslado.tipo_ambulancia === 'Otro') {
                  res.data.data.entidad_egreso_id = x.traslado.entidad_origen_id
                } else {
                  res.data.data.entidad_origen_id = x.traslado.entidad_transporta_id
                }
                this.fecha_llegada = x.traslado.fecha_llegada
              } else {
                this.fecha_llegada = x.fecha
                res.data.data.entidad_egreso_id = res.data.data.entidad_origen_id
              }
            })
            // res.data.data.fecha_llegada = this.datetime3
            this.referenciaItem = res.data.data
            // if (this.referenciaItem.)
            // if (res.data.data) {
            //   this.referenciaItem.fecha_egreso = this.datetime3
            //   this.fecha_llegada = (this.referenciaItem.bitacoras[0].traslado.fecha_llegada !== null)
            //     ? this.referenciaItem.bitacoras[0].traslado.fecha_llegada
            //     : this.referenciaItem.bitacoras[0].fecha
            // }
          }).catch((e) => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' Al traer el registro. ', error: e})
            // this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al traer el registro. ', color: 'danger'})
          })
      },
      formReset () {
        this.referenciaItem = {
          id: null,
          cie10_egreso_id: null,
          estado_egreso: null,
          fecha_egreso: this.moment().format('YYYY-MM-DD HH:mm:ss'),
          cie10_two: null,
          entidad_egreso_id: null,
          entidad_two: null
        }
      },
      updateDatetime (val) {
        this.datetime3 = val
        this.referenciaItem.fecha_egreso = val
      },
      addFormData () {
        let formData = new FormData()
        formData.append('id', this.referenciaItem.id)
        formData.append('consecutivo', this.referenciaItem.consecutivo)
        formData.append('fecha_orden', this.referenciaItem.fecha_orden)
        formData.append('fecha_solicitud', this.referenciaItem.fecha_solicitud)
        formData.append('as_afiliado_id', this.referenciaItem.as_afiliado_id)
        formData.append('entidad_origen_id', this.referenciaItem.entidad_origen_id)
        formData.append('as_regimen_id', this.referenciaItem.as_regimen_id)
        formData.append('tipo_origen', this.referenciaItem.tipo_origen)
        formData.append('cie10_ingreso_id', this.referenciaItem.cie10_ingreso_id)
        formData.append('au_modservicio_id', this.referenciaItem.au_modservicio_id)
        formData.append('rs_servicio_id', this.referenciaItem.rs_servicio_id)
        if (this.referenciaItem.oj_tutela_id) formData.append('oj_tutela_id', this.referenciaItem.oj_tutela_id)
        formData.append('alto_costo', this.referenciaItem.alto_costo)
        formData.append('archivo_two', typeof this.referenciaItem.archivo_two === 'undefined' ? '' : this.referenciaItem.archivo_two)
        formData.append('observaciones', this.referenciaItem.observaciones)
        formData.append('archivo_uno', typeof this.referenciaItem.archivo_uno === 'undefined' ? '' : this.referenciaItem.archivo_uno)
        formData.append('estado', this.referenciaItem.estado)
        formData.append('cie10_egreso_id', this.referenciaItem.cie10_egreso_id)
        formData.append('entidad_egreso_id', this.referenciaItem.entidad_egreso_id)
        formData.append('estado_egreso', this.referenciaItem.estado_egreso)
        formData.append('fecha_egreso', this.referenciaItem.fecha_egreso)
        formData.append('rs_cup_id', this.referenciaItem.rs_cup_id)
        this.payData = formData
      },
      async save () {
        if (await this.validador('formReferencia')) {
          this.addFormData()
          // const typeRequest = this.referenciaItem.id ? 'editar' : 'crear'
          let send = this.referenciaItem.id ? this.axios.post('referencias/' + this.referenciaItem.id, this.payData) : null
          send.then(response => {
            this.$store.commit(SNACK_SHOW, {
              msg: `Se ${response.data.data.id
                ? 'actualizaron los datos' : 'realizó el registro'} correctamente de la referencia.`,
              color: 'success'
            })
            // this.$store.commit(REFERENCIA_RELOAD_ITEM, {item: response.data.data, estado: typeRequest, key: this.parametros.key})
            // this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
            this.$emit('actualizar', response.data.data.id)
            this.$emit('close')
          }).catch(() => {
            this.$store.commit(SNACK_SHOW, {msg: 'Hay un error al guardar el registro. ', color: 'danger'})
          })
        }
      },
      validador (scope) {
        return this.$validator.validateAll(scope).then(result => { return result })
      }
    },
    computed: {
      tapTitulo () {
        return 'Cierre del Proceso'
      },
      complementosReferencias () {
        return store.getters.complementosReferencias
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
