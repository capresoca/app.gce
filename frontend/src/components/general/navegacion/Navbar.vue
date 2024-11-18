<template>
<!--  style="z-index: 10 !important;"-->
  <section>
    <v-toolbar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      color="primary"
      dark
      app
      tabs>
      <v-toolbar-title>
        <v-list-tile avatar @click="">
          <v-list-tile-avatar>
            <img src="@/assets/logo-capresoca.png">
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title class="title">Capresoca EPS</v-list-tile-title>
            <v-list-tile-sub-title class="caption">Licencia N° SI36920180323I</v-list-tile-sub-title>
          </v-list-tile-content>
        </v-list-tile>
        <!--<img src="@/assets/logo-capresoca.png" style="width: 15%"  />-->
        <!--<span>Capresoca EPS</span>-->
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <template v-if="isAuthenticated && getUserName">
        <v-btn icon @click.stop="$store.commit('NAV_SET_MENU_ITEM')" >
          <v-icon>apps</v-icon>
        </v-btn>
        <v-toolbar-items>
          <v-menu offset-y open-on-hover>
            <v-btn flat slot="activator">
              <v-icon class="pr-1 pb-1">person</v-icon>
              {{getUserName}}
            </v-btn>
            <v-list class="pa-0">
              <v-list-tile flat @click="dialogCC = true">
                <v-list-tile-title>
                  <v-icon>security</v-icon>
                  Cambiar contraseña
                </v-list-tile-title>
              </v-list-tile>
              <v-list-tile flat @click="logout">
                <v-list-tile-title>
                  <v-icon>exit_to_app</v-icon>
                  Salir
                </v-list-tile-title>
              </v-list-tile>
            </v-list>
          </v-menu>
        </v-toolbar-items>
        <v-slide-x-reverse-transition mode="out-in">
          <v-tooltip left v-if="!drawerFavoritos">
            <v-btn slot="activator" icon @click.stop="drawerFavoritos = true">
              <v-icon>star</v-icon>
            </v-btn>
            <span>Favoritos</span>
          </v-tooltip>
        </v-slide-x-reverse-transition>
      </template>
      <v-progress-circular
        v-else
        indeterminate
        color="rgba(255, 255, 255, 1)"
      ></v-progress-circular>
      <v-tabs
        style="border-top: solid 2px rgba(255, 255, 255, 0.08)"
        v-model="currentItem"
        slot="extension"
        show-arrows
        color="primary"
        slider-color="accent"
        align-with-title
        max="10"

      >
        <v-hover
          v-for="(item, index) in items"
          :key="item.id"
        >
          <v-tab
            slot-scope="{ hover }"
            :href="'#tab-'+ item.id"
            class="tab-navbar"
            :class="hover ? 'elevation-3' : ''"
          >
            {{item.titulo}}
            <template v-if="index !== 0">
              <v-divider
                class="ml-2 mr-1"
                inset
                vertical
              />
              <v-btn
                relative
                fab
                small
                rigth
                icon
                flat
                @click.stop.prevent="removeItem(item, index)"
                class="ma-0"
              >
                <v-icon color="white" :size="14">close</v-icon>
              </v-btn>
            </template>
          </v-tab>
        </v-hover>
      </v-tabs>
    </v-toolbar>
    <cambiar-contrasenia v-model="dialogCC"/>
    <menu-app></menu-app>
    <menu-favoritos v-model="drawerFavoritos"></menu-favoritos>
  </section>
</template>

<script>
  import {AUTH_LOGOUT} from '@/store/modules/general/auth'
  import {NAV_REMOVE_ITEM, NAV_SET_CURRENT_ITEM} from '../../../store/modules/general/nav'
  import {mapGetters, mapState} from 'vuex'
  import MenuApp from '@/components/general/navegacion/MenuApp'
  import MenuFavoritos from '@/components/general/navegacion/MenuFavoritos'

  export default {
    name: 'Navbar',
    data: () => ({
      currentItem: 'tab-3',
      dialogCC: false,
      drawerFavoritos: null
    }),
    components: {
      MenuApp,
      MenuFavoritos,
      CambiarContrasenia: () => import('@/components/general/seguridad/usuarios/CambiarContrasenia')
    },
    methods: {
      logout () {
        this.$store.dispatch(AUTH_LOGOUT)
          .then(() => {
            this.$router.push('/')
          })
      },
      removeItem (item, index) {
        let indexCurrentItem = this.items.findIndex(x => x.id === this.currentItem.split('tab-')[1])
        if (indexCurrentItem === (index)) {
          let newItemId = this.items[index - 1].id
          const nuevoItem = this.$store.getters.NAV_NEW_CURRENT_ITEM({itemId: newItemId, indelegado: true})
          this.$nextTick(() => {
            this.currentItem = nuevoItem
          })
        }
        this.$store.commit(NAV_REMOVE_ITEM, item)
      },
      nextTick () {
        this.$nextTick(() => {
          this.currentItem = this.$store.state.nav.currentItem
        })
      }
    },
    watch: {
      'currentItem' (value) {
        this.$store.commit(NAV_SET_CURRENT_ITEM, value)
      },
      items () {
        this.nextTick()
      },
      compuertaMenu () {
        this.nextTick()
      }
    },
    mounted () {
      if (localStorage.getItem('vuex')) {
        this.nextTick()
      }
    },
    computed: {
      ...mapGetters(['getProfile', 'isAuthenticated', 'isProfileLoaded', 'getUserName']),
      ...mapState({
        items: state => state.nav.items,
        compuertaMenu: state => state.nav.compuerta
      })
    }
  }
</script>

<style scoped>
  .tab-navbar .v-btn--floating.v-btn--small {
    height: 20px !important;
    width: 20px !important;
  }
</style>
