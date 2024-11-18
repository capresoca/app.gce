<template>
  <div>
    <v-navigation-drawer v-if="$vuetify.breakpoint.smAndDown"
                         v-model="drawer2"
                         absolute
                         temporary
    >
      <v-list class="pt-0" dense>
        <v-divider></v-divider>

        <v-list-tile
          v-for="(item, index) in items"
          :key="item.title"
          v-if="mostrarParto(item)"
          @click="clickMenuMovil(index)"
        >
          <v-list-tile-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-tile-action>

          <v-list-tile-content>
            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
    <v-container
      class="pa-0 ma-0"
      fluid
      grid-list-sm
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
          v-if="floatMenu && !$vuetify.breakpoint.smAndDown"
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
          <v-tooltip v-for="(item, index) in items" right :key="index" v-if="window !== index && mostrarParto(item)">
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
      <v-card key="cardtop" class="elevation-1">
        <v-container
          class="py-2 px-3"
          fluid
          grid-list-lg
        >
          <v-layout align-space-around justify-space-between wrap fill-height v-if="parametros.entidad !== null">
            <v-flex md4 sm6 xs12 order-xs2 order-sm2 order-md1>
              <v-layout align-center justify-space-between>
                <v-tooltip top :disabled="$vuetify.breakpoint.smAndDown">
                  <v-btn
                    slot="activator"
                    icon
                    large
                    @click="activarMenu"
                  >
                    <v-icon large :color="floatMenu ? 'grey' : 'accent'">{{ $vuetify.breakpoint.smAndDown ? 'menu' : 'group_work'}}</v-icon>
                  </v-btn>
                  <span>{{ $vuetify.breakpoint.smAndDown ? 'Menú' : 'Activar menú flotante' }}</span>
                </v-tooltip>
                <v-divider vertical class="mr-2"></v-divider>
                <v-flex>
                  <v-layout v-if="parametros.entidad.afiliado" align-space-between justify-space-between column>
                    <v-toolbar dense class="toolbar-pa0 elevation-0" style="background: transparent !important;">
                      <mini-card-detail :data="parametros.entidad.afiliado.mini_afiliado"></mini-card-detail>
                    </v-toolbar>
                    <span>
                      <v-tooltip top>
                      <v-chip slot="activator" label small dark absolute right top class="ma-0">
                        <span class="caption">{{`${parametros.entidad.afiliado.edad_full.split(',')[0]}`}}</span>
                      </v-chip>
                      <span>{{parametros.entidad.afiliado.edad_full}}</span>
                    </v-tooltip>
                    <v-chip label small dark absolute right top class="ma-0">
                      <span class="caption">{{`${parametros.entidad.afiliado.as_regimene_id === 1 ? 'Contributivo' : 'Subsidiado'}`}}</span>
                    </v-chip>
                    </span>
                    <template v-if="parametros.entidad && parametros.entidad.afiliado && parametros.entidad.afiliado.ips">
                      <span class="caption font-weight-bold">{{parametros.entidad.afiliado.ips.nombre}}</span>
                      <p class="mb-0 caption">Teléfono: {{parametros.entidad.afiliado.ips.telefono_sede}}</p>
                    </template>
                  </v-layout>
                </v-flex>
                <template v-if="$vuetify.breakpoint.xs">
                  <v-divider vertical class="mr-1"></v-divider>
                  <v-tooltip top>
                    <v-btn
                      slot="activator"
                      icon
                      large
                      @click="dataHeaderVisible = !dataHeaderVisible"
                    >
                      <v-icon
                        large
                      >
                        {{dataHeaderVisible ? 'keyboard_arrow_up' : 'keyboard_arrow_down'}}
                      </v-icon>
                    </v-btn>
                    <span>Datos factura</span>
                  </v-tooltip>
                </template>
              </v-layout>
            </v-flex>
            <v-flex v-if="$vuetify.breakpoint.smAndUp" md4 sm12 xs12 order-xs1 order-sm1 order-md2 class="text-xs-center">
            </v-flex>
            <v-flex v-if="$vuetify.breakpoint.smAndUp" md4 sm6 xs12 order-xs3 order-sm3 order-md3 class="text-xs-right">
              <v-toolbar class="toolbar-pa0 elevation-0" style="background: transparent !important;">
                <v-spacer></v-spacer>
                <span class="pt-1 text-xs-right">
                    <span class="title text-capitalize">Concurrencia</span>
                    <p class="mb-0 caption">Estado: {{ parametros.entidad.estado }}</p>
                  </span>
                <v-chip class="text-xs-right" label color="white" text-color="red" dark absolute right top>
                  <span class="subheading">N°</span>&nbsp;
                  <span class="subheading" v-text="parametros.entidad.consecutivo"></span>
                </v-chip>
              </v-toolbar>
            </v-flex>
          </v-layout>
          <v-expand-transition>
            <v-layout row wrap key="layout" v-if="dataHeaderVisible && $vuetify.breakpoint.xs && parametros.entidad">
              <v-flex xs12>
                <v-divider></v-divider>
              </v-flex>
              <v-flex md4 sm12 xs12 order-xs1 order-sm1 order-md2 class="text-xs-center" v-if="parametros.entidad.afiliado && parametros.entidad.afiliado.ips">
                <span>IPS primaria</span><br/>
                <span class="body-2">{{parametros.entidad.afiliado.ips.nombre}}</span>
                <p class="mb-0 caption">Teléfono: {{parametros.entidad.afiliado.ips.telefono_sede}}</p>
              </v-flex>
              <v-flex md4 sm6 xs12 order-xs3 order-sm3 order-md3 class="text-xs-center">
                <span class="title text-capitalize">Concurrencia</span>
                <v-chip class="text-xs-right" label color="white" text-color="red" dark absolute right top>
                  <span class="subheading">N°</span>&nbsp;
                  <span class="subheading" v-text="parametros.entidad.consecutivo"></span>
                </v-chip>
                <p class="mb-0 caption">Estado: {{ parametros.entidad.estado }}</p>
              </v-flex>
            </v-layout>
          </v-expand-transition>
        </v-container>
      </v-card>
      <loading v-model="loading" />
      <v-layout row wrap>
        <v-slide-x-reverse-transition>
          <v-flex sm12 md3 v-if="!floatMenu">
            <v-card class="content-list-p0 hidden-sm-and-down">
              <v-list two-line>
                <template v-for="(item, index) in items">
                  <v-list-tile @click="window = index" v-if="mostrarParto(item)" :key="'list' + index" :class="window === index ? 'grey lighten-2' : ''">
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
                  <v-divider inset/>
                </template>
              </v-list>
            </v-card>
          </v-flex>
        </v-slide-x-reverse-transition>
        <v-flex :md9="!floatMenu">
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
                  <template class="hidden-sm-and-down">
                    <v-list-tile>
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
                    <v-spacer></v-spacer>
                    <v-divider/>
                  </template>
                </v-list>
                <v-card-text>
                  <component :ref="'dataComponent' + n" :is="item.component" :concurrenciaId="parametros.entidad.id" :concurrenciaObj="parametros.entidad" />
                </v-card-text>
              </v-card>
            </v-window-item>
          </v-window>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>
  import Loading from '@/components/general/Loading'

  export default {
    name: 'DetalleConcurrencia',
    props: ['parametros'],
    components: {
      PostuladorV2: () => import('@/components/general/PostuladorV2'),
      Loading
    },
    data: () => ({
      dataHeaderVisible: false,
      concurrencia: {},
      floatMenu: false,
      fabMenu: false,
      loading: false,
      window: 0,
      drawer: true,
      items: [
        { icon: 'folder_shared', avatarColor: 'primary', title: 'Información', subTitle: 'Información', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/secciones/InformacionBasica') },
        { icon: 'assignment', avatarColor: 'success', title: 'Alto Costo', subTitle: 'Alto Costo', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/secciones/ConAltocostos') },
        { icon: 'pregnant_woman', avatarColor: 'orange', title: 'Parto', subTitle: 'Parto', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/secciones/ConMaternos') },
        { icon: 'recent_actors', avatarColor: 'purple', title: 'Visitas', subTitle: 'Visitas', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/secciones/ConVisitas') },
        { icon: 'assignment_return', avatarColor: 'indigo', title: 'Egreso', subTitle: 'Egreso', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/secciones/ConEgresos') },
        { icon: 'exit_to_app', avatarColor: 'teal', title: 'Gestión de riesgo', subTitle: 'Gestión de riesgo', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/secciones/GestionDeRiesgo') },
        { icon: 'home', avatarColor: 'deep-orange', title: 'Atención domiciliaria', subTitle: 'Atención domiciliaria', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/secciones/AtencionDomiciliaria') },
        { icon: 'find_in_page', avatarColor: 'brown', title: 'Eventos', subTitle: 'Eventos', component: () => import('@/components/administrativo/auditoriaCuentas/concurrencias/secciones/Eventos') }
      ],
      detalleAfiliado: () => import('@/components/misional/aseguramiento/afiliados/DetalleAfiliado'),
      drawer2: false
    }),
    methods: {
      clickMenuMovil (index) {
        this.window = index
        this.drawer2 = false
      },
      activarMenu () {
        if (this.$vuetify.breakpoint.smAndDown) this.drawer2 = true
        else this.floatMenu = true
      },
      mostrarParto (item) {
        if (item.title !== 'Parto') return true
        if (this.parametros.entidad.afiliado.sexo.nombre === 'Femenino') return true
        return false
      }
    }
  }
</script>

<style scoped>

</style>
