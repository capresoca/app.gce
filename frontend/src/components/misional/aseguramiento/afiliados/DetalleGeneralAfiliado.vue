<template>
  <v-container
    class="pa-0 ma-0"
    fluid
    grid-list-md
  >
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
    <v-toolbar v-if="afiliado" :dense="!afiliado.id">
      <v-tooltip top class="mr-2" v-if="afiliado.id && !floatMenu">
        <v-btn
          slot="activator"
          icon
          @click="floatMenu = true"
        >
          <v-icon color="accent">group_work</v-icon>
        </v-btn>
        <span>activar menú flotante</span>
      </v-tooltip>
      <span style="font-size: 30px" v-text="afiliado.emoticon"></span>
      <v-toolbar-title v-if="!afiliado.id">Afiliado</v-toolbar-title>
      <v-list-tile v-else>
        <v-list-tile-content>
          <v-list-tile-title v-if="afiliado" v-html="afiliado.nombre_completo"></v-list-tile-title>
          <v-list-tile-sub-title v-html="afiliado.identificacion_completa"></v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>
      <v-spacer/>
      <v-menu offset-y left v-if="afiliado.id">
        <v-btn
          slot="activator"
          color="primary"
          dark
        >
          <v-icon class="mr-1">assignment_returned</v-icon> descargas
        </v-btn>
        <v-list class="pa-0">
          <v-list-tile @click="descargaCertificado(1)">
            <v-list-tile-title>Certificado Afiliación</v-list-tile-title>
          </v-list-tile>
          <v-list-tile @click="descargaCertificado(2)">
            <v-list-tile-title>Certificado Núcleo Familiar</v-list-tile-title>
          </v-list-tile>
          <v-list-tile @click="descargaCertificado(3)">
            <v-list-tile-title>Certificado Red Servicios</v-list-tile-title>
          </v-list-tile>
          <v-list-tile @click="descargaCertificado(4)">
            <v-list-tile-title>Certificado Electrónico</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-menu>
    </v-toolbar>
    <loading v-model="loading" />
    <v-layout row wrap>
      <v-flex v-if="afiliado && afiliado.id && !floatMenu" sm12 md3>
        <v-card class="content-list-p0">
          <v-list two-line>
            <template v-for="(item, index) in items">
              <v-list-tile @click="window = index" :key="item.text" :class="window === index ? 'grey lighten-2' : ''">
                <v-list-tile-avatar class="pl-1" :color="item.avatarColor">
                  <v-icon dark>{{ item.icon }}</v-icon>
                </v-list-tile-avatar>
                <v-list-tile-content>
                  <v-list-tile-title>
                    {{ item.title }}
                  </v-list-tile-title>
                  <v-list-tile-sub-title v-html="item.subTitle"></v-list-tile-sub-title>
                </v-list-tile-content>
              </v-list-tile>
              <v-divider inset/>
            </template>
          </v-list>
        </v-card>
      </v-flex>
      <v-flex :md9="afiliado && afiliado.id && !floatMenu">
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
            <v-card flat class="content-list-p0 pt-0">
              <v-list two-line>
                <template>
                  <v-list-tile>
                    <v-list-tile-avatar class="pl-2" :color="item.avatarColor">
                      <v-icon dark>{{ item.icon }}</v-icon>
                    </v-list-tile-avatar>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ item.title }}
                      </v-list-tile-title>
                      <v-list-tile-sub-title v-html="item.subTitle"></v-list-tile-sub-title>
                    </v-list-tile-content>
                    <v-list-tile-action>
                      <v-btn icon @click="$refs[item.ref][0].getData()">
                        <v-icon>cached</v-icon>
                      </v-btn>
                    </v-list-tile-action>
                  </v-list-tile>
                  <v-spacer></v-spacer>
                  <v-divider/>
                </template>
              </v-list>
              <v-card-text>
                <component :ref="item.ref" :afiliado="afiliado" :is="item.component" :afiliadoId="parametros.entidad.id"/>
              </v-card-text>
            </v-card>
          </v-window-item>
        </v-window>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import Loading from '@/components/general/Loading'
  import {SNACK_ERROR_LIST} from '@/store/modules/general/snackbar'
  import {mapState} from 'vuex'
  export default {
    name: 'DetalleGeneralAfiliado',
    props: ['parametros'],
    components: {
      Loading
    },
    data: () => ({
      loading: false,
      fabMenu: false,
      floatMenu: false,
      window: 0,
      drawer: true,
      afiliado: null,
      items: [
        { ref: 'ref1', icon: 'person', avatarColor: 'primary', title: 'Información básica', subTitle: 'Datos básicos del afiliado', component: () => import('@/components/misional/aseguramiento/afiliados/DetalleGeneralBasicos') },
        { ref: 'ref2', icon: 'folder_shared', avatarColor: 'success', title: 'Afiliación', subTitle: 'Datos de la afiliación', component: () => import('@/components/misional/aseguramiento/afiliados/DetalleGeneralAfiliacion') },
        { ref: 'ref3', icon: 'people', avatarColor: 'danger', title: 'Núcleo familiar', subTitle: 'Personas que conforman el núcleo familiar', component: () => import('@/components/misional/aseguramiento/afiliados/DetalleGeneralNucleoFamiliar') },
        { ref: 'ref4', icon: 'supervisor_account', avatarColor: 'orange', title: 'Relaciones laborales', subTitle: 'Relaciones laborales', component: () => import('@/components/misional/aseguramiento/afiliados/DetalleGeneralRelacionesLaborales') },
        { ref: 'ref4.1', icon: 'monetization_on', avatarColor: 'grey', title: 'Aportes', subTitle: 'Aportes', component: () => import('@/components/misional/aseguramiento/afiliados/DetalleGeneralAportes') },
        { ref: 'ref5', icon: 'widgets', avatarColor: 'teal', title: 'Red servicios', subTitle: 'Servicios garantizados', component: () => import('@/components/misional/aseguramiento/afiliados/RegistroRedServicios') },
        // { ref: 'ref5.1', icon: 'widgets', avatarColor: 'teal', title: 'Red servicios', subTitle: 'Servicios garantizados', component: () => import('@/components/misional/aseguramiento/afiliados/DetalleGeneralRedServicios') },
        { ref: 'ref6', icon: 'note_add', avatarColor: 'purple', title: 'Atenciones y servicios', subTitle: 'Atenciones y servicios', component: () => import('@/components/misional/aseguramiento/afiliados/DetalleGeneralAtencionesServicios') },
        { ref: 'ref8', icon: 'fas fa-layer-group', avatarColor: 'indigo', title: 'Trámites', subTitle: 'Trámites', component: () => import('@/components/misional/aseguramiento/afiliados/DetalleGeneralTramites') },
        { ref: 'ref7', icon: 'note_add', avatarColor: 'brown', title: 'Prescripciones', subTitle: 'Prescripciones', component: () => import('@/components/misional/aseguramiento/afiliados/DetalleGeneralPrescripciones') },
        { ref: 'ref9', icon: 'flag', avatarColor: 'blue', title: 'Log Traslados', subTitle: 'Log Traslados', component: () => import('@/components/misional/aseguramiento/afiliados/DetalleGeneralLogTraslados') }
      ]
    }),
    computed: {
      ...mapState({
        currentUser: state => state.user.profile
      })
    },
    created () {
      this.getData()
    },
    methods: {
      descargaCertificado (tipo) {
        if (tipo === 3) {
          this.filePrint('certificado-red-servicios&id=' + this.afiliado.id)
        } else if (tipo === 4) {
          this.filePrint(`certificado-electronico&id=${this.afiliado.id}&user=${this.currentUser.id}`)
        } else {
          this.filePrint('certificado-afiliacion&id=' + this.afiliado.id + '&tipo=' + tipo)
        }
      },
      getData () {
        this.loading = true
        this.axios.get(`afiliados/${this.parametros.entidad.id}`)
          .then((response) => {
            if (response.data !== '') {
              this.afiliado = response.data.data
              this.loading = false
            }
          })
          .catch(e => {
            this.loading = false
            this.$store.commit(SNACK_ERROR_LIST, {expression: ' al traer los datos básicos del afiliado. ', error: e})
          })
      }
    }
  }
</script>

<style scoped>
  .content-list-p0 .v-list{
    padding: 0 !important;
  }
</style>
