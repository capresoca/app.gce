<template>
    <v-card>
      <v-toolbar dense>
        <v-avatar size="40" color="primary">
          <v-icon class="white--text">{{infoComponent ? infoComponent.icono : ''}}</v-icon>
        </v-avatar>
        <v-toolbar-title>Proceso de asignación masiva</v-toolbar-title>
        <v-spacer></v-spacer>
        <template v-if="parametros && parametros.entidad">
          <v-spacer></v-spacer>tiposProceso
          <v-chip class="text-xs-right" label color="white" text-color="red" dark absolute right top>
            <span class="subheading">N°</span>&nbsp;
            <span class="subheading">{{parametros.entidad.id}}</span>
          </v-chip>
        </template>
      </v-toolbar>
      <v-container
        fluid
        grid-list-sm
      >
        <v-layout row wrap>
          <loading v-model="loading"></loading>
          <v-flex xs12 sm12 md4>
            <v-select
              label="Tipo de proceso"
              :items="complementos.tiposProceso"
              v-model="asignacion.tipo_proceso"
              name="tipo de proceso"
              v-validate="'required'"
              :error-messages="errors.collect('tipo de proceso')"
            ></v-select>
          </v-flex>
          <v-flex xs12 sm12 md4>
            <v-select
              label="Regimen"
              v-model="asignacion.as_regimene_id"
              :items="complementos.regimenes"
              name="regimen"
              item-value="id"
              item-text="nombre"
              v-validate="'required'"
              :error-messages="errors.collect('regimen')"
            ></v-select>
          </v-flex>
          <v-flex xs12 sm12 md4>
            <v-autocomplete
              label="Municipio"
              v-model="asignacion.gn_municipio_id"
              :items="complementos.municipios"
              item-value="id"
              item-text="nombre"
              name="municipio"
              required
              v-validate="'required'"
              :error-messages="errors.collect('municipio')"
              :filter="filterMunicipios"
            >
              <template slot="selection" slot-scope="data">
                <span>{{data.item.nombre}}, {{data.item.nombre_departamento}}</span>
              </template>
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
          <template v-if="asignacion.tipo_proceso === 'Asignar servicios'">
            <v-flex xs12>
              <v-autocomplete
                key="serviciosinasignar"
                label="Servicios sin asignar"
                v-model="asignacion.serviciosPendientes"
                :items="serviciosPrimarios"
                item-value="id"
                item-text="nombre"
                name="servicios sin asignar"
                v-validate="'required'"
                :error-messages="errors.collect('servicios sin asignar')"
                :filter="filterServicios"
                multiple
                chips
                deletable-chips
                small-chips
                no-data-text="No hay servicios para seleccionar"
                hide-selected
              >
              </v-autocomplete>
            </v-flex>
            <v-flex xs12>
              <v-layout align-center>
                <v-switch
                  label="Asignación manual"
                  v-model="asignacion.manual"
                  hide-details
                  class="shrink mr-2"
                ></v-switch>
                <template v-if="!asignacion.manual">
                  <input-file-v2
                    style="width: 100% !important;"
                    label="Archivo de afiliados"
                    v-model="asignacion.archivo"
                    accept="text/plain"
                    :hint="'Extenciones aceptadas: txt'"
                    prepend-icon="description"
                    name="archivo de afiliados"
                    v-validate="'required'"
                    :error-messages="errors.collect('archivo de afiliados')"
                  ></input-file-v2>
                  <template v-if="asignacion.archivo">
                    <v-tooltip top class="mt-3" v-if="validaArchivo.valido">
                      <v-btn flat icon color="success" dark slot="activator">
                        <v-icon>done_all</v-icon>
                      </v-btn>
                      <span>Sin errores</span>
                    </v-tooltip>
                    <muestra-errores-archivo ref="muestraErrores" v-if="!validaArchivo.valido && validaArchivo.errores.length" :data="validaArchivo"></muestra-errores-archivo>
                  </template>
                </template>
              </v-layout>
            </v-flex>
          </template>
          <template v-if="asignacion.tipo_proceso === 'Trasladar servicios'">
            <v-flex xs12 sm12 md6 v-if="asignacion.gn_municipio_id">
              <v-autocomplete
                key="ipsorigen"
                label="IPS Origen"
                v-model="asignacion.rs_entidad_id"
                :items="ipss"
                item-value="id"
                item-text="nombre"
                name="IPS origen"
                v-validate="'required'"
                :error-messages="errors.collect('IPS origen')"
                :filter="filterIPSs"
                no-data-text="No hay IPSs para seleccionar"
              >
                <template slot="selection" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title class="caption"><v-icon size="small">fas fa-phone-alt</v-icon> {{data.item.telefono_sede}} <v-icon size="small">fas fa-map-signs</v-icon> {{data.item.direccion_sede}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </template>
                </template>
                <template slot="item" slot-scope="data">
                  <template>
                    <v-list-tile-content>
                      <v-list-tile-title>{{data.item.nombre}}</v-list-tile-title>
                      <v-list-tile-sub-title class="caption"><v-icon size="small">fas fa-phone-alt</v-icon> {{data.item.telefono_sede}} <v-icon size="small">fas fa-map-signs</v-icon> {{data.item.direccion_sede}}</v-list-tile-sub-title>
                    </v-list-tile-content>
                  </template>
                </template>
              </v-autocomplete>
            </v-flex>
            <v-flex xs12 sm12 md6 v-if="asignacion.gn_municipio_id && asignacion.rs_entidad_id">
              <v-autocomplete
                label="Servicio"
                v-model="asignacion.rs_servicio"
                :items="serviciosIps"
                item-value="id"
                item-text="nombre"
                return-object
                name="servicio"
                v-validate="'required'"
                :error-messages="errors.collect('servicio')"
                :filter="filterServicios"
                no-data-text="No hay servicios para seleccionar"
              >
              </v-autocomplete>
            </v-flex>
          </template>
        </v-layout>
      </v-container>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" v-if="asignacion.manual" @click="filterAfiliados" :disabled="!!afiliados.length" :loading="loading">filtrar</v-btn>
        <v-btn color="primary" v-else @click="filterAfiliados" :loading="loading">Asignar servicios</v-btn>
        <select-servicios ref="selectServicioAfiliadosMasivos" v-if="!asignacion.manual" @save="registroSave" :asignacion="asignacion" :servicios-primarios="serviciosPrimarios"></select-servicios>
      </v-card-actions>
      <v-slide-y-transition group>
        <template v-if="afiliados.length && asignacion.manual">
          <v-divider key="dividerafiliados"></v-divider>
          <v-card-text key="cardafiliados">
            <v-toolbar dense class="elevation-0">
              <v-list-tile class="content-v-list-tile-p0">
                <v-list-tile-avatar color="primary">
                  <v-icon class="white--text">fas fa-users</v-icon>
                </v-list-tile-avatar>
                <v-list-tile-content>
                  <v-list-tile-title class="font-weight-bold title">Afiliados con servicios primarios pendientes por asignar</v-list-tile-title>
                  <v-list-tile-sub-title class="caption red--text">{{afiliados.length}} Afiliados</v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
              <v-spacer></v-spacer>
              <select-servicios @save="registroSave" :asignacion="asignacion" :servicios-primarios="serviciosPrimarios" :disabled="!asignacion.afiliadosSeleccionados.length"></select-servicios>
            </v-toolbar>
            <v-data-table
              select-all="accent"
              v-model="asignacion.afiliadosSeleccionados"
              :headers="headersAfiliados"
              :items="afiliados"
              rows-per-page-text="Registros por página"
              :rows-per-page-items='[50,100,500,1000,2000,{"text":"Todos","value":-1}]'>
              disable-initial-sort
              >
              <template slot="items" slot-scope="props">
                <tr :active="props.selected">
                  <td class="text-xs-left">
                    <v-checkbox
                      :input-value="props.selected"
                      primary
                      hide-details
                      @change="props.selected = !props.selected"
                    ></v-checkbox>
                  </td>
                  <td class="text-xs-left">
                    <mini-card-detail :data="props.item.mini_afiliado"></mini-card-detail>
                  </td>
                  <td>
                    {{props.item.fecha_afiliacion ? moment(props.item.fecha_afiliacion).format('YYYY-MM-DD') : ''}}
                  </td>
                </tr>
              </template>
              <template slot="pageText" slot-scope="props">
                {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
              </template>
            </v-data-table>
          </v-card-text>
        </template>
      </v-slide-y-transition>
    </v-card>
</template>

<script>
  import store from '@/store/complementos/index'
  import SelectServicios from './SelectServicios'
  import MuestraErroresArchivo from './MuestraErroresArchivo'
  export default {
    name: 'RegistroAsignacionMasiva',
    props: ['parametros'],
    components: {
      SelectServicios,
      MuestraErroresArchivo,
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputFileV2: () => import('@/components/general/InputFileV2')
    },
    data: () => ({
      loading: false,
      asignacion: null,
      serviciosPrimarios: [],
      validaArchivo: {
        valido: false,
        titulo: null,
        errores: []
      },
      makeAsignacion: {
        // comun
        as_regimene_id: null,
        gn_municipio_id: null,
        tipo_proceso: null,
        afiliadosSeleccionados: [],
        ipssAsignadas: [],
        // asignar
        serviciosPendientes: [],
        archivo: null,
        manual: true,
        // trasladar
        rs_entidad_id: null,
        rs_servicio: null,
        rs_servhabilitado_anterior: null
      },
      afiliados: [],
      filterMunicipios (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.nombre + ' ' + item.nombre_departamento)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      filterServicios (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.codigo + ' ' + item.nombre)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      filterIPSs (item, queryText) {
        const hasValue = val => val != null ? val : ''
        const text = hasValue(item.cod_habilitacion + ' ' + item.nombre)
        const query = hasValue(queryText)
        return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
      },
      headersAfiliados: [
        {
          text: 'Afiliado',
          align: 'left',
          sortable: false
        },
        {
          text: 'Fecha afiliación',
          align: 'left',
          sortable: false
        }
      ]
    }),
    computed: {
      ipss () {
        if (this.asignacion && this.asignacion.gn_municipio_id && this.serviciosPrimarios) {
          let habilitados = []
          this.serviciosPrimarios.forEach(x => { habilitados = habilitados.concat(x.habilitados) })
          return this.lodash.uniqBy(habilitados, 'rs_entidad_id').filter(z => z.entidad.gn_municipiosede_id === this.asignacion.gn_municipio_id).map(x => x.entidad)
        } else {
          return []
        }
      },
      serviciosIps () {
        if (this.asignacion && this.asignacion.gn_municipio_id && this.asignacion.rs_entidad_id && this.serviciosPrimarios) {
          let habilitados = []
          this.serviciosPrimarios.forEach(x => { habilitados = habilitados.concat(x.habilitados) })
          let idsServicios = habilitados.filter(x => x.rs_entidad_id === this.asignacion.rs_entidad_id).map(z => z.rs_servicio_id)
          return this.serviciosPrimarios.filter(j => j.id === idsServicios.find(h => h === j.id))
        } else {
          return []
        }
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('asignacionmasiva')
      },
      complementos () {
        return store.getters.complementosAsignacionMasiva
      }
    },
    watch: {
      'asignacion.gn_municipio_id' () {
        this.afiliados = []
        this.asignacion.rs_entidad_id = null
        this.asignacion.rs_servicio = null
      },
      'asignacion.as_regimene_id' () {
        this.afiliados = []
      },
      'asignacion.serviciosPendientes' () {
        this.afiliados = []
      },
      'asignacion.rs_entidad_id' () {
        this.afiliados = []
        this.asignacion.rs_servicio = null
      },
      'asignacion.rs_servicio' (val) {
        this.afiliados = []
        if (val) {
          this.asignacion.serviciosPendientes = [val.id]
          this.asignacion.rs_servhabilitado_anterior = val.habilitados.length ? val.habilitados.find(x => x.rs_entidad_id === this.asignacion.rs_entidad_id).id : null
        }
      },
      'asignacion.archivo' (val) {
        val && this.validarArchivo()
      },
      'asignacion.manual' () {
        this.asignacion.archivo = null
        this.validaArchivo.valido = false
        this.validaArchivo.titulo = null
        this.validaArchivo.errores = []
      }
    },
    created () {
      this.getServiciosPrimarios()
      if (this.parametros.entidad && this.parametros.entidad.id) {
        this.getData(this.parametros.entidad.id)
      } else {
        this.resetData()
      }
    },
    methods: {
      validarArchivo () {
        this.loading = true
        let data = new FormData()
        data.append('csv_afiliados', this.asignacion.archivo)
        this.axios.post('redservicios/valida_csv', data)
          .then(() => {
            this.loading = false
            this.validaArchivo.valido = true
            this.validaArchivo.titulo = null
            this.validaArchivo.errores = []
          })
          .catch(e => {
            this.validaArchivo.valido = false
            if (e.response.data && e.response.data[0] && e.response.data[0].afiliados) {
              this.validaArchivo.titulo = e.response.data[0].titulo
              this.validaArchivo.errores = e.response.data[0].afiliados
              setTimeout(() => {
                this.loading = false
                this.$refs.muestraErrores.open()
              }, 400)
            } else {
              this.$store.commit('SNACK_ERROR_LIST', {expression: ' al validar el archivo de afiliados. ', error: e})
              this.loading = false
            }
          })
      },
      registroSave () {
        this.$store.commit('reloadTable', 'tableAsignacionMasiva')
        this.$store.dispatch('NAV_RESPONSE_CONTENT_ITEM', { contexto: this, itemId: this.parametros.tabOrigin })
      },
      filterAfiliados () {
        this.$validator.validateAll().then(result => {
          if (result) {
            if (this.asignacion.manual) {
              this.loading = true
              let url = this.asignacion.tipo_proceso === 'Asignar servicios'
                ? `redservicios/afiliadoservicios/inhabilitados?gn_municipio_id=${this.asignacion.gn_municipio_id}&as_regimene_id=${this.asignacion.as_regimene_id}&rs_servicio_id=${this.asignacion.serviciosPendientes}`
                : `redservicios/afiliadoservicios/cambioservicios?gn_municipio_id=${this.asignacion.gn_municipio_id}&as_regimene_id=${this.asignacion.as_regimene_id}&rs_servicio_id=${this.asignacion.rs_servicio.id}&rs_entidad_id=${this.asignacion.rs_entidad_id}`
              this.axios.get(url)
                .then((response) => {
                  this.afiliados = response.data.data
                  this.loading = false
                })
                .catch(e => {
                  this.loading = false
                  this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer afiliados con servicios pendientes. ', error: e})
                })
            } else {
              if (this.validaArchivo.valido) {
                this.$refs.selectServicioAfiliadosMasivos.open()
              } else {
                this.$store.commit('SNACK_SHOW', {msg: 'Hay errores en el archivo de afiliados.', color: 'orange'})
              }
            }
          }
        })
      },
      getData (id) {
        this.loading = true
        this.axios.get(`redservicios/asignamasivos/${id}`)
          .then((response) => {
            console.log('response', response)
          })
          .catch(e => {
            this.loading = false
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los datos del proceso de asignación masiva. ', error: e})
          })
      },
      getServiciosPrimarios () {
        this.axios.get(`servicios/primarios`)
          .then((response) => {
            this.serviciosPrimarios = response.data.data
          })
          .catch(e => {
            this.$store.commit('SNACK_ERROR_LIST', {expression: ' al traer los servicios primarios. ', error: e})
          })
      },
      resetData () {
        this.asignacion = JSON.parse(JSON.stringify(this.makeAsignacion))
      }
    }
  }
</script>

<style scoped>

</style>
