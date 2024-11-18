<template>
  <v-container
    class="pa-0 ma-0"
    fluid
    grid-list-md
  >
    <loading v-model="loading" />
    <v-slide-x-reverse-transition mode="out-in">
      <v-speed-dial
        class="ml-1"
        key="btn1"
        v-model="fabMenu"
        left
        direction="bottom"
        open-on-hover
        fixed
        v-if="floatMenu"
      >
        <v-tooltip right slot="activator">
          <v-btn
            class="mt-1"
            slot="activator"
            v-model="fabMenu"
            color="blue darken-2"
            dark
            fab
            @click="floatMenu = false"
          >
            <v-icon>vertical_split</v-icon>
          </v-btn>
          <span>Activar menú vertical</span>
        </v-tooltip>
        <v-tooltip v-for="(item, index) in items" right :key="index" v-if="window !== index">
          <v-btn
            slot="activator"
            fab
            dark
            small
            :color="item.avatarColor"
            @click="window = index"
            :class="window === index ? 'elevation-5' : ''"
          >
            <v-icon>{{item.icon}}</v-icon>
          </v-btn>
          <span>{{item.title}}</span>
        </v-tooltip>
      </v-speed-dial>
    </v-slide-x-reverse-transition>
    <v-toolbar :dense="!procesoContractual.id">
      <v-tooltip top class="mr-2" v-if="procesoContractual.id && !floatMenu">
        <v-btn
          slot="activator"
          icon
          @click="floatMenu = true"
        >
          <v-icon color="accent">group_work</v-icon>
        </v-btn>
        <span>activar menú flotante</span>
      </v-tooltip>
      <v-icon>business_center</v-icon>
      <v-toolbar-title class="text-capitalize">{{!procesoContractual.id ? 'Nuevo proceso contractual' : 'Edición proceso contractual' }}</v-toolbar-title>
      <template v-if="procesoContractual.id">
        <v-spacer/>
        <span class="pt-1 text-xs-right">
          <span class="title text-capitalize">Proceso Contractual</span>
          <p class="mb-0 caption">Estado: {{procesoContractual.estado}}</p>
        </span>
        <v-chip label color="white" text-color="red" dark absolute right top>
          <span class="subheading">N°</span>&nbsp;
          <span class="subheading" v-text="procesoContractual.consecutivo == null ? '00' : procesoContractual.consecutivo"></span>
        </v-chip>
      </template>
    </v-toolbar>
    <v-layout row wrap>
      <v-slide-x-reverse-transition>
        <v-flex sm12 md3 v-if="procesoContractual.id && !floatMenu">
          <v-card class="content-list-p0">
            <v-list two-line>
              <template v-for="(item, index) in items">
                <v-list-tile @click="window = index" :key="'list' + index" :class="window === index ? 'grey lighten-2' : ''">
                  <v-list-tile-avatar :color="item.avatarColor">
                    <v-icon dark>{{ item.icon }}</v-icon>
                  </v-list-tile-avatar>
                  <v-list-tile-content>
                    <v-list-tile-title>
                      {{ item.title }}
                    </v-list-tile-title>
                    <v-list-tile-sub-title v-html="item.subTitle"></v-list-tile-sub-title>
                  </v-list-tile-content>
                </v-list-tile>
                <v-divider/>
              </template>
            </v-list>
            <!--<v-list class="mt-5" v-if="procesoContractual.id && procesoContractual.estudios_previos && procesoContractual.estudios_previos.id && infoComponentContratos && infoComponentContratos.permisos.agregar">-->
            <v-list class="mt-5" v-if="procesoContractual.id && infoComponentContratos && infoComponentContratos.permisos.agregar">
              <v-list-tile @click="$store.commit('NAV_ADD_ITEM', { ruta: infoComponentContratos.ruta_registro, titulo: infoComponentContratos.titulo_registro, parametros: {entidad: procesoContractual ? procesoContractual.contrato : null, procesoContractual: procesoContractual, tabOrigin: $store.state.nav.currentItem.split('tab-')[1]} })">
                <v-icon color="grey darken-1" class="mr-2">{{procesoContractual && procesoContractual.contrato ? 'link' : 'add_circle_outline'}}</v-icon>
                <v-list-tile-title class="grey--text text--darken-1">{{procesoContractual && procesoContractual.contrato ? 'Ir al contrato' : 'Crear contrato'}}</v-list-tile-title>
              </v-list-tile>
              <v-list-tile
                v-if="procesoContractual.id && procesoContractual.estudios_previos && procesoContractual.estudios_previos.id && flagEstudioPrevio && infoComponentContratos && infoComponentContratos.permisos.imprimir"
                @click="pdfEstudioPrevio"
              >
                <v-icon color="grey darken-1" class="mr-2">far fa-file-pdf</v-icon>
                <v-list-tile-title class="grey--text text--darken-1">Estudios previo</v-list-tile-title>
              </v-list-tile>
              <v-list-tile
                v-if="procesoContractual.id && procesoContractual.estudios_previos && procesoContractual.estudios_previos.id && procesoContractual.estudios_previos.estado === 'Confirmado' && infoComponentContratos && infoComponentContratos.permisos.imprimir"
                @click="filePrint(`certificado-presupuestal&id=${procesoContractual.estudios_previos.pr_solicitud_cp_id}`)"
              >
                <v-icon color="grey darken-1" class="mr-2">far fa-file-pdf</v-icon>
                <v-list-tile-title class="grey--text text--darken-1">Solicitud de CDP</v-list-tile-title>
              </v-list-tile>
            </v-list>
          </v-card>
        </v-flex>
      </v-slide-x-reverse-transition>
      <v-flex :md9="procesoContractual.id && !floatMenu">
        <v-window
          v-model="window"
          class="elevation-1"
          vertical
        >
          <v-window-item
            v-for="(item, n) in items"
            :key="n"
            lazy
          >
            <template>
              <v-list two-line dense>
                <template>
                  <v-list-tile>
                    <v-list-tile-avatar :color="item.avatarColor">
                      <v-icon dark>{{ item.icon }}</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title class="subheading" v-html="item.title" />
                      <v-list-tile-sub-title v-html="item.subTitle"/>
                    </v-list-tile-content>
                  </v-list-tile>
                  <v-divider/>
                </template>
              </v-list>
              <component :ref="item.ref" :is="item.component" v-model="procesoContractual"></component>
            </template>
          </v-window-item>
        </v-window>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  export default {
    name: 'RegistroProcesoContractual',
    props: ['parametros'],
    components: {
      Loading
    },
    data () {
      return {
        flagEstudioPrevio: false,
        fabMenu: false,
        floatMenu: false,
        window: 0,
        drawer: true,
        procesoContractual: {},
        loading: false,
        loadingSubmit: false,
        makeProcesoContractual: {
          id: null,
          consecutivo: null,
          objeto: null,
          estado: 'Registrado',
          pr_dependencia_id: null,
          servicios_salud: 0,
          gs_usuario_id: null,
          estudios_previos: null,
          contrato: null
        },
        items: [
          { icon: 'info', avatarColor: 'primary', title: 'Información básica', subTitle: 'Datos básicos del proceso contractual.', ref: 'RDatosBasicos', component: () => import('@/components/administrativo/contratacionEstatal/procesosContractuales/procesosContractuales/registroProcesoContractual/RegistroDatosBasicos') },
          { icon: 'verified_user', avatarColor: 'success', title: 'Estudio previo', subTitle: 'Datos estudio previo.', ref: 'REstudioPrevio', component: () => import('@/components/administrativo/contratacionEstatal/procesosContractuales/procesosContractuales/registroProcesoContractual/RegistroEstudioPrevio') }
          // { icon: 'view_list', avatarColor: 'danger', title: 'Convocatorias', subTitle: 'Registro convocatorias.', component: () => import('@/components/administrativo/contratacionEstatal/procesosContractuales/procesosContractuales/registroProcesoContractual/RegistroConvocatoria') }
        ]
      }
    },
    watch: {
      window (val) {
        if (val && val === 1) this.flagEstudioPrevio = true
      },
      itemContrato (val) {
        if (this.procesoContractual && this.procesoContractual.id && val && val.item && (val.item.ce_procontractuale_id === this.procesoContractual.id)) {
          this.getRegistro(this.procesoContractual.id)
        }
      }
    },
    computed: {
      infoComponentContratos () {
        return this.$store.getters.getInfoComponent('contratos')
      },
      itemContrato () {
        return this.$store.state.tables.itemContrato
      }
    },
    created () {
      this.formReset()
      if (this.parametros.entidad !== null && this.parametros.entidad.id !== null) this.getRegistro(this.parametros.entidad.id)
    },
    methods: {
      getRegistro (id) {
        this.loading = true
        this.axios.get('ce_procontractuales/' + id)
          .then(response => {
            if (response.data) {
              if (response.data.data.estudios_previos && !response.data.data.estudios_previos.proconminutageos) {
                response.data.data.estudios_previos.proconminutageos = []
              }
              if (this.parametros.copiar) {
                response.data.data.id = null
                response.data.data.consecutivo = null
                response.data.data.estado = 'Registrado'
                response.data.data.gs_usuario_id = null
                response.data.data.contrato = null
                if (response.data.data.estudios_previos) {
                  response.data.data.estudios_previos.id = null
                  response.data.data.estudios_previos.ce_procontractuale_id = null
                  response.data.data.estudios_previos.consecutivo = null
                  response.data.data.estudios_previos.pr_solicitud_cp_id = null
                  response.data.data.estudios_previos.estado = 'Registrado'
                  response.data.data.estudios_previos.registro = null
                  response.data.data.estudios_previos.reviso = null
                  response.data.data.estudios_previos.confirmo = null
                  response.data.data.estudios_previos.revisado_at = null
                  response.data.data.estudios_previos.confirmado_at = null
                }
              }
              this.procesoContractual = response.data.data
            }
            this.loading = false
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer el proceso contractual. ', error: e})
          })
      },
      async pdfEstudioPrevio () {
      // && procesoContractual.estudios_previos.estado === 'Confirmado'
        await this.$refs.REstudioPrevio[0].save()
        this.filePrint(`documento-estudio-previo&id=${this.procesoContractual.id}`)
      },
      formReset () {
        this.procesoContractual = JSON.parse(JSON.stringify(this.makeProcesoContractual))
      }
    }
  }
</script>

<style scoped>

</style>
