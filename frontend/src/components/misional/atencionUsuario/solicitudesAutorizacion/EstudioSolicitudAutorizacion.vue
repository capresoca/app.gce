<template>
  <v-card>
    <loading v-model="loading"/>
    <v-toolbar dense class="elevation-0">
      <v-icon>ballot</v-icon>
      <v-toolbar-title>Estudio solicitud de autorización</v-toolbar-title>
      <v-spacer/>
      <v-chip label color="white" text-color="red" dark absolute right top v-if="item">
        <span class="subheading">Solicitud de autorización N°</span>&nbsp;
        <span class="subheading" v-text="item.consecutivo == null ? '000' : item.consecutivo"></span>
      </v-chip>
      <v-tooltip
        top
      >
        <v-btn
          slot="activator"
          flat
          icon
          large
          color="blue-grey"
          @click.stop="imprimir"
        >
          <v-icon large v-text="'far fa-file-pdf'"/>
        </v-btn>
        <span>Imprimir solicitud</span>
      </v-tooltip>
    </v-toolbar>

    <v-container v-if="item" fluid grid-list-sm>
      <v-layout row wrap>
        <v-flex xs12 sm12 md12>
          <v-expansion-panel class="v-expansion-panel-pi">
            <v-expansion-panel-content>
              <div slot="header" class="pa-0">
                <v-toolbar dense class="elevation-0" color="rgba(0, 0, 0, 0)">
                  <v-icon>list_alt</v-icon>
                  <v-toolbar-title>Información base</v-toolbar-title>
                  <v-spacer/>
                  <v-tooltip
                    top
                  >
                    <v-btn
                      slot="activator"
                      flat
                      icon
                      large
                      color="blue-grey"
                      @click.stop="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleAutorizacion', titulo: 'Detalle autorización', parametros: {entidad: {id: item.autorizacion.id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
                    >
                      <v-icon x-large v-text="'more_horiz'"/>
                    </v-btn>
                    <span>Ver autorización base</span>
                  </v-tooltip>
                </v-toolbar>
              </div>
              <v-divider/>
              <v-card>
                <v-card-text>
                  <v-layout row wrap>
                    <input-detail-flex
                      flex-class="xs12 sm8 md9"
                      label="Afiliado"
                      :text="item.autorizacion.afiliado.nombre_completo"
                      :hint="item.autorizacion.afiliado.identificacion_completa"
                      prepend-icon="person"
                      :suffix="`Estado: ${item.autorizacion.afiliado.estado_afiliado.codigo} - ${item.autorizacion.afiliado.estado_afiliado.nombre}`"
                      :append-button="{tooltip: 'ver detalle', icon: 'more_horiz', color: 'blue-grey'}"
                      @clickAppend="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: item.autorizacion.afiliado, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
                    />
                    <input-detail-flex
                      flex-class="xs12 sm4 md3"
                      label="Regimen"
                      :text="item.autorizacion.afiliado.regimen.codigo + ' - ' + item.autorizacion.afiliado.regimen.nombre"
                    />
                    <input-detail-flex
                      flex-class="xs12"
                      label="Diagnóstico Principal"
                      :text="item.autorizacion.cie10_principal.descripcion"
                      :hint="item.autorizacion.cie10_principal.codigo"
                      prepend-icon="local_hospital"
                    />
                    <input-detail-flex
                      v-if="item.autorizacion.cie10_rel1"
                      flex-class="xs12"
                      label="Diagnóstico Relacionado 1"
                      :text="item.autorizacion.cie10_rel1.descripcion"
                      :hint="item.autorizacion.cie10_rel1.codigo"
                      prepend-icon="queue"
                    />
                    <input-detail-flex
                      v-if="item.autorizacion.cie10_rel2"
                      flex-class="xs12"
                      label="Diagnóstico Relacionado 2"
                      :text="item.autorizacion.cie10_rel2.descripcion"
                      :hint="item.autorizacion.cie10_rel2.codigo"
                      prepend-icon="queue"
                    />
                    <input-detail-flex
                      flex-class="xs12 sm6 md8"
                      label="IPS Origen"
                      :text="item.autorizacion.entidad_origen.nombre"
                      :hint="item.autorizacion.entidad_origen.tercero.identificacion_completa"
                      prepend-icon="location_city"
                    />
                    <input-detail-flex
                      flex-class="xs12 sm6 md4"
                      label="Tipo Origen"
                      :text="item.autorizacion.tipo_origen"
                    />
                    <input-detail-flex
                      flex-class="xs12 sm6 md8"
                      label="Contrato"
                      :text="item.autorizacion.contrato.entidad.nombre"
                      :hint="'Contrato No. ' + item.autorizacion.contrato.numero_contrato"
                      prepend-icon="location_city"
                    />
                    <input-detail-flex
                      flex-class="xs12 sm6 md4"
                      label="Tipo Destino"
                      :text="item.autorizacion.tipo_destino"
                    />
                    <input-detail-flex
                      flex-class="xs12 sm6 md8"
                      label="Servicio"
                      :text="serviciosContratados && item.autorizacion.rs_servicio_id && serviciosContratados.find(x => x.id === item.autorizacion.rs_servicio_id) && (serviciosContratados.find(x => x.id === item.autorizacion.rs_servicio_id).servicio.codigo + ' - ' + serviciosContratados.find(x => x.id === item.autorizacion.rs_servicio_id).servicio.nombre)"
                    />
                    <input-detail-flex
                      flex-class="xs12 sm6 md4"
                      label="Modalidad servicio"
                      :text="complementos && complementos.modalidadesServicio && item.autorizacion.au_modservicio_id && complementos.modalidadesServicio.find(x => x.id === item.autorizacion.au_modservicio_id) && (complementos.modalidadesServicio.find(x => x.id === item.autorizacion.au_modservicio_id).codigo + ' - ' + complementos.modalidadesServicio.find(x => x.id === item.autorizacion.au_modservicio_id).tipo)"
                      :hint="complementos && complementos.modalidadesServicio && item.autorizacion.au_modservicio_id && complementos.modalidadesServicio.find(x => x.id === item.autorizacion.au_modservicio_id) && complementos.modalidadesServicio.find(x => x.id === item.autorizacion.au_modservicio_id).modalidad"
                    />
                    <v-flex dflex>
                      <v-checkbox readonly v-model="item.autorizacion.alto_costo" label="Alto costo" :false-value="0" :true-value="1"/>
                    </v-flex>
                    <v-flex dflex>
                      <v-checkbox readonly v-model="item.autorizacion.pyp" label="P y P" :false-value="0" :true-value="1"/>
                    </v-flex>
                    <v-flex dflex>
                      <v-checkbox readonly v-model="item.autorizacion.atencion_materna" label="Atención materna" :false-value="0" :true-value="1"/>
                    </v-flex>
                    <v-flex dflex>
                      <v-checkbox readonly v-model="item.autorizacion.enfermedad_trasmisible" label="Enfermedad trasmisible" :false-value="0" :true-value="1"/>
                    </v-flex>
                    <v-flex dflex>
                      <v-checkbox readonly v-model="item.autorizacion.catastrofe" label="Catastrofe" :false-value="0" :true-value="1"/>
                    </v-flex>
                    <v-flex xs12 sm12 md6 v-if="tutelas.length">
                      <v-layout align-center fill-height>
                        <v-flex xs1 dflex>
                          <v-checkbox readonly v-model="marcaTutela" :label="marcaTutela ? '' : 'Tutela'"></v-checkbox>
                        </v-flex>
                        <input-detail-flex
                          label="Tutela"
                          :text="tutelas && item.autorizacion.oj_tutela_id && tutelas.find(x => x.id === item.autorizacion.oj_tutela_id) && (tutelas.find(x => x.id === item.autorizacion.oj_tutela_id).no_tutela + ' - ' + tutelas.find(x => x.id === item.autorizacion.oj_tutela_id).tipo_tutela)"
                          :hint="tutelas && item.autorizacion.oj_tutela_id && tutelas.find(x => x.id === item.autorizacion.oj_tutela_id) && ('Fecha notificación: ' + tutelas.find(x => x.id === item.autorizacion.oj_tutela_id).fecha_notificacion + ' | Estado actual: ' + tutelas.find(x => x.id === item.autorizacion.oj_tutela_id).estado)"
                          :append-button="{tooltip: 'ver detalle', icon: 'more_horiz', color: 'blue-grey'}"
                          @clickAppend="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleTutela', titulo: 'Detalle tutela', parametros: {entidad: {id: item.autorizacion.oj_tutela_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
                        />
                      </v-layout>
                    </v-flex>
                    <input-detail-flex
                      flex-class="xs12"
                      label="Observaciones"
                      :text="item.autorizacion.observaciones"
                    />
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-flex>
        <input-detail-flex
          :flex-class="'xs12 sm12 ' + (item.anexo3 ? 'md12' : 'md6')"
          label="Afiliado"
          :text="item.autorizacion.afiliado.nombre_completo"
          :hint="item.autorizacion.afiliado.identificacion_completa"
          prepend-icon="person"
          :suffix="`Estado: ${item.autorizacion.afiliado.estado_afiliado.codigo} - ${item.autorizacion.afiliado.estado_afiliado.nombre}`"
          :append-button="{tooltip: 'ver detalle', icon: 'more_horiz', color: 'blue-grey'}"
          @clickAppend="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: item.autorizacion.afiliado, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
        />
        <input-detail-flex
          flex-class="xs12 sm6 md3"
          label="Fecha solicitud"
          :text="moment(item.fecha).format('DD/MM/YYYY  HH:mm')"
          prepend-icon="event"
        />
        <input-detail-flex
          flex-class="xs12 sm6 md3"
          label="Estado"
          :text="item.estado"
        />
        <input-detail-flex
          v-if="item.anexo3"
          flex-class="xs12 sm6 md3"
          label="Cama"
          :text="item.cama"
        />
        <input-detail-flex
          v-if="item.anexo3"
          flex-class="xs12 sm12 md12"
          label="Justificación clínica"
          :text="item.justificacion_clinica"
        />
        <template>
          <input-detail-flex
            v-if="!item.anexo3"
            flex-class="xs12 sm7 md9"
            label="Orden Médica"
            prepend-icon="description"
            :text="item.autorizacion.orden_medica.nombre"
            :hint="'Extenciones aceptadas: pdf'"
            :append-button="{tooltip: 'Ir al archivo', icon: 'link', color: 'blue-grey'}"
            @clickAppend="goRuta(item.autorizacion.orden_medica.url_signed)"
          />
          <input-detail-flex
            v-if="!item.anexo3"
            flex-class="xs12 sm5 md3"
            label="Fecha orden médica"
            prepend-icon="event"
            :text="item.autorizacion.fecha_orden"
          />
          <input-detail-flex
            flex-class="xs12"
            label="Historia Clínica"
            prepend-icon="description"
            :text="item.autorizacion.historia_clinica.nombre"
            :hint="'Extenciones aceptadas: pdf'"
            :append-button="{tooltip: 'Ir al archivo', icon: 'link', color: 'blue-grey'}"
            @clickAppend="goRuta(item.autorizacion.historia_clinica.url_signed)"
          />
        </template>
      </v-layout>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card>
            <v-toolbar dense class="elevation-0">
              <v-icon>assignment</v-icon>
              <v-toolbar-title>Items de la solicitud de autorización</v-toolbar-title>
            </v-toolbar>
            <v-data-table
              :headers="headersDetails"
              :items="item.detalles"
              hide-actions
              class="elevation-1 table-detalle-solicitud"
              no-data-text="No hay detalles registrados"
            >
              <template slot="headers" slot-scope="props">
                <tr>
                  <th
                    v-for="(header, index) in props.headers"
                    :key="header.text"
                    :class="!header.align ? 'text-xs-left' : ('text-xs-' + header.align)"
                    v-if="index !== 0 || (index === 0 && calculoPagoError !== null)"
                  >
                    {{ header.text }}
                  </th>
                </tr>
              </template>
              <template slot="items" slot-scope="props">
                <td class="text-xs-center" width="20px" v-if="calculoPagoError !== null">
                  <v-tooltip top>
                    <v-icon @click="" :color="props.item.status ? 'success' : 'error'" slot="activator">{{props.item.status ? 'verified_user' : 'warning'}}</v-icon>
                    <span>{{props.item.status ? 'OK' : 'En conflicto'}}</span>
                  </v-tooltip>
                </td>
                <td>
                  {{ props.item.rs_cum_id ? 'Medicamento' : props.item.rs_cups_id ? 'Procedimiento' : 'Otro' }}
                  <!--<v-tooltip top v-if="props.item.requiere_aprobacion">-->
                    <!--<v-btn-->
                      <!--flat-->
                      <!--icon-->
                      <!--class="ma-0"-->
                      <!--color="warning"-->
                      <!--slot="activator"-->
                    <!--&gt;-->
                      <!--<v-icon>warning</v-icon>-->
                    <!--</v-btn>-->
                    <!--<span>Requiere aprobación</span>-->
                  <!--</v-tooltip>-->
                </td>
                <td>{{ props.item.codigo }}</td>
                <td>{{ props.item.descripcion }}</td>
                <td class="text-xs-center" width="200px">{{props.item.cantidad_solicitada}}</td>
                <td class="text-xs-center" width="200px">
                  <v-text-field
                    :key="props.index"
                    :name="'cantidad' + props.index"
                    data-vv-as="Cantidad aprobada"
                    v-validate="'min_value:0|max_value:' + (props.item.cantidad_solicitada ? props.item.cantidad_solicitada : 0)"
                    min="0"
                    :max="props.item.cantidad_solicitada ? props.item.cantidad_solicitada : 0"
                    type="number"
                    v-model.number="props.item.cantidad_aprobada"
                    :error-messages="errors.collect('cantidad' + props.index)"
                    @input="reloadDetail(props.item)"
                  />
                </td>
                <td class="text-xs-right">{{'$'}}{{ props.item.valor | numberFormat(2) }}</td>
                <td class="text-xs-right">{{'$'}}{{ props.item.valorTotal | numberFormat(2) }}</td>
              </template>
              <template slot="footer">
                <template v-if="!calculoPagoError">
                  <tr>
                    <td :colspan="headersDetails.length - 2" class="text-xs-right">
                      <strong>Valor EPS</strong>
                    </td>
                    <td class="text-xs-right">
                      {{'$'}}{{ valorEPS | numberFormat(2) }}
                    </td>
                  </tr>
                  <tr>
                    <td :colspan="headersDetails.length - 2" class="text-xs-right">
                      <strong>Valor Usuario</strong>
                    </td>
                    <td class="text-xs-right">
                      {{'$'}}{{ valorUsuario | numberFormat(2) }}
                    </td>
                  </tr>
                </template>
                <template v-else>
                  <tr>
                    <td :colspan="calculoPagoError ? (headersDetails.length) : (headersDetails.length -1)" class="text-xs-center">
                      <v-alert
                        :value="true"
                        type="error"
                      >
                        <span v-if="calculoPagoError">{{calculoPagoError}}</span>
                      </v-alert>
                    </td>
                  </tr>
                </template>
                <tr>
                  <td :colspan="headersDetails.length - 2" class="text-xs-right">
                    <strong>TOTAL</strong>
                  </td>
                  <td class="text-xs-right">
                    {{'$'}}{{ valorTotal | numberFormat(2) }}
                  </td>
                </tr>
              </template>
            </v-data-table>
          </v-card>
        </v-flex>
        <input-detail-flex
          v-if="item.anexo3"
          flex-class="xs12 sm12 md12"
          label="Nombre Solicita"
          :text="item.nombre_solicita"
        />
        <input-detail-flex
          v-if="item.anexo3"
          flex-class="xs12 sm6 md6"
          label="Teléfono Solicita"
          :text="item.telefono_solicita"
        />
        <input-detail-flex
          v-if="item.anexo3"
          flex-class="xs12 sm6 md6"
          label="Cargo Solicita"
          :text="item.cargo_solicita"
        />
        <v-flex xs12 v-if="resultado.estado === 'Negada'">
          <v-textarea
            v-model="item.justificacion_negacion"
            auto-grow
            label="Justificación de negación"
            rows="1"
            name="Justificación de negación"
            v-validate="'required'"
            :error-messages="errors.collect('Justificación de negación')"
          />
        </v-flex>
      </v-layout>
      <confirmar :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaConfirmacion" :requiere-motivo="false" @aceptar="aceptaConfirmacion" />
    </v-container>
    <v-divider/>
    <v-card-actions>
      <v-spacer/>
      <v-btn @click="guardarConfirmar" :color="resultado.color">{{'Guardar y ' + resultado.accion}}</v-btn>
      <v-btn @click="submit" color="primary">Guardar</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import store from '@/store/complementos/index'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {AU_SOLICITUD_AUTORIZACION_RELOAD_ITEM} from '@/store/modules/general/tables'
  import {NAV_RESPONSE_CONTENT_ITEM} from '@/store/modules/general/nav'
  import { Validator } from 'vee-validate'
  export default {
    name: 'EstudioSolicitudAutorizacion',
    props: ['parametros'],
    components: {
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      Confirmar: () => import('@/components/general/Confirmar'),
      Loading
    },
    data () {
      return {
        menuDate: false,
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10),
        loading: false,
        headersDetails: [
          { text: 'Status', align: 'center', sortable: false },
          { text: 'Tipo', sortable: false },
          { text: 'Código', sortable: false },
          { text: 'Descripción', sortable: false },
          { text: 'Cantidad solicitada', align: 'center', sortable: false },
          { text: 'Cantidad aprobada', align: 'center', sortable: false },
          { text: 'Valor unitario', align: 'right', sortable: false },
          { text: 'Valor total', align: 'right', sortable: false }
        ],
        tipoDetalle: {id: 1, ruta: 'cumcontratados', text: 'Medicamentos'},
        tiposDetalle: [{id: 1, ruta: 'cumcontratados', text: 'Medicamentos'}, {id: 2, ruta: 'cupcontratados', text: 'Procedimientos'}, {id: 3, ruta: 'otroscontratados', text: 'Otros'}],
        marcaTutela: false,
        fab: false,
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        item: null,
        orden_medica: null,
        historia_clinica: null,
        serviciosContratados: [],
        tutelas: [],
        cuotaCopagos: null,
        rangoActivo: null,
        calculoPagoError: null,
        tipoValorPermitido: null,
        valorTotal: 0,
        valorEPS: 0,
        valorUsuario: 0,
        generaSolicitud: false,
        resultado: {
          estado: null,
          accion: null,
          color: null
        }
      }
    },
    watch: {
      'resultado.estado' (val, prev) {
        if (prev && val !== prev) this.item.justificacion_negacion = null
      },
      'item.autorizacion.contrato' (val) {
        if (val) {
          this.item.autorizacion.rs_contrato_id = val.id
          let servicios = val.servicios_contratados ? val.servicios_contratados : []
          let servicio = servicios.find(x => x.id === this.item.autorizacion.rs_servicio_id)
          this.item.autorizacion.rs_servicio_id = servicio ? servicio.id : null
          this.serviciosContratados = servicios
        }
      },
      async 'item.autorizacion.afiliado' (val, prev) {
        if (val) {
          this.item.autorizacion.afiliado.total_autorizado = await this.getTotalAutorizado()
          if (this.cuotaCopagos === null) this.cuotaCopagos = await this.getCuotaCopagos()
          this.getRangoActivo()
          this.tutelas = val.tutelas ? val.tutelas : []
          this.reloadPagos()
        }
        if (prev && val !== prev) {
          this.item.cie10_principal = null
          this.item.cie10_rel1 = null
          this.item.cie10_rel2 = null
          this.$refs.postuladorCie10P.reset()
          this.$refs.postuladorCie10S.reset()
          this.$refs.postuladorCie10T.reset()
        }
      },
      'tutelas' (val) {
        if (val) {
          let tutela = val.find(x => x.id === this.item.autorizacion.oj_tutela_id)
          this.item.autorizacion.oj_tutela_id = tutela ? tutela.id : null
          this.marcaTutela = this.item.autorizacion.oj_tutela_id !== null
        }
      },
      'item.detalles' (val) {
        if (val) {
          if (val.length) {
            this.tipoValorPermitido = val[val.length - 1].tipo_valor
            this.reloadStatus()
          } else {
            this.tipoValorPermitido = null
          }
          this.generaSolicitud = !!val.find(x => x.requiere_aprobacion)
          this.valorTotal = this.getValorTotal()
        }
      },
      'valorTotal' () {
        this.reloadPagos()
      }
    },
    computed: {
      complementos () {
        return store.getters.complementosFormAutorizacion
      },
      okChecks () {
        return this.item.autorizacion && (this.item.autorizacion.alto_costo || this.item.autorizacion.pyp || this.item.autorizacion.atencion_materna || this.item.autorizacion.enfermedad_trasmisible || this.item.autorizacion.catastrofe)
      }
    },
    created () {
      this.getData()
    },
    beforeMount () {
      Validator.extend('afiliadoValidoSolicitudAutorizacion', {
        validate: (value) => new Promise((resolve) => {
          if ((value !== null) && (typeof value !== 'undefined') && (value !== '')) {
            let response = (this.item && this.item.as_afiliado_id && this.item.afiliado)
              ? (this.item.afiliado.estado === 3)
                ? {valido: true, mensaje: null}
                : {valido: false, mensaje: `El afiliado seleccionado no es valido para este trámite, su estado debe ser: ${this.complementos.estadosAfiliado.find(x => x.id === 3).nombre}.`}
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
      imprimir () {
        this.axios.get('firmar-ruta?nombre_ruta=autsolicitude&id=' + this.item.id)
          .then(response => {
            if (response.data !== '') {
              window.open(response.data, '_blank')
            }
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al generar el documento. ', error: e})
          })
      },
      goRuta (url) {
        window.open(url, '_blank')
      },
      reloadStatus () {
        this.item.detalles.forEach(x => {
          x.status = (x.tipo_valor === this.tipoValorPermitido)
        })
      },
      getRangoActivo () {
        if (this.cuotaCopagos && this.cuotaCopagos.length && this.item.autorizacion.afiliado) {
          if (this.item.autorizacion.afiliado.as_regimene_id === 2) {
            this.rangoActivo = this.cuotaCopagos.find(x => x.grupo === 'Sisben Nivel 2')
          } else {
            let salMinimosCotizante = (this.item.autorizacion.afiliado.ingreso_total / this.cuotaCopagos[0].salminimo.valor)
            let rangosContributivos = JSON.parse(JSON.stringify(this.cuotaCopagos))
            rangosContributivos.splice(this.cuotaCopagos.findIndex(x => x.grupo === 'Sisben Nivel 2'), 1)
            this.rangoActivo = rangosContributivos.find(x =>
              (x.limite_inferior_salario === 'Incluido' ? (salMinimosCotizante >= x.salminimos_inicio) : (salMinimosCotizante > x.salminimos_inicio)) &&
              (x.limite_superior_salario === 'Incluido' ? (salMinimosCotizante <= x.salminimos_fin) : (salMinimosCotizante < x.salminimos_fin))
            )
          }
        }
      },
      getValorTotal () {
        return window.lodash.sum(this.item.detalles.map(x => x.valorTotal))
      },
      async reloadPagos () {
        let cantidadSolicitada = window.lodash.sum(this.item.detalles.map(x => x.cantidad_solicitada)) ? window.lodash.sum(this.item.detalles.map(x => x.cantidad_solicitada)) : 0
        let cantidadAprobada = window.lodash.sum(this.item.detalles.map(x => x.cantidad_aprobada)) ? window.lodash.sum(this.item.detalles.map(x => x.cantidad_aprobada)) : 0
        this.resultado = cantidadAprobada <= 0
          ? {estado: 'Negada', accion: 'Negar', color: 'danger'}
          : cantidadAprobada < cantidadSolicitada
            ? {estado: 'Autorizada Parcialmente', accion: 'Autorizar Parcialmente', color: 'warning'}
            : {estado: 'Autorizada', accion: 'Autorizar', color: 'success'}
        if (!this.rangoActivo || (this.tipoValorPermitido !== null && this.item.detalles.length && this.item.detalles.filter(x => x.tipo_valor !== this.tipoValorPermitido).length)) {
          if (!this.rangoActivo) {
            this.calculoPagoError = 'No existen rangos configurados o el afiliado no aplica a ninguno de los rangos, ésto es indispensable para realizar el calculo de la liquidación de la autorización.'
          } else {
            this.calculoPagoError = 'No se puede generar Copago y Cuota moderadora en una misma autorización, hay items que infringen éste mandato. Para continuar, remueva los items con status "En conflicto".'
          }
        } else {
          this.calculoPagoError = null
          if (this.item.autorizacion.afiliado && ((this.item.autorizacion.afiliado.as_regimene_id === 2 && this.item.autorizacion.afiliado.nivel_sisben >= 2 && !this.okChecks) || (this.item.autorizacion.afiliado.as_regimene_id === 1))) {
            if ((this.item.autorizacion.afiliado.as_regimene_id === 2 && this.item.autorizacion.afiliado.nivel_sisben >= 2)) {
              this.valorUsuario = this.valorTotal * (this.rangoActivo.copago / 100)
              this.valorUsuario = await this.getValorUsuarioTopes(this.valorUsuario)
            } else {
              if (this.tipoValorPermitido === 'Copago') {
                if (this.okChecks) {
                  this.valorUsuario = 0
                } else {
                  if (this.item.autorizacion.afiliado.as_tipafiliado_id === 1) {
                    this.valorUsuario = 0
                  } else {
                    this.valorUsuario = this.valorTotal * (this.rangoActivo.copago / 100)
                    this.valorUsuario = await this.getValorUsuarioTopes(this.valorUsuario)
                  }
                }
              } else {
                this.valorUsuario = this.valorTotal * (this.rangoActivo.cuota_moderadora / 100)
              }
            }
          } else {
            this.valorUsuario = 0
          }
        }
        this.valorEPS = this.valorTotal - this.valorUsuario
      },
      getValorUsuarioTopes (valor) {
        // regla tope por evento
        let valorTemporal = JSON.parse(JSON.stringify(valor))
        valorTemporal = (valorTemporal > this.rangoActivo.limite_evento) ? this.rangoActivo.limite_evento : valorTemporal
        // regla tope año
        let diferencia = (valorTemporal + this.item.autorizacion.afiliado.total_autorizado) - this.rangoActivo.limite_anio
        valorTemporal = (diferencia > 0) ? (valorTemporal - diferencia) : valorTemporal
        return valorTemporal
      },
      reloadDetail (detail) {
        detail.valorTotal = detail.cantidad_aprobada * detail.valor
        this.valorTotal = this.getValorTotal()
      },
      getTotalAutorizado () {
        return new Promise((resolve) => {
          this.axios.get(`afiliados/${this.item.autorizacion.afiliado.id}?copago_anual=1`)
            .then((response) => {
              if (response.data !== '') {
                resolve(response.data.data.copago_anual)
              }
            })
            .catch(e => {
              resolve(0)
              this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer el valor autorizado anual del afiliado. `, error: e})
            })
        })
      },
      getCuotaCopagos () {
        return new Promise((resolve) => {
          this.axios.get(`cuotacopagos`)
            .then((response) => {
              if (response.data !== '') {
                resolve(response.data.data)
              }
            })
            .catch(e => {
              resolve(false)
              this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer los registros activos de cuotas y copagos. `, error: e})
            })
        })
      },
      async getData () {
        this.loading = true
        this.axios.get(`${'autsolicitudes'}/${this.parametros.entidad.id}`)
          .then((response) => {
            if (response.data !== '') {
              response.data.data.detalles.forEach(x => {
                x.valorTotal = (x.cantidad_aprobada && x.valor) ? (x.cantidad_aprobada * x.valor) : 0
                x.status = true
              })
              this.orden_medica = response.data.data.autorizacion.orden_medica ? response.data.data.autorizacion.orden_medica.nombre : null
              this.historia_clinica = response.data.data.autorizacion.historia_clinica ? response.data.data.autorizacion.historia_clinica.nombre : null
              this.item = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer los datos de la solicitud de autorización. `, error: e})
          })
      },
      async guardarConfirmar () {
        if (await this.validado()) {
          this.dialogA.content = `<strong>La solicitud N° ${this.item.consecutivo} será ${this.resultado.estado},</strong> Si decide Aceptar, el formulario se procesará y no podrá editar estos datos.`
          this.dialogA.visible = true
        }
      },
      cancelaConfirmacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
      },
      async aceptaConfirmacion () {
        this.item.estado = this.resultado.estado
        await this.submit()
        this.cancelaConfirmacion()
      },
      async validado () {
        let errorForm = await this.$validator.validateAll()
        let errorDetails = this.item.detalles.length
        if (!errorDetails) {
          this.$store.commit(SNACK_SHOW, {msg: 'La solicitud de autorización debe tener al menos un item.', color: 'warning'})
        }
        return (errorForm && errorDetails && !this.calculoPagoError)
      },
      async submit () {
        if (await this.validado()) {
          this.loading = true
          this.axios.put(`autsolicitudes/${this.item.id}`, this.item)
            .then(response => {
              console.log('response solicitud', response)
              this.$store.commit(AU_SOLICITUD_AUTORIZACION_RELOAD_ITEM, {item: response.data.data, estado: 'reload', key: this.parametros.key})
              if (this.item.estado !== 'Confirmada') {
                this.$store.commit(SNACK_SHOW, {msg: 'La solicitud fue ' + this.item.estado + ' de forma correcta.', color: 'success'})
                this.$store.dispatch(NAV_RESPONSE_CONTENT_ITEM, { contexto: this, itemId: this.parametros.tabOrigin })
                setTimeout(() => {
                  this.$store.commit('NAV_ADD_ITEM', {ruta: 'detalleSolicitudAutorizacion', titulo: 'Detalle Solicitud Autorización', parametros: {entidad: response.data.data, tabOrigin: null}})
                }, 200)
              } else {
                this.$store.commit(SNACK_SHOW, {msg: 'El estudio de la solicitud se ha guardado correctamente.', color: 'success'})
              }
              this.loading = false
            }).catch(e => {
              this.loading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: `al guardar el registro de la autorización.`, error: e})
            })
        } else {
          this.item.estado = 'Confirmada'
        }
      }
    }
  }
</script>

<style>
  .table-detalle-solicitud table tbody td input{
    text-align: center !important;
  }
</style>
