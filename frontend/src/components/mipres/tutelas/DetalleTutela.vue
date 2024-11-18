<template>
  <v-container
    fluid
    grid-list-sm
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
    <!--<v-tooltip left v-if="item.id && infoComponent.permisos && infoComponent.permisos.agregar">-->
      <!--<v-btn-->
        <!--slot="activator"-->
        <!--color="orange"-->
        <!--class="mt-5"-->
        <!--dark-->
        <!--fab-->
        <!--fixed-->
        <!--right-->
        <!--@click.stop="showEstudio = true"-->
      <!--&gt;-->
        <!--<v-icon>ballot</v-icon>-->
      <!--</v-btn>-->
      <!--<span>Estudiar tutela</span>-->
    <!--</v-tooltip>-->
    <!--<estudio :item="item" v-model="showEstudio"/>-->
    <v-card>
      <v-toolbar dense>
        <v-icon large>find_in_page</v-icon>
        <v-list-tile>
          <v-list-tile-content>
            <v-list-tile-title class="subheading">Detalle de tutela MIPRES</v-list-tile-title>
            <v-list-tile-sub-title class="caption" v-if="item.novedad_generadora">
              Tutela Origen:
              <a
                @click.stop="$store.commit('NAV_ADD_ITEM', { ruta: 'detalleTutelaMipres', titulo: 'Detalle Tutela MIPRES', parametros: {entidad: {id: item.novedad_generadora.mp_tutela_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}})"
              >{{item.novedad_generadora.NoPrescripcion}}</a>
            </v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
        <template v-if="item.id">
          <v-spacer/>
          <span class="pt-1 text-xs-right">
          <span class="title text-capitalize">Tutela</span>
          <p class="mb-0 caption">Estado MIPRES: {{item.EstadoTutela}}</p>
        </span>
          <div>
            <v-chip label color="white" text-color="red" dark absolute right top>
              <span class="subheading">N°</span>&nbsp;
              <span class="subheading" v-text="item.NoTutela"></span>
            </v-chip>
          </div>
        </template>
      </v-toolbar>
      <v-card-text v-if="item.id">
        <v-layout row wrap>
          <input-detail-flex
            label="Número"
            :text="item.NoTutela"
          />
          <input-detail-flex
            label="Fecha"
            :text="item.FTutela ? moment(item.FTutela).format('DD-MM-YYYY'): ''"
            prepend-icon="event"
          />
          <input-detail-flex
            label="Hora"
            :text="item.HTutela ? moment(item.HTutela, 'HH:mm:ss').format('HH:mm'): ''"
            prepend-icon="schedule"
          />
          <input-detail-flex
            label="Estado MIPRES"
            :text="item.EstadoTutela"
          />
          <input-detail-flex
            label="Estado en EPS"
            :text="item.estado"
          />
          <input-detail-flex
            label="Identificación EPS"
            :text="`${item.TipoIDEPS ? item.TipoIDEPS : ''} ${item.NroIDEPS ? item.NroIDEPS : ''}`"
            :hint="`Código: ${item.CodEPS ? item.CodEPS : ''}`"
          />
          <input-detail-flex
            class="mt-2"
            flex-class="xs12"
            label="Profesional"
            :text="`${item.PNProfS} ${item.SNProfS} ${item.PAProfS} ${item.SAProfS}`"
            :hint="`${item.TipoIDProf} ${item.NumIDProf}`"
            prepend-icon="local_library"
            :suffix="`Registro Profesional: ${item.RegProfS}`"
          />
          <input-detail-flex
            class="mt-2"
            flex-class="xs12"
            label="Paciente"
            :text="`${item.TipoIDPaciente} ${item.NroIDPaciente} - ${item.PNPaciente} ${item.SNPaciente} ${item.PAPaciente} ${item.SAPaciente}`"
            :hint="`Identificación de la Madre: ${!!item.NroIDMadrePaciente ? (item.TipoIDMadrePaciente + ' ' + item.NroIDMadrePaciente) : 'No Registra.'}`"
            prepend-icon="person"
            :append-button="{tooltip: 'Ir al afiliado', icon: !!item.as_afiliado_id ? 'link' : 'warning', color: !!item.as_afiliado_id ? 'success' : 'error'}"
            @clickAppend="!!item.as_afiliado_id ? $store.commit('NAV_ADD_ITEM', { ruta: 'detalleGeneralAfiliado', titulo: 'Detalle afiliado', parametros: {entidad: {id: item.as_afiliado_id}, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]}}) : ''"
          />
          <v-flex xs12 v-if="item.NroFallo || item.FFalloTutela || item.F1Instan || item.F2Instan || item.FCorte || item.FDesacato">
            <v-layout row wrap>
              <input-detail-flex
                v-if="item.NroFallo"
                label="Número Fallo de Tutela"
                :text="item.NroFallo"
              />
              <input-detail-flex
                v-if="item.FFalloTutela"
                label="Fecha del Fallo de Tutela"
                :text="item.FFalloTutela ? moment(item.FFalloTutela).format('DD-MM-YYYY'): ''"
                prepend-icon="event"
              />
              <input-detail-flex
                v-if="item.F1Instan"
                label="Fecha 1 Instancia"
                :text="item.F1Instan ? moment(item.F1Instan).format('DD-MM-YYYY'): ''"
                prepend-icon="event"
              />
              <input-detail-flex
                v-if="item.F2Instan"
                label="Fecha 2 Instancia"
                :text="item.F2Instan ? moment(item.F2Instan).format('DD-MM-YYYY'): ''"
                prepend-icon="event"
              />
              <input-detail-flex
                v-if="item.FCorte"
                label="Fecha Corte"
                :text="item.FCorte ? moment(item.FCorte).format('DD-MM-YYYY'): ''"
                prepend-icon="event"
              />
              <input-detail-flex
                v-if="item.FDesacato"
                label="Fecha de Desacato"
                :text="item.FDesacato ? moment(item.FDesacato).format('DD-MM-YYYY'): ''"
                prepend-icon="event"
              />
            </v-layout>
          </v-flex>
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
          <input-detail-flex
            flex-class="xs12"
            label="Código Diagnóstico Principal"
            :text="!!item.dx_principal ? (item.dx_principal.codigo + ' - ' + item.dx_principal.descripcion ): item.CodDxPpal"
            :hint="!!item.CodDxPpal && !item.dx_principal ? 'Código no encontrado en los SIE10 registrados.' : ''"
            prepend-icon="local_hospital"
          />
          <input-detail-flex
            v-if="!!item.CodDxRel1 || !!item.dx1"
            flex-class="xs12"
            label="Código Diagnóstico Relacionado 1"
            :text="!!item.dx1 ? (item.dx1.codigo + ' - ' + item.dx1.descripcion ): item.CodDxRel1"
            :hint="!!item.CodDxRel1 && !item.dx1 ? 'Código no encontrado en los SIE10 registrados.' : ''"
            prepend-icon="local_hospital"
          />
          <input-detail-flex
            v-if="!!item.CodDxRel2 || !!item.dx2"
            flex-class="xs12"
            label="Código Diagnóstico Relacionado 2"
            :text="!!item.dx2 ? (item.dx2.codigo + ' - ' + item.dx2.descripcion ): item.CodDxRel1"
            :hint="!!item.CodDxRel1 && !item.dx2 ? 'Código no encontrado en los SIE10 registrados.' : ''"
            prepend-icon="local_hospital"
          />
          <input-detail-flex
            flex-class="xs12"
            label="Aclaraciones del Fallo de Tutela que se relacionan con la Tecnología en Salud a Recobrar"
            :text="item.AclFalloTut"
          />

          <input-detail-flex
            v-if="!!item.CodDxMotS1 || !!item.dx_mot1"
            flex-class="xs12"
            label="Código Diagnóstico que Motiva Solicitud 1"
            :text="!!item.dx_mot1 ? (item.dx_mot1.codigo + ' - ' + item.dx_mot1.descripcion ): item.CodDxMotS1"
            :hint="!!item.CodDxMotS1 && !item.dx_mot1 ? 'Código no encontrado en los SIE10 registrados.' : ''"
            prepend-icon="local_hospital"
          />
          <input-detail-flex
            v-if="!!item.CodDxMotS2 || !!item.dx_mot2"
            flex-class="xs12"
            label="Código Diagnóstico que Motiva Solicitud 2"
            :text="!!item.dx_mot2 ? (item.dx_mot2.codigo + ' - ' + item.dx_mot2.descripcion ): item.CodDxMotS2"
            :hint="!!item.CodDxMotS2 && !item.dx_mot2 ? 'Código no encontrado en los SIE10 registrados.' : ''"
            prepend-icon="local_hospital"
          />
          <input-detail-flex
            v-if="!!item.CodDxMotS3 || !!item.dx_mot3"
            flex-class="xs12"
            label="Código Diagnóstico que Motiva Solicitud 3"
            :text="!!item.dx_mot3 ? (item.dx_mot3.codigo + ' - ' + item.dx_mot3.descripcion ): item.CodDxMotS3"
            :hint="!!item.CodDxMotS3 && !item.dx_mot3 ? 'Código no encontrado en los SIE10 registrados.' : ''"
            prepend-icon="local_hospital"
          />
          <input-detail-flex
            flex-class="xs12"
            label="Justificación médica que demuestra la conexidad de la solicitud recobrada con el Fallo de Tutela"
            :text="item.JustifMed"
          />
          <input-detail-flex
            v-if="item.CritDef1CC !== null"
            label="Criterio 1"
            :text="item.CritDef1CC ? 'SI': 'NO'"
            hint="Definido por la Corte Constitucional"
          />
          <input-detail-flex
            v-if="item.CritDef2CC !== null"
            label="Criterio 2"
            :text="item.CritDef2CC ? 'SI': 'NO'"
            hint="Definido por la Corte Constitucional"
          />
          <input-detail-flex
            v-if="item.CritDef3CC !== null"
            label="Criterio 3"
            :text="item.CritDef3CC ? 'SI': 'NO'"
            hint="Definido por la Corte Constitucional"
          />
          <input-detail-flex
            v-if="item.CritDef4CC !== null"
            label="Criterio 4"
            :text="item.CritDef4CC ? 'SI': 'NO'"
            hint="Definido por la Corte Constitucional"
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
    name: 'DetalleTutelaMIPRES',
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
      showEstudio: false,
      item: {}
    }),
    computed: {
      complementos () {
        return store.getters.complementosTablaPrescripciones
      },
      infoComponent () {
        return this.$store.getters.getInfoComponent('tutelasMipres')
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
        this.axios.get(`mptutelas/${this.parametros.entidad.id}`)
          .then(response => {
            if (response.data !== '') {
              this.item = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los datos de la tutela. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>

</style>
