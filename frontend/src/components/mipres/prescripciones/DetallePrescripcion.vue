<template>
  <v-container
    fluid
    grid-list-md
    class="pa-0 ma-0"
  >
    <loading v-model="loading" />
    <v-tooltip right slot="activator" v-if="item.observaciones">
      <v-btn
        ref="esoes"
        slot="activator"
        color="primary"
        class="mt-1"
        dark
        fab
        left
        fixed
        small
        @click.stop="showMenuObservaciones"
      >
        <v-icon medium>message</v-icon>
      </v-btn>
      <span>Observaciones</span>
    </v-tooltip>
    <v-menu
      v-model="menuObservaciones"
      :close-on-content-click="false"
      :nudge-width="300"
      offset-x
      :position-x="x"
      :position-y="y"
      absolute
      offset-y
    >
      <v-card>
        <v-card-title>
          <span class="title">Observaciones</span>
          <v-spacer/>
          <v-btn flat icon @click.stop="menuObservaciones = false">
            <v-icon>close</v-icon>
          </v-btn>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          {{item.observaciones}}
        </v-card-text>
      </v-card>
    </v-menu>
    <v-card>
      <v-toolbar dense>
        <v-icon large>find_in_page</v-icon>
        <v-list-tile>
          <v-list-tile-content>
            <v-list-tile-title>Detalle de Prescripción</v-list-tile-title>
            <v-list-tile-sub-title class="caption" v-if="item.novedad_generadora">
              Prescripción Origen:
              <a
                @click.stop="$store.commit('NAV_ADD_ITEM', { ruta: 'detallePrescripcion', titulo: 'Detalle Prescripción', parametros: {entidad: {id: item.novedad_generadora.mp_prescripcion_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
              >{{item.novedad_generadora.NoPrescripcion}}</a>
            </v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
        <template v-if="item.id">
          <v-spacer/>
          <span class="pt-1 text-xs-right">
          <span class="title text-capitalize">Prescripción</span>
          <p class="mb-0 caption">Estado MIPRES: {{item.EstadoPrescripcion}}</p>
        </span>
          <div>
            <v-chip label color="white" text-color="red" dark absolute right top>
              <span class="subheading">N°</span>&nbsp;
              <span class="subheading" v-text="item.NoPrescripcion"></span>
            </v-chip>
          </div>
        </template>
      </v-toolbar>
      <v-card-text v-if="item.id">
        <v-layout row wrap>
          <input-detail-flex
            label="Número"
            :text="item.NoPrescripcion"
          />
          <input-detail-flex
            label="Fecha"
            :text="moment(item.FPrescripcion).format('DD-MM-YYYY')"
            prepend-icon="event"
          />
          <input-detail-flex
            label="Hora"
            :text="moment(item.HPrescripcion, 'HH:mm:ss').format('HH:mm')"
            prepend-icon="schedule"
          />
          <input-detail-flex
            label="Estado MIPRES"
            :text="item.EstadoPrescripcion"
          />
          <input-detail-flex
            label="Estado en EPS"
            :text="item.estado"
          />
          <input-detail-flex
            label="Código de la EPS"
            :text="item.CodEPS"
          />
          <v-flex xs12>
            <v-label>IPS</v-label>
            <v-expansion-panel class="v-expansion-panel-pi">
              <v-expansion-panel-content
                class="v-expansion-header-dark"
              >
                <div slot="header">
                  <v-toolbar dense color="rgba(0, 0, 0, 0)" class="elevation-0">
                    <v-toolbar-title>
                      <v-list-tile avatar>
                        <v-list-tile-avatar>
                          <v-icon large>location_city</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="item.ips ? item.ips : 'Código habilitación: ' + item.CodHabIPS"></v-list-tile-title>
                          <v-list-tile-sub-title v-if="item.ips">Código habilitación: {{item.CodHabIPS}}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                    </v-toolbar-title>
                    <v-spacer/>
                    <!--<v-tooltip left>-->
                      <!--<v-btn icon ripple slot="activator" @click.stop="">-->
                        <!--<v-icon :color="!!item.rs_entidad_id ? 'success' : 'error'">{{!!item.rs_entidad_id ? 'link' : 'warning'}}</v-icon>-->
                      <!--</v-btn>-->
                      <!--<span>{{!!item.rs_entidad_id ? 'Ir a la entidad' : 'Entidad no vinculada a la EPS'}}</span>-->
                    <!--</v-tooltip>-->
                  </v-toolbar>
                </div>
                <v-card>
                  <v-card-text>
                    <v-layout>
                      <input-detail-flex
                        label="Tipo Identificación"
                        :text="item.TipoIDIPS"
                      />
                      <input-detail-flex
                        label="Número Identificación"
                        :text="item.NroIDIPS"
                      />
                      <input-detail-flex
                        label="Cód. DANE Municipio"
                        :text="item.CodDANEMunIPS + (item.municipio_ips ? ' - ' + item.municipio_ips.nombre + ', ' + item.municipio_ips.nombre_departamento : '')"
                      />
                      <input-detail-flex
                        label="Dirección Sede"
                        :text="item.DirSedeIPS"
                      />
                      <input-detail-flex
                        label="Teléfono Sede"
                        :text="item.TelSedeIPS"
                      />
                    </v-layout>
                  </v-card-text>
                </v-card>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-flex>
          <input-detail-flex
            class="mt-2"
            flex-class="xs12"
            label="Profesional"
            :text="`${item.PNProfS} ${item.SNProfS} ${item.PAProfS} ${item.SAProfS}`"
            :hint="`${item.TipoIDProf} ${item.NumIDProf}`"
            prepend-icon="local_library"
            :suffix="`<strong>Registro Profesional:</strong> <span style='color: dodgerblue !important'>${item.RegProfS}</span>`"
          />
          <input-detail-flex
            class="mt-2"
            flex-class="xs12"
            label="Paciente"
            :text="`${item.TipoIDPaciente} ${item.NroIDPaciente} - ${item.PNPaciente} ${item.SNPaciente} ${item.PAPaciente} ${item.SAPaciente}`"
            :hint="`Identificación de la Madre: ${!!item.NroIDMadrePaciente ? (item.TipoIDMadrePaciente + ' ' + item.NroIDMadrePaciente) : 'No Registra.'}`"
            prepend-icon="person"
            :append-button="{tooltip: !!item.as_afiliado_id ? 'Ir al afiliado' : 'No es afilaido', icon: !!item.as_afiliado_id ? 'link' : 'warning', color: !!item.as_afiliado_id ? 'success' : 'error'}"
            @clickAppend="!!item.as_afiliado_id ? $store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: {id: item.as_afiliado_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}}) : ''"
          />
          <input-detail-flex
            :flex-class="!!item.dx_principal ? 'xs12' : ''"
            label="Código Diagnóstico Principal"
            :text="!!item.dx_principal ? (item.dx_principal.codigo + ' - ' + item.dx_principal.descripcion ): item.CodDxPpal"
            :hint="!!item.CodDxPpal && !item.dx_principal ? 'Código no encontrado en los SIE10 registrados.' : ''"
            prepend-icon="local_hospital"
          />
          <input-detail-flex
            v-if="!!item.CodDxRel1 || !!item.dx1"
            :flex-class="!!item.dx1 ? 'xs12' : ''"
            label="Código Diagnóstico Relacionado 1"
            :text="!!item.dx1 ? (item.dx1.codigo + ' - ' + item.dx1.descripcion ): item.CodDxRel1"
            :hint="!!item.CodDxRel1 && !item.dx1 ? 'Código no encontrado en los SIE10 registrados.' : ''"
            prepend-icon="local_hospital"
          />
          <input-detail-flex
            v-if="!!item.CodDxRel2 || !!item.dx2"
            :flex-class="!!item.dx2 ? 'xs12' : ''"
            label="Código Diagnóstico Relacionado 2"
            :text="!!item.dx2 ? (item.dx2.codigo + ' - ' + item.dx2.descripcion ): item.CodDxRel1"
            :hint="!!item.CodDxRel1 && !item.dx2 ? 'Código no encontrado en los SIE10 registrados.' : ''"
            prepend-icon="local_hospital"
          />
          <input-detail-flex
            v-if="!!item.NroIDDonanteVivo"
            label="Identificación del Donante Vivo"
            :text="item.TipoIDDonanteVivo + ' ' + item.NroIDDonanteVivo"
          />
          <input-detail-flex
            label="Código Ámbito de Atención"
            :text="`${item.CodAmbAte}: ${item.AmbitoAtencion}`"
          />
          <input-detail-flex
            label="Requiere soporte nutricional"
            :text="!!item.SopNutricional ? 'SI' : 'NO'"
          />
          <input-detail-flex
            v-if="!!item.TipoTranscripcion"
            label="Tipo de Transcripción"
            :text="item.TipoTranscripcion"
          />
          <input-detail-flex
            label="Tiene Enfermedad Huérfana"
            :text="!!item.EnfHuerfana ? 'SI' : 'NO'"
          />
          <input-detail-flex
            v-if="!!item.EnfHuerfana"
            label="Código Enfermedad Huérfana"
            :text="item.CodEnfHuerfana"
          />
          <input-detail-flex
            v-if="!!item.EnfHuerfana"
            label="La Enfermedad Huérfana es el Diagnóstico Principa"
            :text="item.EnfHuerfanaDX"
          />
          <v-flex xs12 class="mt-2">
            <complemento-detalles :item="item"/>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import store from '@/store/complementos/index'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'DetallePrescripcion',
    props: ['parametros'],
    components: {
      InputDetailFlex: () => import('@/components/general/InputDetailFlex'),
      ComplementoDetalles: () => import('@/components/mipres/complementosDetalles/ComplementoDetalles'),
      Loading,
      Estudio: () => import('@/components/mipres/prescripciones/Estudio')
    },
    data: () => ({
      x: 0,
      y: 0,
      menuObservaciones: false,
      loading: true,
      item: {}
    }),
    computed: {
      complementos () {
        return store.getters.complementosTablaPrescripciones
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('prescripciones')
      }
    },
    created () {
      this.getData()
    },
    methods: {
      showMenuObservaciones () {
        this.menuObservaciones = false
        this.x = this.$refs.esoes.$el.offsetLeft + 40
        this.y = this.$refs.esoes.$el.offsetTop + 20
        this.$nextTick(() => {
          this.menuObservaciones = true
        })
      },
      getData () {
        this.loading = true
        this.axios.get(`prescripciones/${this.parametros.entidad.id}`)
          .then(response => {
            if (response.data !== '') {
              this.item = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los datos de la prescripción. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
