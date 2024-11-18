<template>
  <v-container
    fluid
    grid-list-sm
    class="pa-0 ma-0"
  >
    <v-dialog v-model="value" persistent max-width="1000px">
      <loading v-model="dialog.loading" />
      <v-card v-if="parent && item">
        <v-toolbar dense>
          <v-toolbar-title>
            <v-list-tile>
              <v-icon left class="mr-2" style="margin-left: -14px !important;">ballot</v-icon>
              <v-list-tile-content>
                <v-list-tile-title v-html="type + ': ' + item.title"></v-list-tile-title>
                <v-list-tile-sub-title class="caption">{{parent.NoPrescripcion ? ('Prescripción No. ' + parent.NoPrescripcion) : ('Tutela No. ' + parent.NoTutela)}}</v-list-tile-sub-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-toolbar-title>
          <v-spacer/>
          <v-btn flat icon :disabled="dialog.loading" @click="$emit('input', false)">
            <v-icon>close</v-icon>
          </v-btn>
        </v-toolbar>
        <v-container grid-list-sm>
          <v-layout wrap>
            <v-flex xs12 v-if="item.datosBasicos">
              <v-expansion-panel class="v-expansion-panel-pi">
                <v-expansion-panel-content class="v-expansion-header-dark">
                  <div slot="header">
                    <v-list-tile>
                      <v-icon color="primary" class="mr-2">info</v-icon> Datos básicos del {{type}}
                    </v-list-tile>
                  </div>
                  <v-container grid-list-sm class="pa-2">
                    <v-layout row wrap>
                      <input-detail-flex
                        v-for="(prop, i) in item.datosBasicos"
                        :key="`ib_${i}`"
                        v-if="prop.text !== null"
                        :flex-class="prop.flexClass"
                        :label="prop.label"
                        :text="prop.text === 0 ? 'NO' : prop.text === 1 ? 'SI' : prop.text"
                        :hint="prop.hint"
                        :prepend-icon="prop.icon"
                      />
                    </v-layout>
                  </v-container>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-flex>
            <v-flex xs12>
              <v-slide-y-transition>
                <v-card v-if="!newDireccionamiento" class="elevation-0">
                  <v-card-title class="pa-0">
                    <p class="title ma-0">Direccionamientos<br/><span class="caption">Cantidad Total a Direccionar: <strong>{{Number(item.cantidadTotal)}}</strong></span></p>
                    <v-spacer/>
                    <v-tooltip left v-if="infoComponent && infoComponent.permisos && infoComponent.permisos.agregar">
                      <v-btn
                        :disabled="cantidadDireccionamientos >= Number(item.cantidadTotal)"
                        slot="activator"
                        fab
                        right
                        small
                        color="accent"
                        @click="newDireccionamiento = true"
                      >
                        <v-icon>add</v-icon>
                      </v-btn>
                      <span v-text="cantidadDireccionamientos >= Number(item.cantidadTotal) ? 'Ya está cubierta la cantidad total a direccionar' : 'Crear direccionamiento'"></span>
                    </v-tooltip>
                  </v-card-title>
                  <v-divider/>
                  <v-data-table
                    :items="item.direccionamientos"
                    hide-actions
                    :headers="headers"
                    no-data-text="No hay direccionamientos registrados"
                  >
                    <template slot="items" slot-scope="props">
                      <td>{{props.item.IdDireccionamiento}}</td>
                      <td>{{props.item.FecMaxEnt ? moment(props.item.FecMaxEnt).format('DD-MM-YYYY') : '' }}</td>
                      <td class="text-xs-center">{{props.item.CantTotAEntregar}}</td>
                      <td class="text-xs-center">{{props.item.CodSerTecAEntregar}}</td>
                      <td>{{props.item.entidad ? props.item.entidad.nombre : ''}}</td>
                      <td>{{props.item.entidad ? (props.item.entidad.municipio.nombre + ', ' + props.item.entidad.municipio.nombre_departamento) : ''}}</td>
                      <td class="text-xs-center">
                        <v-tooltip right v-if="props.index === item.direccionamientos.length - 1">
                          <v-btn flat icon slot="activator" color="danger" @click.stop="anularDireccionamiento(props)">
                            <v-icon medium>delete_forever</v-icon>
                          </v-btn>
                          <span>Eliminar</span>
                        </v-tooltip>
                      </td>
                    </template>
                  </v-data-table>
                </v-card>
              </v-slide-y-transition>
              <v-slide-y-transition mide="out-in">
                <v-card v-show="newDireccionamiento" class="elevation-0">
                  <v-card-title class="pa-0">
                    <span class="title">Nuevo direccionamiento</span>
                  </v-card-title>
                  <v-layout row wrap>
                    <v-flex xs12>
                      <postulador-v2
                        ref="postuladorEntidad"
                        no-data="Busqueda por NIT, razon social u código de habilitación."
                        hint="tercero.identificacion_completa"
                        item-text="nombre"
                        data-title="nombre"
                        data-subtitle="tercero.identificacion_completa"
                        label="Entidad"
                        entidad="entidades"
                        preicon="location_city"
                        v-model="dialog.entidad"
                        name="entidad"
                        rules="required"
                        v-validate="'required'"
                        :error-messages="errors.collect('entidad')"
                        no-btn-create
                        :min-characters-search="3"
                      />
                    </v-flex>
                    <v-flex xs12 v-if="codigoServicio.visible">
                      <v-autocomplete
                        v-if="codigoServicio.tipo === 'CUM'"
                        ref="autocompleteCUM"
                        :label="'Código servicio o tecnología: ' + this.codigoServicio.tipo"
                        v-model="codigoServicio.objeto"
                        item-text="descripcioncomercial"
                        :items="cumsRemotos"
                        :loading="loadingCUM"
                        :search-input.sync="searchCUM"
                        no-filter
                        placehoder="Buscar por código, descripción comercial o principo activo"
                        no-data-text="No hay resultados para mostrar"
                        return-object
                        name="Código servicio"
                        v-validate="'required'"
                        :error-messages="errors.collect('Código servicio')"
                      >
                        <template slot="selection" slot-scope="data">
                          <div style="width: 100% !important;">
                            <v-list-tile>
                              <v-list-tile-content>
                                <v-list-tile-title>{{data.item.expediente}}-{{ data.item.consecutivocum }} | {{data.item.principioactivo}}</v-list-tile-title>
                                <v-list-tile-sub-title class=caption>{{data.item.producto}}</v-list-tile-sub-title>
                                <v-list-tile-sub-title class=caption>{{ data.item.descripcioncomercial }}</v-list-tile-sub-title>
                              </v-list-tile-content>
                            </v-list-tile>
                            <v-divider/>
                          </div>
                        </template>
                        <template slot="item" slot-scope="data">
                          <div style="width: 100% !important;" v-if="cumsRemotos">
                            <v-list-tile>
                              <v-list-tile-content>
                                <v-list-tile-title>{{data.item.expediente}}-{{ data.item.consecutivocum }} | {{data.item.principioactivo}}</v-list-tile-title>
                                <v-list-tile-sub-title class=caption>{{data.item.producto}}</v-list-tile-sub-title>
                                <v-list-tile-sub-title class=caption>{{ data.item.descripcioncomercial }}</v-list-tile-sub-title>
                              </v-list-tile-content>
                            </v-list-tile>
                            <v-divider/>
                          </div>
                        </template>
                      </v-autocomplete>
                      <postulador-v2
                        v-else-if="codigoServicio.tipo === 'CUP'"
                        ref="postuladorCUP"
                        no-data="Busqueda por código o nombre."
                        item-text="descripcion"
                        :label="'Código servicio o tecnología: ' + this.codigoServicio.tipo"
                        entidad="cups"
                        v-model="codigoServicio.objeto"
                        no-btn-create
                        no-btn-edit
                        name="Código servicio"
                        rules="required"
                        v-validate="'required'"
                        :error-messages="errors.collect('Código servicio')"
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
                      <v-text-field
                        v-else
                        :label="'Código servicio o tecnología: ' + this.codigoServicio.tipo"
                        v-model="data.CodSerTecAEntregar"
                        name="Código servicio"
                        v-validate="'required'"
                        :error-messages="errors.collect('Código servicio')"
                      />
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                      <v-text-field
                        label="Cantidad"
                        name="Cantidad"
                        min="0"
                        :max="cantidadMaxima"
                        type="number"
                        v-model.number="data.CantTotAEntregar"
                        v-validate="'required|min_value:0|max_value:' + cantidadMaxima"
                        :error-messages="errors.collect('Cantidad')"
                      />
                    </v-flex>
                    <v-flex xs12 sm6 md3>
                      <input-date
                        v-model="data.FecMaxEnt"
                        label="Fecha entrega"
                        hint="Formato: (Año-Mes-Día)"
                        name="Fecha entrega"
                        v-validate="'required|fechaValida|date_format:YYYY-MM-DD|after:' + fechaMinimaEntrega + ',true'"
                        :error-messages="errors.collect('Fecha entrega')"
                        :min="fechaMinimaEntrega"
                      />
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-slide-y-transition>
            </v-flex>
          </v-layout>
        </v-container>
        <v-divider/>
        <v-card-actions v-if="!newDireccionamiento">
          <v-spacer/>
          <v-btn :disabled="dialog.loading" @click="$emit('input', false)">Cerrar</v-btn>
        </v-card-actions>
        <v-card-actions v-else>
          <v-btn :disabled="dialog.loading" @click="newDireccionamiento = false">Cancelar</v-btn>
          <v-spacer/>
          <v-btn
            :loading="dialog.loading"
            :disabled="dialog.loading"
            color="blue darken-1"
            class="white--text"
            @click="submitDireccionamiento"
          >
            registrar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <confirmar
      :value="dialogA.visible"
      :loading="dialogA.loading"
      :content="dialogA.content"
      @cancelar="cancelaAnulacion"
      :requiere-motivo="false"
      @aceptar="aceptaAnulacion"
    />
  </v-container>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import store from '@/store/complementos/index'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import Soda from 'soda-js'
  export default {
    name: 'RegistroDireccionamientos',
    props: {
      parent: {
        type: Object,
        default: null
      },
      item: {
        type: Object,
        default: null
      },
      type: {
        type: String,
        default: 'medicamento'
      },
      value: {
        type: Boolean,
        default: false
      }
    },
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      Loading,
      Confirmar: () => import('@/components/general/Confirmar'),
      InputDate: () => import('@/components/general/InputDate'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex')
    },
    data: () => ({
      loadingCUM: false,
      searchCUM: null,
      cumsRemotos: [],
      codigoServicio: null,
      makeCodigoServicio: {
        visible: true,
        tipo: null,
        objeto: null
      },
      dialogA: {
        loading: false,
        visible: false,
        content: null,
        item: null
      },
      newDireccionamiento: false,
      cantidadMaxima: false,
      fechaMinimaEntrega: null,
      dialog: null,
      data: null,
      makeDialog: {
        show: false,
        loading: false,
        entidad: null
      },
      makeData: {
        id: null,
        IdDireccionamiento: null,
        mp_prescripcion_id: null,
        NoPrescripcion: null,
        TipoTec: null,
        ConTec: null,
        TipoIDPaciente: null,
        NoIDPaciente: null,
        as_afiliado_id: null,
        NoEntrega: null,
        NoSubEntrega: null,
        TipoIDProv: null,
        NoIDProv: null,
        rs_entidade_id: null,
        CodMunEnt: null,
        gn_munentidad_id: null,
        FecMaxEnt: null,
        CantTotAEntregar: null,
        DirPaciente: null,
        CodSerTecAEntregar: null,
        mp_medicamento_id: null,
        mp_coplementario_id: null,
        mp_nutricional_id: null,
        mp_dispositivo_id: null,
        mp_procedimiento_id: null
      },
      headers: [
        {
          text: 'Código',
          sortable: false,
          value: 'IdDireccionamiento'
        },
        {
          text: 'Fecha entrega',
          sortable: false,
          value: 'FecMaxEnt'
        },
        {
          text: 'Cantidad',
          sortable: false,
          align: 'center',
          value: 'CantTotAEntregar'
        },
        {
          text: 'CodSerTecAEntregar',
          sortable: false,
          value: 'CodSerTecAEntregar'
        },
        {
          text: 'Proveedor',
          sortable: false,
          value: 'entidad.nombre'
        },
        {
          text: 'Municipio Proveedor',
          sortable: false,
          value: 'entidad.municipio.nombre'
        },
        {
          text: 'Opciones',
          sortable: false,
          align: 'center',
          value: 'id'
        }
      ]
    }),
    watch: {
      'value' (val) {
        if (!val) {
          setTimeout(() => this.resetAll(), 800)
        } else {

        }
      },
      'codigoServicio.objeto' (val) {
        this.data.CodSerTecAEntregar = val ? this.codigoServicio.tipo === 'CUM' ? (val.expediente + '-' + val.consecutivocum) : val.codigo : null
      },
      'searchCUM' (val) {
        val && this.buscarCUM()
      },
      'data.estado' (val) {
        if (val && this.dialog.show) {
          this.dialog.entidad = null
          this.data.farmacia_id = null
          this.$validator.reset()
        }
      },
      'newDireccionamiento' (val) {
        if (!val) {
          if (this.$refs.postuladorEntidad) this.$refs.postuladorEntidad.reset()
          this.resetData()
        } else {
          switch (this.item.TipoTec) {
            case 'M' :
              this.data.mp_medicamento_id = this.item.id
              switch (this.item.TipoMed) {
                default:
                  this.codigoServicio.tipo = 'CUM'
                  if (this.item.title) {
                    const rTemporal = this.item.title.split('[')
                    const rFinal = rTemporal[1] ? rTemporal[1].split(']') : null
                    rFinal[0] && this.buscarCUM(rFinal[0])
                  }
                  break
                case 2:
                  this.codigoServicio.tipo = 'Código IUM'
                  break
                case 3:
                  this.codigoServicio.visible = false
                  this.data.CodSerTecAEntregar = ' '
                  break
              }
              break
            case 'P' :
              this.codigoServicio.tipo = 'CUP'
              this.data.mp_procedimiento_id = this.item.id
              break
            case 'D' :
              this.codigoServicio.tipo = 'Código MIPRES'
              this.data.mp_dispositivo_id = this.item.id
              break
            case 'N' :
              this.codigoServicio.tipo = 'Expediente INVIMA'
              this.data.mp_nutricional_id = this.item.id
              break
            case 'S' :
              this.codigoServicio.tipo = 'Código MIPRES'
              this.data.mp_coplementario_id = this.item.id
              break
          }
          this.cantidadMaxima = Number(this.item.cantidadTotal) - this.cantidadDireccionamientos
          this.fechaMinimaEntrega = this.item.direccionamientos.length ? (window.lodash.last(this.item.direccionamientos).FecMaxEnt) : (this.parent.NoPrescripcion ? this.parent.FPrescripcion : this.parent.FTutela)
        }
      }
    },
    computed: {
      cantidadDireccionamientos () {
        return this.item.direccionamientos.length ? window.lodash.sum(this.item.direccionamientos.map(x => x.CantTotAEntregar)) : 0
      },
      complementos () {
        return this.parent.NoPrescripcion ? store.getters.complementosTablaPrescripciones : store.getters.complementosTablaTutelas
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent(this.parent.NoPrescripcion ? 'prescripciones' : 'tutelasMipres')
      }
    },
    created () {
      this.resetAll()
    },
    methods: {
      getRows (point, parametro, param) {
        return new Promise((resolve, reject) => {
          let urlComplement = isNaN(Number(param[0])) ? `descripcioncomercial like "%${param[0].toUpperCase()}%" OR principioactivo like "%${param[0].toUpperCase()}%"` : `expediente = ${param[0]}`
          const config = {
            hostDomain: 'datos.gov.co',
            resource: point
          }
          const api = new Soda.Consumer(config.hostDomain)
          api.query()
            .withDataset(config.resource)
            .limit(50)
            .where(urlComplement)
            .getRows()
            .on('success', (rows) => {
              resolve(rows)
            })
            .on('error', (error) => {
              this.loadingCUM = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer los CUMS del API. `, error: error})
              reject(error)
            })
        })
      },
      async getDataCUMS (parametro) {
        let param = !parametro ? this.searchCUM.split('-') : parametro.split('-')
        let rows1 = await this.getRows('i7cb-raxc', parametro, param)
        let rows2 = await this.getRows('vgr4-gemg', parametro, param)
        this.loadingCUM = false
        let rowsTotal = rows1.concat(rows2).sort((a, b) => a.expediente - b.expediente).sort((a, b) => a.consecutivocum - b.consecutivocum)
        console.log('total', rowsTotal)
        this.cumsRemotos = param[1] ? rowsTotal.filter(x => x.consecutivocum.search(param[1]) !== -1) : rowsTotal
      },
      buscarCUM: window.lodash.debounce(function (parametro) {
        console.log('como va', this.searchCUM)
        if (this.searchCUM || parametro) {
          this.loadingCUM = true
          this.getDataCUMS(parametro)
        }
      }, 500),
      resetAll () {
        this.newDireccionamiento = false
        this.dialog = JSON.parse(JSON.stringify(this.makeDialog))
        this.resetData()
      },
      resetData () {
        this.dialog.entidad = null
        this.cantidadMaxima = null
        this.fechaMinimaEntrega = null
        this.data = JSON.parse(JSON.stringify(this.makeData))
        this.codigoServicio = JSON.parse(JSON.stringify(this.makeCodigoServicio))
        this.$validator.reset()
      },
      anularDireccionamiento (item) {
        this.dialogA.item = JSON.parse(JSON.stringify(item))
        this.dialogA.content = `El direccionamiento No. ${this.dialogA.item.item.IdDireccionamiento} será eliminado y el registro no se podrá ver ni cambiar de estado. </br><strong>¿Está seguro de continuar?</strong>`
        this.dialogA.visible = true
      },
      cancelaAnulacion () {
        this.dialogA.visible = false
        this.dialogA.loading = false
        this.dialogA.content = null
        this.dialogA.item = null
      },
      async aceptaAnulacion () {
        this.dialogA.loading = true
        this.axios.delete(`direccionamientos/${this.dialogA.item.item.id}`)
          .then(() => {
            this.$store.commit(SNACK_SHOW, {msg: `El direccionamiento se anuló correctamente.`, color: 'success'})
            this.item.direccionamientos.splice(this.dialogA.item.index, 1)
            this.cancelaAnulacion()
          })
          .catch(e => {
            let serverError = ''
            if (e.response.data.message && (e.response.data.message.ModelState || e.response.data.message.Message)) {
              e.response.data.errors = e.response.data.message.ModelState
              serverError = ' en el servidor MIPRES'
            }
            this.dialogA.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: `${serverError} al realizar la anulación del direccionamiento. `, error: e})
          })
      },
      submitDireccionamiento () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.dialog.loading = true
            this.data.mp_prescripcion_id = this.parent.NoPrescripcion ? this.parent.id : null
            this.data.mp_tutela_id = this.parent.NoPrescripcion ? null : this.parent.id
            this.data.NoPrescripcion = this.parent.NoPrescripcion ? this.parent.NoPrescripcion : this.parent.NoTutela
            this.data.TipoTec = this.item.TipoTec
            this.data.ConTec = this.item.consecutivo
            this.data.TipoIDPaciente = this.parent.TipoIDPaciente
            this.data.NoIDPaciente = this.parent.NroIDPaciente
            this.data.as_afiliado_id = this.parent.as_afiliado_id
            this.data.TipoIDProv = 'NI'
            this.data.NoIDProv = this.dialog.entidad.tercero ? this.dialog.entidad.tercero.identificacion : null
            this.data.rs_entidade_id = this.dialog.entidad.id
            this.data.CodMunEnt = this.dialog.entidad.municipio.codigo
            this.data.gn_munentidad_id = this.dialog.entidad.municipio.id
            this.data.DirPaciente = this.parent.afiliado ? this.parent.afiliado.direccion : null
            this.data.NoEntrega = this.item.direccionamientos.length + 1
            this.data.NoSubEntrega = 0

            this.axios.post('direccionamientos', this.data)
              .then(response => {
                this.item.direccionamientos.push(response.data.data)
                this.$store.commit(SNACK_SHOW, {msg: `El direccionamiento se registró satisfactoriamente.`, color: 'success'})
                this.dialog.loading = false
                this.resetAll()
              })
              .catch(e => {
                let serverError = ''
                if (e.response.data.message && (e.response.data.message.ModelState || e.response.data.message.Message)) {
                  e.response.data.errors = (e.response.data.message.Errors && e.response.data.message.Errors.length) ? Object.assign({}, e.response.data.message.Errors) : e.response.data.message.ModelState
                  serverError = ' en el servidor MIPRES'
                }
                this.dialog.loading = false
                this.$store.commit(SNACK_ERROR_LIST, {expression: `${serverError} al realizar el registro del direccionamiento. `, error: e})
              })
          }
        })
      }
    }
  }
</script>

<style scoped>

</style>
