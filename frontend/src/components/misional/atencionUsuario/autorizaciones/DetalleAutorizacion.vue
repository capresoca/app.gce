<template>
  <v-card>
    <loading v-model="loading"/>
    <v-toolbar dense class="elevation-0">
      <v-icon>assignment</v-icon>
      <v-toolbar-title>Detalle autorización</v-toolbar-title>
    </v-toolbar>
    <v-container v-if="item" fluid grid-list-xl>
      <v-layout row wrap>
        <input-detail-flex
          flex-class="xs12 sm8 md9"
          label="Afiliado"
          :text="item.afiliado.nombre_completo"
          :hint="item.afiliado.identificacion_completa"
          :prepend-icon="item.afiliado.emoticon"
          :suffix="`Estado: ${item.afiliado.estado_afiliado.codigo} - ${item.afiliado.estado_afiliado.nombre}`"
          :append-button="{tooltip: 'ver detalle', icon: 'more_horiz', color: 'blue-grey'}"
          @clickAppend="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: item.afiliado, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
        />
        <input-detail-flex
          flex-class="xs12 sm4 md3"
          label="Regimen"
          :text="item.afiliado.regimen.codigo + ' - ' + item.afiliado.regimen.nombre"
        />
        <input-detail-flex
          flex-class="xs12"
          label="Diagnóstico Principal"
          :text="item.cie10_principal.descripcion"
          :hint="item.cie10_principal.codigo"
          prepend-icon="local_hospital"
        />
        <input-detail-flex
          v-if="item.cie10_rel1"
          flex-class="xs12"
          label="Diagnóstico Relacionado 1"
          :text="item.cie10_rel1.descripcion"
          :hint="item.cie10_rel1.codigo"
          prepend-icon="queue"
        />
        <input-detail-flex
          v-if="item.cie10_rel2"
          flex-class="xs12"
          label="Diagnóstico Relacionado 2"
          :text="item.cie10_rel2.descripcion"
          :hint="item.cie10_rel2.codigo"
          prepend-icon="queue"
        />
        <input-detail-flex
          flex-class="xs12 sm6 md8"
          label="IPS Origen"
          :text="item.entidad_origen.nombre"
          :hint="item.entidad_origen.tercero.identificacion_completa"
          prepend-icon="location_city"
        />
        <input-detail-flex
          flex-class="xs12 sm6 md4"
          label="Tipo Origen"
          :text="item.tipo_origen"
        />
        <input-detail-flex
          flex-class="xs12 sm6 md8"
          label="Contrato"
          :text="item.contrato.contrato.entidad.nombre"
          :hint="'Contrato No. ' + item.contrato.contrato.numero_contrato"
          prepend-icon="location_city"
        />
        <input-detail-flex
          flex-class="xs12 sm6 md4"
          label="Tipo Destino"
          :text="item.tipo_destino"
        />
        <input-detail-flex
          flex-class="xs12 sm6 md8"
          label="Servicio"
          :text="serviciosContratados && item.rs_servicio_id && serviciosContratados.find(x => x.id === item.rs_servicio_id) && (serviciosContratados.find(x => x.id === item.rs_servicio_id).servicio.codigo + ' - ' + serviciosContratados.find(x => x.id === item.rs_servicio_id).servicio.nombre)"
        />
        <input-detail-flex
          flex-class="xs12 sm6 md4"
          label="Modalidad servicio"
          :text="complementos && complementos.modalidadesServicio && item.au_modservicio_id && complementos.modalidadesServicio.find(x => x.id === item.au_modservicio_id) && (complementos.modalidadesServicio.find(x => x.id === item.au_modservicio_id).codigo + ' - ' + complementos.modalidadesServicio.find(x => x.id === item.au_modservicio_id).tipo)"
          :hint="complementos && complementos.modalidadesServicio && item.au_modservicio_id && complementos.modalidadesServicio.find(x => x.id === item.au_modservicio_id) && complementos.modalidadesServicio.find(x => x.id === item.au_modservicio_id).modalidad"
        />
        <v-flex dflex>
          <v-checkbox readonly v-model="item.alto_costo" label="Alto costo" :false-value="0" :true-value="1"/>
        </v-flex>
        <v-flex dflex>
          <v-checkbox readonly v-model="item.pyp" label="P y P" :false-value="0" :true-value="1"/>
        </v-flex>
        <v-flex dflex>
          <v-checkbox readonly v-model="item.atencion_materna" label="Atención materna" :false-value="0" :true-value="1"/>
        </v-flex>
        <v-flex dflex>
          <v-checkbox readonly v-model="item.enfermedad_trasmisible" label="Enfermedad trasmisible" :false-value="0" :true-value="1"/>
        </v-flex>
        <v-flex dflex>
          <v-checkbox readonly v-model="item.catastrofe" label="Catastrofe" :false-value="0" :true-value="1"/>
        </v-flex>
        <v-flex xs12 sm12 md6 v-if="tutelas.length">
          <v-layout align-center fill-height>
            <v-flex xs1 dflex>
              <v-checkbox readonly v-model="marcaTutela" :label="marcaTutela ? '' : 'Tutela'"></v-checkbox>
            </v-flex>
            <input-detail-flex
              label="Tutela"
              :text="tutelas && item.oj_tutela_id && tutelas.find(x => x.id === item.oj_tutela_id) && (tutelas.find(x => x.id === item.oj_tutela_id).no_tutela + ' - ' + tutelas.find(x => x.id === item.oj_tutela_id).tipo_tutela)"
              :hint="tutelas && item.oj_tutela_id && tutelas.find(x => x.id === item.oj_tutela_id) && ('Fecha notificación: ' + tutelas.find(x => x.id === item.oj_tutela_id).fecha_notificacion + ' | Estado actual: ' + tutelas.find(x => x.id === item.oj_tutela_id).estado)"
              :append-button="{tooltip: 'ver detalle', icon: 'more_horiz', color: 'blue-grey'}"
              @clickAppend="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleTutela', titulo: 'Detalle tutela', parametros: {entidad: {id: item.oj_tutela_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
            />
          </v-layout>
        </v-flex>
        <input-detail-flex
          flex-class="xs12"
          label="Observaciones"
          :text="item.observaciones"
        />
        <v-flex xs12>
          <v-card v-if="item.estado === 'Registrada'">
            <v-toolbar dense class="elevation-0">
              <v-icon>assignment</v-icon>
              <v-toolbar-title>Detalles de la autorización</v-toolbar-title>
              <small class="mt-2 ml-1"> Medicamentos, Procedimientos, Otros</small>
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
                    v-if="index !== 1 || (index === 1 && calculoPagoError !== null)"
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
                  <v-tooltip top v-if="props.item.requiere_aprobacion">
                    <v-btn
                      flat
                      icon
                      class="ma-0"
                      color="warning"
                      slot="activator"
                    >
                      <v-icon>warning</v-icon>
                    </v-btn>
                    <span>Requiere aprobación</span>
                  </v-tooltip>
                </td>
                <td>{{ props.item.codigo }}</td>
                <td>{{ props.item.descripcion }}</td>
                <td class="text-xs-center" width="200px">{{props.item.cantidad_solicitada}}</td>
                <td class="text-xs-right">{{'$'}}{{ props.item.valor | numberFormat(2) }}</td>
                <td class="text-xs-right">{{'$'}}{{ props.item.valorTotal | numberFormat(2) }}</td>
              </template>
              <template slot="footer">
                <template v-if="!calculoPagoError && !multiplesTiposError">
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
                        <span v-if="multiplesTiposError">{{multiplesTiposError}}</span>
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
          <template v-if="item.estado === 'Confirmada'">
            <v-card v-if="item.aprobaciones.length">
              <v-toolbar dense class="elevation-0">
                <v-icon>assignment_turned_in</v-icon>
                <v-toolbar-title>Items Aprobados</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <v-card v-for="(aprobacion, index) in item.aprobaciones" :key="`card-aprobaciones-${index}`">
                  <v-data-table
                    :headers="headersDetailsAprobadas"
                    :items="aprobacion.detalles"
                    hide-actions
                    class="elevation-1 table-detalle-solicitud"
                    no-data-text="No hay detalles registrados"
                  >
                    <template slot="items" slot-scope="props">
                      <td>
                        {{ props.item.rs_cum_id ? 'Medicamento' : props.item.rs_cups_id ? 'Procedimiento' : 'Otro' }}
                      </td>
                      <td>{{ props.item.codigo }}</td>
                      <td>{{ props.item.descripcion }}</td>
                      <td class="text-xs-center" width="200px">{{props.item.cantidad_aprobada}}</td>
                      <td class="text-xs-right">{{'$'}}{{ (props.item.valor_usuario * props.item.cantidad_aprobada) | numberFormat(2) }}</td>
                      <td class="text-xs-right">{{'$'}}{{ (props.item.valor * props.item.cantidad_aprobada) | numberFormat(2) }}</td>
                    </template>
                  </v-data-table>
                  <v-card
                    class="mx-auto"
                    color="blue-grey"
                    dark
                  >
                    <v-card-actions>
                    <span class="subheading">
                      <v-tooltip top>
                        <v-btn
                          slot="activator"
                          small
                          color="blue-grey lighten-2"
                          class="white--text"
                          @click="imprimir(aprobacion.id)"
                        >
                        <v-icon left dark>link</v-icon>
                        Consecutivo N° {{aprobacion.consecutivo}}
                      </v-btn>
                        <span>Ir al documento</span>
                      </v-tooltip>
                      <span class="mr-3"></span>
                      <span class="body-2">Fecha aprobación: {{moment(aprobacion.fecha).format('DD/MM/YYYY')}}</span>
                      <span class="mr-3"></span>
                      <span class="body-2">Fecha vencimiento: {{moment(aprobacion.fecha_vencimiento).format('DD/MM/YYYY')}}</span>
                    </span>
                      <v-spacer class="mx-4"/>
                      <span
                        align-center
                        justify-end
                      >
                      <span class="body-2 mr-1">Usuario:</span>
                      <span class="body-2">{{'$'}}{{aprobacion.valor_usuario | numberFormat(2)}}</span>
                      <span class="mr-3"></span>
                      <span class="body-2 mr-1">EPS:</span>
                      <span class="body-2">{{'$'}}{{(aprobacion.valor_total - aprobacion.valor_usuario) | numberFormat(2)}}</span>
                      <span class="mr-3"></span>
                      <span class="body-2 mr-1">TOTAL:</span>
                      <span class="body-2">{{'$'}}{{aprobacion.valor_total | numberFormat(2)}}</span>
                    </span>
                    </v-card-actions>
                  </v-card>
                </v-card>
              </v-card-text>
            </v-card>
            <v-divider/>
            <v-card v-if="item.solicitudes.length">
              <v-toolbar dense class="elevation-0">
                <v-icon>assignment_late</v-icon>
                <v-toolbar-title>Items Solicitados</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <v-card v-for="(solicitud, index) in item.solicitudes" :key="`card-solicitudes-${index}`">
                  <v-data-table
                    :headers="headersDetailsSolicitadas"
                    :items="solicitud.detalles"
                    hide-actions
                    class="elevation-1 table-detalle-solicitud"
                    no-data-text="No hay detalles registrados"
                  >
                    <template slot="items" slot-scope="props">
                      <td>
                        {{ props.item.rs_cum_id ? 'Medicamento' : props.item.rs_cups_id ? 'Procedimiento' : 'Otro' }}
                      </td>
                      <td>{{ props.item.codigo }}</td>
                      <td>{{ props.item.descripcion }}</td>
                      <td class="text-xs-center" width="200px">{{props.item.cantidad_solicitada}}</td>
                      <td class="text-xs-center" width="200px">{{props.item.cantidad_aprobada}}</td>
                      <td class="text-xs-right">{{'$'}}{{ (props.item.valor_usuario * props.item.cantidad_aprobada) | numberFormat(2) }}</td>
                      <td class="text-xs-right">{{'$'}}{{ (props.item.valor * props.item.cantidad_aprobada) | numberFormat(2) }}</td>
                    </template>
                  </v-data-table>
                  <v-card
                    class="mx-auto"
                    color="blue-grey"
                    dark
                  >
                    <v-card-actions>
                    <span class="subheading">
                      <v-tooltip top>
                        <v-btn
                          slot="activator"
                          small
                          color="blue-grey lighten-2"
                          class="white--text"
                          @click="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleSolicitudAutorizacion', titulo: 'Detalle Solicitud Autorización', parametros: {entidad: solicitud, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })"
                        >
                        <v-icon left dark>find_in_page</v-icon>
                        Consecutivo N° {{solicitud.consecutivo}}
                      </v-btn>
                        <span>Ir al detalle</span>
                      </v-tooltip>
                      <span class="mr-3"></span>
                      <span class="body-2">Fecha aprobación: {{moment(solicitud.fecha).format('DD/MM/YYYY')}}</span>
                      <span class="mr-3"></span>
                      <span class="body-2">Estado: {{solicitud.estado}}</span>
                    </span>
                      <v-spacer class="mx-4"/>
                      <span
                        align-center
                        justify-end
                      >
                      <span class="body-2 mr-1">Usuario:</span>
                      <span class="body-2">{{'$'}}{{solicitud.valor_usuario | numberFormat(2)}}</span>
                      <span class="mr-3"></span>
                      <span class="body-2 mr-1">EPS:</span>
                      <span class="body-2">{{'$'}}{{(solicitud.valor_total - solicitud.valor_usuario) | numberFormat(2)}}</span>
                      <span class="mr-3"></span>
                      <span class="body-2 mr-1">TOTAL:</span>
                      <span class="body-2">{{'$'}}{{solicitud.valor_total | numberFormat(2)}}</span>
                    </span>
                    </v-card-actions>
                  </v-card>
                </v-card>
              </v-card-text>
            </v-card>
          </template>
        </v-flex>
        <template v-if="generaSolicitud">
          <template v-if="!item.solicitudes.find(x => x.anexo3)">
            <input-detail-flex
              flex-class="xs12 sm7 md9"
              label="Orden Médica"
              prepend-icon="description"
              :text="item.orden_medica.nombre"
              :hint="'Extenciones aceptadas: pdf'"
              :append-button="{tooltip: 'Ir al archivo', icon: 'link', color: 'blue-grey'}"
              @clickAppend="goRuta(item.orden_medica.url_signed)"
            />
            <input-detail-flex
              flex-class="xs12 sm5 md3"
              label="Fecha orden médica"
              prepend-icon="event"
              :text="item.fecha_orden"
            />
          </template>
          <input-detail-flex
            flex-class="xs12"
            label="Historia Clínica"
            prepend-icon="description"
            :text="item.historia_clinica.nombre"
            :hint="'Extenciones aceptadas: pdf'"
            :append-button="{tooltip: 'Ir al archivo', icon: 'link', color: 'blue-grey'}"
            @clickAppend="goRuta(item.historia_clinica.url_signed)"
          ></input-detail-flex>
        </template>
      </v-layout>
      <confirmar :value="dialogA.visible" :content="dialogA.content" @cancelar="cancelaConfirmacion" :requiere-motivo="false" @aceptar="aceptaConfirmacion" />
    </v-container>
    <v-divider/>
    <!--<v-card-actions>-->
      <!--<v-btn @click="refresh(null)">Limpiar</v-btn>-->
      <!--<v-spacer/>-->
      <!--<v-btn @click="guardarConfirmar" color="primary">Guardar y Confirmar</v-btn>-->
      <!--<v-btn @click="submit" color="primary">Guardar</v-btn>-->
    <!--</v-card-actions>-->
  </v-card>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import store from '@/store/complementos/index'
  import {SNACK_SHOW, SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {AU_AUTORIZACION_RELOAD_ITEM} from '@/store/modules/general/tables'
  import { Validator } from 'vee-validate'
  export default {
    name: 'DetalleAutorizacion',
    props: ['parametros'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      InputFile: () => import('@/components/general/InputFile'),
      Confirmar: () => import('@/components/general/Confirmar'),
      Loading
    },
    data () {
      return {
        menuDate: false,
        minDate: '1900-01-01',
        maxDate: new Date().toISOString().substr(0, 10),
        loading: false,
        loadingContratos: false,
        detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
        filterIPSDestino (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.entidad.nombre + ' ' + item.numero_contrato)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterModalidades (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.codigo + ' ' + item.tipo + ' ' + item.modalidad)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        filterServicios (item, queryText) {
          const hasValue = val => val != null ? val : ''
          const text = hasValue(item.servicio.codigo + ' ' + item.servicio.nombre)
          const query = hasValue(queryText)
          return text.toString().toLowerCase().indexOf(query.toString().toLowerCase()) > -1
        },
        headersDetails: [
          { text: 'Status', align: 'center', sortable: false },
          { text: 'Tipo', sortable: false },
          { text: 'Código', sortable: false },
          { text: 'Descripción', sortable: false },
          { text: 'Cantidad', align: 'center', sortable: false },
          { text: 'Valor unitario', align: 'right', sortable: false },
          { text: 'Valor total', align: 'right', sortable: false }
        ],
        headersDetailsAprobadas: [
          { text: 'Tipo', sortable: false },
          { text: 'Código', sortable: false },
          { text: 'Descripción', sortable: false },
          { text: 'Cantidad', align: 'center', sortable: false },
          { text: 'Valor usuario', align: 'right', sortable: false },
          { text: 'Valor total', align: 'right', sortable: false }
        ],
        headersDetailsSolicitadas: [
          { text: 'Tipo', sortable: false },
          { text: 'Código', sortable: false },
          { text: 'Descripción', sortable: false },
          { text: 'Cantidad solicitada', align: 'center', sortable: false },
          { text: 'Cantidad aprobada', align: 'center', sortable: false },
          { text: 'Valor usuario', align: 'right', sortable: false },
          { text: 'Valor total', align: 'right', sortable: false }
        ],
        registroDetalle: null,
        tipoDetalle: {id: 1, ruta: 'cumcontratados', text: 'Medicamentos'},
        tiposDetalle: [{id: 1, ruta: 'cumcontratados', text: 'Medicamentos'}, {id: 2, ruta: 'cupcontratados', text: 'Procedimientos'}, {id: 3, ruta: 'otroscontratados', text: 'Otros'}],
        marcaTutela: false,
        fab: false,
        idAfiliadoValido: null,
        dialogA: {
          visible: false,
          content: null,
          registroID: null
        },
        item: null,
        makeAutorizacion: {
          id: null,
          as_afiliado_id: null,
          as_regimen_id: null,
          entidad_origen_id: null,
          tipo_origen: null,
          tipo_destino: null,
          cie10_principal_id: null,
          cie10_rel1_id: null,
          cie10_rel2_id: null,
          au_modservicio_id: null,
          rs_contrato_id: null,
          rs_servicio_id: null,
          orden_medica: null,
          fecha_orden: null,
          historia_clinica: null,
          oj_tutela_id: null,
          alto_costo: 0,
          pyp: 0,
          atencion_materna: 0,
          enfermedad_trasmisible: 0,
          catastrofe: 0,
          estado: 'Registrada',
          observaciones: null,
          // Objetos
          afiliado: null,
          contrato: null,
          servicio: null,
          entidad_origen: null,
          cie10_principal: null,
          cie10_rel1: null,
          cie10_rel2: null,
          detalles: []
        },
        orden_medica: null,
        historia_clinica: null,
        contratosActivos: [],
        serviciosContratados: [],
        tutelas: [],
        cuotaCopagos: null,
        rangoActivo: null,
        calculoPagoError: null,
        multiplesTiposError: null,
        tipoValorPermitido: null,
        valorTotal: 0,
        valorEPS: 0,
        valorUsuario: 0,
        generaSolicitud: false
      }
    },
    watch: {
      'contratosActivos' (val) {
        let contrato = val.find(x => x.id === this.item.rs_contrato_id)
        if (!contrato) this.item.contrato = null
      },
      'item.contrato' (val, prev) {
        if (val) {
          console.log('el val del contrato', val)
          this.item.rs_contrato_id = val.id
          let servicios = val.servicios_contratados ? val.servicios_contratados : []
          let servicio = servicios.find(x => x.id === this.item.rs_servicio_id)
          this.item.rs_servicio_id = servicio ? servicio.id : null
          this.serviciosContratados = servicios
          if (prev && val !== prev) {
            this.item.detalles = []
            this.$refs.postuladorDetalle.reset()
          }
        } else {
          this.item.detalles = []
          this.item.rs_contrato_id = null
          this.serviciosContratados = []
          this.item.rs_servicio_id = null
        }
      },
      async 'item.afiliado' (val, prev) {
        if (val) {
          this.item.afiliado.total_autorizado = await this.getTotalAutorizado()
          this.getContratos()
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
          let tutela = val.find(x => x.id === this.item.oj_tutela_id)
          this.item.oj_tutela_id = tutela ? tutela.id : null
          this.marcaTutela = this.item.oj_tutela_id !== null
        }
      },
      async 'registroDetalle' (val) {
        if (val && this.item.detalles) {
          console.log('val registro', val)
          let copyVal = await JSON.parse(JSON.stringify(val))
          let registro = this.item.detalles.find(x =>
            (x.rs_cum_id === (copyVal.rs_cum_id ? copyVal.id : null)) &&
            (x.rs_cups_id === (copyVal.rs_cups_id ? copyVal.id : null)) &&
            (x.rs_otros_id === (copyVal.rs_otros_id ? copyVal.id : null))
          )
          if (registro) {
            registro.cantidad_solicitada++
            registro.valorTotal = registro.valor * registro.cantidad_solicitada
          } else {
            let makeRegistroDetalle = {
              au_autaprobada_id: null,
              au_autorizacion_id: this.item.id,
              au_autsolicitud_id: null,
              cantidad_aprobada: null,
              cantidad_solicitada: 1,
              codigo: copyVal.codigo,
              descripcion: copyVal.descripcion,
              id: null,
              observaciones: null,
              rs_cum_id: copyVal.rs_cum_id ? copyVal.id : null,
              rs_cups_id: copyVal.rs_cups_id ? copyVal.id : null,
              rs_otros_id: copyVal.rs_otros_id ? copyVal.id : null,
              tipo_valor: copyVal.tipo_valor,
              valor: copyVal.tarifa,
              valorTotal: copyVal.tarifa,
              valor_usuario: null,
              status: !this.tipoValorPermitido ? true : this.tipoValorPermitido === copyVal.tipo_valor,
              requiere_aprobacion: copyVal.nivel_autorizacion ? (copyVal.nivel_autorizacion && copyVal.nivel_autorizacion !== 'Asesor') : false
            }
            this.item.detalles.unshift(makeRegistroDetalle)
          }
          this.valorTotal = this.getValorTotal()
          this.registroDetalle = null
        }
      },
      'okChecks' () {
        this.reloadPagos()
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
      },
      'multiplesTipos.length' (val, prev) {
        if (this.$refs.postuladorDetalle && prev !== 0) this.$refs.postuladorDetalle.reset()
        if (val === 1) {
          this.tipoDetalle = this.tiposDetalle.find(x => x.id === this.multiplesTipos[0])
        }
      }
    },
    computed: {
      multiplesTipos () {
        let tipos = []
        if (this.item && this.item.detalles) {
          this.item.detalles.forEach(x => {
            tipos.push(x.rs_cum_id ? 1 : x.rs_cups_id ? 2 : 3)
          })
        }
        return window.lodash.uniq(tipos)
      },
      complementos () {
        return store.getters.complementosFormAutorizacion
      },
      okChecks () {
        return this.item && (this.item.alto_costo || this.item.pyp || this.item.atencion_materna || this.item.enfermedad_trasmisible || this.item.catastrofe)
      }
    },
    created () {
      this.getData()
    },
    beforeMount () {
      Validator.extend('afiliadoValidoAutorizacion', {
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
      imprimir (id) {
        this.axios.get('firmar-ruta?nombre_ruta=autorizacione&id=' + id)
          .then(response => {
            this.goRuta(response.data)
          })
          .catch(e => {
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al generar el documento. ', error: e})
          })
      },
      reloadStatus () {
        this.item.detalles.forEach(x => {
          x.status = (x.tipo_valor === this.tipoValorPermitido)
        })
      },
      getRangoActivo () {
        console.log('this.cuotaCopagos', this.cuotaCopagos)
        console.log('this.item.afiliado', this.item.afiliado)
        if (this.cuotaCopagos && this.cuotaCopagos.length && this.item.afiliado) {
          if (this.item.afiliado.as_regimene_id === 2) {
            this.rangoActivo = this.cuotaCopagos.find(x => x.grupo === 'Sisben Nivel 2')
          } else {
            let salMinimosCotizante = (this.item.afiliado.ingreso_total / this.cuotaCopagos[0].salminimo.valor)
            console.log('salMinimosCotizante', salMinimosCotizante)
            let rangosContributivos = JSON.parse(JSON.stringify(this.cuotaCopagos))
            rangosContributivos.splice(this.cuotaCopagos.findIndex(x => x.grupo === 'Sisben Nivel 2'), 1)
            console.log('rangosContributivos', rangosContributivos)
            this.rangoActivo = rangosContributivos.find(x =>
              (x.limite_inferior_salario === 'Incluido' ? (salMinimosCotizante >= x.salminimos_inicio) : (salMinimosCotizante > x.salminimos_inicio)) &&
              (x.limite_superior_salario === 'Incluido' ? (salMinimosCotizante <= x.salminimos_fin) : (salMinimosCotizante < x.salminimos_fin))
            )
          }
        }
        console.log('rango activo', this.rangoActivo)
      },
      getValorTotal () {
        return window.lodash.sum(this.item.detalles.map(x => x.valorTotal))
      },
      async reloadPagos () {
        console.log('el filtro', this.multiplesTipos.length)
        if (this.multiplesTipos.length > 1) {
          this.calculoPagoError = null
          this.multiplesTiposError = 'Solo se pueden asignar items que pertenezcan a un mismo tipo.'
        } else {
          this.multiplesTiposError = null
          // if (!this.rangoActivo || (this.tipoValorPermitido !== null && this.item.detalles.length && this.item.afiliado.as_regimene_id === 1 && this.item.detalles.filter(x => x.tipo_valor !== this.tipoValorPermitido).length)) {
          if (!this.rangoActivo || (this.tipoValorPermitido !== null && this.item.detalles.length && this.item.detalles.filter(x => x.tipo_valor !== this.tipoValorPermitido).length)) {
            if (!this.rangoActivo) {
              this.calculoPagoError = 'No existen rangos configurados o el afiliado no aplica a ninguno de los rangos, ésto es indispensable para realizar el calculo de la liquidación de la autorización.'
            } else {
              this.calculoPagoError = 'No se puede generar Copago y Cuota moderadora en una misma autorización, hay items que infringen éste mandato. Para continuar, remueva los items con status "En conflicto".'
            }
          } else {
            this.calculoPagoError = null
            if (this.item.afiliado && ((this.item.afiliado.as_regimene_id === 2 && this.item.afiliado.nivel_sisben >= 2 && !this.okChecks) || (this.item.afiliado.as_regimene_id === 1))) {
              if ((this.item.afiliado.as_regimene_id === 2 && this.item.afiliado.nivel_sisben >= 2)) {
                this.valorUsuario = this.valorTotal * (this.rangoActivo.copago / 100)
                this.valorUsuario = await this.getValorUsuarioTopes(this.valorUsuario)
              } else {
                console.log('spy contributivo')
                if (this.tipoValorPermitido === 'Copago') {
                  if (this.okChecks) {
                    this.valorUsuario = 0
                    console.log('spy contributivo copago ok check')
                  } else {
                    if (this.item.afiliado.as_tipafiliado_id === 1) {
                      this.valorUsuario = 0
                    } else {
                      this.valorUsuario = this.valorTotal * (this.rangoActivo.copago / 100)
                      this.valorUsuario = await this.getValorUsuarioTopes(this.valorUsuario)
                    }
                  }
                } else {
                  this.valorUsuario = this.rangoActivo.cuota_moderadora
                  console.log('spy contributivo cuota')
                }
              }
            } else {
              this.valorUsuario = 0
            }
          }
        }
        this.valorEPS = this.valorTotal - this.valorUsuario
      },
      getValorUsuarioTopes (valor) {
        console.log('entrante', valor)
        // regla tope por evento
        let valorTemporal = JSON.parse(JSON.stringify(valor))
        valorTemporal = (valorTemporal > this.rangoActivo.limite_evento) ? this.rangoActivo.limite_evento : valorTemporal
        console.log('regla tope', valorTemporal)
        // regla tope año
        let diferencia = (valorTemporal + this.item.afiliado.total_autorizado) - this.rangoActivo.limite_anio
        console.log('diferecioa', diferencia)
        valorTemporal = (diferencia > 0) ? (valorTemporal - diferencia) : valorTemporal
        console.log('regla tope año', valorTemporal)
        return valorTemporal
      },
      getContratos () {
        if (this.item.afiliado) {
          this.loadingContratos = true
          this.axios.get(`contratos?${this.item.afiliado.portabilidad_activa ? 'portabilidad' : this.item.afiliado.as_regimene_id === 1 ? 'contributivo' : 'subsidiado'}=1`)
            .then((response) => {
              if (response.data !== '') {
                console.log('el response', response)
                this.contratosActivos = response.data.data
                this.loadingContratos = false
              }
            })
            .catch(e => {
              this.loadingContratos = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer los contratos para realizar la autorización. `, error: e})
            })
        }
      },
      // reloadDetail (detail) {
      //   detail.valorTotal = detail.cantidad_solicitada * detail.valor
      //   this.valorTotal = this.getValorTotal()
      // },
      getTotalAutorizado () {
        return new Promise((resolve) => {
          this.axios.get(`afiliados/${this.item.afiliado.id}?copago_anual=1`)
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
        this.axios.get(`${'autorizaciones'}/${this.parametros.entidad.id}`)
          .then((response) => {
            if (response.data !== '') {
              this.idAfiliadoValido = JSON.parse(JSON.stringify(response.data.data.as_afiliado_id))
              response.data.data.detalles.forEach(x => {
                x.valorTotal = x.cantidad_solicitada * x.valor
                x.status = true
              })
              this.orden_medica = response.data.data.orden_medica ? response.data.data.orden_medica.nombre : null
              this.historia_clinica = response.data.data.historia_clinica ? response.data.data.historia_clinica.nombre : null
              this.item = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ` al traer los datos de la autorización. `, error: e})
          })
      },
      refresh () {
        this.item = JSON.parse(JSON.stringify(this.makeAutorizacion))
        this.idAfiliadoValido = null
        if (this.$refs.ordenMedica) this.$refs.ordenMedica.reset()
        if (this.$refs.historiaClinica) this.$refs.historiaClinica.reset()
        this.$validator.reset()
      },
      getBase64 (file) {
        return new Promise((resolve, reject) => {
          console.log('file', file)
          const reader = new FileReader()
          reader.readAsDataURL(file)
          reader.onload = () => resolve(reader.result)
          reader.onerror = error => reject(error)
        })
      },
      async guardarConfirmar () {
        if (await this.validado()) {
          this.dialogA.content = 'Si decide Aceptar, el formulario se procesará y no podrá editar estos datos.'
          this.dialogA.visible = true
        }
      },
      cancelaConfirmacion () {
        this.dialogA.visible = false
        this.dialogA.content = null
      },
      async aceptaConfirmacion () {
        this.item.estado = 'Confirmada'
        await this.submit()
        this.cancelaConfirmacion()
      },
      async validado () {
        let errorOrdenMedica = await this.$refs.ordenMedica.validate()
        let errorHistoriaClinica = await this.$refs.historiaClinica.validate()
        let errorForm = await this.$validator.validateAll()
        let errorDetails = this.item.detalles.length
        if (!errorDetails) {
          this.$store.commit(SNACK_SHOW, {msg: 'Hay que registrar al menos un detalle.', color: 'warning'})
        }
        return (errorForm && errorDetails && errorHistoriaClinica && errorOrdenMedica && !this.calculoPagoError && !this.multiplesTiposError)
      },
      async submit () {
        if (await this.validado()) {
          this.loading = true
          if (this.orden_medica instanceof File) {
            this.item.orden_medica = {
              id: null,
              name: this.orden_medica.name,
              size: this.orden_medica.size,
              type: this.orden_medica.type,
              string: await this.getBase64(this.orden_medica)
            }
          }
          if (this.historia_clinica instanceof File) {
            this.item.historia_clinica = {
              id: null,
              name: this.orden_medica.name,
              size: this.orden_medica.size,
              type: this.orden_medica.type,
              string: await this.getBase64(this.historia_clinica)
            }
          }
          let typeRequest = this.item.id ? 'editar' : 'crear'
          let request = typeRequest === 'editar' ? this.axios.put(`autorizaciones/${this.item.id}`, this.item) : this.axios.post('autorizaciones', this.item)
          request
            .then(response => {
              console.log('response autorización', response)
              this.item = response.data.item
              this.$store.commit(AU_AUTORIZACION_RELOAD_ITEM, {item: response.data.item_o, estado: typeRequest, key: this.parametros.key})
              this.loading = false
            }).catch(e => {
              this.loading = false
              this.$store.commit(SNACK_ERROR_LIST, {expression: `al guardar el registro de la autorización.`, error: e})
            })
        } else {
          this.item.estado = 'Registrada'
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
