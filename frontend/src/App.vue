<template>
  <v-app id="app">
    <section v-if="isAuthenticated && $router.currentRoute.path === '/main'">
      <navbar></navbar>
      <search-afiliados></search-afiliados>
      <v-content class="grey lighten-4">
        <v-container fluid fill-height class="pb-5">
          <v-layout row wrap>
            <v-flex>
              <v-tabs-items v-model="currentItem" xs-12 touchless>
                <v-tab-item
                  v-for="item in items"
                  :key="item.id"
                  :value="'tab-'+ item.id"
                  lazy
                >
                  <keep-alive>
                    <router-view :name="item.ruta" :parametros="item.parametros">
                    </router-view>
                  </keep-alive>
                </v-tab-item>
              </v-tabs-items>
            </v-flex>
          </v-layout>
        </v-container>
        <footer-app></footer-app>
      </v-content>
      <snack-bar></snack-bar>
    </section>
    <section v-else>
      <router-view></router-view>
    </section>
    <re-auth-login></re-auth-login>
  </v-app>
</template>
<script>
  import Navbar from '@/components/general/navegacion/Navbar'
  import Drawable from '@/components/general/navegacion/Drawable'
  import {mapGetters, mapState} from 'vuex'
  import FooterApp from '@/components/general/navegacion/Footer'
  import SnackBar from '@/components/general/SnackBar'
  import ReAuthLogin from '@/components/general/navegacion/ReAuthLogin'
  import ComplementosStore from '@/store/complementos/index'
  import {SNACK_SHOW} from './store/modules/general/snackbar'
  import {COMP_OBTENER} from './store/complementos'
  import Echo from 'laravel-echo'
  import SearchAfiliados from '@/components/general/SearchAfiliados'
  export default {
    name: 'app',
    data: () => ({
      currentItem: null
    }),
    methods: {
    },
    components: {
      SearchAfiliados,
      Navbar,
      Drawable,
      FooterApp,
      SnackBar,
      ReAuthLogin
    },
    created () {
      ComplementosStore.dispatch(COMP_OBTENER)
        .catch(error => {
          this.$store.commit(SNACK_SHOW, {msg: error, color: 'danger'})
        })
      this.$nextTick(() => {
        this.currentItem = this.currentItemTab
      })
    },
    watch: {
      'currentItemTab' (value) {
        this.$nextTick(() => {
          this.currentItem = value
        })
      },
      'currentUser' (value) {
        if (value.id) {
          let echo = new Echo({
            broadcaster: 'socket.io',
            host: 'http://' + window.location.hostname + ':6001',
            auth: {headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }},
            key: '0330753d0e16efd42bf6b7d0b88488a5'
          })
          echo.private('user.' + value.id).listen('UserNotificationsEvent', (data) => {
            if (Notification.permission !== 'granted') {
              Notification.requestPermission()
            }
            const options = {
              body: data.message.text,
              icon: '/static/img/logo-capresoca.a2ec44c.png'
            }
            // eslint-disable-next-line
            let noti = new Notification(data.message.title, options)
          })
        }
      }
    },
    computed: {
      ...mapGetters(['items', 'isAuthenticated']),
      ...mapState({
        currentItemTab: state => state.nav.currentItem,
        items: state => state.nav.items,
        currentUser: state => state.user.profile
      })
    }
}
</script>
<style>
  .toolbar-pa0>.v-toolbar__content {
    padding:  0px !important;
  }
  .checkbox-ma0>.v-input__control>.v-input__slot {
    margin: 0px !important;
  }

  .vue-treeselect__control {
    border: 0px solid #ddd !important;
    border-bottom: 1px solid grey !important;
    border-radius: 0px !important;
  }
  .cursor-pointer{
    cursor: pointer !important;
  }
  .content-list-p0>.v-list{
    padding: 0 !important;
  }
  .content-v-list-tile-p0>.v-list__tile{
    padding: 0 !important;
  }
  .subrayado-dot{
    border-bottom: 0.107em dotted #9999CC !important;
  }

  .v-expansion-panel__container>.v-expansion-panel__header{
    padding: 10px 24px !important;
  }
  .v-expansion-panel-pi>.v-expansion-panel__container>.v-expansion-panel__header{
    padding: 0 20px 0 0 !important;
  }

  .v-expansion-header-dark>.v-expansion-panel__header{
    background-color: #f5f5f5 !important;
  }
  .theme--light .v-chip{
    background: #ff4081;
    color: white;
  }

  .input-number-style-text input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  .input-number-style-text input[type=number] { -moz-appearance:textfield; }

  .CodeMirror-gutters {
    z-index: 0 !important;
  }
  .CodeMirror-gutter-wrapper {
    z-index: 1 !important;
  }
  .CodeMirror-vscrollbar {
    z-index: 1 !important;
  }

  .truncate-content {
    contain: size !important;
  }

  .checkbox-pt0.v-input.v-input--selection-controls.v-input--checkbox.v-input--hide-details {
    margin-top: 0px !important;
  }
</style>
